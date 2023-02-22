<?php

namespace Main;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $params = [
        "name" => [
            
        ]
    ];
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        parent::__construct("Main");
    }
    
    /*--------------------------------------------------*/
    
    public function getInitPage(){
        $this->view->show("page.index.main");
    }
    
    /*--------------------------------------------------*/
    
    public function getClientListPage(){
        $client = new \Client\Controller();
        $account = new \Account\Controller();
        $clientList = $client->clientList();
        $tableFilterList = \Settings\Client::tableFilterList();
        $result = [];
        foreach($clientList as $value){
            
            $tarif = $client->getTarif($value["clientId"]);
            
            $value["tarif"] = "{$tarif["amountShow"]}тг - {$tarif["speedShow"]}мбит/с";
            $value["amount"] = $tarif["amount"];
            $value["speed"] = $tarif["speed"];
            $value["address"] = getAddress($value);
            if (($value["clientStatus"] == "Подключен")){
                if ($client->isBlocked($value["clientId"], time())){
                    $value["clientStatus"] = "Приостановлен";
                }
            }
            $paramsJson = [];
            foreach($tableFilterList as $key => $v){
                $paramsJson[$key] = $value[$key];
            }
            $paramsJson["averageLife"] = $client->getAverageLife($value["clientId"]);
            $value["paramsJson"] = \Json_u::encode($paramsJson);
            $keys = [
                "contractDate",
                "activateDate",
                "changeDate",
                "createDate"
            ];
            foreach($keys as $k){
                if ($value[$k]){
                    $value[$k] = date("d.m.Y",$value[$k]);
                }
            }
            
            if ((isset($value["dnum"])) && ($value["dnum"])){
                $doc = $account->getParentDoc($value["dnum"], $value["clientId"]);
                if ($doc["docType"] == "specification"){
                    
                    $value["docName"] = "ОСНОВНАЯ";
                }
                else{
                    $value["docName"] = $doc["docName"];
                }
            }
            else{
                $value["docName"] = "";
            }
            
            $result[] = $value;
        }
        
        $this->view->show("page.clientList.main",[
            "clientList" => $result
        ]);
    }
    
    
    
    /*--------------------------------------------------*/
    
    public function showClientListFilterForm(
            $param
    ){
        $tableFilterList = \Settings\Client::tableFilterList();
        $paramValueList = [];
        foreach($tableFilterList as $key => $value){
            $paramValueList[$key] = $value["name"];
        }
        $this->view->show("page.clientList.filterForm",[
            "paramValue" => $param,
            "paramInfo" => $tableFilterList[$param],
            "paramValueList" => $paramValueList
        ]);
    }
    
    /*--------------------------------------------------*/
    
}
