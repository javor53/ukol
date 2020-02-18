<?php

namespace App\Forms;

use Nette;

interface IForm
{

public function create();   

public function formSucceeded(Nette\Application\UI\Form $form, $values);
}
