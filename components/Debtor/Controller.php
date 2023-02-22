<?php

namespace Debtor;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        parent::__construct("Debtor");
        $this->model = new \Debtor\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function save(
            $debt
    ){
        $account = new \Account\Controller();
        if (!$account->isDnumExists($debt["dnum"])){
            throw new \Exception("Wrong client id '{$debt["clientId"]}'");
        }
        $this->model->save($debt);
    }
    
    /*--------------------------------------------------*/
    
    public function getKeyList(){
        return $this->model->getKeyList();
    }
    
    /*--------------------------------------------------*/
    
    public function checkAll(){
        $account = new \Account\Controller();
        $billing = new \Billing();
        $accountList = $account->getAllDnums();
        $balanceTable = $billing->getBalanceTable();
        $amountTable = $billing->getAmountList();
        foreach($accountList as $dnum){
            $balance = isset($balanceTable[$dnum]) ? (int)$balanceTable[$dnum] : 0;
            $amount = isset($amountTable[$dnum]) ? (int)$amountTable[$dnum] : 0;
            if (($balance + (2 * $amount)) > 2){
                $debt = [
                    "dnum" => $dnum,
                    "type" => "active",
                    "date" => ""
                ];
                
                $this->save($debt);
            }
            else{
                $debt = $this->model->get($dnum);
                
                if (!$debt["dnum"]){
                    $debt["dnum"] = $dnum;
                    $debt["date"] = "";
                }
                if ((!$debt["type"]) || ($debt["type"] == "active")){
                    $debt["type"] = "debtor";
                }
                $this->save($debt);
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getDebtorTable(
            $showView = true
    ){
        $account = new \Account\Controller();
        $billing = new \Billing();
        $dnumList = $account->getAllDnums();
        $balanceList = $billing->getBalanceTable();
        $amountList = $billing->getAmountList();
        $accountList = [
            "terminated" => [],
            "active" => [],
            "debtor" => [],
            "fl" => [],
            "gu" => [],
            "shift" => [],
            "wait" => [],
            "forTerminate" => []
        ];
        $amountSum = [
            "all" => 0,
            "flGu" => 0,
            "work" => 0,
        ];
        $debtSum = [
            "all" => 0,
            "forTerminate" => 0,
            "wait" => 0,
            "work" => 0
        ];
        $debt = [
            "gu" => [
                "lock" => [],
                "exclude" => [],
                "all" => []
            ],
            "fl" => [
                "lock" => [],
                "exclude" => [],
                "all" => []
            ],
            "debtor" => [
                "lock" => [],
                "exclude" => [],
                "all" => []
            ]
        ];
        foreach($dnumList as $dnum){
            $info = $account->getRequisites($dnum);
            $info["balance"] = isset($balanceList[$dnum]) ? (int)$balanceList[$dnum] : "Нет данных";
            $info["amount"] = isset($amountList[$dnum]) ? (int)$amountList[$dnum] : "Нет данных";
            if (($info["balance"] == "Нет данных") || ($info["amount"] == "Нет данных")){
                continue;
            }
            $info["debt"] = $this->model->get($dnum);
            $info["contactList"] = $account->getContactList($dnum);
            $type = $info["debt"]["type"];
            $amount = is_numeric($info["amount"]) ? $info["amount"] : 0; 
            $amountSum["all"] += $amount;
            if (($info["clientType"] == "ФЛ") || ($info["clientType"] == "ГУ")){
                $amountSum["flGu"] += $amount;
            }
            else{
                $amountSum["work"] += $amount;
            }
            if (isset($info["terminated"]) && ($info["terminated"])){
                $accountList["terminated"][] = $info;
            }
            else if($info["debt"]["type"] != "debtor"){
                if ($info["debt"]["type"] != "active"){
                    $debtSum["all"] += $amount;
                }
                if ($info["debt"]["type"] == "shift"){
                    $debtSum["work"] += $amount;
                }
                switch($type):
                    case "wait":
                        $debtSum["wait"] += $amount;
                        break;
                    case "forTerminate":
                        $debtSum["forTerminate"] += $amount;
                        break;
                endswitch;
                $accountList[$info["debt"]["type"]][] = $info;
            }
            else if($info["debt"]["type"] == "debtor"){
                $lock = isset($info["debt"]["lock"]) ? $info["debt"]["lock"] : "";
                $exclude = isset($info["debt"]["exclude"]) ? $info["debt"]["exclude"] : "";
                $debtSum["all"] += $amount;
                if (!$exclude){
                    $debtSum["work"] += $amount;
                }
                switch ($info["clientType"]):
                    case "ГУ":
                        $type = "gu";
                        break;
                    case "ФЛ":
                        $type = "fl";
                        break;
                    default:
                        $type = "debtor";
                        break;
                endswitch;
                if ($lock){
                    $debt[$type]["lock"][] = $info;
                }
                else if($exclude){
                    $debt[$type]["exclude"][] = $info;
                }
                else{
                    $debt[$type]["all"][] = $info;
                }
            }
        }
        $sortByDelta = function(
                $a,
                $b
        ){
            $a_amount = is_numeric($a["amount"]) ? (int)$a["amount"] : 0;
            $a_balance = is_numeric($a["balance"]) ? (int)$a["balance"] : 0;
            $b_amount = is_numeric($b["amount"]) ? (int)$b["amount"] : 0;
            $b_balance = is_numeric($b["balance"]) ? (int)$b["balance"] : 0;
            $a_delta = ($a_amount == 0) ? 0 : ($a_balance / $a_amount);
            $b_delta = ($b_amount == 0) ? 0 : ($b_balance / $b_amount);
            if ($a_delta > $b_delta){
                return 1;
            }
            if ($a_delta < $b_delta){
                return -1;
            }
            return 0;
        };
        $sortByDate = function(
                $a,
                $b
        ){
            $a_date = isset($a["debt"]["date"]) ? (int)$a["debt"]["date"] : 0;
            $b_date = isset($b["debt"]["date"]) ? (int)$b["debt"]["date"] : 0;
            if ($a_date > $b_date){
                return -1;
            }
            if ($a_date < $b_date){
                return 1;
            }
            return 0;
        };
        $order = [
            "lock",
            "exclude",
            "all"
        ];
        foreach($debt as $key => $value){
            foreach($order as $type){
                $ar = $value[$type];
                usort($ar,$sortByDelta);
                foreach($ar as $info){
                    $accountList[$key][] = $info;
                }
            }
        }
        usort($accountList["active"],$sortByDelta);
        usort($accountList["shift"],$sortByDate);
        $percent = (int)($debtSum["work"] * 100 / $amountSum["work"]);
        if ($showView){
            return $this->view->get("debtorTable.main",[
                "accountList" => $accountList,
                "amountSum" => $amountSum,
                "debtSum" => $debtSum,
                "percent" => $percent
            ]);
        }
        else{
            return [
                "percent" => $percent,
                "debtSum" => $debtSum,
                "amountSum" => $amountSum,
                "accountList" => $accountList
            ];
        }
    }
    
    /*--------------------------------------------------*/
    
}













