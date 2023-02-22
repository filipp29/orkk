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
    "content" => $viewClient->show("contractClient.firstPage",$params,true),
]);

$view->show("inc.div",[
    "type" => "column",
    "class" => "createSecondPage hidden",
    "content" => $viewClient->show("contractClient.secondPage",$params,true)
]);

$view->show("inc.div",[
    "type" => "column",
    "class" => "createThirdPage hidden",
    "content" => $viewClient->show("contractClient.thirdPage",[],true)
]);




