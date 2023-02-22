<?php

namespace ClientList;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');
//require_once './php/Document.php';


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    public function __construct(){
        parent::__construct("ClientList");
        $this->model = new \ClientList\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function getListTable(){
        $oldTable = $this->model->getOldList();
        $doneList = $this->model->getDoneList();
        $sum = 0;
        $markedSum = 0;
        $markedCount = 0;
        $allMarkedCount = 0;
        foreach($oldTable as $key => $value){
            if (in_array($value["status"], ["8","10"])){
                $sum += (int)$value["rrate"];
                if (isset($doneList[$key])){
                    $markedSum += (int)$value["rrate"];
                    $markedCount++;
                }
            }
            if (isset($doneList[$key])){
                $allMarkedCount++;
            }
        }
        return $this->view->get("clientTable.main",[
            "oldTable" => $oldTable,
            "doneList" => $doneList,
            "count" => count($oldTable),
            "sum" => $sum,
            "markedSum" => $markedSum,
            "markedCount" => $markedCount,
            "allMarkedCount" => $allMarkedCount
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function addDoneList(
            $id,
            $manager
    ){
        $this->model->addDoneList($id,$manager);
    }
    
    /*--------------------------------------------------*/
    
    
}












