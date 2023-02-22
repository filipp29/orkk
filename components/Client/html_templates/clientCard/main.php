<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
$viewClient = $client->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "800"
    
];

/*�������������--------------------------------------------------*/


$historyBlock = $viewClient->show("clientCard.historyBlock",[
    "history" => $history
],true);
$paramsBlock = $viewClient->show("clientCard.paramsBlock",[
    "params" => $params,
    "posName" => $posName,
    "parentDoc" => $parentDoc,
    "currentBlock" => $currentBlock
],true);
$contactsBlock = $viewClient->show("clientCard.contactsBlock",[
    "contacts" => $contacts,
    "params" => $params
],true);
$docListBlock = $viewClient->show("clientCard.docListBlock",[
    "docList" => $docList,
],true);
$commentBlock = $viewClient->show("clientCard.commentBlock",[
    "params" => $params,
    "commentList" => $commentList
],true);
$chronologyBlock = $viewClient->show("clientCard.chronology",$chronology,true);

$chronologyContainer = $view->show("hiddenPanels.right",[
    "buttonText" => "����������",
    "content" => $chronologyBlock,
    "top" => "110px",
    "height" => "270px",
    "maxHeight" => "70vh"
],true);

$historyContainer = $view->show("hiddenPanels.right",[
    "buttonText" => "�������",
    "content" => $historyBlock,
    "top" => "400px",
    "height" => "200px",
    "maxHeight" => "55vh"
],true);


/*�����������--------------------------------------------------*/


$view->show("inc.div",[
    "type" => "row",
    "style" => [
        "overflow-x" => "auto",
        "padding-right" => "50px",
    ],
    "content" => $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "flex-grow" => "1",
            "padding-bottom" => "35px"
        ],
        
        "content" => $view->show("inc.div",[
            "type" => "column",
            
            "content" =>  $paramsBlock. $docListBlock .$commentBlock
        ],true)
    ],true). $contactsBlock. $historyContainer. $chronologyContainer
]);



/*����������--------------------------------------------------*/

$view->show("inc.var",[
    "key" => "title",
    "value" => "�������� �������"
]);
$tabTitle = (isset($params["dnum"]) && ($params["dnum"])) ? $params["dnum"] : "###";
$view->show("inc.var",[
    "key" => "tabTitle",
    "value" => $tabTitle
]);

$view->show("inc.var",[
    "key" => "cur_dnum",
    "value" => $params["dnum"]
]);

$view->show("inc.var",[
    "key" => "cur_clientId",
    "value" => $params["clientId"]
]);





