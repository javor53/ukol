<?php
namespace App\Model;

use Nette;

abstract class ModelManager{
    
    use Nette\SmartObject;
    
    /**
     * @var Nette\Database\Context
     */
    protected $database;
    
    public function __construct(Nette\Database\Context $database){
        
        $this->database = $database;

    }

    
}