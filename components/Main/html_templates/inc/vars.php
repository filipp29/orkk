<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();


foreach($vars as $key => $value){
    $view->show("inc.var",[
        "key" => $key,
        "value" => $value
    ]);
}

