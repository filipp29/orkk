<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Client;

/**
 * Description of Document
 *
 * @author Admin
 */
class Document {
    private $path;
    private $settings;
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Client/";
        $this->settings = new \Settings\Client();
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $clientId,
            $doc
    ){
        if (!isset($doc["id"]) || !$doc["id"]){
            $doc["id"] = makeId();
        }
        if (!isset($doc["timeStamp"])){
            $doc["timeStamp"] = time();
        }
        if (!isset($doc["author"])){
            $doc["author"] = $_COOKIE["login"];
        }
        $result = [];
        $keyList = $this->getKeys();
        foreach($keyList as $key){
            $result[$key] = isset($doc[$key]) ? $doc[$key] : "";
        }
        $path = $this->path. "{$clientId}/DocList/{$doc["id"]}";
        objSave($path, "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    public function getDoc(
            $clientId,
            $docId
    ){
        $path = $this->path. "{$clientId}/DocList/{$docId}";
        $obj = objLoad($path);
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function getDocList(
            $clientId
    ){
        $path = $this->path. "{$clientId}/DocList/";
        return array_keys(objLoadBranch($path, true, false));
    }
    
    /*--------------------------------------------------*/
    
    public function getKeys(){
        return [
            "timeStamp",
            "comment",
            "author",
            "docType",
            "docName",
            "filePath",
            "fileName",
            "id",
            "placement",
            "register",
            "date"
        ];
    }
    
    /*--------------------------------------------------*/
    
}



















