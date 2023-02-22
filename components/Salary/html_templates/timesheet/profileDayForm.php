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

$buttonStyle = [
    "margin-top" => "0px",
    "min-width" => "0px",
    "width" => "auto",
    "padding" => "0px 25px"
];

/*Инициализация--------------------------------------------------*/

$blockList = [];
$vars = $view->get("inc.vars",[
    "vars" => [
        "profile" => $profile,
        "year" => $year,
        "month" => $month,
        "day" => $day
    ]    
]);


$blockList[] = $view->get("inc.div",[
    "type" => "column",
    "class" => "valueBlock",
    "content" => $view->get("inc.input.text",[
        "id" => "value",
        "style" => [
            "width" => "60px",
            "text-align" => "center",
            "margin" => "0px 0px 10px 0px"
        ] + $textStyle
    ]). $view->get("buttons.acceptSquare",[
        "onclick" => "Timesheet.saveProfileDay(this)"
    ]). $vars
]);

$stateList = [
    "ОТ",
    "Б",
    "БС"
];

foreach($stateList as $value){
    $blockList[] = $view->get("inc.div",[
    "type" => "column",
    "class" => "valueBlock",
    "content" => $view->get("buttons.normal",[
        "text" => $value,
        "onclick" => "Timesheet.saveProfileDay(this)",
        "style" => $buttonStyle
    ]). $view->get("inc.vars",[
        "vars" => [
            "value" => $value
        ] 
    ]). $vars
]);
}





$content = "";
foreach($blockList as $value){
    $content .= $value;
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $content,
    "style" => [
        "width" => "100%",
        "height" => "100%",
        "align-items" => "flex-start",
        "justify-content" => "space-around",
        "padding-top" => "15px"
    ]
]);
