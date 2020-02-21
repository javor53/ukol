<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\Forms;
use Nette\Application\UI\Form;

class PostPresenter extends BasePresenter
{       
        /** @var Forms\InfoForm @inject */
        public $infoForm;
        
        /** @var Model\InfoModel @inject */
        public $infoModel;
        
        /** @var Model\SessionModel @inject */
        public $sessionModel;
    
             
        
               
        public function renderDefault() {
            
        }
        
        public function createComponentInfoForm() {
           
            $form = $this->infoForm->create();
            
            $form->onSuccess[] = function(Form $form,$values){
           
                    $values = $form->getValues();
                    $this->infoModel->createInfo($values);
                    $this->redirect('Get:');
      
                
            };
            return $form;
        }
}
