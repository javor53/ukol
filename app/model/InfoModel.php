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
    
    function filterInfoBy($limit,$offset,$transport,$food,$id="",$title="",$message="",$col='id',$type='DESC',$packaging = NULL){
        
        if($packaging == NULL){
            $p = $this->database->query('SELECT * FROM info '
                    . 'WHERE transport LIKE ? '
                    . 'AND food LIKE ? '
                    . 'AND id LIKE ? '
                    . 'AND title LIKE ? '
                    . 'AND message LIKE ? '
                    . 'ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',
                    $transport,$food,"%".$id."%","%".$title."%","%".$message."%",$limit,$offset)->fetchAll();
        }else{
            $cnt=0;
            $t="";
            foreach($packaging as $item){
                if($cnt == count($packaging)-1){
                    $t .= 'packaging LIKE \'%'.$item.'%\'';
                    break;
                }
                $t .= 'packaging LIKE \'%'.$item.'%\' AND ';
                $cnt++;        
            }
            $p = $this->database->query('SELECT * FROM info '
                    . 'WHERE transport LIKE ? '
                    . 'AND food LIKE ? '
                    . 'AND id LIKE ? '
                    . 'AND title LIKE ? '
                    . 'AND message LIKE ? '
                    . 'AND '.$t.' ORDER BY '.$col.' '.$type.' LIMIT ? OFFSET ?',
                   $transport,$food,"%".$id."%","%".$title."%","%".$message."%",$limit,$offset)->fetchAll();
        }
        
        return $p; 
    }
    
    function getFilterItemsCount($transport,$food,$id="",$title="",$message="",$packaging = NULL) {
        
        if($packaging == NULL){
            $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info '
                    . 'WHERE transport LIKE ? '
                    . 'AND food LIKE ? '
                    . 'AND id LIKE ? '
                    . 'AND title LIKE ? '
                    . 'AND message LIKE ? ',
                    $transport,$food,"%".$id."%","%".$title."%","%".$message."%")->fetch();
        }else{
            $cnt=0;
            $t="";
            foreach($packaging as $item){
                if($cnt == count($packaging)-1){
                    $t .= 'packaging LIKE \'%'.$item.'%\'';
                    break;
                }
                $t .= 'packaging LIKE \'%'.$item.'%\' AND ';
                $cnt++;        
            }
            $p = $this->database->query('SELECT COUNT(*) AS cnt FROM info '
                    . 'WHERE transport LIKE ? '
                    . 'AND food LIKE ? '
                    . 'AND id LIKE ? '
                    . 'AND title LIKE ? '
                    . 'AND message LIKE ? '
                    . 'AND '.$t,
                   $transport,$food,"%".$id."%","%".$title."%","%".$message."%")->fetch();
        }
        
        return $p->cnt;
    }
    
}