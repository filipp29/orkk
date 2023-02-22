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
    "font-weight" => "bolder"
];

/*Инициализация--------------------------------------------------*/


$paramsBlock = $viewClient->show("clientCard.paramsBlock",[
    "params" => $params
],true);
$contactsBlock = $viewClient->show("clientCard.contactsBlock",[
    "contacts" => $contacts,
    "params" => $params
],true);
$docListBlock = $viewClient->show("clientCard.docListBlock",[
    "docList" => $docList
],true);
$commentBlock = $viewClient->show("clientCard.commentBlock",[
    "params" => $params,
    "commentList" => $commentList
],true);
$chronologyBlock = $viewClient->show("clientCard.chronology",$chronology,true);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "45%",
            "padding" => "0px 10px 50px 0px",
            "border-right" => "1px var(--modColor_darkest) dashed"
        ],
        "content" => $paramsBlock. $docListBlock. $commentBlock
    ],true). $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "55%",
            "padding" => "0px 0px 50px 20px",
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "content" => $contactsBlock,
            "style" => [
                "max-width" => "450px"
            ]
        ],true). $view->show("inc.div",[
            "type" => "row",
            "content" => $chronologyBlock,
            "style" => [
                "margin-top" => "40px"
            ]
        ],true)
    ],true)
]);

/*Переменные--------------------------------------------------*/

$view->show("inc.var",[
    "key" => "title",
    "value" => "Карточка клиента"
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





