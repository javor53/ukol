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
            'kamion'=>'Kamion',
            'letadlo'=>'Letadlo'
            ]);
        $form->addCheckboxList('packaging','Packaging', [
            'A'=>"Ananas navíc",
            'B'=>"Borůvky navíc",
            'C'=>"Celer navíc"
            ]);
        $form->addMultiSelect('food','Food',[
            'Ovoce'=>[
                'jablko' => 'Jablko',
                'pomeranč' => 'Pomeranč'
            ],
            'Zelenina'=>[
                'okurka' => 'Okurka',
                'paprika' => 'Paprika'
            ]
        ])->setRequired('Je třeba zvolit zboží.');
        $form->addSubmit('submit');
        
        return $form;
    }
    
    public function createFilter() {
        $form = new Form();
        
        $form->addSelect('transport','Transport',[
            'kamion'=>'kamion',
            'letadlo'=>'letadlo',
            '%%'=>'vše'
            ]);
        
        $form->addCheckboxList('packaging','Packaging', [
            'A'=>"Ananas navíc",
            'B'=>"Borůvky navíc",
            'C'=>"Celer navíc"
            ]);
        
        $form->addSelect('food','Food',[
                'jablko' => 'Jablko',
                'pomeranč' => 'Pomeranč',
                'okurka' => 'Okurka',
                'paprika' => 'Paprika',
                '%%'=>'vše'
        ]);
        
        $form->addInteger('id');
        $form->addText('title');
        $form->addText('message');
        
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
