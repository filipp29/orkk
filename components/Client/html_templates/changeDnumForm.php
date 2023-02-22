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

$title = $view->show("inc.text",[
    "text" => "",
    "style" => [
        "margin-top" => "20px"
    ] + $textStyle
],true);

$params = [
    "Номер договора" => $view->show("inc.input.text",[
        "id" => "changeDnum_dnum",
        "style" => [
            "width" => "30%"
        ],
    ],true)
];
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
        "onclick" => "changeDnum(this)",
    ],true)
],true);        
        

$vars = [
    "changeDnum_clientId" => $clientId
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
    "content" => $title. $body. $buttonBlock. $varsBlock
]);

/*--------------------------------------------------*/








