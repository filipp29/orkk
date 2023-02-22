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

$view->show("inc.div",[
    "type" => "column",
    "class" => "createFirstPage ",
    "content" => $viewClient->show("createPoint.firstPage",$params,true),
]);
$view->show("inc.div",[
    "type" => "column",
    "class" => "createSecondPage hidden",
    "content" => $viewClient->show("createPoint.secondPage",$params,true)
]);

$view->show("inc.div",[
    "type" => "column",
    "class" => "createThirdPage hidden",
    "content" => $viewClient->show("createPoint.thirdPage",[
        "getDoc" => $getDoc,
    ],true)
]);

$view->show("inc.var",[
    "key" => "additionalName",
    "value" => $additionalName
]);

$view->show("inc.var",[
    "key" => "docId",
    "value" => $docId
]);


