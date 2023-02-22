<?php


namespace Debtor;


class Model {
    
    private $path;
    
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Account/";
    }
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $dnum
    ){
        return $this->path.ltrim(trim($dnum),"/")."/debtor.info";
    }
    
    /*--------------------------------------------------*/
    
    public function getKeyList(){
        return [
            "dnum",
            "type",
            "comment",
            "date",
            "lock",
            "exclude"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getTypeList(){
        return [
            "active" => "Активный",
            "debtor" => "Должник",
            "waiting" => "Ожидание",
            "shift" => "Перенос",
            "terminate" => "На расторжение"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $debt
    ){
        $clientPath = "{$this->path}{$debt["dnum"]}/";
        $path = $this->getPath($debt["dnum"]);
        if (!objCheckExist($clientPath, "raw")){
            throw new \Exception("Wrong dnum '{$debt["dnum"]}'");
        }
        $obj = objLoad($path);
        $keyList = $this->getKeyList();
        foreach($keyList as $key){
            if (!isset($obj[$key])){
                $obj[$key] = "";
            }
            $obj[$key] = isset($debt[$key]) ? $debt[$key] : "";
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function get(
            $dnum
    ){
        $path = $this->getPath($dnum);
        $obj = objLoad($path);
        unset($obj["#e"]);
        $keys = $this->getKeyList();
        $result = [];
        foreach($keys as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    
}












