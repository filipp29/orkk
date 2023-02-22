<?php

namespace Admin;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        parent::__construct("Admin");
        $this->model = new \Admin\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function showSalaryPanel(){
        $salary = $this->model->getSalary();
        $roleList = $this->model->getRoleList();
        $bonusList = [];
        foreach($roleList as $role){
            $bonusList[$role] = $this->model->getSalaryBonus($role);
        }
        return $this->view->get("salary.main",[
            "salary" => $salary,
            "bonusList" => $bonusList
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showPlanPanel(
            $year
    ){
        $plan = $this->model->getPlan($year);
        return $this->view->get("plan.main",[
            "plan" => $plan,
            "year" => $year
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryParams(){
        return $this->model->getSalary();
    }
    
    /*--------------------------------------------------*/
    
    public function showMainPage(){
        $salaryPanel = $this->showSalaryPanel();
        $planPanel = $this->showPlanPanel(date("Y",time()));
        return $this->view->get("main",[
            "panelList" => [
                "salary" => $salaryPanel,
                "plan" => $planPanel
            ]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalary(
            $salary
    ){
        $this->model->saveSalary($salary);
    }
    
    /*--------------------------------------------------*/
    
    public function getProfileList(
            $type
    ){
        $typeList = \Settings\Main::profileList();
        if ($type == "all"){
            return $typeList;
        }
        else{
            return isset($typeList[$type]) ? $typeList[$type] : [];
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getPlanClientTypeList(){
        return [
            "Живая агитация",
            "Партнерская сеть",
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function showSalaryBonusForm(){
        return $this->view->get("salary.newBonusForm");
    }
    
    /*--------------------------------------------------*/
    
    public function saveSalaryBonus(
            $role,
            $bonusList,
            $getBonusRow = false
    ){
        $this->model->saveSalaryBonus($role, $bonusList);
        
        if ($getBonusRow){
            return $this->view->get("salary.bonusRow",[
                "role" => $role,
                "bonusList" => $bonusList
            ]);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function deleteSalaryBonus(
            $role,
            $bonusList
    ){
        $this->model->deleteSalaryBonus($role, $bonusList);
    }
    
    /*--------------------------------------------------*/
    
    public function getSalaryBonus(
            $role
    ){
        return $this->model->getSalaryBonus($role);
    }
    
    /*--------------------------------------------------*/
    
    public function savePlan(
            $year,
            $month,
            $data
    ){
        $this->model->savePlan($year, $month, $data);
    }
    
    /*--------------------------------------------------*/
    
    public function getPlan(
            $year,
    ){
        return $this->model->getPlan($year);
    }
    
    /*--------------------------------------------------*/
    
    public function getYearPlan(
            $year
    ){
        $managerList = $this->getProfileList("manager");
        $yearPlan = $this->model->getPlan($year);
        $salaryParams = $this->getSalaryParams();
        $defaultPlan = (int)$salaryParams["plan"]["mid"];
        $plan = 0;
        foreach($yearPlan as $monthPlan){
            $plan += ($monthPlan["mid"] !== "") ? (int)$monthPlan["mid"] : $defaultPlan;
        }
        return $plan * count($managerList);
    }
    
    /*--------------------------------------------------*/
    
}















