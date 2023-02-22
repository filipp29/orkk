<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Admin\Controller();
$adminView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];



/*Èíèöèàëèçàöèÿ--------------------------------------------------*/


$menuList = [
    "salary" => "Îñíîâíîå",
    "plan" => "Ïëàí",
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
            "onclick" => "Admin.tableMenuSelect(this)"
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


$header = $view->show("inc.div",[
    "type" => "column",
    "content" => $view->show("inc.div",[
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
$hidden = "";
foreach($panelList as $type => $panel){
    $body .= $view->show("inc.div",[
        "type" => "row",
        "class" => "tableContainer {$hidden}",
        "id" => "{$type}_table",
        "content" =>  $panel      
    ],true);
    $hidden = "hidden";
}


/*Îòîáğàæåíèå--------------------------------------------------*/

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
    "tabTitle" => "Àäìèíêà",
];

$view->show("inc.vars",[
    "vars" => $vars
]);


