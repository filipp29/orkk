<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];




/*Инициализация--------------------------------------------------*/

$typeList = [
    "internet_service" => "Интернет",
    "cou_service" => "ЦОУ",
    "esdi_service" => "ЕШДИ",
    "channel_service" => "Аренда канала",
    "lan_service" => "Обслуживание ЛВС",
];
foreach($typeList as $key => $value){
    $params[$value] = $view->show("inc.input.radio",[
        "divType" => "row",
        "id" => $key,
        "style" => [
            "width" => "30%"
        ],
        "values" => [
            "1" => "Да",
            "0" => "Нет"
        ],
        "value" => ($clientInfo[$key]) ? $clientInfo[$key] : "0"
    ],true);
}


$body = "";
foreach($params as $label => $value){
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "flex-start",
            "align-items" => "center",
            "margin-top" => "20px"
        ],
        "content" => $view->show("inc.text",[
            "text" => $label,
            "style" => [
                "width" => "35%",
                "justify-content" => "flex-end"
            ] + $textStyle
        ],true). $value
    ],true);
}


$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
        "margin-top" => "20px"
    ],
    "content" => $view->show("buttons.acceptSquare",[
        "onclick" => "saveServiceType(this)",
    ],true)
],true);  


$vars = [
    "id" => $clientInfo["id"]
];

$varsBlock = $view->show("inc.vars",[
    "vars" => $vars
],true);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "justify-content" => "flex-start",
        "align-items" => "center"
    ],
    "content" => $body. $buttonBlock.  $varsBlock
]);

/*--------------------------------------------------*/


