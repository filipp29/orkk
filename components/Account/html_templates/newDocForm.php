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

$body  = $view->show("buttons.normal",[
    "onclick" => "showNewPointForm(this)",
    "text" => "Новая точка",
    "style" => $buttonStyle
],true). $view->show("buttons.normal",[
    "onclick" => "showAccountClientList(this)",
    "text" => "Выбрать действующую",
    "style" => $buttonStyle
],true). $view->show("inc.var",[
    "key" => "dnum",
    "value" => $dnum
],true). $view->show("inc.var",[
    "key" => "docId",
    "value" => $docId
],true);

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "align-items" => "center",
        "justify-content" => "center"
    ],
    "content" => $body
]);





