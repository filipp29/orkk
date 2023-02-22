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

$itemList = [];
foreach($clientList as $key => $value){
    
    $itemList[] = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
        ],
        "content" => $view->show("inc.text",[
            "text" => $value["name"],
            "style" => [
                "padding" => "3px 8px",
                "justify-content" => "flex-end",
                "width" => "calc(50% - 16px)",
                "height" => "auto"
            ]
        ],true). $view->show("inc.text",[
            "text" => $value["address"],
            "style" => [
                "padding" => "3px 8px",
                "text-align" => "left",
                "border-left" => "1px var(--modColor_darkest) dashed",
                "height" => "auto"
            ]
        ],true). $view->show("inc.var",[
            "key" => "table_clientId",
            "value" => $key
        ],true). $view->show("inc.var",[
            "key" => "table_docId",
            "value" => $docId
        ],true)
    ],true);
}

/*Отображение--------------------------------------------------*/

$view->show("selectItem",[
    "itemList" => $itemList
]);


/*Переменные--------------------------------------------------*/





