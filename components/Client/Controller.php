<?php

namespace Client;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');
//require_once './php/Document.php';


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    public function __construct(){
        parent::__construct("Client");
        $this->model = new \Client\Model();
        /*--------------------------------------------------*/
//        if ($_COOKIE["login"] == "filipp"){
//            require_once $_SERVER['DOCUMENT_ROOT']. $this->path. "/php/Model_test.php";
//            $this->model = new \Client\Model_test();
//        }
        /*--------------------------------------------------*/
        require_once $_SERVER['DOCUMENT_ROOT']. $this->path. "/php/Document.php";
    }
    
    /*--------------------------------------------------*/
    
    public function getCreatePage(){
        $this->view->show("createCard.main");
    }
    
    /*--------------------------------------------------*/
    
    public function getFirstPage(){
        $this->view->show("createCard.firstPage");
    }
    
    /*--------------------------------------------------*/
    
    public function getSecondPage(
            $id
    ){
        
        $params = $this->model->getParams($id);
        $this->view->show("createCard.secondPage",[
            "name" => $params["name"],
            "remark" => $params["remark"],
            "id" => $id
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientCard(
            $id
    ){
        
        $account = new \Account\Controller();
        $data["params"] = $this->clientInfo($id);
        $buf = $this->getTarif($id);
        $data["params"]["fullAmount"] = $buf["amount"];
        $data["params"]["fullSpeed"] = $buf["speed"];
        $dnum = $data["params"]["dnum"];
        $data["contacts"] = $this->model->getContacts($id);
        $commentList = $this->model->getCommentList($id);
        $commentBuf = [
            "fixed" => [],
            "clientDoc" => [],
            "event" =>[],
            "normal" => [],
            "important" => [],
            "disconnect" => [],
            "renew" => [],
            "block" => []
        ];
        $commentOrder = [
            "disconnect",
            "renew",
            "important",
            "fixed",
            "clientDoc",
            "event",
            "block",
            "normal"
        ];
        $clientDoc = new \Client\Document();
        $clientDocList = [];
        $bufDocList = $clientDoc->getDocList($id);
        $blockList = $this->model->getBlockList($id);
        foreach($blockList as $block){
            if ($block["blockType"] == "current"){
                continue;
            }
            $profile = $block["author"];
            $timeStamp = $block["timeStamp"];
            $com = [
                "text" => $block["comment"],
                "fileName" => $block["fileName"],
                "filePath" => $block["filePath"],
                "fileNameEnd" => $block["fileNameEnd"],
                "filePathEnd" => $block["filePathEnd"],
                "type" => "block",
                "author" => $profile,
                "title" => profileGetUsername($profile). " - ". date("d.m.Y - H:i:s",$timeStamp),
                "img" => profileGetAvatar($profile),
                "timeStamp" => $timeStamp,
                "twoDocs" => $block["twoDocs"],
                "blockType" => $block["blockType"],
                "blockStart" => $block["blockStart"],
                "blockEnd" => $block["blockEnd"]
            ];
            $commentBuf["block"][] = $com;
        }
        foreach($bufDocList as $docId){
            $buf = $clientDoc->getDoc($id, $docId);
            $clientDocList[] = [
                "name" => $buf["docName"],
                "comment" => nl2br($buf["comment"]),
                "docType" => $buf["docType"],
                "clientId" => $id,
                "register" => isset($buf["register"]) ? $buf["register"] : "",
                "filePath" => $buf["filePath"],
                "address" => getAddress($data["params"]),
                "docId" => $buf["id"],
                "clientStatus" => $data["params"]["clientStatus"],
                "clientStatusShow" => $data["params"]["clientStatusShow"],
                "docPlacement" => $buf["placement"],
                "date" => $buf["date"]
            ];
            $profile = $buf["author"];
            $timeStamp = $buf["timeStamp"];
            $com = [
                "text" => $buf["comment"],
                "fileName" => $buf["fileName"],
                "filePath" => $buf["filePath"],
                "type" => "clientDoc",
                "author" => $profile,
                "title" => profileGetUsername($profile). " - ". date("d.m.Y - H:i:s",$timeStamp),
                "img" => profileGetAvatar($profile),
                "timeStamp" => $timeStamp,
                "docId" => $buf["id"],
                "date" => $buf["date"]
            ];
            $commentBuf["clientDoc"][] = $com;
        }
        
        if ($dnum){
            $docListAll = $account->getClientDocList($id, true);
            foreach($docListAll as $v){
                if ($v["comment"]){
                    $profile = $v["commentAuthor"];
                    $timeStamp = $v["commentTimeStamp"];
                    $buf = [
                        "text" => $v["comment"],
                        "fileName" => $v["fileName"],
                        "filePath" => $v["filePath"],
                        "type" => "fixed",
                        "author" => $profile,
                        "title" => profileGetUsername($profile). " - ". date("d.m.Y - H:i:s",$timeStamp),
                        "img" => profileGetAvatar($profile),
                        "timeStamp" => $timeStamp,
                        "dnum" => $dnum,
                        "docId" => $v["docId"],
                        "posId" => $v["posId"]
                    ];
                    $commentBuf["fixed"][] = $buf;
                }
            }
        }
        $block = $this->model->getCurrentBlock($id);
        if ($block){
            $data["currentBlock"] = [
                "blockStart" => $block["blockStart"],
                "blockEnd" => $block["blockEnd"]
            ]; 
        }
        else{
            $data["currentBlock"] = [
                "blockStart" => "",
                "blockEnd" => ""
            ];
        }
        foreach($commentList as $key => $value){
            $profile = $value["author"];
            $timeStamp = $value["timeStamp"];
            $commentList[$key]["title"] = profileGetUsername($profile). " - ". date("d.m.Y - H:i:s",$timeStamp);
            $commentList[$key]["img"] = profileGetAvatar($profile);
            $commentBuf[$commentList[$key]["type"]][] = $commentList[$key];
        }
        $commentResult = [];
        foreach($commentOrder as $key){
            foreach($commentBuf[$key] as $value){
                $commentResult[] = $value;
            }
        }
        $data["commentList"] = $commentResult;
        
        $changeHistory = $this->getClientHistory($id);
        $blockHistory = $this->model->getBlockHistory($id);
        
        $data["history"] = sortByKey(array_merge($blockHistory,$changeHistory), "timeStamp", true);
        if ($dnum){
            $data["parentDoc"] = $account->getParentDoc($dnum, $id);
            $data["chronology"] = [
                "docList" => $account->getChronologyList($dnum),
                "currentClientId" => $id,
                "dnum" => $dnum,
                "clientDocList" => $clientDocList
            ];
            $data["docList"] = $account->getClientDocList($id);
            $parentDoc = $account->getParentDoc($dnum, $id);
            if ($parentDoc["posType"] == "Спецификация"){
                $data["posName"] = "ОСНОВНАЯ";
            }
            else{
                $data["posName"] = $parentDoc["docName"];
                
            }
        }
        
        else{
            $data["parentDoc"] = [];
            $data["chronology"] = [
                "docList" => [],
                "currentClientId" => $id,
                "dnum" => "",
                "clientDocList" => $clientDocList
            ];
            $data["docList"] = [];
            $data["posName"] = "";
        }
        
        
        
        $this->view->show("clientCard.main",$data);
        
    }
    
    /*--------------------------------------------------*/
    
    public function clientList(){
        $idList = $this->model->getClientList();
        $result = [];
        foreach($idList as $id){
            $result[] = $this->model->getParams($id);
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getClientIdList(){
        return $this->model->getClientList();
    }
    
    /*--------------------------------------------------*/
    
    private function saveCommonParams(
            $id,
            $params
    ){
        $clientInfo = $this->clientInfo($id);
        $dnum = $clientInfo["dnum"];
        if ($dnum){
            $account = new \Account\Controller();
            $clientList = $account->getClientList($dnum);
            foreach($clientList as $clientId){
                if ($clientId != $id){
                    $params["id"] = $clientId;
                    $this->saveClient(["params" => $params], false);
//                    print_u($params);
                }
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    
    public function checkDate(
            $clientId
    ){
        $info = $this->clientInfo($clientId);
        $currentDate = (int)date("Ymd",time());
        
        if (($info["disconnectDate"]) && ($info["clientStatus"] == "Подключен")){
            $disconnectDate = (int)date("Ymd",$info["disconnectDate"]);
            if ($currentDate >= $disconnectDate){
                $this->disconnectLock($clientId);
                return;
            }
        }
        
        if (($info["activateDate"]) && ($info["clientStatus"] == "Ожидает подключение")){
            $activateDate = (int)date("Ymd",$info["activateDate"]);
            if ($currentDate >= $activateDate){
                $this->connect($clientId, $info["activateDate"]);
                return;
            }
        } 
        
        if (($info["renewDate"]) && ($info["clientStatus"] == "Подключен")){
            $activateDate = (int)date("Ymd",$info["renewDate"]);
            if ($currentDate >= $activateDate){
                $this->renewLock($clientId);
                return;
            }
        } 
        
        $block = $this->model->getCurrentBlock($clientId);
        if (($block) && $block["blockEnd"]){
            $end = date("Ymd",$block["blockEnd"]);
            if ($currentDate >= $end){
                $this->closeClientBlock($clientId);
                return;
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function saveClient(
            $data,
            $common = true
    ){
        $paramKeys = array_keys(\Settings\Client::paramKeys());
        $commonParamKeys = array_keys(\Settings\Client::commonParamKeys());
        $contactsBuf = isset($data["contacts"]) ? $data["contacts"] : [];
        $contacts = [];
        $i = 0;
        foreach($contactsBuf as $value){
            foreach($value as $k => $v){
                $contacts[(String)$i. "_". $k] = $v;
            }
            $i++;
        }
        $commonParams = [];
        foreach($paramKeys as $key){
            if (isset($data["params"][$key])){
                $params[$key] = $data["params"][$key];
                if (in_array($key, $commonParamKeys)){
                    $commonParams[$key] = $data["params"][$key];
                }
            }
        }
        
        if (isset($data["contacts"])){
            $clientId =  $this->model->save($params, $contacts);
        }
        else{
            $clientId =  $this->model->save($params, null);
        }
        if ($commonParams && $common){
            $this->saveCommonParams($clientId, $commonParams);
        }
        if (isset($data["doc"])){
            
            $account = new \Account\Controller();
            $data["doc"]["clientId"] = $clientId;
            
            $dnum = $account->save($data);
            $parentDoc = $account->getParentDoc($dnum, $clientId);
//            echo "{$dnum} - {$clientId}<br>";
            $result = [];
//            print_u($parentDoc);
            if ($parentDoc["posType"] == "Спецификация"){
                $result["main"] = "1";
            }
            else{
                $result["main"] = "0";
            }
            $result["id"] = $clientId;
//            $result["contractDate"] = $parentDoc["timeStamp"];
            $keyList = [
                "dnum",
                "docId",
                "posId",
            ];
            foreach($keyList as $key){
                $result[$key] = $parentDoc[$key];
            }
            $this->model->saveParams($result, false);
        }
        if (isset($data["renewData"]) && $data["renewData"]){
            $renewData = $data["renewData"]["data"];
            $oldId = $data["renewData"]["clientId"];
            $params = $this->model->getParams($oldId);
            $params["renewDate"] = $renewData["date"];
            $params["renewDnum"] = $renewData["dnum"];
            $params["renewFilePath"] = $renewData["filePath"];
            $params["renewFileName"] = $renewData["fileName"];
            $params["renewType"] = $renewData["renewType"];
            $params["renewComment"] = $renewData["comment"];
            print_u($params);
            $this->saveClient([
                "params" => $params
            ]);
        }
        $this->checkDate($clientId);
        return $clientId;
    }
    
    /*--------------------------------------------------*/
    
    public function addComment(
            $clientId,
            $comment
    ){
        $filePath = isset($comment["filePath"]) ? $comment["filePath"] : "";
        $fileName = isset($comment["fileName"]) ? $comment["fileName"] : "";
        if ($comment["type"] == "renew"){
            $this->saveClient([
                "params" => [
                    "id" => $clientId,
                    "renewFilePath" => $filePath,
                    "renewFilrName" => $fileName
                ]
            ]);
        }
        if ($comment["type"] == "disconnect"){
            $this->saveClient([
                "params" => [
                    "id" => $clientId,
                    "disconnectFilePath" => $filePath,
                    "disconnectFilrName" => $fileName
                ]
            ]);
        }
        if ($comment["type"] == "block"){
            $block = $this->model->getBlock($clientId, $comment["timeStamp"]);
            print_u($block);
            print_u($comment);
            if ($block){
                $block["filePath"] = $comment["filePath"];
                $block["fileName"] = $comment["fileName"];
                $this->model->saveBlock($clientId, $block);
            }
        }
        $this->model->addComment($clientId, $comment);
    }
    
    /*--------------------------------------------------*/
    
    public function addDocComment(
            $clientId,
            $comment
    ){
        $clientDoc = new \Client\Document();
        $doc = $clientDoc->getDoc($clientId, $comment["docId"]);
        $doc["comment"] = $comment["text"];
        $doc["filePath"] = $comment["filePath"];
        $doc["fileName"] = $comment["fileName"];
        $doc["date"] = $comment["date"];
        $clientDoc->save($clientId, $doc);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteComment(
            $clientId,
            $commentId
    ){
        $this->model->deleteComment($clientId, $commentId);
    }
    
    /*--------------------------------------------------*/
    
    public function clientInfo(
            $id
    ){
        $obj = $this->model->getParams($id);
        if (($obj["clientStatus"] == "Подключен") && ($this->isBlocked($id, time()))){
            $obj["clientStatusShow"] = "Приостановлен";
        }
        else{
            $obj["clientStatusShow"] = $obj["clientStatus"];
        }
        
        return $obj;
    }
    
    /*--------------------------------------------------*/
    
    public function showNewPointForm(
            $dnum,
            $docId,
            $defaultParams = null
    ){
        $account = new \Account\Controller();
        $additionalName = "";
        if ($docId){
            $doc = $account->getDoc($dnum, $docId);
            $docType = $doc["docType"];
            $additionalName = $doc["name"];
        }
        else{
            $docType = "none";
            $additionalName = $account->getAdditionalName($dnum);
        }
        if ($docType == "specification"){
            $getDoc = "getNewSpecificationPos(`{$docId}`,`{$dnum}`)";
            $newPoint = false;
        }
        else{
            $getDoc = "getNewAdditionalAgreement(`Дополнительная точка`,getDnumFromPage(this),getAdditionalNameFromPage(this))";
            $newPoint = true;
        }
        $mainList = $account->getMainList($dnum);
        $clientId = $mainList[0];
        if ($defaultParams){
            $params = $defaultParams;
            $params["default"] = true;
        }
        else{
            $params = $this->model->getParams($clientId);
            $contacts = $this->model->getContacts($clientId);
            $params["contacts"] = $contacts;
            $params["default"] = false;
        }
        $params["newPoint"] = $newPoint;
        $this->view->show("createPoint.main",[
            "params" => $params,
            "additionalName" => $additionalName,
            "docId" => $docId,
            "getDoc" => $getDoc,
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteClient(
            $clientId
    ){
        $account = new \Account\Controller();
        $account->deleteClient($clientId);
        $this->model->deleteClient($clientId);
    }
    
    /*--------------------------------------------------*/
    
    public function showContractForm(
            $clientId
    ){
        $params = $this->model->getParams($clientId);
        $contacts = $this->model->getContacts($clientId);
        $params["contacts"] = $contacts;
        $this->view->show("contractClient.main",[
            "params" => $params,
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientHistory(
            $clientId
    ){
        $history = $this->model->getChangeHistory($clientId);
        foreach($history as $key => $value){
            foreach($value["changeList"] as $k => $val){
                $history[$key]["changeList"][$k]["prev"] = $this->getParamValueText($k, $val["prev"]);
                $history[$key]["changeList"][$k]["next"] = $this->getParamValueText($k, $val["next"]);
            }
        }
        return $history;
    }
    
    /*--------------------------------------------------*/
    
    public function getParamValueText(
            $key,
            $value
    ){
        switch ($key):
            case "manager":
                return profileGetUsername($value);
                break;
            case "contractDate":
            case "activateDate":
            case "createDate":
            case "changeDate":
                return ($value) ? date("d.m.Y",$value) : "";
                break;
            case "cameras":
            case "staticIp":   
            case "cameras":
            case "ipPhone":
            case "kTv":
            case "service":
                return ($value == "1") ? "Требуется" : "Не ребуется";
                break;
            default:
                return $value;
                break;
        endswitch;
    }
    
    /*--------------------------------------------------*/
    
    public function showBlockForm(
            $clientId
    ){
        $block = $this->model->getCurrentBlock($clientId);
        $params = [
            "comment" => isset($block["comment"]) ? $block["comment"] : "",
            "clientId" => $clientId,
            "blockStart" => isset($block["blockStart"]) ? $block["blockStart"] : "",
            "blockEnd" => isset($block["blockEnd"]) ? $block["blockEnd"] : "",
            "filePath" => isset($block["filePath"]) ? $block["filePath"] : "",
            "fileName" => isset($block["fileName"]) ? $block["fileName"] : "",
            "filePathEnd" => isset($block["filePathEnd"]) ? $block["filePathEnd"] : "",
            "fileNameEnd" => isset($block["fileNameEnd"]) ? $block["fileNameEnd"] : "",
            "timeStamp" => isset($block["timeStamp"]) ? $block["timeStamp"] : "",
            "twoDocs" => isset($block["twoDocs"]) ? $block["twoDocs"] : ""
        ];
        $this->view->show("blockForm",$params);
    }
    
    /*--------------------------------------------------*/
    public function saveClientBlock(
            $clientId,
            $block
    ){
        if (!$block["timeStamp"]){
            $block["timeStamp"] = time();
        }
        if (!isset($block["author"]) || !$block["author"]){
            $block["author"] = $_COOKIE["login"];
        }
        $currentDate = (int)date("Ymd",time());
        if ($block["blockType"] == "current"){
            $this->model->saveBlock($clientId, $block, true);
        }
        else{
            $this->model->saveBlock($clientId, $block, false);
        }
        if (($block["blockEnd"]) && ($block["blockType"] == "current")){
            $end = date("Ymd",$block["blockEnd"]);
            if ($currentDate >= $end){
                $this->closeClientBlock($clientId);
                return;
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function deleteClientBlock(
            $clientId
    ){
        $this->model->deleteCurrentBlock($clientId);
    }
    
    /*--------------------------------------------------*/
    
    public function closeClientBlock(
            $clientId
    ){
        $block = $this->model->getCurrentBlock($clientId);
        $start = date("d.m.Y",$block["blockStart"]);
        $end = date("d.m.Y",$block["blockEnd"]);
        $block["comment"] = "Блок с {$start} по {$end}\n\n".$block["comment"];
//        $comment = [
//            "text" => $text,
//            "filePath" => isset($block["filePath"]) ? $block["filePath"] : "",
//            "fileName" => isset($block["fileName"]) ? $block["fileName"] : "",
//            "timeStamp" => time(),
//            "author" => $_COOKIE["login"],
//            "type" => "block",
//            "timeStamp" => $block["timeStamp"]
//        ];
        
        $this->model->saveBlock($clientId, $block);
        $this->model->deleteCurrentBlock($clientId);
//        $this->addComment($clientId, $comment);
        $this->model->saveBlockHistory($clientId, $block + ["type" => "block"]);
    }
    
    /*--------------------------------------------------*/
    
    public function showRenewForm(
            $clientId
    ){
        $clientInfo = $this->model->getParams($clientId);
        $params = [
            "date" => $clientInfo["renewDate"],
            "dnum" => $clientInfo["renewDnum"],
            "filePath" => $clientInfo["renewFilePath"],
            "fileName" => $clientInfo["fileName"],
            "clientId" => $clientId,
            "type" => $clientInfo["renewType"]
        ];
        $this->view->show("renewForm",$params);
    }
    
    /*--------------------------------------------------*/
    
    function renew(
            $clientId,
            $data
    ){
        $commonParams = \Settings\Client::commonParamKeys();
        $account = new \Account\Controller();
        $params = $this->model->getParams($clientId);
        $oldDnum = $params["dnum"];
        $contacts = $this->model->getContacts($clientId);
        $params["contacts"] = $contacts;
        $params["oldDnum"] = $params["dnum"];
        $params["dnum"] = $data["dnum"];
        $params["activateDate"] = "";
        $params["amount"] = "";
        $params["speed"] = "";
        $params["new_clientStatus"] = "Ожидает подключение";
        $params["oldId"] = $params["clientId"];
        $params["oldName"] = $params["name"];
        $params["oldClientType"] = $params["clientType"];
        $params["renew_date"] = $data["date"];
        $params["renew_dnum"] = $data["dnum"];
        $params["renew_filePath"] = $data["filePath"];
        $params["renew_fileName"] = $data["fileName"];
        $params["renew_type"] = $data["renewType"];
        $params["renewFlag"] = "1";
        $params["clientStatus"] = "Ожидает подключение";
        if (!$account->isDnumExists($data["dnum"])){
            
            $params["id"] = makeId();
//            $params["amount"] = "";
//            $params["speed"] = "";
            
            foreach($commonParams as $key => $value){
                $params[$key] = "";
            }
            $this->view->show("contractClient.main",[
                "params" => $params,
            ]);
        }else{
            if ($data["docType"] == "specification"){
                $docId = $account->getSpecificationId($data["dnum"]);
                
            }
            else{
                $docId = "";
            }
            $buf_clientId = $account->getClientList($data["dnum"])[0];
            $clientInfo = $this->model->getParams($buf_clientId);
            foreach($commonParams as $key => $value){
                $params[$key] = $clientInfo[$key];
            }
//            $params["amount"] = "";
//            $params["speed"] = "";
            $params["id"] = "";
            
            $this->showNewPointForm($data["dnum"], $docId, $params);
        }
        
        /*--------------------------------------------------*/
        
//        $params = $this->model->getParams($clientId);
//        $params["renewDate"] = $data["date"];
//        $params["renewDnum"] = $data["dnum"];
//        $params["renewFilePath"] = $data["filePath"];
//        $params["renewFileName"] = $data["fileName"];
//        $params["renewType"] = $data["renewType"];
//        $this->saveClient([
//            "params" => $params
//        ]);
        
    }
    
    /*--------------------------------------------------*/
    
    public function renewLock(
            $clientId
    ){
        $params = $this->model->getParams($clientId);
        $params["clientStatus"] = "Переоформлен";
//        $oldTarif = $this->getTarif($clientId);
//        $params["oldTarif"] = "{$oldTarif["amount"]} тг - {$oldTarif["speed"]} мбит/с";
//        $params["amount"] = "";
//        $params["speed"] = "";
        
        $comment = [
            "text" => $params["renewComment"],
            "fileName" => $params["renewFileName"],
            "filePath" => $params["renewFilePath"],
            "type" => "renew",
            "timeStamp" => time(),
            "author" => $_COOKIE["login"]        
        ];
            
        $this->saveClient([
            "params" => $params
        ]);    
        $this->addComment($clientId, $comment);
    }
    
    /*--------------------------------------------------*/
    
    public function showConnectForm(
            $clientId
    ){
        $info = $this->model->getParams($clientId);
        $params = [
            "clientId" => $clientId,
            "date" => $info["activateDate"]
        ];
        $this->view->show("connectForm",$params);
    }
    
    /*--------------------------------------------------*/
    
    public function connect(
            $clientId,
            $activateDate
    ){
        $params = $this->model->getParams($clientId);
        $params["clientStatus"] = "Подключен";
        $params["activateDate"] = $activateDate;
        $this->saveClient([
            "params" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showDisconnectForm(
            $clientId
    ){
        $params = $this->model->getParams($clientId);
        $params["clientId"] = $params["id"];
        $this->view->show("disconnectForm",$params);
    }
    
    /*--------------------------------------------------*/
    
    public function disconnect(
            $clientId,
            $data
    ){
        $keyList = [
            "disconnectType",
            "disconnectReason",
            "disconnectDate",
            "disconnectReasonDesc",
            "disconnectComment",
            "disconnectFilePath",
            "disconnectFileName",
            "disconnectMethod"
        ];
        $params = [
            "id" => $clientId
        ];
        foreach($keyList as $key){
            $params[$key] = isset($data[$key]) ? $data[$key] : "";
        }
        $this->saveClient([
            "params" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function disconnectAll(
            $clientId,
            $data
    ){
        $account = new \Account\Controller();
        $clientInfo = $this->clientInfo($clientId);
        $dnum = $clientInfo["dnum"];
        $clientList = $account->getClientList($dnum);
        foreach($clientList as $id){
            $info = $this->clientInfo($id);
            if ($info["clientStatus"] == "Подключен"){
                $this->disconnect($id, $data);
            }
        }
    }
    
    /*--------------------------------------------------*/
    
    public function disconnectLock(
            $clientId
    ){
        $account = new \Account\Controller();
        $params = $this->model->getParams($clientId);
        $params["clientStatus"] = "Отключен";
        $city = $params["city"];
        $manager = profileGetUsername($params["manager"]);
        $payType = $params["payType"];
        $disconnectType = $params["disconnectType"];
        $date = date("d.m.Y",$params["disconnectDate"]);
        $reasonDesc = "";
        $disconnectMethod = isset($params["disconnectMethod"]) ? $params["disconnectMethod"] : "Точка";
        if ($disconnectMethod == "Точка"){
            $beforeAmount = (int)$account->getFullAmount($params["dnum"]);
            $tarif = $this->getTarif($clientId);
            $afterAmount = $beforeAmount - (int)$tarif["amount"];
            $disconnectMethodBlock = "Отключение точки\n";
            $amountBlock = "Общий ежемесячный тариф по договору измениться \nс {$beforeAmount} на {$afterAmount}\n";
        }
        else{
            $disconnectMethodBlock = "Отключение договора\n";
            $amountBlock = "";
        }
        switch($params["disconnectReason"]):
            case "Уход к конкуренту":
                $reasonDesc = "Конкурент {$params["disconnectReasonDesc"]}\n";
                break;
            case "Переезд вне МЁ":
                $reasonDesc = "Адрес {$params["disconnectReasonDesc"]}\n";
                break;
        endswitch;
        $text = "{$city},{$manager},{$payType}\n{$disconnectMethodBlock}Тип отключения {$disconnectType}\nОтключен с {$date}\nПричина {$params["disconnectReason"]}\n". $reasonDesc.  $amountBlock. "\n". $params["disconnectComment"];
        $comment = [
            "text" => $text,
            "fileName" => $params["disconnectFileName"],
            "filePath" => $params["disconnectFilePath"],
            "type" => "disconnect",
            "timeStamp" => time(),
            "author" => $_COOKIE["login"]        
        ];
            
        $this->saveClient([
            "params" => $params
        ]);    
        $this->addComment($clientId, $comment);
    }
    
            
    
    /*--------------------------------------------------*/
    
    public function removeClientContract(
            $clientId
    ){
        $keyList = [
            "renewDate",
            "renewDnum",
            "renewType",
            "renewFilePath",
            "renewFileName",
            "disconnectType",
            "disconnectReason",
            "disconnectDate",
            "disconnectReasonDesc",
            "disconnectComment",
            "disconnectFilePath",
            "disconnectFileName",
            "dnum",
            "contractDate",
            "connectDate",
            "activateDate"
        ];
        $account = new \Account\Controller();
        $params = $this->model->getParams($clientId);
        $params["clientStatus"] = "Обход/Диалог";
        foreach($keyList as $key){
            $params[$key] = "";
        }
        $this->model->saveBlockHistory($clientId, [
            "type" => "removeContract",
            "author" => $_COOKIE["login"]
        ]);
        $account->deleteClient($clientId);
        $this->saveClient([
            "params" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function showClientDocForm(
            $clientId
    ){
        $this->view->show("clientDoc.createForm",[
            "clientId" => $clientId
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function saveClientDoc(
            $clientId,
            $doc
    ){
        $clientDoc = new \Client\Document();
        $clientDoc->save($clientId, $doc);
    }
    
    /*--------------------------------------------------*/
    
    public function changeClientDocPlacement(
            $clientId,
            $docId,
            $placement
    ){
        $clientDoc = new \Client\Document();
        $doc = $clientDoc->getDoc($clientId, $docId);
        $doc["placement"] = $placement;
        $clientDoc->save($clientId, $doc);
    }
    
    /*--------------------------------------------------*/
    
    public function getTarif(
            $clientId,
            $date = null
    ){
        $amountKey = \Settings\Account::amountPlus();
        if (!$date){
            $date = time();
        }
        
        $currentDate = (int)date("Ymd",$date);
        $account = new \Account\Controller();
        
        $clientInfo = $this->model->getParams($clientId);
        $nullStatus = [
            "Переоформлен",
            "Отключен"
        ];
//        if (in_array($clientInfo["clientStatus"], $nullStatus)){
//            return [
//                "amount" => 0,
//                "speed" => 0
//            ];
//        }
        $speedAmount = (int)$clientInfo["amount"];
        $amount = 0;
        $speed = (int)$clientInfo["speed"];
        $income = 0;
        $outcome = 0;
        $docList = sortByKey($account->getClientDocList($clientId),"param_activateDate");
        foreach($docList as $value){
            if (!isset($value["param_activateDate"])){
                continue;
            }
            $posType = $value["posType"];
            $activateDate = $value["param_activateDate"] ? (int)date("Ymd",$value["param_activateDate"]) : null;
            if (($activateDate) && ($activateDate <= $currentDate)){
                if ((in_array($posType, $amountKey))){
                    $amount += (int)$value["param_amount"];
                    $income += (int)$value["param_amount"];
                }
                if ($posType == "Уменьшение ширины канала"){
                    $speedAmount -= (int)$value["param_amount"];
                    $speed -= (int)$value["param_speed"];
                    $outcome += (int)$value["param_amount"];
                }
                if ($posType == "Увеличение ширины канала"){
                    $speedAmount += (int)$value["param_amount"];
                    $speed += (int)$value["param_speed"];
                    $income += (int)$value["param_amount"];
                }
            }
        }
        if (in_array($clientInfo["clientStatus"], $nullStatus)){
            return [
                "amount" => $amount + $speedAmount,
                "speed" => $speed,
                "amountShow" => 0,
                "speedShow" => 0,
                "income" => $income,
                "outcome" => $outcome
            ];
        }
        return [
            "amount" => $amount + $speedAmount,
            "speed" => $speed,
            "amountShow" => $amount + $speedAmount,
            "speedShow" => $speed,
            "income" => $income,
            "outcome" => $outcome
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function isBlocked(
            $clientId,
            $timeStamp
    ){
        $date = (int)date("Ymd",$timeStamp);
        $block = $this->model->getCurrentBlock($clientId);
        if (($block["blockStart"])){
            $blockStart = (int)date("Ymd",$block["blockStart"]);
            if ($blockStart <= $date){
                return true;
            }
        }
        return false;
    }
    
    /*--------------------------------------------------*/
    
    public function getAverageLife(
            $clientId
    ){
        $info = $this->clientInfo($clientId);
        if ($info["clientStatus"] != "Отключен"){
            return "0";
        }
        $connectDate = $info["activateDate"];
        $disconnectDate = $info["disconnectDate"];
        if ($connectDate && $disconnectDate){
            $delta = ((int)$disconnectDate - (int)$connectDate)/(60*60*24);
            if ($delta > 0){
                return $delta;
            }
        }
        return 0;
    }
    
    /*--------------------------------------------------*/
    
    public function showChangeDnumForm(
            $clientId
    ){
        $this->view->show("changeDnumForm",[
            "clientId" => $clientId
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function changeDnum(
            $clientId,
            $newDnum
    ){
        $account = new \Account\Controller();
        $clientInfo = $this->clientInfo($clientId);
        $dnum = $clientInfo["dnum"];
        if(!$account->changeNumber($dnum, $newDnum)){
            return false;
        }
        $this->saveClient([
            "params" => [
                "id" => $clientId,
                "dnum" => $newDnum
            ]
        ]);
        return true;
    }
    
    /*--------------------------------------------------*/
    
    public function changeDocRegister(
            $clientId,
            $docId,
            $register
    ){
        $clientDoc = new \Client\Document();
        $doc = $clientDoc->getDoc($clientId, $docId);
        $doc["register"] = $register;
        $clientDoc->save($clientId, $doc);
    }
    
    /*--------------------------------------------------*/
    
    public function getDisconnectComment(
            $clientId,
    ){
        $commentList = $this->model->getCommentList($clientId);
        foreach($commentList as $value){
            if ($value["type"] == "disconnect"){
                return $value;
            }
        }
        $params = $this->model->getParams($clientId);
        $params["clientStatus"] = "Отключен";
        $city = $params["city"];
        $manager = profileGetUsername($params["manager"]);
        $payType = $params["payType"];
        $disconnectType = $params["disconnectType"];
        $date = date("d.m.Y",$params["disconnectDate"]);
        $reasonDesc = "";
        switch($params["disconnectReason"]):
            case "Уход к конкуренту":
                $reasonDesc = "Конкурент {$params["disconnectReasonDesc"]}\n";
                break;
            case "Переезд вне МЁ":
                $reasonDesc = "Адрес {$params["disconnectReasonDesc"]}\n";
                break;
        endswitch;
        $text = "{$city},{$manager},{$payType}\nТип отключения {$disconnectType}\nОтключен с {$date}\nПричина {$params["disconnectReason"]}\n". $reasonDesc. "\n". $params["disconnectComment"];
        $comment = [
            "text" => $text,
            "fileName" => $params["disconnectFileName"],
            "filePath" => $params["disconnectFilePath"],
            "type" => "disconnect",
            "timeStamp" => time(),
            "author" => "SYSTEM"        
        ];
        
        
        return $comment;
    }
    
    /*--------------------------------------------------*/
    
    public function getRenewComment(
            $clientId
    ){
        $commentList = $this->model->getCommentList($clientId);
        foreach($commentList as $value){
            if ($value["type"] == "renew"){
                return $value;
            }
        }
        return [];
    }
    
    /*--------------------------------------------------*/
    
    public function getBlockComment(
            $clientId,
            $timeStamp
    ){
        $commentList = $this->model->getCommentList($clientId);
        foreach($commentList as $value){
            if (($value["type"] == "block")&&($value["timeStamp"] == $timeStamp)){
                return $value;
            }
        }
        return [];
    }
    
    /*--------------------------------------------------*/
    
    public function getBlockCommentList(
            $clientId,
    ){
        $commentList = $this->model->getCommentList($clientId);
        $result = [];
        foreach($commentList as $value){
            if ($value["type"] == "block"){
                $result[$value["timeStamp"]] = $value;
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getBlockList(
            $clientId
    ){
        $blockList = $this->model->getBlockList($clientId);
        $blockCommentList = $this->getBlockCommentList($clientId);
        $result = [];
        foreach($blockList as $block){
            if (isset($blockCommentList[$block["timeStamp"]])){
                $block["comment"] = $blockCommentList[$block["timeStamp"]]["text"];
            }
            else{
                $start = date("d.m.Y",$block["blockStart"]);
                $end = ($block["blockEnd"]) ? date("d.m.Y",$block["blockEnd"]) : "бессрочно";
                $text = "Блок с {$start} по {$end}\n\n".$block["comment"];
                $block["comment"] = $text;
            }
            $result[] = $block;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getContacts(
            $clientId
    ){
        $result = $this->model->getContacts($clientId);
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportInfo(
            $clientId
    ){
        $result = $this->model->getSupportInfo($clientId);
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function saveSupportInfo(
            $clientId,
            $params
    ){
        $this->model->saveSupportInfo($clientId, $params);
    }
    
    /*--------------------------------------------------*/
    
    public function showServiceTypeForm(
            $clientId
    ){
        $params = $this->clientInfo($clientId);
        $this->view->show("serviceTypeForm",[
            "clientInfo" => $params
        ]);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientDocList(
            $clientId
    ){
        $clientDoc = new \Client\Document();
        $docList = $clientDoc->getDocList($clientId);
        $result = [];
        foreach($docList as $docId){
            $doc = $clientDoc->getDoc($clientId, $docId);
            $result[] = $doc;
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function checkId(
            $clientId
    ){
        return $this->model->checkId($clientId);
    }
    
    /*--------------------------------------------------*/
    
    public function getClientStatus(
            $clientId,
            $timeStamp
    ){
        $statusList = $this->model->getClientStatusHistory($clientId);
        $block = $this->isBlocked($clientId, $timeStamp);
        if ($block){
            return "Приостановлен";
        }
        $keyList = array_keys($statusList);
        sort($keyList);
        $result = "none";
        foreach($keyList as $time){
            $status = $statusList[$time];
            if ((int)$timeStamp >= (int)$time){
                $result = $status;
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
}












