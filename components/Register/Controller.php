<?php

namespace Register;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    
    private $model;
    
    public function __construct(){
        parent::__construct("Register");
        $this->model = new \Register\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function getAgreementRegister(
            $year,
            $month,
            $close = false,
            $print = false,
            $fl = true
    ){
        $account = new \Account\Controller();
        $client = new \Client\Controller();
        $clientInfoList = $client->clientList();
        $ignoreList = [
            "Спецификация",
            "Дополнительная точка"
        ];
        $result = [
            "connected" => [],
            "connectedGu" => [],
            "changed" => [],
            "disconnected" => [],
            "blocked" => []
        ];
        $sumList = [
            "connected" => [],
            "disconnected" => [],
            "waiting" => []
        ];
        $clientList = [];
        $connected = [];
        $connectedGu = [];
        $changed = [];
        $disconnected = [];
        $blocked = [];
        $waiting = [];
        $timeStamp = 0;
        $dnumList = $account->getAllDnums();
        $mainContractDateList = [];
        $clientDocList = [];
        
        /*--------------------------------------------------*/
        
        foreach($clientInfoList as $clientInfo){
            
            if((!$clientInfo["dnum"])){
                continue;
            }
            
            $buf = $client->getTarif($clientInfo["id"]);
            $clientInfo["fullAmount"] = $buf["amount"];
            $clientInfo["fullSpeed"] = $buf["speed"];
            $bufDate = $clientInfo["contractDate"] ? date("d.m.Y",$clientInfo["contractDate"]) : "";
            $clientInfo["gosDnumFull"] = "№{$clientInfo["gosDnum"]} {$bufDate} по биллингу {$clientInfo["dnum"]}";
            $blockList = $client->getBlockList($clientInfo["id"]);
            foreach($blockList as $block){
                $blockStart = explode(".",date("Y.m",$block["blockStart"]));
                $flag = false;
                if (((int)$blockStart[0] <= (int)$year) && ((int)$blockStart[1] <= (int)$month)){
                    if ($block["blockEnd"]){
                        $blockEnd = explode(".",date("Y.m",$block["blockEnd"]));
                        if (((int)$blockEnd[0] >= (int)$year) && ((int)$blockEnd[1] >= (int)$month)){
                            $flag = true;
                        }
                    }
                    else{
                        $flag = true;
                    }
                }
                if ($flag){
                    $block["clientId"] = $clientInfo["id"];
                    $block["contractDate"] = $clientInfo["contractDate"];
                    $block["clientType"] = $clientInfo["clientType"];
                    $block["dnum"] = $clientInfo["dnum"];
                    $block["clientName"] = $clientInfo["name"];
                    $blocked[$clientInfo["dnum"]][$block["timeStamp"]] = $block;
                }
            }
            $clientStatus = $clientInfo["clientStatus"];
            $type = "none";

            if ($clientInfo["activateDate"]){
                $timeStamp = $clientInfo["activateDate"];
                $type = "connected";
            }
            if ($clientInfo["renewDate"]){
                $timeStamp = $clientInfo["renewDate"];
                $type = "renew";
            }
            if ($clientInfo["disconnectDate"]){
                $timeStamp = $clientInfo["disconnectDate"];
                $type = "disconnected";
            }
            
            if ((!$fl) && ($clientInfo["clientType"] == "ФЛ")){
                $type = "none";
            }
            $clientInfo["registerType"] = $type;
            $activateYear = date("Y",$timeStamp);
            $activateMonth = date("m",$timeStamp);
            $parentDoc = $account->getParentDoc($clientInfo["dnum"], $clientInfo["id"]);
            
            $parentDoc["activateDate"] = $clientInfo["activateDate"];
            $parentDoc["clientType"] = $clientInfo["clientType"];
            $parentDoc["registerType"] = $clientInfo["registerType"];
            $parentDoc["contractDate"] = $clientInfo["contractDate"];
            $parentDoc["clientType"] = $clientInfo["clientType"];
            $parentDoc["name"] = $clientInfo["name"];
            $parentDoc["amount"] = $clientInfo["amount"];
            $parentDoc["speed"] = $clientInfo["speed"];
            $parentDoc["clientType"] = $clientInfo["clientType"];
            $parentDoc["fullAmount"] = ($clientInfo["fullAmount"] > 0) ? $clientInfo["fullAmount"] : "";
            $parentDoc["fullSpeed"] = ($clientInfo["fullSpeed"] > 0) ? $clientInfo["fullSpeed"] : "";
            $parentDoc["connectSum"] = $clientInfo["connectSum"];
            $parentDoc["activateDate"] = $clientInfo["activateDate"];
            $parentDoc["gosDnumFull"] = $clientInfo["gosDnumFull"];
            $parentDoc["disconnectDate"] = $clientInfo["disconnectDate"];
            $parentDoc["renewDate"] = $clientInfo["renewDate"];
            $parentDoc["renewFilePath"] = $clientInfo["renewFilePath"];
            $parentDoc["disconnectFilePath"] = $clientInfo["disconnectFilePath"];
            $parentDoc["clientName"] = $clientInfo["name"];
            if (($clientInfo["clientStatus"] == "Ожидает подключение") && (!$clientInfo["activateDate"])){
                $clientInfo["docComment"] = $parentDoc["comment"];
                $waiting[$clientInfo["dnum"]][] = $clientInfo["id"];
                if (!isset($sumList["waiting"][$clientInfo["dnum"]])){
                    $sumList["waiting"][$clientInfo["dnum"]] = 0;
                }
                $sumList["waiting"][$clientInfo["dnum"]] += (int)$clientInfo["fullAmount"];
            }
            if ($type == "connected"){
                if ((($activateMonth == $month)&&($activateYear == $year))){
                    if (!isset($sumList["connected"][$clientInfo["dnum"]])){
                        $sumList["connected"][$clientInfo["dnum"]] = 0;
                    }
                    $sumList["connected"][$clientInfo["dnum"]] += (int)$clientInfo["fullAmount"];
                    
                    if ($parentDoc["docType"] == "specification"){
                        if ($clientInfo["clientType"] == "ГУ"){
                            $connectedGu[$clientInfo["dnum"]][$clientInfo["id"]] = $parentDoc;
                        }
                        else{
                            $connected[$clientInfo["dnum"]][$clientInfo["id"]] = $parentDoc;
                        }
                    }
                    else{
                        $parentDoc["param_activateDate"] = $timeStamp;
                        $parentDoc["param_amount"] = $clientInfo["amount"];
                        $parentDoc["param_contractDate"] = $clientInfo["contractDate"];
                        $parentDoc["param_connectSum"] = $clientInfo["connectSum"];
                        $parentDoc["param_speed"] = $clientInfo["speed"];
                        $changed[$clientInfo["dnum"]][$parentDoc["posId"]] = $parentDoc; 
                    }
                }
            }
            if (($type == "disconnected")||($type == "renew")){
                if (!isset($sumList["disconnected"][$clientInfo["dnum"]])){
                    $sumList["disconnected"][$clientInfo["dnum"]] = 0;
                }
                $sumList["disconnected"][$clientInfo["dnum"]] += $clientInfo["fullAmount"];
                $activateYear = date("Y",$timeStamp);
                $activateMonth = date("m",$timeStamp);
                if (($activateMonth == $month)&&($activateYear == $year)){
                    if ($type == "disconnected"){
                        $comment = $client->getDisconnectComment($clientInfo["id"]);
                    }
                    else{
                        $comment = $client->getRenewComment($clientInfo["id"]);
                    }
                    $parentDoc["comment"] = isset($comment["text"]) ? $comment["text"] : "";
                    $disconnected[$clientInfo["dnum"]][$clientInfo["id"]] = $parentDoc;
                }
            }
            
            $bufDocList = $client->getClientDocList($clientInfo["id"]);
            foreach($bufDocList as $value){
                if ($value["date"]){
                    $docMonth = date("m",$value["date"]);
                    $docYear = date("Y",$value["date"]);
                    if (($docMonth == $month) && ($docYear == $year) && ($value["register"] == "1")){
                        $value["dnum"] = $clientInfo["dnum"];
                        $value["clientType"] = $clientInfo["clientType"];
                        $value["clientName"] = $clientInfo["name"];
                        $value["clientId"] = $clientInfo["id"];
                        $clientDocList[$clientInfo["dnum"]][$value["id"]] = $value;
                    }
                }
            }
            
            $clientList[$clientInfo["id"]] = $clientInfo;
        }
        
        
        /*--------------------------------------------------*/
        
        foreach($dnumList as $dnum){
            $posList = $account->getAllPosList($dnum);
            foreach($posList as $pos){
                
                if ($pos["posType"] == "Спецификация"){
                    $info = $client->clientInfo($pos["clientId"]);
                    $mainContractDateList[$pos["dnum"]] = isset($info["contractDate"]) ? $info["contractDate"] : "";
                }
                if (in_array($pos["posType"], $ignoreList)){
                    continue;
                }
                if (isset($pos["param_activateDate"])){
                    $timeStamp = ($pos["param_activateDate"]) ? $pos["param_activateDate"] : 0;
                }
                else if (isset($pos["param_contractDate"])){
                    $timeStamp = ($pos["param_contractDate"]) ? $pos["param_contractDate"] : 0;
                }
                $dateYear = date("Y",$timeStamp);
                $dateMonth = date("m",$timeStamp);
                if (!$fl && ($clientList[$pos["clientId"]]["clientType"] == "ФЛ")){
                    continue;
                }
                if (($dateMonth == $month)&&($dateYear == $year)){
                    $pos["param_activateDate"] = $timeStamp;
                    $pos["clientDnum"] = $clientList[$pos["clientId"]]["dnum"];
                    $pos["clientName"] = $clientList[$pos["clientId"]]["name"];
                    $pos["clientType"] = $clientList[$pos["clientId"]]["clientType"];
                    $pos["param_contractDate"] = $clientList[$pos["clientId"]]["contractDate"];
                    $pos["checked"] = ($pos["checked"] != "0") ? $pos["checked"] : "";
                    $changed[$pos["dnum"]][$pos["posId"]] = $pos;
                }
            }
            
        }
        
        /*--------------------------------------------------*/
        
        
        foreach($connected as $key => $posList){
            foreach($posList as $k => $pos){
                $connected[$key][$k]["mainContractDate"] = $mainContractDateList[$pos["dnum"]];
            }
        }
        
        foreach($disconnected as $key => $posList){
            foreach($posList as $k => $pos){
                $disconnected[$key][$k]["mainContractDate"] = isset($mainContractDateList[$pos["dnum"]]) ? $mainContractDateList[$pos["dnum"]] : "";
            }
        }
        
        foreach($changed as $key => $posList){
            foreach($posList as $k => $pos){
                $changed[$key][$k]["mainContractDate"] = $mainContractDateList[$pos["dnum"]];
            }
        }
        
        foreach($connectedGu as $key => $posList){
            foreach($posList as $k => $pos){
                $connectedGu[$key][$k]["mainContractDate"] = $mainContractDateList[$pos["dnum"]];
            }
        }
        
        
        
        /*--------------------------------------------------*/
        
        $billing = new \Billing();
        $buf = $billing->getServiceList($year, $month);
        $serviceTable = [];
        foreach($buf as $value){
            $serviceTable[$value["number"]][] = $value;
        }
        $buf = $billing->getFlServiceList($year, $month);
        foreach($buf as $value){
            $serviceTable[$value["number"]][] = $value;
        }
        if ($close){
            $data = [
                "connected" => $connected,
                "disconnected" => $disconnected,
                "changed" => $changed,
                "connectedGu" => $connectedGu,
                "waiting" => $waiting,
                "serviceTable" => $serviceTable,
                "clientDocList" => $clientDocList,
                "block" => $blocked,
                "closed" => time()
            ];
            $this->model->agreementRegisterUpdate($year, $month, $data);
        }
        $currentRegister = $this->model->agreementRegisterGet($year, $month);
        if (isset($currentRegister["closed"]) && ($currentRegister["closed"])){
            $closed = true;
        }
        else{
            $closed = false;
        }
        if (!$print){
            $this->view->show("agreementRegister.main",[
                "connected" => $connected,
                "disconnected" => $disconnected,
                "changed" => $changed,
                "connectedGu" => $connectedGu,
                "waiting" => $waiting,
                "serviceTable" => $serviceTable,
                "clientDocList" => $clientDocList,
                "clientList" => $clientList,
                "sumList" => $sumList,
                "blocked" => $blocked,
                "year" => $year,
                "month" => $month,
                "closed" => $closed,
                "currentRegister" => $currentRegister
            ]);
        }
        else{
            $content = $this->view->show("print.agreementRegister.main",[
                "connected" => $connected,
                "disconnected" => $disconnected,
                "changed" => $changed,
                "connectedGu" => $connectedGu,
                "waiting" => $waiting,
                "serviceTable" => $serviceTable,
                "clientDocList" => $clientDocList,
                "clientList" => $clientList,
                "sumList" => $sumList,
                "blocked" => $blocked,
                "year" => $year,
                "month" => $month,
                "closed" => $closed,
                "currentRegister" => $currentRegister
            ],true);
            return $content;
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportRegister(){
        $client = new \Client\Controller();
        $account = new \Account\Controller();
        $clientList = $client->clientList();
        $lsk = [
            "gu" => [],
            "all" => []
        ];
        $kchr = [
            "gu" => [],
            "all" => []
        ];
        $kst = [
            "gu" => [],
            "all" => []
        ];
        $disconnected = [
            "all" => []
        ];
        $searchList = [];
        $renewList = [];
        foreach($clientList as $info){
            if (!$info["dnum"]){
                continue;
            }
            $info["contactList"] = $client->getContacts($info["id"]);
            $info["supportInfo"] = $client->getSupportInfo($info["id"]);
            if ($info["clientStatus"] == "Переоформлен"){
                if ($info["renewDnum"]){
                    $buf = $account->getRequisites($info["renewDnum"]);
                    $info["newName"] = isset($buf["name"]) ? $buf["name"] : "";
                    $renewList[$info["renewDnum"]] = [
                        "dnum" => $info["dnum"],
                        "name" => $info["name"],
                        "type" => $info["renewType"]
                    ];
                }
                else{
                    $info["newName"] = "Неизвестно";
                }
            }
            
            $address = getAddress($info);
            $dnum = $info["dnum"];
            
            if (!isset($searchList[$dnum])){
                $name = $info["name"];
                $bin = $info["bin"];
                $district = $info["district"];
                $searchList[$dnum] = $dnum.$name.$bin.$district;
            }
            $searchList[$dnum] .= $address;
            
            $city = mb_strtolower($info["city"]);
            if (($info["clientStatus"] == "Отключен")||($info["clientStatus"] == "Переоформлен")){
                $disconnected["all"][$info["dnum"]][] = $info;
            }
            else if (preg_match("/лисаковск/",$city)){
                if ($info["clientType"] == "ГУ"){
                    $lsk["gu"][$info["dnum"]][] = $info;
                }
                else{
                    $lsk["all"][$info["dnum"]][] = $info;
                }
            }
            else if (preg_match("/качар/",$city)){
                if ($info["clientType"] == "ГУ"){
                    $kchr["gu"][$info["dnum"]][] = $info;
                }
                else{
                    $kchr["all"][$info["dnum"]][] = $info;
                }
            }
            else{
                if ($info["clientType"] == "ГУ"){
                    $kst["gu"][$info["dnum"]][] = $info;
                }
                else{
                    $kst["all"][$info["dnum"]][] = $info;
                }
            }
        }
        $phonebook = new \Phonebook\Controller();
        $this->view->show("supportRegister.main",[
            "clientList" => [
                "kst" => $kst,
                "lsk" => $lsk,
                "kchr" => $kchr,
                "disconnected" => $disconnected,
            ],
            "phonebook" => $phonebook->getContactTable(),
            "renewList" => $renewList,
            "searchList" => $searchList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function saveRegisterComment(
            $year,
            $month,
            $tableType,
            $dnum,
            $tableKey,
            $registerComment
    ){
        $data = [
            $tableType => [
                $dnum => [
                    $tableKey => [
                        "registerComment" => $registerComment
                    ]
                ]
            ]    
        ];
        $this->model->agreementRegisterUpdate($year, $month, $data);
    }
    
    /*--------------------------------------------------*/
    
    public function getDocumentRegister(){
        $account = new \Account\Controller();
        $client = new \Client\Controller();
        $clientList = $client->clientList();
        $dnumList = $account->getAllDnums();
        $result = [];
        foreach($dnumList as $dnum){
            $docList = $account->getChronologyList($dnum);
            $buf = [
                "specification" => [],
                "additional" => []
            ];
            foreach($docList as $doc){
                if ($doc["docType"] == "specification"){
                    $buf["specification"] = $doc;
                }
                else{
                    $buf["additional"][] = $doc;
                }
            }
            $buf["registerData"] = $account->getDocumentRegisterData($dnum);
            $buf["info"] = $account->getRequisites($dnum);
            if ($buf["info"]["clientType"] == "ФЛ"){
                $result["fl"][$dnum] = $buf;
            }
            else if ($buf["info"]["clientType"] == "ГУ"){
                $result["gu"][$dnum] = $buf;
            }
            else{
                $result["all"][$dnum] = $buf;
            }
        }
        $disconnected = [];
        $blocked = [];
        $managerList = [];
        $clientDocList = [];
        $disconnectMethod = [];
        foreach($clientList as $info){
            if ($info["dnum"]){
                if (!isset($managerList[$info["dnum"]])){
                    $managerList[$info["dnum"]] = [];
                }
                $managerList[$info["dnum"]][$info["manager"]] = true;
            }
            else{
                continue;
            }
            $clientDoc = $client->getClientDocList($info["id"]);
            foreach($clientDoc as $doc){
                if ($doc["docType"] == "Заявление"){
                    if (!isset($clientDocList[$info["dnum"]])){
                        $clientDocList[$info["dnum"]] = [
                            "info" => $info,
                            "docList" => []
                        ];
                    }
                    $doc["address"] = getAddress($info);
                    $doc["manager"] = $info["manager"];
                    $doc["clientId"] = $info["id"];
                    $clientDocList[$info["dnum"]]["docList"][] = $doc;
                }
            }
            if(($info["clientStatus"] == "Отключен") && ($info["disconnectType"] == "По заявлению")){
                if (!isset($disconnected[$info["dnum"]])){
                    $disconnected[$info["dnum"]] = [];
                }
                if (!isset($disconnectMethod[$info["dnum"]])){
                    $disconnectMethod[$info["dnum"]] = "Точка";
                }
                if ($info["disconnectMethod"] == "Договор"){
                    $disconnectMethod[$info["dnum"]] = "Договор";
                }
                $info["scanFilePath"] = $info["disconnectFilePath"];
                $disconnected[$info["dnum"]][] = $info;
            }
            if($info["clientStatus"] == "Переоформлен"){
                if (!isset($disconnected[$info["dnum"]])){
                    $disconnected[$info["dnum"]] = [];
                }
                $info["scanFilePath"] = $info["renewFilePath"];
                $disconnected[$info["dnum"]][] = $info;
            }
            if($info["clientStatus"] == "Подключен"){
                $blockList = $client->getBlockList($info["id"]);
                if ($blockList){
                    if (!isset($blocked[$info["dnum"]])){
                        $blocked[$info["dnum"]] = [
                            "info" => [
                                "clientType" => $info["clientType"],
                                "name" => $info["name"],
                                "dnum" => $info["dnum"]
                            ],
                            "blockList" => []
                        ];
                    }
                    $buf = [];
                    foreach($blockList as $block){
                        $block["clientId"] = $info["id"];
                        $block["manager"] = $info["manager"];
                        $blocked[$info["dnum"]]["blockList"][] = $block;
                    }
                }
            }
        }
        return $this->view->get("documentRegister.main",[
            "accountList" => $result,
            "disconnected" => $disconnected,
            "blocked" => $blocked,
            "managerList" => $managerList,
            "clientDocList" => $clientDocList,
            "disconnectMethod" => $disconnectMethod
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getDocumentRegisterPlacementForm(
            $docType
    ){
        $params = [
            "У менеджера",
            "У клиента",
            "У офис менеджера",
            "В бухгалтерии",
            "Есть",
            "Нет"
        ];
        return $this->view->get("documentRegister.placementForm",[
            "params" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
}













