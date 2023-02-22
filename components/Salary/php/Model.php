<?php

namespace Salary;

class Model {
    
    private $path;
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Salary/";
    }
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $path
    ){
        return $this->path.ltrim(trim($path),"/");
    }
    
    /*--------------------------------------------------*/
    
    public function getDocList(
            $profile,
            $year,
            $month
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/docList");
        $obj = objLoad($path);
        unset($obj["#e"]);
        $result = [];
        foreach($obj as $docId => $doc){
            $result[$docId] = \Json_u::decode($doc);
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveDocList(
            $profile,
            $year,
            $month,
            $docList
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/docList");
        $currentDocList = $this->getDocList($profile, $year, $month);
        $buf = [];
        $keyList = $this->getDocKeyList();
        foreach($docList as $docId => $doc){
            $buf[$docId] = [];
            foreach($keyList as $key){
                if (isset($doc[$key])){
                    $buf[$docId][$key] = $doc[$key];
                }
            }
        }
        $docList = array_replace_recursive($currentDocList,$buf);
        $result = [];
        foreach($docList as $docId => $doc){
            $result[$docId] = \Json_u::encode($doc);
        }
        objSave($path, "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    public function getDocKeyList(){
        return [
            "dnum",
            "docId",
            "name",
            "docType",
            "attractType",
            "clientName",
            "clientType",
            "salarySum",
            "salaryBonus",
            "salaryComment",
            "newConnect"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getParams(
            $profile,
            $year,
            $month
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/params");
        $obj = objLoad($path);
        $keyList = $this->paramsKeyList();
        $result = [];
        foreach($keyList as $key){
            $result[$key] = isset($obj[$key]) ? $obj[$key] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveParams(
            $profile,
            $year,
            $month,
            $params
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/params");
        $obj = objLoad($path);
        $keyList = $this->paramsKeyList();
        foreach($keyList as $key){
            if (isset($params[$key])){
                $obj[$key] = $params[$key];
            }
            if (!isset($obj[$key])){
                $obj[$key] = "";
            }
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function paramsKeyList(){
        return [
            "closed",
            "salary",
            "punishment",
            "reward",
            "orkk",
            "orfl",
            "orkkPercent",
            "orflPercent",
            "prepayment",
            "debtorCall",
            "plan"
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getBonus(
            $profile,
            $year,
            $month
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/bonus");
        $obj = objLoad($path);
        unset($obj["#e"]);
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function saveBonus(
            $profile,
            $year,
            $month,
            $bountyList
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/bonus");
        $obj = objLoad($path);
        foreach($bountyList as $key => $value){
            $obj[$key] = $value;
        }
        objSave($path, "raw", $obj);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteBonus(
            $profile,
            $year,
            $month,
            $bountyList
    ){
        $path = $this->getPath("{$profile}/{$year}{$month}/bonus");
        $obj = objLoad($path);
        unset($obj["#e"]);
        foreach($bountyList as $key){
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
    
    public function getProfileTimesheet(
            $profile,
            $year,
            $month
    ){
        $path = $this->getPath("Timesheet/{$year}/{$month}/{$profile}");
        $calendarPath = $this->getPath("Timesheet/{$year}/{$month}/calendar");
        $calendar = objLoad($calendarPath);
        $sheet = objLoad($path);
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $result = [];
        for($i = 1; $i <= (int)$days; $i++){
            $index = (String)$i;
            $timeStamp = strtotime("{$year}/{$month}/{$i}");
            $dayOfWeek = date("w",$timeStamp);
            if (isset($sheet[$index])){
                $result[$index] = $sheet[$index];
            }
            elseif(isset($calendar[$index])){
                if ($calendar[$index] == "h"){
                    $result[$index] = "0";
                }
                else{
                    $result[$index] = "8";
                }
            }
            elseif(in_array($dayOfWeek, [0,6])){
                $result[$index] = "0";
            }
            else{
                $result[$index] = "8";
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getTimesheetCalendar(
            $year,
            $month
    ){
        /*
         * h - выходной
         * w - рабочий
         */
        $path = $this->getPath("Timesheet/{$year}/{$month}/calendar");
        $sheet = objLoad($path);
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $result = [];
        $weekDays = \Settings\Main::weekDays();
        for($i = 1; $i <= (int)$days; $i++){
            $index = (String)$i;
            $timeStamp = strtotime("{$year}/{$month}/{$i}");
            $dayOfWeek = date("w",$timeStamp);
            $result[$index]["name"] = $weekDays[$dayOfWeek];
            $index = (string)$i;
            if (isset($sheet[$index])){
                $result[$index]["type"] = $sheet[$index];
            }
            else if(in_array($dayOfWeek, [0,6])){
                $result[$index]["type"] = "h";
            }
            else{
                $result[$index]["type"] = "w";
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveTimesheetCalendar(
            $year,
            $month,
            $data
    ){
        $path = $this->getPath("Timesheet/{$year}/{$month}/calendar");
        $sheet = objLoad($path);
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i = 1; $i <= (int)$days; $i++){
            $index = (String)$i;
            if (isset($data[$index])){
                $sheet[$index] = $data[$index];
            }
        }
        objSave($path, "raw", $sheet);
    }
    
    /*--------------------------------------------------*/
    
    public function saveTimesheetProfile(
            $profile,
            $year,
            $month,
            $data
    ){
        $path = $this->getPath("Timesheet/{$year}/{$month}/{$profile}");
        $sheet = objLoad($path);
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i = 1; $i <= (int)$days; $i++){
            $index = (String)$i;
            if (isset($data[$index])){
                $sheet[$index] = $data[$index];
            }
        }
        objSave($path, "raw", $sheet);
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryReport(
            $year,
            $month,
            $data
    ){
        
        $path = $this->getPath("Report/{$year}/{$month}/");
        foreach($data as $profile => $params){
            
            objSave($path.$profile, "raw", $params);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryReport(
            $year,
            $month
    ){
        $path = $this->getPath("Report/{$year}/{$month}/");
        $br = array_keys(objLoadBranch($path, true, false));
        $result = [];
        foreach($br as $value){
            $result[$value] = objLoad($path.$value);
            $result["timeStamp"] = isset($result[$value]["timeStamp"]) ? $result[$value]["timeStamp"] : "";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getMonthPlan(
            $year,
            $month
    ){
        $ignoreList = [
            "unmarked",
            "Timesheet"
        ];
        if (strlen($month) < 2){
            $date = "{$year}0{$month}";
        }
        else{
            $date = "{$year}{$month}";
        }
        $path = $this->getPath("");
        $profileList = array_keys(objLoadBranch($path, false, true));
        $plan = 0;
        foreach($profileList as $profile){
            if (in_array($profile, $ignoreList)){
                continue;
            }
            $obj = objLoad($this->getPath("{$profile}/{$date}/params"));
            $plan += isset($obj["plan"]) ? (int)$obj["plan"] : 0;
        }
        return $plan;
    }
    
    /*--------------------------------------------------*/
    
}










