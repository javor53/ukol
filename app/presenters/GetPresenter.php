<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\Forms;
use Nette\Application\UI\Form;

class GetPresenter extends BasePresenter
{       
        /** @var Forms\InfoForm @inject */
        public $infoForm;
        
        /** @var Model\InfoModel @inject */
        public $infoModel;
        
        /** @var Model\SessionModel @inject */
        public $sessionModel;
    
        public $configTransport = [
            'kamion'=>'Kamion',
            'letadlo'=>'Letadlo'
            ];
        
        public $configFood = [
                'jablko' => 'Jablko',
                'pomeranč' => 'Pomeranč',
                'okurka' => 'Okurka',
                'paprika' => 'Paprika'
        ];
        
        

        public function handleSetPage($page){
           // $this->error($page);
            $this->sessionModel->sessionSection->page = $page;
            $this->infoModel->paginator->setPage($page);
            $this->template->paginator = $this->infoModel->paginator;
            $this->redrawControl('pages');
            $this->redirect('Get:');
        }
        
        
        public function handleSortAbc($col,$type){
            
            $this->infoModel->paginator->setPage($this->sessionModel->sessionSection->page);
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->sessionModel->sessionSection->page ;

            
            if( isset($this->sessionModel->sessionSection->FilterTransport) || 
                isset($this->sessionModel->sessionSection->FilterPackaging)  ||
                isset($this->sessionModel->sessionSection->FilterFood)  ||
                isset($this->sessionModel->sessionSection->FilterId)  ||
                isset($this->sessionModel->sessionSection->FilterTitle)  ||
                isset($this->sessionModel->sessionSection->FilterMessage) 
                       ){
                $transport = $this->sessionModel->sessionSection->FilterTransport;
                $food = $this->sessionModel->sessionSection->FilterFood;
                $id = $this->sessionModel->sessionSection->FilterId;
                $title = $this->sessionModel->sessionSection->FilterTitle;
                $message = $this->sessionModel->sessionSection->FilterMessage;
                
                if(!isset($this->sessionModel->sessionSection->FilterPackaging)){
                    
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message));
                   
                    $info = $this->infoModel->filterInfoBy(
                            $this->infoModel->paginator->getLength(),
                            $this->infoModel->paginator->getOffset(),
                            $transport,$food,$id,$title,$message,$col,$type
                            );
                    
                }else{
                    $packaging = $this->sessionModel->sessionSection->FilterPackaging;
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message,$packaging));
                    $info = $this->infoModel->filterInfoBy(
                            $this->infoModel->paginator->getLength(),
                            $this->infoModel->paginator->getOffset(),
                            $transport,$food,$id,$title,$message,$col,$type,$packaging);
                }
            }else{
                $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
                $info = $this->infoModel->sortInfo($col, $type,$this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
            }
            
            $this->sessionModel->sessionSection->SortCol = $col;
            $this->sessionModel->sessionSection->SortType = $type;
                        
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');
            $this->redirect('Get:');
        }
        
        public function handleFilter($transport,$packaging,$food,$id,$title,$message){
            if($id==NULL)
                $id="";
            elseif($title==NULL)
                $title="";
            elseif($message==NULL)
                $message=""; 

            if($packaging==NULL){
                $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message));
                $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$food,$id,$title,$message);
            }else{
                $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message,$packaging));
                $info = $this->infoModel->filterInfoBy(
                        $this->infoModel->paginator->getLength(),
                        $this->infoModel->paginator->getOffset(),
                        $transport,$food,$id,$title,$message,'id','DESC',$packaging);
                $this->sessionModel->sessionSection->FilterPackaging = $packaging;      
            }
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->infoModel->paginator->setPage($this->sessionModel->sessionSection->page);
            $this->sessionModel->sessionSection->page;
            //$info = $this->infoModel->filterInfoBy($transport);
              

            $this->sessionModel->sessionSection->FilterTransport = $transport;
            
            $this->sessionModel->sessionSection->FilterFood = $food;
            $this->sessionModel->sessionSection->FilterId = $id;
            $this->sessionModel->sessionSection->FilterTitle = $title;
            $this->sessionModel->sessionSection->FilterMessage = $message;
            
            
            
            
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');
            $this->redirect('Get:');
        }
        
        public function handleFilterOff(){
            $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->infoModel->paginator->setPage($this->sessionModel->sessionSection->page);
            $this->sessionModel->sessionSection->page;
            
            $info = $this->infoModel->getInfoTable($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
            
            unset($this->sessionModel->sessionSection->SortCol);
            unset($this->sessionModel->sessionSection->SortType);
            unset($this->sessionModel->sessionSection->FilterTransport);
            unset($this->sessionModel->sessionSection->FilterPackaging);
            unset($this->sessionModel->sessionSection->FilterFood);
            unset($this->sessionModel->sessionSection->FilterId);
            unset($this->sessionModel->sessionSection->FilterTitle);
            unset($this->sessionModel->sessionSection->FilterMessage);
            
            $this->template->info = $info;
            $this->template->paginator = $this->infoModel->paginator;
            
            $this->redrawControl('info');
            $this->redrawControl('pages');
            $this->redirect('Get:');
        }
        
        public function renderDefault() {
            
            $this->template->configTransport = $this->configTransport;
            $this->template->configFood = $this->configFood;
            $transport = $this->sessionModel->sessionSection->FilterTransport;
            $packaging = $this->sessionModel->sessionSection->FilterPackaging;
            $food = $this->sessionModel->sessionSection->FilterFood;
            $id = $this->sessionModel->sessionSection->FilterId;
            $title = $this->sessionModel->sessionSection->FilterTitle;
            $message = $this->sessionModel->sessionSection->FilterMessage;
            $col = $this->sessionModel->sessionSection->SortCol;
            $type = $this->sessionModel->sessionSection->SortType;
            
            $this->infoModel->paginator->setItemsPerPage(3);
            $this->infoModel->paginator->setPage($this->sessionModel->sessionSection->page);
            
            $this['filterForm']->setDefaults([
                'transport'=> $transport,
                'packaging'=> $packaging,
                'food'=> $food,
                'id'=> $id,
                'title'=> $title,
                'message'=> $message
                ]);
            
            if(!isset($this->template->info)){
                $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
                $this->infoModel->paginator->setItemsPerPage(3);
                $this->sessionModel->sessionSection->page;
                $this->infoModel->paginator->setPage($this->sessionModel->sessionSection->page);
                
                $info = $this->infoModel->getInfoTable($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
               
                $this->template->info = $info;
                $this->template->paginator = $this->infoModel->paginator;
            }    
            
            
            
            if($this->sessionModel->sessionSection->SortCol && !($this->sessionModel->sessionSection->FilterTransport || 
                                                                 $this->sessionModel->sessionSection->FilterPackaging ||
                                                                 $this->sessionModel->sessionSection->FilterFood ||
                                                                 $this->sessionModel->sessionSection->FilterId ||
                                                                 $this->sessionModel->sessionSection->FilterTitle ||
                                                                 $this->sessionModel->sessionSection->FilterMessage
                                                                )){
                $this->infoModel->paginator->setItemCount($this->infoModel->getInfoCount());
                $info = $this->infoModel->sortInfo($col, $type,$this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset());
                $this->template->info = $info;
            }elseif($this->sessionModel->sessionSection->SortCol && ($this->sessionModel->sessionSection->FilterTransport || 
                                                                 $this->sessionModel->sessionSection->FilterPackaging ||
                                                                 $this->sessionModel->sessionSection->FilterFood ||
                                                                 $this->sessionModel->sessionSection->FilterId ||
                                                                 $this->sessionModel->sessionSection->FilterTitle ||
                                                                 $this->sessionModel->sessionSection->FilterMessage
                                                                )){
                
                if(!isset($this->sessionModel->sessionSection->FilterPackaging)){
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message));
                    $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$food,$id,$title,$message,$col,$type);
                }else{
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message,$packaging));
                    $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$food,$id,$title,$message,$col,$type,$packaging);
                }
                $this->template->info = $info;
            }elseif(!$this->sessionModel->sessionSection->SortCol && ($this->sessionModel->sessionSection->FilterTransport || 
                                                                 $this->sessionModel->sessionSection->FilterPackaging ||
                                                                 $this->sessionModel->sessionSection->FilterFood ||
                                                                 $this->sessionModel->sessionSection->FilterId ||
                                                                 $this->sessionModel->sessionSection->FilterTitle ||
                                                                 $this->sessionModel->sessionSection->FilterMessage
                                                                )){
                
                if(!isset($this->sessionModel->sessionSection->FilterPackaging)){
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message));
                    $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$food,$id,$title,$message);
                }else{
                    $this->infoModel->paginator->setItemCount($this->infoModel->getFilterItemsCount($transport,$food,$id,$title,$message,$packaging));
                    $info = $this->infoModel->filterInfoBy($this->infoModel->paginator->getLength(), $this->infoModel->paginator->getOffset(),$transport,$food,$id,$title,$message,'id','DESC',$packaging);
                }     
                $this->template->info = $info;
            }
            
            $this->template->paginator = $this->infoModel->paginator; 
   
        }
        
        public function createComponentFilterForm() {
           
            $form = $this->infoForm->createFilter();
            
            $form->onSuccess[] = function(Form $form,$values){
           
                    $values = $form->getValues();
                    
                    if($values->packaging == NULL && isset($this->sessionModel->sessionSection->FilterPackaging)){
                        unset($this->sessionModel->sessionSection->FilterPackaging);
                    }
                    
                    $this->handleFilter(
                            $values->transport,
                            $values->packaging,
                            $values->food,
                            $values->id,
                            $values->title,
                            $values->message
                            );
                    
            };
            return $form;
        }
}
