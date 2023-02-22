<?php

namespace Account;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        parent::__construct("Account");
        $this->model = new \Account\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $data
    ){
        $new = true;
        if (isset($data["params"]["dnum"]) && ($data["params"]["dnum"])){
            $dnum = $data["params"]["dnum"];
            $new = false;
        }
        else{
            $dnum = (String)random_int(1000, 7000);
        }
        
        if (isset($data["doc"])){
            $data["doc"]["dnum"] = $dnum;
            if ($new){
                $this->model->create($data["doc"]);
            }
            else{
                $this->model->change($data["doc"]);
            }
            return $dnum;
        }
        else{
            return null;
        }
    }
    
    /*--------------------------------------------------*/
    
    private function changeStaticIp(
            $doc
    ){
        $client = new \Client\Controller();
        $params = $client->clientInfo($doc["clientId"]);
        $currentDate = (int)date("Ymd",time());
        if ($doc["param_activateDate"]){
            $date = (int)date("Ymd",$doc["param_activateDate"]);
            
            if (($date <= $currentDate) && ((!isset($doc["checked"])) || ($doc["checked"] != "1"))){
                $doc["checked"] = "1";
                $this->model->change($doc);
                $params["staticIp"] = "1";
                $client->saveClient([
                    "params" => $params
                ]);
            }
        }
    }
    
    
    /*--------------------------------------------------*/
    
    private function changeAddress(
            $doc
    ){
        $keyList = [
            "city",
            "streetType",
            "street",
            "buildingType",
            "building",
            "flatType",
            "flat"
        ];
        $client = new \Client\Controller();
        $params = $client->clientInfo($doc["clientId"]);
        $currentDate = (int)date("Ymd",time());
        $flag = false;
        if ($doc["param_activateDate"]){
            $date = (int)date("Ymd",$doc["param_activateDate"]);
            if (($date <= $currentDate) && ((!isset($doc["checked"])) || ($doc["checked"] != "1"))){
                $doc["checked"] = "1";
                $this->model->change($doc);
                foreach($keyList as $key){
                    if ($doc["param_{$key}"]){
                        $params[$key] = $doc["param_{$key}"];
                        $flag = true;
                    }
                }
                if ($flag){
                    $client->saveClient([
                        "params" => $params
                    ]);
                }
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    private function changeRequisits(
            $doc
    ){
        $keyList = [
            "name",
            "iban",
            "bik",
            "bank",
            "legalCity",
            "legalStreetType",
            "legalStreet",
            "legalBuildingType",
            "legalBuilding",
            "legalFlatType",
            "legalFlat",
        ];
        $client = new \Client\Controller();
        $params = $client->clientInfo($doc["clientId"]);
        $currentDate = (int)date("Ymd",time());
        $flag = false;
        if ($doc["param_activateDate"]){
            $date = (int)date("Ymd",$doc["param_activateDate"]);
            
            if (($date <= $currentDate) && ((!isset($doc["checked"])) || ($doc["checked"] != "1"))){
                $doc["checked"] = "1";
                $this->model->change($doc);
                foreach($keyList as $key){
                    if ($doc["param_{$key}"]){
                        $params[$key] = $doc["param_{$key}"];
                        $flag = true;
                    }
                }
                if ($flag){
                    $client->saveClient([
                        "params" => $params
                    ]);
                }
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function checkPos(
            $dnum,
            $docId,
            $posId
    ){
        
        $doc = $this->model->getPos($dnum, $docId, $posId);
        $posType = $doc["posType"];
        switch($posType):
            case "Аренда статического IP адреса":
                $this->changeStaticIp($doc);
                break;
            case "Смена местонахождения":
                $this->changeAddress($doc);
                break;
            case "Смена реквизитов":
                $this->changeRequisits($doc);
                break;
        endswitch;
    }
    
    /*--------------------------------------------------*/
    
    public function changePos(
            $doc
    ){
        
        $obj = $this->model->getPos($doc["dnum"], $doc["docId"], $doc["posId"]);
        foreach($doc as $key => $value){
            $obj[$key] = $value;
        }
        $obj["checked"] = "0";
        $newDoc = $this->model->change($obj);
        $this->checkPos($newDoc["dnum"], $newDoc["docId"], $newDoc["posId"]);
    }
    
    /*--------------------------------------------------*/
    
    public function getMainList(
            $dnum
    ){
        $docList = $this->model->getDocList($dnum);
        $result = [];
        foreach($docList as $value){
            $docId = explode(".", $value)[0];
            $doc = $this->model->getDoc($dnum, $docId);
            foreach($doc["posList"] as $v){
                if ($v["posType"] = "Спецификация"){
                    $result[] = $v["clientId"];
                }
            }
            
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getParentDoc(
            $dnum,
            $clientId
    ){
        $docList = $this->model->getDocList($dnum);
        foreach($docList as $value){
            $docId = $value;
            $doc = $this->model->getDoc($dnum, $docId);
            foreach($doc["posList"] as $v){
                
                if (($v["posType"] == "Спецификация") || ($v["posType"] == "Дополнительная точка")){
                    if ($v["clientId"] == $clientId){
                        return $v;
                    }
                }
            }
            
        }
        return [];
    }
    
    /*--------------------------------------------------*/
    
    public function getChronologyList(
            $dnum
    ){
        $docList = $this->model->getDocList($dnum);
        $result = [];
        foreach($docList as $docId){
            $doc = $this->model->getDoc($dnum, $docId);
            $result[] = $doc;
        }
        usort($result,"sortByTimeStamp");
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getAllPosList(
            $dnum
    ){
        $result = [];
        $docList = $this->model->getDocList($dnum);
        foreach($docList as $docId){
            $posList = $this->model->getPosList($dnum, $docId);
            foreach($posList as $posId){
                $result[] = $this->model->getPos($dnum, $docId, $posId);
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function setDocPlacement(
            $dnum,
            $docId,
            $docPlacement,
            $placementType = "docPlacement"
    ){
        $posList = $this->model->getPosList($dnum, $docId);
        foreach($posList as $posId){
            $pos = $this->model->getPos($dnum, $docId, $posId);
            $pos[$placementType] = $docPlacement;
            $this->model->change($pos);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function setForPayment(
            $dnum,
            $docId,
            $forPayment
    ){
        $posList = $this->model->getPosList($dnum, $docId);
        foreach($posList as $posId){
            $pos = $this->model->getPos($dnum, $docId, $posId);
            $pos["forPayment"] = $forPayment;
            $this->model->change($pos);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function setPayManager(
            $dnum,
            $docId,
            $payManager
    ){
        $posList = $this->model->getPosList($dnum, $docId);
        foreach($posList as $posId){
            $pos = $this->model->getPos($dnum, $docId, $posId);
            $pos["payManager"] = $payManager;
            $this->model->change($pos);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function showNewDocForm(
            $dnum,
            $docId = ""
    ){
        
        $this->view->show("newDocForm",[
            "dnum" => $dnum,
            "docId" => $docId
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getAdditionalName(
            $dnum
    ){
        $docList = $this->model->getDocList($dnum);
        $docCount = 1;
        foreach($docList as $docId){
            $doc = $this->model->getDoc($dnum, $docId);
            $docType = $doc["docType"];
            if ($docType == "additional"){
                $docCount++;
            }
        }
        return "Дополнительное соглашение №{$docCount}";
    }
    
    /*--------------------------------------------------*/
    
    public function getClientList(
            $dnum
    ){
        $client = new \Client\Controller();
        $docList = $this->model->getDocList($dnum);
        $buf = [];
        foreach($docList as $docId){
            $clientList = $this->model->getClientList($dnum, $docId);
            foreach($clientList as $key => $clientId){
                $buf[$clientId] = true;
            }
        }
        return array_keys($buf);
    }
    
    /*--------------------------------------------------*/
    
    public function showClientList(
            $dnum,
            $curDocId = ""
    ){
        $client = new \Client\Controller();
        $docList = $this->model->getDocList($dnum);
        $buf = [];
        foreach($docList as $docId){
            $clientList = $this->model->getClientList($dnum, $docId);
            foreach($clientList as $key => $clientId){
                $buf[$clientId] = true;
            }
        }
        $result = [];
        $skipList = [
            "Переоформлен",
            "Отключен"
        ];
        foreach($buf as $key => $value){
            $clientInfo = $client->clientInfo($key);
            if (in_array($clientInfo["clientStatus"],$skipList)){
                continue;
            }
            $address = getAddress($clientInfo);
            $name = getFullName($clientInfo);
            $result[$key] = [
                "address" => $address,
                "name" => $name
            ];
        }
        $this->view->show("clientList",[
            "clientList" => $result,
            "docId" => $curDocId
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showAdditionalTypeList(){
        $typeList = \Settings\Main::docType();
        unset($typeList["Спецификация"],$typeList["Дополнительная точка"]);
        $this->view->show("additionalList",[
            "typeList" => $typeList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function addAdditional(
            $clientId,
            $posType,
            $docId = ""
    ){
        
        $client = new \Client\Controller();
        $clientInfo = $client->clientInfo($clientId);
        $dnum = $clientInfo["dnum"];
        if ($docId){
            $buf = $this->model->getDoc($dnum, $docId);
            $docName = $buf["name"];
        }
        else{
            $docName = $this->getAdditionalName($clientInfo["dnum"]);
        }
        $doc = [
            "dnum" => $clientInfo["dnum"],
            "docId" => $docId,
            "posId" => "",
            "clientId" => $clientId,
            "posType" => $posType,
            "docType" => "additional",
            "docName" => $docName,
            "comment" => "",
            "docPlacement" => "У менеджера",
            "filePath" => "",
            "fileName" => "",
            "timeStamp" => time()
        ];
        $paramList = \Settings\Account::posTypeParamList()[$posType];
        foreach($paramList as $value){
            $doc["param_". $value] = "";
        }
        $this->model->change($doc);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientDocList(
            $clientId,
            $getAll = false
    ){
        $client = new \Client\Controller();
        $dnum = $client->clientInfo($clientId)["dnum"];
        if (!$dnum){
            return [];
        }
        $docList = $this->model->getDocList($dnum);
        $result = [];
        foreach($docList as $docId){
            $clientList = $this->model->getClientList($dnum, $docId);
            foreach($clientList as $posId => $clId){
                if ($clientId == $clId){
                    $pos = $this->model->getPos($dnum, $docId, $posId);
                    if ((!$getAll) && (($pos["posType"] == "Спецификация") || ($pos["posType"] == "Дополнительная точка"))){
                        continue;
                    }
                    $result[] = $pos;
                }
            }
        }
        
        return sortByKey($result, "timeStamp", true);
    }
    
    /*--------------------------------------------------*/
    
    public function getSpecificationId(
            $dnum
    ){
        $docList = $this->model->getDocList($dnum);
        $result = [];
        foreach($docList as $docId){
            $clientList = $this->model->getClientList($dnum, $docId);
            foreach($clientList as $posId => $clId){
                $pos = $this->model->getPos($dnum, $docId, $posId);
                if ($pos["posType"] == "Спецификация"){
                    return $docId;
                }
            }
        }
        return null;
    }
    
    
    
    /*--------------------------------------------------*/
    
    public function deleteClient(
            $clientId
    ){
        $client = new \Client\Controller();
        $dnum = $client->clientInfo($clientId)["dnum"];
        $docList = $this->model->getDocList($dnum);
        foreach($docList as $docId){
            $clientList = $this->model->getClientList($dnum, $docId);
            foreach($clientList as $posId => $clId){
                if ($clientId == $clId){
                    $this->model->deletePos($dnum, $docId, $posId);
                }
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function deletePos(
            $dnum,
            $docId,
            $posId
    ){
        $this->model->deletePos($dnum, $docId, $posId);
    }
    
    /*--------------------------------------------------*/
    
    public function getDoc(
            $dnum,
            $docId    
    ){
        return $this->model->getDoc($dnum, $docId);
    }
    
    /*--------------------------------------------------*/
    
    public function getAmount(
            $clientId
    ){
        $client = new \Client\Controller;
        $clientInfo = $client->clientInfo($clientId);
        $dnum = $clientInfo["dnum"];
        if(!$dnum){
            return 0;
        }
        
    }
    
    /*--------------------------------------------------*/
    
    public function addComment(
            $comment
    ){
        $client = new \Client\Controller();
        $dnum = $comment["dnum"];
        $docId = $comment["docId"];
        $posId = $comment["posId"];
        $pos = $this->model->getPos($dnum, $docId, $posId);
        if ($pos){
            $pos["comment"] = $comment["text"];
            $pos["fileName"] = $comment["fileName"];
            $pos["filePath"] = $comment["filePath"];
            if (($pos["posType"] == "Спецификация") || ($pos["posType"] == "Дополнительная точка")){
                $client->saveClient([
                    "params" => [
                        "id" => $pos["clientId"],
                        "filePath" => $pos["filePath"],
                        "fileName" => $pos["fileName"]
                    ]
                ]);
            }
            $this->model->change($pos);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function isDnumExists(
            $dnum
    ){
        return $this->model->isDnumExists($dnum);
    }
    
    /*--------------------------------------------------*/
    
    public function getRequisites(
            $dnum
    ){
        $client = new \Client\Controller();
        $clientList = $this->getClientList($dnum);
        if ($clientList){
            $clientId = $clientList[0];
        }
        else{
            return [];
        }
        $info = $client->clientInfo($clientId);
        $reqKeyList = \Settings\Client::commonParamKeys();
        $result = [];
        foreach($reqKeyList as $key => $value){
            $result[$key] = isset($info[$key]) ? $info[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function changeNumber(
            $dnum,
            $newDnum
    ){
        if (!$this->model->createEmptyAccount($newDnum)){
            return false;
        }
        $docList = $this->model->getDocList($dnum);
        foreach($docList as $docId){
            $posList = $this->model->getPosList($dnum, $docId);
            foreach($posList as $posId){
                $pos = $this->model->getPos($dnum, $docId, $posId);
                $pos["dnum"] = $newDnum;
                $this->model->change($pos);
                $this->model->deletePos($dnum, $docId, $posId);
            }
        }
        return true;
    }
    
    /*--------------------------------------------------*/
    
    public function getNewNumber(){
        $billing = new \Billing();
        return $billing->getNumber();
    }
    
    /*--------------------------------------------------*/
    
    public function getAllDnums(){
        return $this->model->getAllDnums();
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportAll(
            $dnum
    ){
        $supportList = $this->model->getSupportList($dnum);
        $result = [];
        foreach($supportList as $supportName){
            $support = $this->model->getSupport($dnum, $supportName);
            $result[$supportName] = $support;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportList(
            $dnum
    ){
        return $this->model->getSupportList($dnum);
    }
    
    /*--------------------------------------------------*/
    
    public function getSupport(
            $dnum,
            $supportName
    ){
        return $this->model->getSupport($dnum, $supportName);
    }
    
    /*--------------------------------------------------*/
    
    public function saveSupport(
            $dnum,
            $name,
            $rate,
            $comment,
            $callDate
    ){
        if (!$name){
            return;
        }
        $support = [
            "name" => $name,
            "rate" => $rate,
            "comment" => $comment,
            "callDate" => $callDate
        ];
        $this->model->saveSupport($dnum, $support);
    }
    
    /*--------------------------------------------------*/
    
    public function getContactList(
            $dnum
    ){
        $client = new \Client\Controller();
        $clientList = $this->getClientList($dnum);
        $result = [];
        foreach($clientList as $clientId){
            $contactList = $client->getContacts($clientId);
            foreach($contactList as $contact){
                $result[$contact["phone"]] = $contact;
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getMainContractDate(
            $dnum
    ){
        $client = new \Client\Controller();
        $mainList = $this->getMainList($dnum);
        if ($mainList){
            $info = $client->clientInfo($mainList[0]);
            return $info["contractDate"];
        }
        else{
            return "";
        }
    }
    
    /*--------------------------------------------------*/
    
    public function showContactListForm(
            $dnum
    ){
        $contactList = $this->getContactList($dnum);
        $this->view->show("contactListForm",[
            "contacts" => $contactList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getFullAmount(
            $dnum
    ){
        $client = new \Client\Controller();
        $clientList = $this->getClientList($dnum);
        $fullAmount = 0;
        foreach($clientList as $clientId){
            $info = $client->clientInfo($clientId);
            if ($info["clientStatus"] == "Подключен"){
                $tarif = $client->getTarif($clientId);
                $fullAmount += (int)$tarif["amount"];
            }
        }
        return $fullAmount;
    }
    
    /*--------------------------------------------------*/
    
    public function getDocumentRegisterData(
            $dnum
    ){
        return $this->model->getDocumentRegisterData($dnum);
    }
    
    /*--------------------------------------------------*/
    
    public function saveDocumentRegisterData(
            $data
    ){
        $this->model->saveDocumentRegisterData($data);
    }
    
    
    
    /*--------------------------------------------------*/
    
    public function showDocumentPlacementForm(
            $dnum,
            $docId
    ){
        $doc = $this->model->getDoc($dnum, $docId, true);
        $keyList = \Settings\Account::placementKeys();
        foreach($keyList as $key => $unused){
            if (!$doc[$key]){
                $doc[$key] = "Не требуется";
            }
        }
        return $this->view->get("documentPlacementForm",[
            "info" => $doc
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function changeDocumentAttractType(
            $dnum,
            $docId,
            $attractType
    ){
        $client = new \Client\Controller();
        $posList = $this->model->getPosList($dnum, $docId);
        $typeList = [
            "Дополнительная точка",
            "Спецификация"
        ];
        foreach($posList as $posId){
            $pos = $this->model->getPos($dnum, $docId, $posId);
            if (!in_array($pos["posType"], $typeList)){
                $pos["param_attractType"] = $attractType;
                $this->model->change($pos);
            }
            else{
                $clientId = $pos["clientId"];
                $client->saveClient([
                    "params" => [
                        "id" => $clientId,
                        "attractType" => $attractType
                    ]
                ]);
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function showChangePayManagerForm(
            $dnum,
            $docId
    ){
        $account = new \Account\Controller();
        $doc = $account->getDoc($dnum, $docId);
        $year = (isset($doc["payDate"]) && ($doc["payDate"])) ? explode(".",$doc["payDate"])[0] : date("Y",time());
        $month = (isset($doc["payDate"]) && ($doc["payDate"])) ? explode(".",$doc["payDate"])[1] : date("m",time());
        $buf = \Settings\Main::profileList();
        $profileList = $buf["manager"];
        unset($buf);
        $managerList = [];
        foreach($profileList as $profile){
            $managerList[$profile] = profileGetUsername($profile);
        }
        return $this->view->get("changePayManagerForm",[
            "yearValue" => $year,
            "monthValue" => $month,
            "doc" => $doc,
            "managerList" => $managerList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function changePayment(
            $dnum,
            $docId,
            $data
    ){
        $posList = $this->model->getPosList($dnum, $docId);
        if ($data["year"] && $data["month"]){
            $date = "{$data["year"]}.{$data["month"]}";
        }
        else{
            $date = "";
        }
        foreach($posList as $posId){
            $pos = $this->model->getPos($dnum, $docId, $posId);
            $pos["forPayment"] = $data["forPayment"];
            $pos["payManager"] = $data["payManager"];
            $pos["payDate"] = $date;
            $this->model->change($pos);
        }
    }
    
    /*--------------------------------------------------*/
    
}















