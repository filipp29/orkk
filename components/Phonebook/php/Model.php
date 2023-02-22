<?php

namespace Phonebook;

class Model {
    
    private $path;
    private $settings;
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Phonebook/";
        $this->settings = new \Settings\Client();
    }
    
    /*--------------------------------------------------*/
    
    private function makePhoneList(
            $phoneList
    ){
        if (is_array($phoneList)){
            return join(";", $phoneList);
        }
        else{
            return $phoneList;
        }
    }
    
    /*--------------------------------------------------*/
    
    private function parsePhoneList(
            $phoneList
    ){
        return explode(";", $phoneList);
    }
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $path
    ){
        return $this->path.ltrim(trim($path),"/");
    }
    
    /*--------------------------------------------------*/
    
    public function getKeyList(){
        return [
            "id" => "Id",
            "organization" => "Организация",
            "name" => "Имя",
            "role" => "Должность",
            "phoneList" => "Контакты",
            "comment" => "Примечание",
            "city" => "Город"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $contact
    ){
        if (!isset($contact["id"]) || (!$contact["id"])){
            $contact["id"] = makeId();
        }
        $contact["phoneList"] = $this->makePhoneList($contact["phoneList"]);
        $keyList = $this->getKeyList();
        $result = [];
        foreach($keyList as $key => $unused){
            $result[$key] = isset($contact[$key]) ? $contact[$key] : "";
        }
        $path = $this->getPath($contact["id"]);
        objSave($path, "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    public function delete(
            $contactId
    ){
        if ($contactId != ""){
            $path = $this->getPath($contactId);
            objKill($path);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function update(
            $contact
    ){
        if (!isset($contact["id"])){
            throw new Exception("Id is empty");
        }
        $path = $this->getPath($contact["id"]);
        if (!objCheckExist($path, "raw")){
            throw new Exception("Contact {$id} is wrong");
        }
        if (isset($contact["phoneList"])){
            $contact["phoneList"] = $this->makePhoneList($contact["phoneList"]);
        }
        $obj = objLoad($path);
        $keyList = $this-$this->getKeyList();
        foreach($keyList as $key => $unused){
            if (isset($contact[$key])){
                $obj[$key] = $contact[$key];
            }
        }
        objSave($path, "raw", $obj);
        
    }
    
    /*--------------------------------------------------*/
    
    public function getContact(
            $id
    ){
        $path = $this->getPath($id);
        if (!objCheckExist($path, "raw")){
            throw new Exception("Contact {$id} is wrong");
        }
        $obj = objLoad($path);
        if (isset($obj["phoneList"])){
            $obj["phoneList"] = $this->parsePhoneList($obj["phoneList"]);
        }
        $keyList = $this->getKeyList();
        $result = [];
        foreach($keyList as $key => $unused){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getContactList(){
        $path = $this->getPath("");
        $br = array_keys(objLoadBranch($path, true, false));
        return $br;
    }
    
    /*--------------------------------------------------*/
    
    
}














