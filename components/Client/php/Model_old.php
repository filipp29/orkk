<?php

namespace Client;

class Model {
    
    private $path;
    private $settings;
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Client/";
        $this->settings = new \Settings\Client();
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $params,
            $contacts = null
    ){
        $history = true;
        if ((!isset($params["id"])) || (!$params["id"])){
            $params["id"] = makeId();
            $history = false;
        }
        $this->saveParams($params,$history);
        if ($contacts !== null){
            if ($contacts){
                objSave($this->path."{$params["id"]}/contacts.info", "raw", $contacts);
            }
            else{
                objKill($this->path."{$params["id"]}/contacts.info");
            }
        }
        return $params["id"];
    }
    
    /*--------------------------------------------------*/
    
    private function getClientStatusHistory(
            $clientId
    ){
        $result = objLoad($this->path. "{$clientId}/statusHistory");
        unset($result["#e"]);
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    private function saveClientStatusHistory(
            $params
    ){
        $currentParams = $this->getParams($params["id"]);
        $currentClientStatus = $currentParams["clientStatus"];
        if (isset($params["clientStatus"]) && ($params["clientStatus"] != $currentClientStatus)){
            $clientStatus = $params["clientStatus"];
            $history = $this->getClientStatusHistory($params["id"]);
            if ((count($history) == 0) && ($currentClientStatus)){
                $history["0"] = $$currentClientStatus;
            }
            if ($clientStatus == "Подключен"){
                
            }
        }
        else{
            echo "????";
        }
    }
    
    /*--------------------------------------------------*/
    
    public function saveParams(
            $params,
            $history = false
    ){
        $paramsCur = objLoad($this->path. "{$params["id"]}/client.info");
        $paramsOld = [];
        $changed = false;
        $keys = array_keys($this->settings->paramKeys());
        foreach($keys as $key){
            
            if (!isset($paramsCur[$key])){
                $paramsCur[$key] = "";
                $changed = true;
            }
            $paramsOld[$key] = $paramsCur[$key];
            if (isset($params[$key])){
                if ($paramsCur[$key] != $params[$key]){
                    $paramsCur[$key] = $params[$key];
                    $changed = true;
                }
            }
            
        }
        if (!$changed){
            return;
        }
        if ($_COOKIE["login"] == "filip"){
            $this->saveClientStatusHistory($params);
            exit();
        }
        if ($history){
            objSave($this->path. "{$params["id"]}/history/". time(), "raw", $paramsOld + ["author" => $_COOKIE["login"]]);
            $paramsCur["changeDate"] = time();
        }
        else{
            $paramsCur["createDate"] = time();
        }
        objSave($this->path."{$params["id"]}/client.info", "raw", $paramsCur);
    }
    
    /*--------------------------------------------------*/
    
    public function checkId(
            $clientId
    ){
        $path = $this->path."{$params["id"]}/";
        return objCheckExist($path, "raw");
    }
    
    /*--------------------------------------------------*/
    
    public function addComment(
            $clientId,
            $comment
    ){
        objSave($this->path. "{$clientId}/comments/{$comment["timeStamp"]}", "raw", $comment);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteComment(
            $clientId,
            $commentId
    ){
        objKill($this->path. "{$clientId}/comments/{$commentId}");
        
    }
    
    /*--------------------------------------------------*/
    
    public function getParams(
            $id
    ){
        $obj =  objLoad($this->path."{$id}/client.info");
        unset($obj["#e"]);
        
        $result = [];
        $keyList = array_keys(\Settings\Client::paramKeys());
        foreach($keyList as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        $result["clientId"] = $id;
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function deleteClient(
            $id
    ){
        objUnlinkBranch($this->path."{$id}/");
    }
    
    /*--------------------------------------------------*/
    
    public function getContacts(
            $id
    ){
        $obj = objLoad($this->path."{$id}/contacts.info");
        unset($obj["#e"]);
        $result = [];
        foreach($obj as $key => $value){
            $buf = explode("_",$key);
            $result[$buf[0]][$buf[1]] = $value;
        }
        return $result;
    }
    
    
    
    /*--------------------------------------------------*/
    
    public function getCommentList(
            $clientId
    ){
        $path = $this->path. "{$clientId}/comments/";
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        foreach($br as $value){
            $obj = objLoad($path. $value);
            unset($obj["#e"]);
            $result[] = $obj;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getClientList(){
        $br = array_keys(objLoadBranch($this->path, false, true));
        return $br;
    }
    
    /*--------------------------------------------------*/
    
    public function setMain(
            $clientId,
            $main
    ){
        $path = $this->path. "{$clientId}/client.info";
        $obj = objLoad($path);
        $obj["main"] = $main;
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function setDnum(
            $clientId,
            $dnum
    ){
        $path = $this->path. "{$clientId}/client.info";
        $obj = objLoad($path);
        $obj["dnum"] = $dnum;
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function getChangeHistory(
            $clientId
    ){
        $paramKeys = \Settings\Client::paramKeys();
        $path = $this->path. "{$clientId}/";
        $br = array_keys(objLoadBranch($path. "history/", true, false));
        sort($br);
        $history = [];
        foreach($br as $value){
            $buf = objLoad($path. "history/{$value}");
            unset($buf["#e"]);
            $buf["historyTimeStamp"] = $value;
            $history[] = $buf;
        }
        $buf = objLoad($path. "client.info");
        unset($buf["#e"]);
        $history[] = $buf;
        $result = [];
        for($i = 0; $i < count($history) - 1; $i++){
            $changeList = [];
            $prev = $history[$i];
            $next = $history[$i + 1];
            
            foreach($paramKeys as $key => $value){
                $prevValue = isset($prev[$key]) ? $prev[$key] : "";
                $nextValue = isset($next[$key]) ? $next[$key] : "";
                if ($prevValue != $nextValue){
                    $changeList[$key] = [
                        "name" => ucfirst($paramKeys[$key]),
                        "prev" => $prevValue,
                        "next" => $nextValue
                    ];
                }
            }
            unset($changeList["changeDate"]);
            if (count($changeList)){
                $result[] = [
                    "timeStamp" => $prev["historyTimeStamp"],
                    "author" => $prev["author"],
                    "changeList" => $changeList,
                    "type" => "change"
                ];
            }
        }
        usort($result,"sortByTimeStampReverse");
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    public function getBlockKeyList(){
        return [
            "blockStart",
            "blockEnd",
            "comment",
            "author",
            "timeStamp",
            "author",
            "filePath",
            "fileName",
            "filePathEnd",
            "fileNameEnd",
            "twoDocs",
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getCurrentBlock(
            $clientId
    ){
        $path = $this->path. "{$clientId}/Block/current.block";
        $obj = objLoad($path);
        $result = [];
        $keyList = $this->getBlockKeyList();
        foreach($keyList as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function isCurrentBlock(
            $clientId
    ){
        $path = $this->path. "{$clientId}/Block/current.block";
        return objCheckExist($path, "raw");
    }
    
    /*--------------------------------------------------*/
    
    public function getBlock(
            $clientId,
            $timeStamp
    ){
        $path = $this->path. "{$clientId}/Block/{$timeStamp}.block";
        $obj = objLoad($path);
        $result = [];
        $keyList = $this->getBlockKeyList();
        foreach($keyList as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getBlockList(
            $clientId
    ){
        $path = $this->path. "{$clientId}/Block/";
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        foreach($br as $key => $value){
            $name = explode(".",$value)[0];
            if ($name == "current"){
                $buf = $this->getCurrentBlock($clientId);
                $buf["blockType"] = "current";
                $result[] = $buf;
            }
            else{
                $buf = $this->getBlock($clientId, $name);
                $buf["blockType"] = "history";
                $result[] = $buf;
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveBlock(
            $clientId,
            $block,
            $current = false
    ){
        if ($current){
            $name = "current";
        }
        else{
            $name = $block["timeStamp"];
        }
        $path = $this->path. "{$clientId}/Block/{$name}.block";
        $obj = objLoad($path);
        $keyList = $this->getBlockKeyList();
        foreach($keyList as $key){
            if (!isset($obj[$key])){
                $obj[$key] = "";
            }
            if (isset($block[$key])){
                $obj[$key] = $block[$key];
            }
        }
        
        objSave($path, "row", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteCurrentBlock(
            $clientId
    ){
        $path = $this->path. "{$clientId}/Block/current.block";
        objKill($path);
    }
    
    /*--------------------------------------------------*/
    
    public function saveBlockHistory(
            $clientId,
            $data
    ){
        if (!isset($data["timeStamp"]) || (!$data["timeStamp"])){
            $data["timeStamp"] = time();
        }
        $path = $this->path. "{$clientId}/BlockHistory/{$data["timeStamp"]}";
        $obj = objLoad($path);
        foreach($data as $key => $value){
            $obj[$key] = $value;
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function getBlockHistory(
            $clientId
    ){
        $path = $this->path. "{$clientId}/BlockHistory/";
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        foreach($br as $key => $value){
            $obj = objLoad($path.$value);
            unset($obj["#e"]);
            $result[] = $obj;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportInfo(
            $clientId
    ){
        $obj =  objLoad($this->path."{$clientId}/support.info");
        unset($obj["#e"]);
        
        $result = [];
        $keyList = array_keys(\Settings\Client::clientSupportParams());
        foreach($keyList as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveSupportInfo(
            $clientId,
            $params
    ){
        $path = $this->path. "{$clientId}/support.info";
        $result = $this->getSupportInfo($clientId);
        $keyList = \Settings\Client::clientSupportParams();
        foreach($keyList as $key => $value){
            if (isset($params[$key])){
                $result[$key] = $params[$key];
            }
            if (!isset($result[$key])){
                $result[$key] = "";
            }
        }
        objSave($path, "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    
}













