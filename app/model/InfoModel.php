<?php
namespace App\Model;

use Nette;

class InfoModel extends ModelManager{
    
    public $paginator;
    
    
    function __construct(Nette\Database\Context $database){
        parent::__construct($database);
        $this->paginator = new Nette\Utils\Paginator;
    }
    
    function getInfoTable($limit,$offset) {
        $p = $this->database->query('SELECT * FROM info LIMIT ? OFFSET ?',$limit,$offset)->fetchAll();
        return $p;
    }
    
    function getInfo($id){
        $p = $this->database->query('SELECT * FROM info WHERE id=?',$id)->fetch();
        return $p;
    }
    
    function getInfoCount() {
        $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info')->fetch();
        return $p->cnt; 
    }
    
    function createInfo($values) {
        try{
            $pck = "";
            foreach($values->packaging as $item){
                $pck .= $item . ","; 
            }
            
            $this->database->table('info')->insert([
                'title' => $values->title,
                'message' => $values->message,
                'transport' => $values->transport,
                'packaging' => $pck,
                'food' => $values->food,
                ]);
        }
        catch(\Exception $e){
            
        }
    }
    
    function sortInfo($col,$type,$limit,$offset) {
        $p = $this->database->query('SELECT * FROM info ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',$limit,$offset);
        return $p;
    }
    
    function filterInfoBy($limit,$offset,$transport,$packaging = NULL,$col=NULL,$type=NULL){
        if($col==NULL && $type==NULL){
            $col='id';
            $type='DESC';
        }
        
        if($packaging == NULL){
            $p = $this->database->query('SELECT * FROM info WHERE transport LIKE ? ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',$transport,$limit,$offset)->fetchAll();
        }
        elseif(count($packaging)==2){
           $p = $this->database->query('SELECT * FROM info WHERE transport LIKE ? AND packaging LIKE ? AND packaging LIKE ? ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',
                   $transport,'%'.$packaging[0].'%','%'.$packaging[1].'%',$limit,$offset)->fetchAll(); 
        }elseif(count($packaging)==3){
           $p = $this->database->query('SELECT * FROM info WHERE transport LIKE ? AND packaging LIKE ? AND packaging LIKE ? AND packaging LIKE ? ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',
                   $transport,'%'.$packaging[0].'%','%'.$packaging[1].'%','%'.$packaging[2].'%',$limit,$offset)->fetchAll(); 
        }else{
          $p = $this->database->query('SELECT * FROM info WHERE transport LIKE ? AND packaging LIKE ? ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',
                  $transport,'%'.$packaging[0].'%',$limit,$offset)->fetchAll();
        }
        return $p;
    }
    
    function getFilterItemsCount($transport,$packaging = NULL) {
        if($packaging == NULL){
            $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info WHERE transport LIKE ?',$transport)->fetch();
        }
        elseif(count($packaging)==2){
           $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info WHERE transport LIKE ? AND packaging LIKE ? AND packaging LIKE ?',
                   $transport,'%'.$packaging[0].'%','%'.$packaging[1].'%')->fetch(); 
        }elseif(count($packaging)==3){
           $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info WHERE transport LIKE ? AND packaging LIKE ? AND packaging LIKE ? AND packaging LIKE ?',
                   $transport,'%'.$packaging[0].'%','%'.$packaging[1].'%','%'.$packaging[2].'%')->fetch(); 
        }else{
          $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info WHERE transport LIKE ? AND packaging LIKE ?',
                  $transport,'%'.$packaging[0].'%')->fetch();
        }
        return $p->cnt;
    }
    
}