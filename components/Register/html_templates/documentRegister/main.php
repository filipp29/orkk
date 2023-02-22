<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$buf = new \Register\Controller();
$regView = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableLabelStyle = [
    "font-size" => "20px",
    "font-wight" => "600",
    "margin" => "20px 0px 10px 30px"
];

/*Èíèöèàëèçàöèÿ--------------------------------------------------*/

$menuList = [
    "all" => "Şğ/ÈÏ",
    "fl" => "ÔË",
    "gu" => "ÃÓ",
    "disconnected" => "Îòêëş÷åíèÿ",
    "blocked" => "Ïğèîñòàíîâîêè",
    "clientDoc" => "Ïğî÷åå"
];
$menu = "";
$selected = "menuItem_selected";
foreach($menuList as $key => $value){
    $menu .= $view->show("inc.div",[
        "type" => "row",
        "class" => "menuItem {$selected}",
        "content" => $value,
        "id" => $key,
        "attribute" => [
            "onclick" => "supportRegisterMenuSelect(this)"
        ],
        "style" => [
            "cursor" => "pointer",
            "font-size" => "20px",
            "color" => "var(--modColor_darkest)",
            "opacity" => "0.4",
            "transform" => "scale(0.92)",
            "width" => "fit-content",
            "margin-right" => "10px"
        ]
    ],true);
    $selected = "";    
}

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("buttons.normal",[
        "text" => "Îáíîâèòü",
        "onclick" => "DocumentRegister.reloadRegister(this)"
    ],true),
    "style" => [
        "margin" => "10px 0px"
    ]
],true);

$header = $view->show("inc.div",[
    "type" => "column",
    "content" => $buttonBlock. $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "40px",
            "align-items" => "flex-end",
            "margin" => "10px",
            "padding" => "0px 10px",
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "class" => "menuBlock",
            "content" => $menu
        ],true)
    ],true)
],true);



$body = "";
//foreach($clientList as $city => $value){
//    foreach($value as $type => $v){
//        if ("{$city}-{$type}" == "kst-all"){
//            $hidden = "";
//        }
//        else{
//            $hidden = "hidden";
//        }
//        $body .= $view->show("inc.div",[
//            "type" => "row",
//            "class" => "tableContainer {$hidden}",
//            "id" => "{$city}-{$type}_table",
//            "content" => $regView->show("supportRegister.table",[
//                "accountList" => $clientList[$city][$type],
//                "renewList" => $renewList,
//                "searchList" => $searchList
//            ],true)        
//        ],true);
//    }
//}
//$body .= $view->show("inc.div",[
//    "type" => "row",
//    "class" => "tableContainer {$hidden}",
//    "id" => "phonebook_table",
//    "content" => $phonebook       
//],true);
//print_u(array_keys($accountList));
//foreach($accountList as $key => $value){
//    if ($key == "all"){
//        $hidden = "";
//    }
//    else {
//        $hidden = "hidden";
//    }
//    $body .= $view->show("inc.div",[
//        "type" => "row",
//        "class" => "tableContainer {$hidden}",
//        "id" => "{$key}_table",
//        "content" => $regView->show("documentRegister.table",[
//            "accountList" => $value,
//            "managerList" => $managerList
//        ],true)        
//    ],true);
//}

$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer",
    "id" => "all_table",
    "content" => $regView->show("documentRegister.table",[
        "accountList" => isset($accountList["all"]) ? $accountList["all"] : [],
        "managerList" => $managerList
    ],true)        
],true);

$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer hidden",
    "id" => "fl_table",
    "content" => $regView->show("documentRegister.flTable",[
        "accountList" => isset($accountList["fl"]) ? $accountList["fl"] : [],
        "managerList" => $managerList
    ],true)        
],true);

$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer hidden",
    "id" => "gu_table",
    "content" => $regView->show("documentRegister.guTable",[
        "accountList" => isset($accountList["gu"]) ? $accountList["gu"] : [],
        "managerList" => $managerList
    ],true)        
],true);

$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer hidden",
    "id" => "disconnected_table",
    "content" => $regView->show("documentRegister.disconnectTable",[
        "accountList" => $disconnected,
        "disconnectMethod" => $disconnectMethod
    ],true)        
],true);
$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer hidden",
    "id" => "blocked_table",
    "content" => $regView->show("documentRegister.blockedTable",[
        "accountList" => $blocked,
        "managerList" => $managerList
    ],true)        
],true);
$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer hidden",
    "id" => "clientDoc_table",
    "content" => $regView->show("documentRegister.clientDocTable",[
        "accountList" => $clientDocList,
        "managerList" => $managerList
    ],true)        
],true);








/*Îòîáğàæåíèå--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $body,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Ğååñòğ äîêóìåíòîâ",
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























