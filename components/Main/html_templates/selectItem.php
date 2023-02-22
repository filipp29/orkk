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

$tbody = "";
$i = 0;
foreach($itemList as $key => $value){
    $i++;
    if ($i % 2 == 0){
        $class = "even";
    }
    else{
        $class = "odd";
    }
    
    $tbody .= $view->show("table.tr",[
        "content" => $view->show("table.td",[
            "content" => $view->show("inc.div",[
                "type" => "row",
                "content" => $value,
                "style" => [
                    "justify-content" => "center"
                ]
            ],true)
        ],true),
        "class" => "hoverable ". $class,
        "style" => [
            "border" => "2px white solid"
        ],
        "attribute" => [
            "onclick" => "selectItemFromTable(this)"
        ]
    ],true);
}

/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "thead" => "",
    "tbody" => $tbody
]);

/*Переменные--------------------------------------------------*/





