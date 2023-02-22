<?php
$globalPath = "/_modules/orkkNew";
error_reporting(E_ALL); 
ini_set('display_errors', 1);
mb_internal_encoding('cp1251');
date_default_timezone_set ('Asia/Almaty');
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once $_SERVER['DOCUMENT_ROOT']. $globalPath.  "/php/classes/View2.php";
require_once $_SERVER['DOCUMENT_ROOT']. $globalPath.  "/php/lib/mainFunctions.php";
require_once "./php/Main.php";
require_once "./php/mainFunctions.php";
require_once "./settings/Main.php";

function getInitPage(){
    $settings = new Settings\Main();
    global $view;
    $view->show("forms.createCard.firstPage");
}



/*--------------------------------------------------*/
/*--------------------------------------------------*/
/*--------------------------------------------------*/

$action = $_REQUEST["action"];
$view = new \View2($globalPath. "/components/Main/");


switch ($action):
    case "getInitPage":
        getInitPage();
        break;
endswitch;



