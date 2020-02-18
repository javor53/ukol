<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

class InfoForm implements IForm
{
    /**
     * 
     * @return Form
     */
    public function create() {
        $form = new Form();
        
        $form->addText('title');
        $form->addTextArea('message');
        
        $form->addSelect('transport','Transport',[
            'truck'=>'kamion',
            'plane'=>'letadlo'
            ]);
        $form->addCheckboxList('packaging','Packaging', [
            'a'=>"Požadavek A",
            'b'=>"Požadavek B",
            'c'=>"Požadavek C"
            ]);
        $form->addMultiSelect('food','Food',[
            'fruits'=>[
                'apple' => 'Jablko',
                'orange' => 'Pomeranč'
            ],
            'vegetables'=>[
                'cucumber' => 'Okurka',
                'pepper' => 'Paprika'
            ]
        ]);
        $form->addSubmit('submit');
        
        return $form;
    }
    
    public function createFilter() {
        $form = new Form();
        
        $form->addSelect('transport','Transport',[
            'truck'=>'kamion',
            'plane'=>'letadlo',
            '%%'=>'vše'
            ]);
        
        $form->addCheckboxList('packaging','Packaging', [
            'a'=>"Požadavek A",
            'b'=>"Požadavek B",
            'c'=>"Požadavek C"
            ]);
        
        
        $form->addSubmit('submit');
        
        return $form;
        
    }
    
    
    /**
     * 
     * @param Form $form
     * @param type $values
     */
    public function formSucceeded(Form $form, $values) {
        
    }
    
}
