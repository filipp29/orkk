<?php
//if (($_COOKIE["login"] != "filipp") 
////        && ($_COOKIE["login"] != "nadezhdat")
//){
//    echo "<h1>Ведутся работы<h1>";
//    exit();
//}
error_reporting(E_ALL); 
ini_set('display_errors', 1);
mb_internal_encoding('cp1251');
date_default_timezone_set ('Asia/Almaty');
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
date_default_timezone_set("Asia/Almaty");
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/Json_u.php');

require_once "../php/settings/Main.php";
require_once "../php/settings/Client.php";
require_once "../php/settings/Account.php";
require_once "../php/lib/mainFunctions.php";
/*Classes--------------------------------------------------*/
require_once './Support/Controller.php';
require_once './Csv/Controller.php';
require_once './Admin/Controller.php';
if (($_COOKIE["login"] == "filipp") 
        || ($_COOKIE["login"] == "nadezhdat")
){
    require_once './Salary/Controller.php';
    require_once './Report/Controller.php';
}
require_once './Phonebook/Controller.php';
require_once './Main/Controller.php';
require_once './Debtor/Controller.php';
require_once './Account/Controller.php';
require_once './Client/Controller.php';
require_once './Register/Controller.php';
require_once '../TreeExplorer/Controller_inner.php';
require_once '../php/classes/Billing.php';

require_once './ClientList/Controller.php';


/*--------------------------------------------------*/

function debug(){
    $timeStamp = $_GET["time"];
    $clientId = $_GET["clientId"];
    $client = new \Client\Controller();
    $tarif = $client->getTarif($clientId, $timestamp);
}

/*--------------------------------------------------*/

function showBalance(){
    
}

/*--------------------------------------------------*/

function getIndexPage(){
    $main = new \Main\Controller();
    $main->getInitPage();
}

/*--------------------------------------------------*/

function downloadCsvFromData(){
    $data = Json_u::decode($_POST["data"]);
    $csv = new \Csv\Controller();
    $csvContent = $csv->getCsvFromArray($data);
    $csv->download($csvContent);
    exit;
}

/*--------------------------------------------------*/

function saveClient(){
    $client = new \Client\Controller();
    $data = isset($_GET["data"]) ? Json_u::decode($_GET["data"]) : [];
    $clientId = $client->saveClient($data);
    echo $clientId;
//    if (isset($data["comment"])){
//        $client->addComment($clientId,$data["comment"]);
//    }
}

/*--------------------------------------------------*/

function getSettings(){
    
    $settings = [
        "Main" => \Settings\Main::getAll(),
        "Client" => \Settings\Client::getAll(),
        "Account" => \Settings\Account::getAll(),
        "role" => getRole(),
        "actionBanList" => \Settings\Main::actionBanList()
    ];
    
    echo \Json_u::encode($settings);
}

/*--------------------------------------------------*/

function getSecondPage(){
    $id = isset($_GET["id"]) ? $_GET["id"] : "";
    $client = new \Client\Controller();
    $client->getSecondPage($id);
}

/*--------------------------------------------------*/

function getClientCreatePage(){
    $client = new \Client\Controller();
    $client->getCreatePage();
}

/*--------------------------------------------------*/

function getClientListPage(){
    $main = new \Main\Controller();
    $main->getClientListPage();
}

/*--------------------------------------------------*/

function getClientCard(){
    $id = isset($_GET["id"]) ? $_GET["id"] : "";
    $client = new \Client\Controller();
    $client->getClientCard($id);
}

/*--------------------------------------------------*/

function changeDocPlacement(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $docPlacement = $_GET["docPlacement"];
    $clientDoc = $_GET["clientDoc"];
    $clientId = $_GET["clientId"];
    if ($clientDoc){
        $client = new \Client\Controller();
        $client->changeClientDocPlacement($clientId, $docId, $docPlacement);
    }
    else{
        $account = new \Account\Controller();
        $account->setDocPlacement($dnum, $docId, $docPlacement);
    }
}

/*--------------------------------------------------*/

function changeForPayment(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $forPayment = $_GET["forPayment"];
    $account = new \Account\Controller();
    $account->setForPayment($dnum, $docId, $forPayment);
}

/*--------------------------------------------------*/

function getNewDocForm(){
    $dnum = $_GET["dnum"];
    $account = new \Account\Controller();
    if (isset($_GET["docId"])){
        $account->showNewDocForm($dnum,$_GET["docId"]);
    }
    else{
        $account->showNewDocForm($dnum);
    }
}

/*--------------------------------------------------*/

function getNewPointForm(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $client = new \Client\Controller();
    $client->showNewPointForm($dnum,$docId);
}

/*--------------------------------------------------*/

function getAdditionalName(){
    $dnum = $_GET["dnum"];
    $account = new \Account\Controller();
    echo $account->getAdditionalName($dnum);
}

/*--------------------------------------------------*/

function getAccountClientList(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $account = new \Account\Controller();
    $account->showClientList($dnum,$docId);
}

/*--------------------------------------------------*/

function getAdditionalTypeList(){
    $account = new \Account\Controller();
    $clientId = isset($_GET["clientId"]) ? $_GET["clientId"] : "";
    $docId = isset($_GET["docId"]) ? $_GET["docId"] : "";
    $account->showAdditionalTypeList($clientId,$docId);
}

/*--------------------------------------------------*/

function addNewAdditional(){
    $account = new \Account\Controller();
    $clientId = $_GET["clientId"];
    $posType = $_GET["posType"];
    $docId = $_GET["docId"];
    $account->addAdditional($clientId, $posType,$docId);
}

/*--------------------------------------------------*/

function deleteClient(){
    $clientId = $_GET["clientId"];
    $client = new \Client\Controller();
    $client->deleteClient($clientId);
}

/*--------------------------------------------------*/

function getFile(){
    $fullPath = $_GET["fullPath"];
    $explorer = new \Explorer\Controller_inner();
    $explorer->getFile($fullPath);
}

/*--------------------------------------------------*/

function saveComment(){
    $client = new \Client\Controller();
    $account = new \Account\Controller();
    $comment = isset($_GET["data"]) ? \Json_u::decode($_GET["data"],true) : [];
    if ($comment["type"] == "fixed"){
        $account->addComment($comment);
    }
    else if($comment["type"] == "clientDoc"){
        $clientId = $_GET["clientId"];
        $client->addDocComment($clientId, $comment);
    }
    else if($comment["type"] == "block"){
        $clientId = $_GET["clientId"];
        $client->saveClientBlock($clientId, $comment);
    }
    else{
        $clientId = $_GET["clientId"];
        $client->addComment($clientId,$comment);    
    }
}

/*--------------------------------------------------*/

function saveAdditional(){
    $client = new \Client\Controller();
    $account = new \Account\Controller();
    $data = Json_u::decode($_GET["data"]);
    $account->changePos($data["doc"]);
//    if (isset($data["comment"])){
//        $client->addComment($data["params"]["clientId"], $data["comment"]);
//    }
    
}

/*--------------------------------------------------*/

function deleteDoc(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $posId = $_GET["posId"];
    $account = new \Account\Controller();
    $account->deletePos($dnum, $docId, $posId);
}

/*--------------------------------------------------*/

function getContractForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showContractForm($clientId);
}

/*--------------------------------------------------*/

function deleteComment(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $commentId = $_GET["commentId"];
    $client->deleteComment($clientId, $commentId);
}

/*--------------------------------------------------*/

function getClientBlockForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showBlockForm($clientId);
}

/*--------------------------------------------------*/

function saveClientBlock(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $data = Json_u::decode($_GET["data"]);
    $client->saveClientBlock($clientId, $data);
}

/*--------------------------------------------------*/

function deleteClientBlock(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->deleteClientBlock($clientId);
}

/*--------------------------------------------------*/

function closeClientBlock(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->closeClientBlock($clientId);
}

/*--------------------------------------------------*/

function getRenewClientForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showRenewForm($clientId);
}

/*--------------------------------------------------*/

function renewClient(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $data = Json_u::decode($_GET["data"]);
    $client->renew($clientId,$data);
}

/*--------------------------------------------------*/

function renewClientLock(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->renewLock($clientId);
}

/*--------------------------------------------------*/

function connectClient(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $activateDate = $_GET["activateDate"];
    $client->saveClient([
        "params" => [
            "id" => $clientId,
            "activateDate" => $activateDate
        ]
    ]);
}

/*--------------------------------------------------*/

function getConnectClientForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showConnectForm($clientId);
}

/*--------------------------------------------------*/

function getDisconnectClientForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showDisconnectForm($clientId);
}

/*--------------------------------------------------*/

function saveClientDisconnect(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $data = Json_u::decode($_GET["data"]);
    if ($data["disconnectMethod"] == "Договор"){
        $client->disconnectAll($clientId, $data);
    }
    else{
        $client->disconnect($clientId,$data);
    }
}

/*--------------------------------------------------*/

function lockClientDisconnect(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->disconnectLock($clientId);
}

/*--------------------------------------------------*/

function removeClientContract(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->removeClientContract($clientId);
}

/*--------------------------------------------------*/

function getCreateClientDocForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showClientDocForm($clientId);
}

/*--------------------------------------------------*/

function saveClientDoc(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $data = Json_u::decode($_GET["data"]);
    $client->saveClientDoc($clientId,$data);
}

/*--------------------------------------------------*/

function getClientListFilterForm(){
    $main = new \Main\Controller();
    $param = $_GET["param"];
    $main->showClientListFilterForm($param);
}

/*--------------------------------------------------*/

function getChangeDnumForm(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $client->showChangeDnumForm($clientId);
}

/*--------------------------------------------------*/

function changeDnum(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $dnum = $_GET["dnum"];
    
    $client->changeDnum($clientId, $dnum);
}

/*--------------------------------------------------*/

function getNewNumber(){
    $account = new \Account\Controller();
    echo $account->getNewNumber();
}

/*--------------------------------------------------*/

function changeClientDocRegister(){
    $client = new \Client\Controller();
    $clientId = $_GET["clientId"];
    $register = $_GET["register"];
    $docId = $_GET["docId"];
    $client->changeDocRegister($clientId, $docId, $register);
}

/*--------------------------------------------------*/

function getAgreementRegister(){
    $year = $_GET["year"];
    $month = $_GET["month"];
    $register = new \Register\Controller();
    $register->getAgreementRegister($year,$month);
}

/*--------------------------------------------------*/

function getMonthSupportTable(){
    $year = $_GET["year"];
    $month = $_GET["month"];
    $support = new \Support\Controller();
    $supportList = $support->showMonthSupportAll($year, $month);
}

/*--------------------------------------------------*/

function saveAccountSupport(){
    $account = new \Account\Controller();
    $dnum = $_GET["dnum"];
    $rate = $_GET["rate"];
    $comment = $_GET["comment"];
    $name = $_GET["name"];
    $callDate = $_GET["callDate"];
    $account->saveSupport($dnum, $name, $rate, $comment, $callDate);
}

/*--------------------------------------------------*/

function getSupportRegister(){
    $register = new \Register\Controller();
    $register->getSupportRegister();
}

/*--------------------------------------------------*/

function saveClientSupportInfo(){
    $client = new \Client\Controller();
    $keyList = \Settings\Client::clientSupportParams();
    $clientId = $_GET["clientId"];
    $params = Json_u::decode($_GET["params"]);
    $client->saveSupportInfo($clientId, $params);
}

/*--------------------------------------------------*/

function getServiceTypeForm(){
    $clientId = $_GET["clientId"];
    $client = new \Client\Controller();
    $client->showServiceTypeForm($clientId);
}

/*--------------------------------------------------*/

function closeAgreementRegister(){
    $year = $_GET["year"];
    $month = $_GET["month"];
    $register = new \Register\Controller();
    $register->getAgreementRegister($year,$month,true);
}

/*--------------------------------------------------*/

function saveAgreementUserComment(){
    $register = new \Register\Controller();
    $tableType = $_GET["tableType"];
    $dnum = $_GET["dnum"];
    $tableKey = $_GET["tableKey"];
    $year = $_GET["year"];
    $month = $_GET["month"];
    $registerComment = $_GET["registerComment"];
    $register->saveRegisterComment($year, $month, $tableType, $dnum, $tableKey, $registerComment);
}

/*--------------------------------------------------*/

function printAgreementRegister(){
    $year = $_GET["year"];
    $month = $_GET["month"];
    $register = new \Register\Controller();
    $fl = $_GET["fl"];
    $content = $register->getAgreementRegister($year,$month,false,true,$fl);
    $buf = new \Main\Controller();
    $view = $buf->getView();
    $view->show("print",[
        "content" => $content
    ]);
}

/*--------------------------------------------------*/

function getContactListForm(){
    $account = new \Account\Controller();
    $dnum = $_GET["dnum"];
    $account->showContactListForm($dnum);
}

/*--------------------------------------------------*/

function getPhoneBookTable(){
    $phonebook = new \Phonebook\Controller();
    echo $phonebook->getContactTable();
}

/*--------------------------------------------------*/

function phonebookContactSave(){
    $contact = Json_u::decode($_GET["contact"]);
//    echo $_GET["contact"];
    $phonebook = new \Phonebook\Controller();
    $phonebook->saveContact($contact);
    echo $phonebook->getContactTable();
}

/*--------------------------------------------------*/

function phonebookContactDelete(){
    $contactId = $_GET["contactId"];
    $phonebook = new \Phonebook\Controller();
    $phonebook->deleteContact($contactId);
    echo $phonebook->getContactTable();
}

/*--------------------------------------------------*/

function getDebtorTable(){
    $debtor = new \Debtor\Controller();
    $debtor->checkAll();
    echo $debtor->getDebtorTable();
}

/*--------------------------------------------------*/

function debtorSave(){
    $debtor = new \Debtor\Controller();
    $keys = $debtor->getKeyList();
    $debt = [];
    foreach($keys as $key){
        $debt[$key] = $_GET[$key];
    }
    print_u($debt);
    
    $debtor->save($debt);
}

/*--------------------------------------------------*/

function getDocumentRegister(){
    $register = new \Register\Controller();
    echo $register->getDocumentRegister();
}

/*--------------------------------------------------*/

function getDocumentRegisterPlacementForm(){
    $register = new \Register\Controller();
    $docType = $_GET["docType"];
    echo $register->getDocumentRegisterPlacementForm($docType);
}
/*--------------------------------------------------*/

function saveDocumentRegisterPlacement(){
    $account = new \Account\Controller();
    $client = new \Client\Controller();
    $keyList = [
        "dnum",
        "docType",
        "docId",
        "docPlacement",
        "clientId"
    ];
    $params = [];
    foreach($keyList as $key){
        $params[$key] = $_GET[$key];
    }
//    print_u($params);
    if (($params["docType"] == "register") || ($params["docId"] == "agreement")){
        $account->saveDocumentRegisterData([
            "dnum" => $params["dnum"],
            $params["docId"] => $params["docPlacement"]
        ]);
    }
    else 
    if($params["docType"] == "clientDoc"){
        $client->changeClientDocPlacement($params["clientId"], $params["docId"], $params["docPlacement"]);
    }
    else{
        $account->setDocPlacement($params["dnum"], $params["docId"], $params["docPlacement"],$params["docType"]);
    }
}

/*--------------------------------------------------*/

function getDocumentPlacementForm(){
    $account = new \Account\Controller();
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    echo $account->showDocumentPlacementForm($dnum, $docId);
}

/*--------------------------------------------------*/

function changePayManager(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $payManager = $_GET["payManager"];
    $account = new \Account\Controller();
    $account->setPayManager($dnum, $docId, $payManager);
}

/*--------------------------------------------------*/

function getAdminMainPage(){
    $admin = new \Admin\Controller();
    echo $admin->showMainPage();
}

/*--------------------------------------------------*/

function adminSalarySave(){
    $salary = Json_u::decode($_GET["salary"]);
    $admin = new \Admin\Controller();
    $admin->saveSalary($salary);
}

/*--------------------------------------------------*/

function getSalaryTable(){
    $year = isset($_GET["year"]) ? $_GET["year"] : date("Y",time());
    $currentMonth = date("m",time());
    if(strlen($currentMonth) != 2){
        $currentMonth = "0{$currentMonth}";
    }
    $month = isset($_GET["month"]) ? $_GET["month"] : $currentMonth;
    $salary = new \Salary\Controller();
    echo $salary->showSalaryTable($year, $month);
}

/*--------------------------------------------------*/

function saveSalaryDoc(){
    $salObject = new \Salary\Controller();
    $salary = Json_u::decode($_GET["salary"]);
    $month = $_GET["month"];
    $year = $_GET["year"];
    $salObject->saveSalaryDocList($year, $month, $salary);
}

/*--------------------------------------------------*/

function getAdminSalaryBonusForm(){
    $admin = new \Admin\Controller();
    echo $admin->showSalaryBonusForm();
}

/*--------------------------------------------------*/

function saveAdminSalaryBonus(){
    $role = $_GET["role"];
    $bonusList = Json_u::decode($_GET["bonusList"]);
    $admin = new \Admin\Controller();
    echo $admin->saveSalaryBonus($role, $bonusList, true);
}

/*--------------------------------------------------*/

function removeAdminSalaryBonus(){
    $role = $_GET["role"];
    $bonusList = Json_u::decode($_GET["bonusList"]);
    $admin = new \Admin\Controller();
    $admin->deleteSalaryBonus($role, $bonusList);
}

/*--------------------------------------------------*/

function saveSalaryParams(){
    $paramList = Json_u::decode($_GET["paramList"]);
    $year = $_GET["year"];
    $month = $_GET["month"];
    $salary = new \Salary\Controller();
    $salary->saveSalaryParams($year, $month, $paramList);
}

/*--------------------------------------------------*/

function saveSalaryBonus(){
    $bonusList = Json_u::decode($_GET["bonusList"]);
    $year = $_GET["year"];
    $month = $_GET["month"];
    $salary = new \Salary\Controller();
    $salary->saveSalaryBonus($year, $month, $bonusList);
}

/*--------------------------------------------------*/

function saveSalaryAll(){
    $params = Json_u::decode($_POST["params"],false);
    $year = $_POST["year"];
    $month = $_POST["month"];
    $salary = new \Salary\Controller();
    $salary->saveSalaryAll($year, $month, $params);
}

/*--------------------------------------------------*/

function getChangePayManagerForm(){
    $salary = new \Salary\Controller();
    echo $salary->showChangePayManagerForm();
}

/*--------------------------------------------------*/

function changeDocumentAttractType(){
    $account = new \Account\Controller();
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $attractType = $_GET["attractType"];
    $account->changeDocumentAttractType($dnum, $docId, $attractType);
}

/*--------------------------------------------------*/

function getOldClientList(){
    $clientList = new \ClientList\Controller();
    echo $clientList->getListTable();
}

/*--------------------------------------------------*/

function oldClientListAddMark(){
    $id = $_GET["id"];
    $manager = $_GET["manager"];
    $clientList = new \ClientList\Controller();
    $clientList->addDoneList($id, $manager);
}

/*--------------------------------------------------*/

function downloadMonthSupportTable(){
    $year = $_REQUEST["year"];
    $month = $_REQUEST["month"];
    $support = new \Support\Controller();
    $support->showMonthSupportAll($year, $month, true);
}

/*--------------------------------------------------*/

function changePayManagerForm(){
    $account = new \Account\Controller();
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    echo $account->showChangePayManagerForm($dnum,$docId);
}

/*--------------------------------------------------*/

function changePayment(){
    $dnum = $_GET["dnum"];
    $docId = $_GET["docId"];
    $data = Json_u::decode($_GET["data"]);
    $account = new \Account\Controller();
    $account->changePayment($dnum, $docId, $data);
}

/*--------------------------------------------------*/

function adminPlanSave(){
    $admin = new \Admin\Controller();
    $month = $_GET["month"];
    $year = $_GET["year"];
    $data = Json_u::decode($_GET["data"]);
    $admin->savePlan($year, $month, $data);
}

/*--------------------------------------------------*/

function getAdminPlanTable(){
    $admin = new \Admin\Controller();
    $year = $_GET["year"];
    echo $admin->showPlanPanel($year);
}

/*--------------------------------------------------*/

function getTimesheetPage(){
    $salary = new \Salary\Controller();
    $year = $_GET["year"] ? $_GET["year"] : date("Y",time());
    $month = $_GET["month"] ? $_GET["month"] : date("m",time());
    if (strlen($month) < 2){
        $month = "0{$month}";
    }
    echo $salary->getTimeSheet($year, $month);
}

/*--------------------------------------------------*/

function saveTimesheetCalendarDay(){
    $data = Json_u::decode($_GET["data"]);
    $salary = new \Salary\Controller();
    $salary->saveTimesheetCalendarDay($data);
}

/*--------------------------------------------------*/

function getTimesheetProfileDayForm(){
    $data = Json_u::decode($_GET["data"]);
    $salary = new \Salary\Controller();
    echo $salary->showProfileDayForm($data);
}

/*--------------------------------------------------*/

function saveTimesheetProfileDay(){
    $data = Json_u::decode($_GET["data"]);
    $salary = new \Salary\Controller();
    $salary->saveTimesheetProfileDay($data);
}

/*--------------------------------------------------*/

function getClientReportPage(){
    $reportType = $_GET["reportType"];
    $report = new \Report\Controller();
    echo $report->getClientReportPage($reportType);
}

/*--------------------------------------------------*/

function getReportPage(){
    $report = new \Report\Controller();
    echo $report->getMainPage();
}

/*--------------------------------------------------*/

function getConnectReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getConnectReportTable($params);
}

/*--------------------------------------------------*/

function getClientReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getClientReportTable($params);
}

/*--------------------------------------------------*/

function getAmountReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getAmountReportTable($params);
}

/*--------------------------------------------------*/

function getContactReportPage(){
    $reportType = $_GET["reportType"];
    $report = new \Report\Controller();
    echo $report->getContactReportPage($reportType);
}

/*--------------------------------------------------*/

function getEmailReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getEmailReportTable($params);
}

/*--------------------------------------------------*/

function getContactReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getContactReportTable($params);
}

/*--------------------------------------------------*/

function getSupportReportPage(){
    $report = new \Report\Controller();
    echo $report->getSupportReportPage();
}

/*--------------------------------------------------*/

function getSupportReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getSupportReportTable($params);
}

/*--------------------------------------------------*/

function getConnectSumReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getConnectSumReportTable($params);
}

/*--------------------------------------------------*/

function getGuReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getGuReportTable($params);
}

/*--------------------------------------------------*/

function getDistrictReportTable(){
    $params = Json_u::decode($_GET["params"]);
    $report = new \Report\Controller();
    echo $report->getDistrictReportTable($params);
}

/*--------------------------------------------------*/
/*--------------------------------------------------*/
/*--------------------------------------------------*/
//print_u(getallheaders());
//print_u($_POST);
//
//    exit();
    
$action = $_REQUEST["action"];
switch ($action):
    case "debug":
        debug();
        break;
    case "getIndexPage":
        getIndexPage();
        break;
    case "saveClient":
        saveClient();
        break;
    case "getSettings":
        getSettings();
        break;
    case "getClientCreatePage":
        getClientCreatePage();
        break;
    case "getSecondPage":
        getSecondPage();
        break;
    case "getClientListPage":
        getClientListPage();
        break;
    case "getClientCard":
        getClientCard();
        break;
    case "changeDocPlacement":
        changeDocPlacement();
        break;
    case "changeForPayment":
        changeForPayment();
        break;
    case "getNewDocForm":
        getNewDocForm();
        break;
    case "getNewPointForm":
        getNewPointForm();
        break;
    case "getAdditionalName":
        getAdditionalName();
        break;
    case "getAccountClientList":
        getAccountClientList();
        break;
    case "getAdditionalTypeList":
        getAdditionalTypeList();
        break;
    case "addNewAdditional":
        addNewAdditional();
        break;
    case "deleteClient":
        deleteClient();
        break;
    case "getFile":
        getFile();
        break;
    case "saveComment":
        saveComment();
        break;
    case "saveAdditional":
        saveAdditional();
        break;
    case "deleteDoc":
        deleteDoc();
        break;
    case "getContractForm":
        getContractForm();
        break;
    case "deleteComment":
        deleteComment();
        break;
    case "getClientBlockForm":
        getClientBlockForm();
        break;
    case "saveClientBlock":
        saveClientBlock();
        break;
    case "deleteClientBlock":
        deleteClientBlock();
        break;
    case "closeClientBlock":
        closeClientBlock();
        break;
    case "getRenewClientForm":
        getRenewClientForm();
        break;
    case "renewClient":
        renewClient();
        break;
    case "renewClientLock":
        renewClientLock();
        break;
    case "connectClient":
        connectClient();
        break;
    case "getConnectClientForm":
        getConnectClientForm();
        break;
    case "getDisconnectClientForm":
        getDisconnectClientForm();
        break;
    case "saveClientDisconnect":
        saveClientDisconnect();
        break;
    case "lockClientDisconnect":
        lockClientDisconnect();
        break;
    case "removeClientContract":
        removeClientContract();
        break;
    case "getCreateClientDocForm":
        getCreateClientDocForm();
        break;
    case "saveClientDoc":
        saveClientDoc();
        break;
    case "getClientListFilterForm":
        getClientListFilterForm();
        break;
    case "getChangeDnumForm":
        getChangeDnumForm();
        break;
    case "changeDnum":
        changeDnum();
        break;
    case "getNewNumber":
        getNewNumber();
        break;
    case "changeClientDocRegister":
        changeClientDocRegister();
        break;
    case "getAgreementRegister":
        getAgreementRegister();
        break;
    case "getMonthSupportTable":
        getMonthSupportTable();
        break;
    case "saveAccountSupport":
        saveAccountSupport();
        break;
    case "getSupportRegister":
        getSupportRegister();
        break;
    case "saveClientSupportInfo":
        saveClientSupportInfo();
        break;
    case "getServiceTypeForm":
        getServiceTypeForm();
        break;
    case "closeAgreementRegister":
        closeAgreementRegister();
        break;
    case "saveAgreementUserComment":
        saveAgreementUserComment();
        break;
    case "printAgreementRegister":
        printAgreementRegister();
        break;
    case "getContactListForm":
        getContactListForm();
        break;
    case "getPhoneBookTable":
        getPhoneBookTable();
        break;
    case "phonebookContactSave":
        phonebookContactSave();
        break;
    case "phonebookContactDelete":
        phonebookContactDelete();
        break;
    case "getDebtorTable":
        getDebtorTable();
        break;
    case "debtorSave":
        debtorSave();
        break;
    case "getDocumentRegister":
        getDocumentRegister();
        break;
    case "getDocumentRegisterPlacementForm":
        getDocumentRegisterPlacementForm();
        break;
    case "saveDocumentRegisterPlacement":
        saveDocumentRegisterPlacement();
        break;
    case "getDocumentPlacementForm":
        getDocumentPlacementForm();
        break;
    case "changePayManager":
        changePayManager();
        break;
    case "getAdminMainPage":
        getAdminMainPage();
        break;
    case "adminSalarySave":
        adminSalarySave();
        break;
    case "getSalaryTable":
        getSalaryTable();
        break;
    case "saveSalaryDoc":
        saveSalaryDoc();
        break;
    case "getAdminSalaryBonusForm":
        getAdminSalaryBonusForm();
        break;
    case "saveAdminSalaryBonus":
        saveAdminSalaryBonus();
        break;
    case "removeAdminSalaryBonus":
        removeAdminSalaryBonus();
        break;
    case "saveSalaryParams":
        saveSalaryParams();
        break;
    case "saveSalaryBonus":
        saveSalaryBonus();
        break;
    case "saveSalaryAll":
        saveSalaryAll();
        break;
    case "getChangePayManagerForm":
        getChangePayManagerForm();
        break;
    case "changeDocumentAttractType":
        changeDocumentAttractType();
        break;
    case "getOldClientList":
        getOldClientList();
        break;
    case "oldClientListAddMark":
        oldClientListAddMark();
        break;
    case "downloadMonthSupportTable":
        downloadMonthSupportTable();
        break;
    case "changePayManagerForm":
        changePayManagerForm();
        break;
    case "changePayment":
        changePayment();
        break;
    case "adminPlanSave":
        adminPlanSave();
        break;
    case "getAdminPlanTable":
        getAdminPlanTable();
        break;
    case "getTimesheetPage":
        getTimesheetPage();
        break;
    case "saveTimesheetCalendarDay":
        saveTimesheetCalendarDay();
        break;
    case "getTimesheetProfileDayForm":
        getTimesheetProfileDayForm();
        break;
    case "saveTimesheetProfileDay":
        saveTimesheetProfileDay();
        break;
    case "downloadCsvFromData":
        downloadCsvFromData();
        break;
    case "getClientReportPage":
        getClientReportPage();
        break;
    case "getReportPage":
        getReportPage();
        break;
    case "getConnectReportTable":
        getConnectReportTable();
        break;
    case "getClientReportTable":
        getClientReportTable();
        break;
    case "getAmountReportTable":
        getAmountReportTable();
        break;
    case "getContactReportPage":
        getContactReportPage();
        break;
    case "getEmailReportTable":
        getEmailReportTable();
        break;
    case "getContactReportTable":
        getContactReportTable();
        break;
    case "getSupportReportPage":
        getSupportReportPage();
        break;
    case "getSupportReportTable":
        getSupportReportTable();
        break;
    case "getConnectSumReportTable":
        getConnectSumReportTable();
        break;
    case "getGuReportTable":
        getGuReportTable();
        break;
    case "getDistrictReportTable":
        getDistrictReportTable();
        break;
        
endswitch;













