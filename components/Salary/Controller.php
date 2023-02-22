<?php

namespace Salary;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    public function __construct(){
        parent::__construct("Salary");
        $this->model = new \Salary\Model();
    }
    
    
    /*--------------------------------------------------*/
    
    private function getDocBonus(
            $sum,
            $plan,
            $monthPlan
    ){
        $plan = (int)$plan;
        $sum = (int)$sum;
        $admin = new \Admin\Controller();
        $params = $admin->getSalaryParams();
        $planList = $params["plan"];
        foreach($monthPlan as $key => $value){
            if ($value){
                $planList[$key] = $value;
            }
        }
        if ($plan < (int)$planList["min"]){
            return 0;
        }
        else if($plan < (int)$planList["mid"]){
            $planKey = "min";
        }
        else if($plan <= (int)$planList["max"]){
            $planKey = "mid";
        }
        else{
            $planKey = "max";
        }
        $amount = $params["amount"];
        $percent = (int)$params["reward5"][$planKey];
        for($i = 4; $i >= 1; $i--){
            $index = (string)$i;
            if ($sum <= (int)$amount[$index]){
                $percent = (int)$params["reward{$index}"][$planKey];
            }
        }
        return (int)($sum * $percent / 100);
//        if ($sum <= (int)$amount["min"]){
//            if ($planKey == "max"){
//                $percent = (int)(($plan * 100) / (int)$planList["mid"]);
//                if ($percent > (int)$planList["percent"]){
//                    $percent = (int)$planList["percent"];
//                }
//                return (int)($sum * $percent / 100);
//            }
//            else{
//                return (int)($sum * (int)$params["rewardMin"][$planKey] / 100);
//            }
//        }
//        else if($sum <= (int)$amount["mid"]){
//            return (int)($sum * (int)$params["rewardMid"][$planKey] / 100);
//        }
//        else if($sum <= (int)$amount["max"]){
//            return (int)($sum * (int)$params["rewardMax"][$planKey] / 100);
//        }
//        else{
//            return 0;
//        }
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryDocList(
            $year,
            $month
    ){
        $client = new \Client\Controller();
        $account = new \Account\Controller();
        $admin = new \Admin\Controller();
        $dnumList = $account->getAllDnums();
        $result = [];
        $paramsList = [];
        $docKeyList = $this->model->getDocKeyList();
        $monthPlan = $admin->getPlan($year)[$month];
        foreach($dnumList as $dnum){
            $docList = $account->getChronologyList($dnum);
            foreach($docList as $doc){
                $buf = isset($doc["payDate"]) ? explode(".",$doc["payDate"]) : "";
                if ($buf){
                    $payYear = isset($buf[0]) ? $buf[0] : "";
                    $payMonth = isset($buf[1]) ? $buf[1] : "";
                }
                else{
                    $payYear = "";
                    $payMonth = "";
                }
                
                $payManager = isset($doc["payManager"]) ? $doc["payManager"] : "";
                if (!isset($paramsList[$payManager])){
                    $paramsList[$payManager] = $this->model->getParams($payManager, $year, $month);
                }
//                if ($paramsList[$payManager]["closed"]){
//                    continue;
//                }
                if (
                        ($payManager) && 
//                        (!isset($doc["forPayment"]) || ($doc["forPayment"] != "1")) &&
                        (($year == $payYear) && ($month == $payMonth))
                ){
                    $doc["salarySum"] = $doc["fullAmount"];
                    $result[$payManager][$doc["docId"]] = [];
                    foreach($docKeyList as $key){
                        $result[$payManager][$doc["docId"]][$key] = isset($doc[$key]) ? $doc[$key] : "";
                    }
                }
                else if(!$payManager 
                        && (!isset($doc["forPayment"]) || ($doc["forPayment"] != "1"))
                ){
                    $buf = [];
                    $flag = true;
                    foreach($doc["posList"] as $k => $pos){
                        $buf[$pos["clientId"]] = true;
                    }
                    foreach($buf as $clientId => $unused){
                        $info = $client->clientInfo($clientId);
                        $valueList = [
                            "Подключен",
                            "Ожидает подключение"
                        ];
                        if (!in_array($info["clientStatus"],$valueList)){
                            $flag = false;
                        }
                    }
                    if ($flag){
                        $doc["salarySum"] = $doc["fullAmount"];
                        $result["unmarked"][$doc["docId"]] = [];
                        foreach($docKeyList as $key){
                            $result["unmarked"][$doc["docId"]][$key] = isset($doc[$key]) ? $doc[$key] : "";
                        }
                    }
                }
            }
        }
        $changeList = [];
        $managerList = $admin->getProfileList("manager");
        
        
        foreach($managerList as $manager){
            
            $salary = $this->model->getDocList($manager, $year, $month);
//            if ($registerClosed){
//                $result[$manager] = $salary;
//                
//            }
//            else 
            if($salary){
                foreach($salary as $docId => $value){
                    if (!isset($result[$manager][$docId])){
                        continue;
                    }
                    if (!isset($result[$manager][$docId])){
                        $result[$manager][$docId] = [];
                    }
                    foreach($docKeyList as $key){
                        if (isset($value[$key]) && ($value[$key] != "")){
                            if (!isset($changeList[$manager])){
                                $changeList[$manager] = [];
                            }
                            if (!isset($changeList[$manager][$docId])){
                                $changeList[$manager][$docId] = [];
                            }
                            $changeList[$manager][$docId][$key] = $result[$manager][$docId][$key];
                            $result[$manager][$docId][$key] = $value[$key];
                            
                        }
                    }
                }
            }
        }
        $plan = [];
        $salaryParams = $admin->getSalaryParams();
        foreach(["min","mid","max"] as $key){
            if (!$monthPlan[$key]){
                $monthPlan[$key] = $salaryParams["plan"][$key];
            }
        }
        if ($monthPlan["salary"]){
            $salaryParams["manager"]["salary"] = $monthPlan["salary"];
        }
        $planTypeList = $admin->getPlanClientTypeList();
        foreach($result as $profile => $docList){
            foreach($docList as $docId => $doc){
//                if (($doc["newConnect"]) && (in_array($doc["attractType"], $planTypeList))){
                if ((isset($doc["attractType"])) && (in_array($doc["attractType"], $planTypeList))){
                    if (!isset($plan[$profile])){
                        $plan[$profile] = 0;
                    }
                    $plan[$profile] += (int)$doc["salarySum"];
                }
            }
        }
        $percentList = [];
        $bonusList = [];
        $bonusAttractList = [
            "Живая агитация"
        ];
        foreach($result as $profile => $docList){
            
            if (!isset($plan[$profile])){
                $plan[$profile] = 0;
            }
            foreach($docList as $docId => $doc){
                
                
                if ((!in_array($profile,$managerList)) || ((int)$plan[$profile] < (int)$salaryParams["plan"]["min"])){
                    $result[$profile][$docId]["salaryBonus"] = 0;
                    continue;
                }
//                if (($doc["newConnect"]) && (in_array($doc["attractType"], $planTypeList))){
                if ((isset($doc["attractType"])) && (in_array($doc["attractType"], $planTypeList))){
                    $bonus = $this->getDocBonus($doc["salarySum"], $plan[$profile],$monthPlan);
                }
                else{
                    $bonus = (int)$doc["salarySum"];
                }
                if ($result[$profile][$docId]["salaryBonus"] != ""){
                    if (!isset($changeList[$profile])){
                        $changeList[$profile] = [];
                    }
                    if (!isset($changeList[$profile][$docId])){
                        $changeList[$profile][$docId] = [];
                    }
                    $changeList[$profile][$docId]["salaryBonus"] = $bonus;
                }
                else{
                    $result[$profile][$docId]["salaryBonus"] = $bonus;
                }
                if (!isset($bonusList[$profile])){
                    $bonusList[$profile] = 0;
                }
                $bonusList[$profile] += (int)$result[$profile][$docId]["salaryBonus"];
            }
        }
        foreach($managerList as $profile){
            
            if (!isset($bonusList[$profile])){
                $bonusList[$profile] = 0;
            }
            if (!isset($plan[$profile])){
                $percentList[$profile] = [
                    "percent" => 0,
                    "color" => "red"
                ];
                continue;
            }
            $percent = (int)(((int)$plan[$profile] * 100) / $monthPlan["mid"]);
            if ($plan[$profile] < $monthPlan["min"]){
                $color = "red";
            }
            else if($plan[$profile] < $monthPlan["mid"]){
                $color = "orange";
            }
            else if($plan[$profile] <= $monthPlan["max"]){
                $color = "lightgreen";
            }
            else{
                $color = "green";
            }
            $percentList[$profile] = [
                "percent" => $percent,
                "color" => $color
            ];
        }
        return [
            "result" => $result,
            "changeList" => $changeList,
            "plan" => $plan,
            "percentList" => $percentList,
            "bonusList" => $bonusList,
            "salaryParams" => $salaryParams,
            "monthPlan" => $monthPlan
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryDocList(
            $year,
            $month,
            $salary
    ){
        foreach($salary as $profile => $docList){
            $this->model->saveDocList($profile, $year, $month, $docList);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryParams(
            $year,
            $month,
            $paramList
    ){
        foreach($paramList as $profile => $value){
            $this->model->saveParams($profile, $year, $month, $value);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryBonus(
            $year,
            $month,
            $bonusList
    ){
        foreach($bonusList as $profile => $value){
            $this->model->saveBonus($profile, $year, $month, $value);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryReport(
            $year,
            $month,
            $paramList,
            $bonusSumList
    ){
        $result = [];
        $keyList = [
            "salary",
            "punishment",
            "reward",
            "prepayment"
        ];
        $timeStamp = time();
        foreach($paramList as $manager => $params){
            foreach($keyList as $key){
                $result[$manager][$key] = $params[$key];
            }
            $result[$manager]["bonus"] = $bonusSumList[$manager];
            $result[$manager]["timeStamp"] = $timeStamp;
        }
        $this->model->saveSalaryReport($year, $month, $result);
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryReport(
            $year,
            $month
    ){
        return $this->model->getSalaryReport($year, $month);
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryAll(
            $year,
            $month,
            $params
    ){
        $account = new \Account\Controller();
        $bonusList = $params["bonusList"];
        $docList = $params["docList"];
        $paramList = $params["paramList"];
        $bonusSumList = $params["bonusSumList"];
        unset($docList["unmarked"]);
        $this->saveSalaryReport($year,$month,$paramList, $bonusSumList); 
        $this->saveSalaryBonus($year, $month, $bonusList);
        $this->saveSalaryDocList($year, $month, $docList);
        $this->saveSalaryParams($year, $month, $paramList);
        foreach($docList as $profile => $value){
            foreach($value as $docId => $doc){
                $account->setForPayment($doc["dnum"], $doc["docId"], "1");
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getTimeSheet(
            $year,
            $month
    ){
        $report = $this->model->getSalaryReport($year, $month);
        $getValue = function(
                $profile,
                $key
        )use($report){
            return isset($report[$profile][$key]) ? (int)$report[$profile][$key] : 0;
        };
        $admin = new \Admin\Controller();
        $profileRoleList = $admin->getProfileList("all");
        unset($profileRoleList["admin"]);
        $sheet = [];
        $timesheetCalendar = $this->model->getTimesheetCalendar($year, $month);
        $workDays = 0;
        foreach($timesheetCalendar as $value){
            if ($value["type"] == "w"){
                $workDays++;
            }
        }
        $keyList = [
            "salary",
            "punishment",
            "bonus",
            "prepayment",
            "reward"
        ];
        $roleList = \Settings\Main::roleList();
        foreach($profileRoleList as $role => $value){
            foreach($value as $profile){
                $sheet = $this->model->getProfileTimesheet($profile, $year, $month);
                $hours = 0;
                foreach($sheet as $value){
                    $hours += (int)$value;
                }
                $salary = $getValue($profile,"salary");
                $salary = (int)(($hours / ($workDays * 8)) * $salary);
                $punishment = $getValue($profile,"punishment");
                $bonus = $getValue($profile,"bonus");
                $prepayment = $getValue($profile,"prepayment");
                $reward = $getValue($profile,"reward");
                $params = [
                    "name" => profileGetUsername($profile),
                    "role" => $roleList[$role],
                    "hours" => $hours,
                    "salary" => $salary,
                    "bonus" => $bonus,
                    "prepayment" => $prepayment,
                    "punishment" => $punishment,
                    "reward" => $reward,
                    "total" => $salary + $bonus + $reward - $punishment - $prepayment
                ];
                $profileList[$profile]["sheet"] = $sheet;
                $profileList[$profile]["params"] = $params;
            }
        }
        return $this->view->get("timesheet.main",[
            "calendar" => $timesheetCalendar,
            "year" => $year,
            "month" => $month,
            "profileList" => $profileList,
            "workDays" => $workDays
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function saveTimesheetCalendarDay(
            $data
    ){
        $params = [
            $data["day"] => $data["type"]
        ];
        $year = $data["year"];
        $month = $data["month"];
        $this->model->saveTimesheetCalendar($year, $month, $params);
    }
    
    /*--------------------------------------------------*/
    
    public function showSalaryTable(
            $year,
            $month
    ){
        $admin = new \Admin\Controller();
        $debtor = new \Debtor\Controller();
        $billing = new \Billing();
        $balanceTable = $billing->getBalanceTable();
        $buf = $admin->getProfileList("all");
        $currentYearPlan = $this->getYearPlan($year);
        $profileList = [];
        $menuList = [];
        $paramList = [];
        $report = $this->getSalaryReport($year, $month);
        $reportDate = isset($report["timeStamp"]) ? date("d.m.Y H:i:s",$report["timeStamp"]) : "";
        foreach($buf as $type => $value){
            if ($type == "admin"){
                continue;
            }
            foreach($value as $profile){
                $paramList[$profile] = $this->model->getParams($profile, $year, $month);
                $profileList[$profile] = $type;
                $buf = explode(" ",profileGetUsername($profile));
                $menuList[$profile] = isset($buf[1]) ? $buf[1] : $buf[0];
            }
        }
        $menuList["unmarked"] = "Не отмеченные";
        $docList = $this->getSalaryDocList($year, $month);
        $planAll = 0;
        $managerList = $admin->getProfileList("manager");
        $salaryParams = $docList["salaryParams"];
        
        $progress = [];
        $keyList = [
            "min",
            "mid",
            "max",
            "limit"
        ];
        $planList = $docList["monthPlan"];
        $monthPlan = (int)$planList["mid"] * count($managerList);
        foreach($managerList as $profile){
            $planProfile = isset($docList["plan"][$profile]) ? (int)$docList["plan"][$profile] : 0;
            $planAll += $planProfile;
            
        }
        
        $salaryList = [];
        $otherBonusList = [];
        
        foreach($paramList as $profile => $value){
            if ($profileList[$profile] == "admin"){
                continue;
            }
            $currentBonus = $this->model->getBonus($profile, $year, $month);
            $roleBonus = $admin->getSalaryBonus($profileList[$profile]);
            $salaryBuf = $salaryParams[$profileList[$profile]];
            if ($profileList[$profile] == "leader"){
                $salaryBuf["orkkPercent"] = (int)($planAll * 100 / $monthPlan);
                $salaryBuf["orflPercent"] = 0;
            }
            if($profileList[$profile] == "assistant"){
                $debtorCalling = (int)$salaryParams["assistant"]["debtorCalling"];
                $debtorTable = $debtor->getDebtorTable(false);
                $debtorPercent = (int)$debtorTable["percent"];
                $roleBonus["Обвон должников"] = (int)((100 - $debtorPercent) * $debtorCalling / 100);
            }
            
            $otherBonus = 0;
            foreach($roleBonus as $key => $val){
                if (isset($currentBonus[$key]) && ($currentBonus[$key] != "")){
                    if (!isset($docList["changeList"][$profile])){
                        $docList["changeList"][$profile] = [];
                    }
                    if (!isset($docList["changeList"][$profile]["bonus"])){
                        $docList["changeList"][$profile]["bonus"] = [];
                    }
                    $docList["changeList"][$profile]["bonus"][$key] = $roleBonus[$key];
                    $roleBonus[$key] = $currentBonus[$key];
                }
                $otherBonus += (int)$roleBonus[$key];
            }
            
            foreach($currentBonus as $key => $val){
                if (!isset($roleBonus[$key])){
                    $roleBonus[$key] = $currentBonus[$key];
                    $otherBonus += $roleBonus[$key];
                }
            }
            $otherBonusList[$profile] = $roleBonus;
            foreach($salaryBuf as $key => $val){
                if (isset($value[$key]) && ($value[$key] != "")){
                    if (!isset($docList["changeList"][$profile])){
                        $docList["changeList"][$profile] = [];
                    }
                    if (!isset($docList["changeList"][$profile]["params"])){
                        $docList["changeList"][$profile]["params"] = [];
                    }
                    $docList["changeList"][$profile]["params"][$key] = $salaryBuf[$key];
                    $salaryBuf[$key] = $value[$key];
                }
            }
            $salaryBuf["reward"] = $value["reward"] ? $value["reward"] : "0";
            $salaryBuf["punishment"] = $value["punishment"] ? $value["punishment"] : "0";
            $salaryBuf["prepayment"] = $value["prepayment"] ? $value["prepayment"] : "0";
            if ($profileList[$profile] == "leader"){
                if ((int)$salaryBuf["orkkPercent"] >= 100){
                    $salaryBuf["orkkSum"] = $salaryBuf["orkk"];
                }
                else{
                    $salaryBuf["orkkSum"] = (int)((int)$salaryBuf["orkk"] * (int)$salaryBuf["orkkPercent"] / 100); 
                }
                if ((int)$salaryBuf["orflPercent"] >= 100){
                    $salaryBuf["orflSum"] = $salaryBuf["orfl"];
                }
                else{
                    $salaryBuf["orflSum"] = (int)((int)$salaryBuf["orfl"] * (int)$salaryBuf["orflPercent"] / 100); 
                }
            }
            $salaryList[$profile] = $salaryBuf;
            if ($profileList[$profile] == "leader"){
                $docList["bonusList"][$profile] = (int)$salaryBuf["orkkSum"] + (int)$salaryBuf["orflSum"];
            }
            else if($profileList[$profile] != "manager"){
                
            }
            if (isset($docList["bonusList"][$profile])){
                $docList["bonusList"][$profile] += (int)$otherBonus;
            }
            else{
                $docList["bonusList"][$profile] = (int)$otherBonus;
            }
            $bonus = isset($docList["bonusList"][$profile]) ? (int)$docList["bonusList"][$profile] : 0;
            $salary = (int)$salaryBuf["salary"];
            $reward = (int)$salaryBuf["reward"];
            $prepayment = (int)$salaryBuf["prepayment"];
            $punishment = (int)$salaryBuf["punishment"];
            $sumList[$profile] = $bonus + $salary + $reward - $punishment - $prepayment;
        }
        $planList["limit"] = (int)((int)$salaryParams["plan"]["percent"] * (int)$planList["mid"] / 100);
        $rateList = [];
        $rewardMin = $salaryParams["reward1"];
        foreach($managerList as $profile){
            $rateList[$profile] = "0.0";
            $planProfile = isset($docList["plan"][$profile]) ? (int)$docList["plan"][$profile] : 0;
            $accpet = [];
            $text = [];
            $bar = [];
            
            foreach($keyList as $k){
                $accpet[$k] = (int)$planList[$k] <= $planProfile;
                $text[$k] = $planList[$k];
                
            }
            if ($planProfile >= (int)$planList["min"]){
                $planDelta = (int)$planList["mid"] - (int)$planList["min"];
                $profileDelta = $planProfile - (int)$planList["min"];
                $prc = (($profileDelta * 100) / $planDelta);
                $bar["min"] = ($prc < 100) ? (int)$prc : 100;
                $rateList[$profile] = (int)$rewardMin["min"] / 100;
            }
            else{
                $bar["min"] = 0;
            }
            if ($planProfile >= (int)$planList["mid"]){
                $planDelta = (int)$planList["max"] - (int)$planList["mid"];
                $profileDelta = $planProfile - (int)$planList["mid"];
                $prc = (($profileDelta * 100) / $planDelta);
                $bar["mid"] = ($prc < 100) ? (int)$prc : 100;
                $rateList[$profile] = (int)$rewardMin["mid"] / 100;
            }
            else{
                $bar["mid"] = 0;
            }
            if ($planProfile >= (int)$planList["max"]){
                $planDelta = (int)$planList["limit"] - (int)$planList["max"];
                $profileDelta = $planProfile - (int)$planList["max"];
                $prc = (($profileDelta * 100) / $planDelta);
                $bar["max"] = ($prc < 100) ? (int)$prc : 100;
                $rateList[$profile] = (int)$docList["percentList"][$profile]["percent"] / 100;
            }
            else{
                $bar["max"] = 0;
            }
            if ($planProfile >= (int)$planList["limit"]){
                $bar["limit"] = 100;
                $rateList[$profile] = (int)$salaryParams["plan"]["percent"] / 100;
            }
            else{
                $bar["limit"] = 0;
            }
            $progress[$profile] = [
                "accept" => $accpet,
                "text" => $text,
                "bar" => $bar
            ];
        }
        $yearProgress = [
            "accept" => [
                "min" => true,
                "mid" => false
            ],
            "text" => [
                "min" => "0",
                "mid" => ""
            ],
            "bar" => []
        ];
        $yearPlan = 0;
        foreach($progress as $value){
            $buf = isset($value["text"]["mid"]) ? (int)$value["text"]["mid"] : 0;
            $yearPlan = $admin->getYearPlan($year);
            $yearPlanPercent = (int)($currentYearPlan * 100 / $yearPlan);
            $yearProgress["text"]["mid"] = $yearPlan;
            if ($yearPlanPercent >= 100){
                $yearProgress["accept"]["mid"] = true;
                $yearProgress["bar"]["min"] = 100;
                $yearProgress["bar"]["mid"] = ($yearPlanPercent > 100) ? 100 : 0;
            }
            else{
                $yearProgress["accept"]["mid"] = false;
                $yearProgress["bar"]["min"] = $yearPlanPercent;
                $yearProgress["bar"]["mid"] = 0;
            }
        }
        return $this->view->get("salaryTable.main",[
            "docList" => $docList["result"],
            "profileList" => $profileList,
            "menuList" => $menuList,
            "changeList" => $docList["changeList"],
            "year" => $year,
            "month" => $month,
            "planList" => $docList["plan"],
            "percentList" => $docList["percentList"],
            "paramList" => $paramList,
            "salaryParams" => $docList["salaryParams"],
            "salaryList" => $salaryList,
            "bonusList" => $docList["bonusList"],
            "sumList" => $sumList,
            "otherBonusList" => $otherBonusList,
            "reportDate" => $reportDate,
            "progress" => $progress,
            "rateList" => $rateList,
            "balanceTable" => $balanceTable,
            "currentYearPlan" => $currentYearPlan,
            "yearPlanPercent" => $yearPlanPercent,
            "yearPlan" => $yearPlan,
            "yearProgress" => $yearProgress
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showChangePayManagerForm(){
        $admin = new \Admin\Controller();
        $profileList = $admin->getProfileList("manager");
        $managerList = [];
        foreach($profileList as $profile){
            $managerList[$profile] = profileGetUsername($profile);
        }
        return $this->view->get("salaryTable.changeManagerForm",[
            "managerList" => $managerList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showProfileDayForm(
            $data
    ){
        return $this->view->get("timesheet.profileDayForm",$data);
    }
    
    /*--------------------------------------------------*/
    
    public function saveTimesheetProfileDay(
            $data
    ){
        $params = [
            $data["day"] => $data["value"]
        ] ;
        $this->model->saveTimesheetProfile($data["profile"], $data["year"], $data["month"], $params);
    }
    
    /*--------------------------------------------------*/
    
    public function getYearPlan(
            $year
    ){
        $plan = 0;
        for($i = 1; $i <= 12; $i++){
            $month = ($i < 10) ? ("0".(string)$i) : (string)$i;
            $plan += $this->model->getMonthPlan($year, $month);
        }
        return $plan;
    }
    
    /*--------------------------------------------------*/
    
    
}












