<?php

namespace ClientList;

class Model {
    
    private $path;
    private $settings;
    private $statusList = [];
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/oldClientList";
        $this->settings = new \Settings\Client();
        $path = "/reference/cli_status/";
        $br = array_keys(objLoadBranch($path, true, false));
        foreach($br as $value){
            $obj = objLoad($path.$value);
            $this->statusList[$obj["id"]] = $obj["value"];
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getOldList(){
        $brPath = "/bclient/";
        $br = array_keys(objLoadBranch($brPath, false, true));
        $result = [];
        foreach($br as $id){
            $path = "{$brPath}{$id}/client.cln";
            $obj = objLoad($path);
            $obj["statusValue"] = $this->getStatus($obj["status"]);
            unset($obj["#e"]);
            $result[$id] = $obj;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getStatus(
            $number
    ){
        return $this->statusList[$number];
    }
    
    /*--------------------------------------------------*/
    
    public function getDoneList(){
        $obj = objLoad($this->path);
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function addDoneList(
            $id,
            $profile
    ){
        $obj = objLoad($this->path);
        $obj[$id] = $profile;
        objSave($this->path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    
    
}













