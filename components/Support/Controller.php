<?php

namespace Support;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    
    private $model;
    
    public function __construct(){
        parent::__construct("Support");
        $this->model = new \Support\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function getClientSupportAll(
            $dnum
    ){
        $result = [];
        $supportList = $this->model->getSupportList($dnum);
        foreach($supportList as $timeStamp){
            $result[] = $this->model->getSupport($dnum, $timeStamp);
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function showMonthSupportAll(
            $year,
            $month,
            $download = false
    ){
        $account = new \Account\Controller();
        $dnumList = $account->getAllDnums();
        $accountList = [];
        $clientList = [];
        $sum = 0;
        $count = 0;
        $fullCount = 0;
        foreach($dnumList as $dnum){
            $info = $account->getRequisites($dnum);
            $clientList[$dnum] = $info;
            $clientList[$dnum]["support"] = $account->getSupportAll($dnum);
            $supportList = $this->getClientSupportAll($dnum);
            $flag = false;
            foreach($supportList as $support){
                $buf = explode(".",date("Y.m",$support["inc_time"]));
                if (($year == $buf[0]) && ($month == $buf[1])){
                    $flag = true;
                    $accountList[$dnum][] = $support;
                    $incTime = $support["inc_time"];
                    $buf = $clientList[$dnum]["support"];
                    $fullCount++;
                    if (isset($buf[$incTime])){
                        if ((int)$buf[$incTime]["rate"] > 0){
                            $sum += (int)$buf[$incTime]["rate"];
                            $count++;
                        }
                    }
                }
            }
            if ($flag){
                $first = [];
                $last = [];
                $buf = $clientList[$dnum]["support"];
                foreach($accountList[$dnum] as $key => $value){
                    if (isset($buf[$value["inc_time"]])){
                        $timeStamp = isset($buf[$value["inc_time"]]["callDate"]) ? $buf[$value["inc_time"]]["callDate"] : "";
                        if ($timeStamp){
                            $v_date = date("Y-m-d",$timeStamp);
                            $c_date = date("Y-m-d",time());
                            if ($v_date == $c_date){
                                $value["today"] = true;
                                $first[] = $value;
                            }
                            else{
                                $value["today"] = false;
                                $last[] = $value;
                            }
                        }
                        else{
                            $value["today"] = false;
                            $last[] = $value;
                        }
                    }
                    else{
                        $value["today"] = false;
                        $last[] = $value; 
                    }
                }
                $result = [];
                foreach($first as $value){
                    $result[] = $value;
                }
                foreach($last as $value){
                    $result[] = $value;
                }
                $accountList[$dnum] = $result;
                $clientList[$dnum]["contactList"] = $account->getContactList($dnum);
            }
        }
        if ($download){
            $table = [
                "accountList" => $accountList,
                "clientList" => $clientList,
                "year" => $year,
                "month" => $month,
                "averageRate" => ($count > 0) ? number_format(($sum / $count),1) : 0,
                "fullCount" => $fullCount
            ];
            $csv = new \Csv\Controller();
            $csvContent = $csv->getMonthSupport($table);
            $csv->download($csvContent);
            exit;
        }
        $this->view->show("monthSupport.main",[
            "accountList" => $accountList,
            "clientList" => $clientList,
            "year" => $year,
            "month" => $month,
            "averageRate" => ($count > 0) ? number_format(($sum / $count),1) : 0,
            "fullCount" => $fullCount
        ]);
    }
    
    
    /*--------------------------------------------------*/
    
    
}

