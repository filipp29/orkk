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
$buttonStyle = [
    "width" => "auto",
    "padding" => "0px 10px"
];

/*Инициализация--------------------------------------------------*/

$itemList = [];
foreach($typeList as $value){
    
    $itemList[] = $view->show("inc.text",[
        "text" => $value,
    ],true). $view->show("inc.var",[
        "key" => "table_additionalType",
        "value" => $value
    ],true);
}

/*Отображение--------------------------------------------------*/

$view->show("selectItem",[
    "itemList" => $itemList
]);

/*Переменные--------------------------------------------------*/







