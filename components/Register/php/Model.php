<?php

namespace Register;

class Model {
    
    private $path;
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Register/";
    }
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $path
    ){
        return $this->path.ltrim(trim($path),"/");
    }
    
    /*--------------------------------------------------*/
    
    public function agreementRegisterSave(
            $year,
            $month,
            $data
    ){
        $obj = [
            "data" => \Json_u::encode($data)
        ];
        $path = $this->getPath("{$year}/{$month}.info");
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function agreementRegisterUpdate(
            $year,
            $month,
            $data
    ){
        $currentData = $this->agreementRegisterGet($year, $month);
        $result = array_replace_recursive($currentData,$data);
        $result["changeDate"] = time();
        $result["changeAuthor"] = $_COOKIE["login"];
        $this->agreementRegisterSave($year, $month, $result);
    }
    
    /*--------------------------------------------------*/
    
    public function agreementRegisterGet(
            $year,
            $month
    ){
        $path = $this->getPath("{$year}/{$month}.info");
        $obj = objLoad($path);
        if (isset($obj["data"]) && ($obj["data"])){
            return \Json_u::decode($obj["data"]);
        }
        else{
            return [];
        }
    }
    
    /*--------------------------------------------------*/
    
    
}










