<?php

namespace Report;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');
require_once $_SERVER['DOCUMENT_ROOT']. $globalPath. "/components/Report/php/ClientReport.php";
require_once $_SERVER['DOCUMENT_ROOT']. $globalPath. "/components/Report/php/ContactReport.php";
require_once $_SERVER['DOCUMENT_ROOT']. $globalPath. "/components/Report/php/SupportReport.php";

/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    private $clientReport;
    private $contactReport;
    private $supportReport;
    
    public function __construct(){
        parent::__construct("Report");
        $this->model = new \Report\Model();
        $this->clientReport = new \Report\ClientReport();
        $this->contactReport = new \Report\ContactReport();
        $this->supportReport = new \Report\SupportReport();
    }
    
    /*--------------------------------------------------*/
    
    public function getMainPage(){
        return $this->view->get("main");
    }
    
    /*--------------------------------------------------*/
    
    public function getClientReportPage(
            $type
    ){
        return $this->view->get("clientReport.main",[
            "reportType" => $type
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getConnectReportTable(
            $params
    ){
        if (isset($params["period"]["first"]["end"])){
            $params["period"]["first"]["end"] = (int)$params["period"]["first"]["end"] + 64799;
        }
        $report = $this->clientReport->getConnectReport($params);
        if (!$params["city"]){
            $params["city"][] = "all";
        }
        if(!$params["manager"]){
            $params["manager"][] = "all";
        }
        return $this->view->get("clientReport.connectTable",[
            "result" => $report["result"],
            "delta" => $report["delta"],
            "params" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientReportTable(
            $params
    ){
        if (isset($params["period"]["first"]["end"])){
            $params["period"]["first"]["end"] = (int)$params["period"]["first"]["end"] + 64799;
        }
        $report = $this->clientReport->getClientReport($params);
        return $this->view->get("clientReport.clientTable",[
            "report" => $report,
            "period" => $params["period"]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getAmountReportTable(
            $params
    ){
        if (isset($params["period"]["first"]["end"])){
            $params["period"]["first"]["end"] = (int)$params["period"]["first"]["end"] + 64799;
        }
        $report = $this->clientReport->getAmountReport($params);
        return $this->view->get("clientReport.amountTable",[
            "report" => $report,
            "period" => $params["period"]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getContactReportPage(
            $type
    ){
        return $this->view->get("contactReport.main",[
            "reportType" => $type
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getEmailReportTable(
            $params
    ){
        $report = $this->contactReport->getEmailReport($params);
        return $this->view->get("contactReport.emailTable",[
            "report" => $report
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getContactReportTable(
            $params
    ){
        $report = $this->contactReport->getContactReport($params);
        return $this->view->get("contactReport.contactTable",[
            "report" => $report
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportReportPage(){
        return $this->view->get("supportReport.main");
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportReportTable(
            $params
    ){
        if ($params["type"] == "all"){
            $report = $this->supportReport->getSupportReportAll($params);
        }
        else{
            $report = $this->supportReport->getSupportReportAll($params,true);
        }
        return $this->view->get("supportReport.supportTable",[
            "report" => $report
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getConnectSumReportTable(
            $params
    ){
        $report = $this->clientReport->getConnectSumReport($params);
        return $this->view->get("clientReport.connectSumTable",[
            "report" => $report,
            "period" => $params["period"]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getGuReportTable(
            $params
    ){
        $report = $this->clientReport->getGuReport($params);
        return $this->view->get("clientReport.guTable",[
            "report" => $report,
            "period" => $params["period"]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getDistrictReportTable(
            $params
    ){
        $report = $this->clientReport->getDistrictReport($params);
        return $this->view->get("clientReport.districtTable",[
            "report" => $report,
            "period" => $params["period"]
        ]);
    }
    
    /*--------------------------------------------------*/
    
    
    
    
}













