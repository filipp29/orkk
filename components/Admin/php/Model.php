<?php

namespace Admin;


class Model {
    
    private $path;
    
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath(). "/Admin/";
    }
    
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $path
    ){
        return $this->path.ltrim(trim($path),"/");
    }
    
    /*--------------------------------------------------*/
    
    public function getSalary(){
        $path = $this->getPath("Salary");
        $keys = $this->getSalaryKeys();
        $result = [];
        foreach($keys as $key => $value){
            $obj = objLoad("{$path}/{$key}");
            foreach($value as $k){
                $result[$key][$k] = isset($obj[$k]) ? $obj[$k] : "";
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalary(
            $salary
    ){
        $path = $this->getPath("Salary");
        $keys = $this->getSalaryKeys();
        foreach($keys as $key => $value){
            $obj = objLoad("{$path}/{$key}");
            foreach($value as $k){
                if (isset($salary[$key][$k])){
                    $obj[$k] = $salary[$key][$k];
                }
                if (!isset($obj[$k])){
                    $obj[$k] = "";
                }
            }
            objSave("{$path}/{$key}", "raw", $obj);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryBonus(
            $role
    ){
        $path = $this->getPath("Salary/bonus/{$role}");
        $obj = objLoad($path);
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryBonus(
            $role,
            $bonusList
    ){
        $path = $this->getPath("Salary/bonus/{$role}");
        $obj = objLoad($path);
        foreach($bonusList as $key => $value){
            $obj[$key] = $bonusList[$key];
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteSalaryBonus(
            $role,
            $bonusList
    ){
        $path = $this->getPath("Salary/bonus/{$role}");
        $obj = objLoad($path);
        unset($obj["#e"]);
        foreach($bonusList as $key){
            unset($obj[$key]);
        }
        if (count($obj) > 0){
            objSave($path, "raw", $obj);
        }
        else{
            objKill($path);
        }
        
    }
    
    /*--------------------------------------------------*/
    
    public function getPlan(
            $year
    ){
        $path = $this->getPath("Plan/{$year}/");
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        $keyList = $this->getPlanKeyList();
        for($i = 1; $i <= 12; $i++){
            if ($i < 10){
                $month = "0".(String)$i;
            }
            else{
                $month = (String)$i;
            }
            $params = objLoad($path.$month);
            $result[$month] = [];
            foreach($keyList as $key){
                $result[$month][$key] = isset($params[$key]) ? $params[$key] : "";
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function savePlan(
            $year,
            $month,
            $data
    ){
        $keyList = $this->getPlanKeyList();
        $path = $this->getPath("Plan/{$year}/{$month}");
        $obj = objLoad($path);
        foreach($keyList as $key){
            if (isset($data[$key])){
                $obj[$key] = $data[$key];
            }
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function getPlanKeyList(){
        return [
            "salary",
            "min",
            "mid",
            "max"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getRoleList(){
        return [
            "manager",
            "leader",
            "assistant",
            "marketer"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryKeys(){
        return [
            "plan" => [
                "min",
                "mid",
                "max",
                "percent",
                "monthPlan"
            ],
            "amount" => [
                "1",
                "2",
                "3",
                "4",
            ],
            "reward1" => [
                "min",
                "mid",
                "max",
            ],
            "reward2" => [
                "min",
                "mid",
                "max",
            ],
            "reward3" => [
                "min",
                "mid",
                "max",
            ],
            "reward4" => [
                "min",
                "mid",
                "max",
            ],
            "reward5" => [
                "min",
                "mid",
                "max",
            ],
            "manager" => [
                "salary",
            ],
            "leader" => [
                "salary",
                "orkk",
                "orfl"
            ],
            "assistant" => [
                "salary",
                "debtorCalling"
            ],
            "marketer" => [
                "salary",
            ]
        ];
    }
    
    /*--------------------------------------------------*/
    
}












