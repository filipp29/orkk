<?php

namespace Account;


class Model {
    
    private $path;
    
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath(). "/Account";
        
    }
    
    /*--------------------------------------------------*/
    
    public function create(
            $doc
    ){
        
        $path = $this->path. "/{$doc["dnum"]}/";
        $obj = [
            "dnum" => $doc["dnum"]
        ];
        if (objCheckExist($path. "account.info", "raw")){
            throw new Exception("Договор {$doc["dnum"]} уже существует");
        }
        objSave($path. "account.info", "raw", $obj);
        $docId = makeId();
        $doc["docId"] = $docId;
        $posId = makeId();
        $doc["posId"] = $posId;
        objSave($path. "docList/{$doc["docId"]}/{$doc["posId"]}.pos", "raw", $doc);
        $this->makeClientList($doc["dnum"], $doc["docId"]);
        return $docId;
    }
    
    /*--------------------------------------------------*/
    
    public function createEmptyAccount(
            $dnum
    ){
        $path = $this->path. "/{$dnum}/";
        $obj = [
            "dnum" => $dnum
        ];
        if (objCheckExist($path. "account.info", "raw")){
            return false;
        }
        objSave($path. "account.info", "raw", $obj);
        return true;
    }
    
    /*--------------------------------------------------*/
    
    public function deletePos(
            $dnum,
            $docId,
            $posId
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/{$posId}.pos";
        objKill($path);
        $this->makeClientList($dnum, $docId);
    }
    
    /*--------------------------------------------------*/
    
    public function checkDnum(
            $dnum
    ){
        $path = $this->path. "/{$dnum}/docList/";
        $br = array_keys(objLoadBranch($path, false, true));
        if (count($br) == 0){
            objUnlinkBranch($this->path. "/{$dnum}/");
        }
    }
    
    /*--------------------------------------------------*/
    
    private function makeClientList(
            $dnum,
            $docId
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/";
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        foreach($br as $value){
            if ($value == "clients.list"){
                continue;
            }
            $obj = objload($path. "{$value}");
            $result[$obj["posId"]] = $obj["clientId"];
        }
        if (!$result){
            objUnlinkBranch($path);
            $this->checkDnum($dnum);
            return;
        }
        objSave($path. "clients.list", "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    public function change(
            $doc
    ){
        $path = $this->path. "/{$doc["dnum"]}/";
        if (!objCheckExist($path, "raw")){
            if ($doc["dnum"]){
                $this->create($doc);
                return;
            }
            throw new \Exception("Договор {$doc["dnum"]} не существует");
        }
        if (objCheckExist($path. "docList/{$doc["docId"]}/", "raw")){
            $docId = $doc["docId"];
        }
        else{
            $docId = makeId();
        }
        if (objCheckExist($path. "docList/{$doc["docId"]}/{$doc["posId"]}.pos", "raw")){
            $posId = $doc["posId"];
        }
        else{
            $posId = makeId();
        }
        $doc["docId"] = $docId;
        $doc["posId"] = $posId;
        objSave($path. "docList/{$docId}/{$posId}.pos", "raw", $doc);
        $this->makeClientList($doc["dnum"], $doc["docId"]);
        return $doc;
    }
    
    
    
    /*--------------------------------------------------*/
    
    public function getDoc(
            $dnum,
            $docId,
            $onlyParams = false
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/";
        $doc = [];
        $doc["docId"] = $docId;
        $doc["dnum"] = $dnum;
        $doc["comment"] = "";
        $doc["posList"] = [];
        $doc["fullAmount"] = 0;
        $doc["newConnect"] = "";
        $br = array_keys(objLoadBranch($path, true, false));
        $attractType = "";
        foreach($br as $value){
            if ($value == "clients.list"){
                continue;
            }
            $obj = objLoad($path. "{$value}");
            $client = new \Client\Controller();
            $clientInfo = $client->clientInfo($obj["clientId"]);
            $obj["address"] = getAddress($clientInfo);
            $obj["clientStatus"] = $clientInfo["clientStatus"];
            $obj["clientStatusShow"] = $clientInfo["clientStatusShow"];
            $clientName = $clientInfo["name"];
            $clientType = $clientInfo["clientType"];
            if (($obj["posType"] == "Дополнительная точка") || ($obj["posType"] == "Спецификация")){
                $amount = (int)$clientInfo["amount"];
                $attractType = $clientInfo["attractType"];
                $newConnect = true;
//                if (!isset($obj["safekeepingPlacement"])){
//                    $obj["safekeepingPlacement"] = $obj["docPlacement"];
//                }
                if (!isset($obj["transferActPlacement"])){
                    $obj["transferActPlacement"] = $obj["docPlacement"];
                }
            }
            else{
                if (isset($obj["param_amount"]) && ($obj["param_amount"])){
                    $amount = (int)$obj["param_amount"];
                }
                else{
                    $amount = 0;
                }
                if (!$attractType){
                    $attractType = isset($obj["param_attractType"]) ? $obj["param_attractType"] : "";
                }
                $newConnect = false;
            }
            $doc["newConnect"] = $newConnect ? "1" : "";
            $doc["fullAmount"] += (int)$amount;
            $doc["attractType"] = !(isset($doc["attractType"]) && ($doc["attractType"])) ? $attractType : $doc["attractType"];
            $doc["clientName"] = !(isset($doc["clientName"]) && ($doc["clientName"])) ? $clientName : $doc["clientName"];
            $doc["clientType"] = !(isset($doc["clientType"]) && ($doc["clientType"])) ? $clientType : $doc["clientType"];
            $doc["posList"][] = $obj;
            if ($obj["comment"]){
                $doc["comment"] .= "\n--------------------\n". $obj["comment"];
            }
            if ($onlyParams){
                break;
            }
        }
        
        $doc["comment"] = nl2br($doc["comment"]);
        if (isset($doc["posList"][0])){
            $pos = $doc["posList"][0];
            $doc["name"] = $pos["docName"];
            $placementKeys = \Settings\Account::placementKeys();
            foreach($placementKeys as $key => $unused){
                $doc[$key] = isset($pos[$key]) ? $pos[$key] : "";
            }
            $doc["forPayment"] = isset($pos["forPayment"]) ? $pos["forPayment"] : "0";
            $doc["docType"] = isset($pos["docType"]) ? $pos["docType"] : "";
            $doc["timeStamp"] = $pos["timeStamp"];
            $doc["payManager"] = isset($pos["payManager"]) ? $pos["payManager"] : "";
            $doc["payDate"] = isset($pos["payDate"]) ? $pos["payDate"] : "";
        }
        if ($onlyParams){
            unset($doc["posList"]);
            unset($doc["comment"]);
        }
        return $doc;
    }
    
    /*--------------------------------------------------*/
    
    public function getDocList(
            $dnum
    ){
        return array_keys(objLoadBranch($this->path. "/{$dnum}/docList", false, true));
    }
    
    /*--------------------------------------------------*/
    
    public function getPos(
            $dnum,
            $docId,
            $posId
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/{$posId}.pos";
        $obj = objLoad($path);
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function getClientList(
            $dnum,
            $docId
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/";
        $obj =  objLoad($path. "clients.list");
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function getPosList(
            $dnum,
            $docId
    ){
        $path = $this->path. "/{$dnum}/docList/{$docId}/";
        $br = objLoadBranch($path, true, false);
        unset($br["clients.list"]);
        $result = [];
        foreach($br as $key => $value){
            $buf = explode(".", $key);
            $result[] = $buf[0];
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function isDnumExists(
            $dnum
    ){
        $path = $this->path. "/{$dnum}/";
        return objCheckExist($path, "raw");
    }
    
    /*--------------------------------------------------*/
    
    public function getAllDnums(){
        $path = $this->path."/";
        return array_keys(objLoadBranch($path, false, true));
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportList(
            $dnum
    ){
        $path = $this->path. "/{$dnum}/Support/";
        $br = array_keys(objLoadBranch($path, true, false));
        return $br;
    }
    
    /*--------------------------------------------------*/
    
    public function getSupport(
            $dnum,
            $supportName
    ){
        $path = $this->path. "/{$dnum}/Support/{$supportName}";
        $obj = objLoad($path);
        unset($obj["#e"]);
        return $obj;
        
    }
    
    /*--------------------------------------------------*/
    
    public function saveSupport(
            $dnum,
            $support
    ){
        $path = $this->path. "/{$dnum}/Support/{$support["name"]}";
        objSave($path, "raw", $support);
    }
    
    /*--------------------------------------------------*/
    
    public function getDocumentRegisterData(
            $dnum
    ){
        $path = $this->path. "/{$dnum}/docReg.info";
        $obj = objLoad($path);
        $keyList = [
            "agreement" => "",
            "act" => "У менеджера",
            "disclaimer" => "Нет"
        ];
        $result = [];
        foreach($keyList as $key => $value){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : $value;
        }
        $result["dnum"] = $dnum;
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveDocumentRegisterData(
            $data
    ){
        
        $path = $this->path. "/{$data["dnum"]}/";
        if (!objCheckExist($path, "raw")){
            throw new \Exception("Dnum {$data["dnum"]} is wrong");
        }
        $path .= "docReg.info";
        $obj = objLoad($path);
        $keyList = [
            "agreement" => "",
            "act" => "У менеджера",
            "disclaimer" => "Нет"
        ];
        foreach($keyList as $key => $value){
            if (isset($data[$key])){
                $obj[$key] = $data[$key];
            }
            if (!isset($obj[$key])){
                $obj[$key] = $value;
            }
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
}












