<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\Forms;
use Nette\Application\UI\Form;

class HomepagePresenter extends BasePresenter
{       
        /** @var Forms\InfoForm @inject */
        public $infoForm;
        
        /** @var Model\InfoModel @inject */
        public $infoModel;
        
        /** @var Model\SessionModel @inject */
        public $sessionModel;
    
        public $configTransport = [
            'truck'=>'kamion',
            'plane'=>'letadlo'
            ];
        
        public $configFood = [
                'apple' => 'Jablko',
                'orange' => 'Pomeranč',
                'cucumber' => 'Okurka',
                'pepper' => 'Paprika'
        ];
        
        

        
        public function handleSortAbc($col,$type){
            if(isset($this->sessionModel->sessionSection->FilterTransport) || isset($this->sessionModel->sessionSection->FilterPackaging)){
                $transport = $this->sessionModel->sessionSection->FilterTransport;
                $packaging = $this->sessionModel->sessionSection->FilterPackaging;
                if($packaging==NULL){
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport));
                }
            
                $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport, $packaging));
            }else{
                $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
            }
            $this->infoModel->paginator->setPage(1);
            $this->infoModel->paginator->setItemsPerPage(3);
            
            if(isset($this->sessionModel->sessionSection->FilterTransport) || isset($this->sessionModel->sessionSection->FilterPackaging)){
                if(!isset($this->sessionModel->sessionSection->FilterPackaging)){
                    $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$col,$type);
                }
                $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$packaging,$col,$type);
            }else{
                $info = $this->infoModel->sortInfo($col, $type,$this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
            }
            $this->sessionModel->sessionSection->SortCol = $col;
            $this->sessionModel->sessionSection->SortType = $type;
                        
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');

        }
        
        public function handleFilter($transport,$packaging){
            
            if($packaging==NULL){
                $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport));
            }
            
            $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport, $packaging));
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->infoModel->paginator->setPage(1);
            //$info = $this->infoModel->filterInfoBy($transport);
            if($packaging==NULL){
                $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport);
            }

            $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$packaging);
            $this->sessionModel->sessionSection->FilterTransport = $transport;
            $this->sessionModel->sessionSection->FilterPackaging = $packaging;
            
            
            
            
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');
        }
        
        public function handleFilterOff(){
            $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->infoModel->paginator->setPage(1);
            
            $info = $this->infoModel->getInfoTable($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
            
            unset($this->sessionModel->sessionSection->SortCol);
            unset($this->sessionModel->sessionSection->SortType);
            unset($this->sessionModel->sessionSection->FilterTransport);
            unset($this->sessionModel->sessionSection->FilterPackaging);
            
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');

        }
        
	public function renderDefault()
	{
            $this->redirect('Post:');
	}
        
       
        
        
        public function createComponentInfoForm() {
           
            $form = $this->infoForm->create();
            
            $form->onSuccess[] = function(Form $form,$values){
           
                    $values = $form->getValues();
                    $this->infoModel->createInfo($values);
                    $this->redirect(':get');
      
                
            };
            return $form;
        }

}
