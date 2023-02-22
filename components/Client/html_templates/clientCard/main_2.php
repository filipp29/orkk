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
    "font-weight" => "800"
    
];

/*Инициализация--------------------------------------------------*/



$paramsBlock = $viewClient->show("clientCard.paramsBlock",[
    "params" => $params,
    "posName" => $posName
],true);
$contactsBlock = $viewClient->show("clientCard.contactsBlock",[
    "contacts" => $contacts,
    "params" => $params
],true);
$docListBlock = $viewClient->show("clientCard.docListBlock",[
    "docList" => $docList
],true);
$commentBlock = $viewClient->show("clientCard.commentBlock",[
    "params" => $params,
    "commentList" => $commentList
],true);
$chronologyBlock = $viewClient->show("clientCard.chronology",$chronology,true);

$chronologyButton = $view->show("inc.div",[
    "type" => "row",
    "content" => "Хронология",
    "style" => [
        "border-radius" => "50px 0px 0px 50px",
        "background-color" => "var(--modColor_dark)",
        "color" => "var(--modBGColor)",
        "align-items" => "center",
        "justify-content" => "center",
        "width" => "25px",
        "height" => "270px",
        "writing-mode" => "vertical-lr",
        "text-orientation" => "upright",
        "font-size" => "20px",
        "cursor" => "pointer",
        
    ],
    "attribute" => [
        "onclick" => "chronologyToggleHiding(this)"
    ]
],true);

$chronologyBox = $view->show("inc.div",[
    "type" => "row",
    "content" => $chronologyBlock,
    "id" => "chronologyBlock",
    "class" => "hidden",
    "style" => [
        "min-height" => "270px",
        "min-width" => "250px",
        "border" => "1px var(--modColor_dark) solid",
        "background-color" => "var(--modBGColor)",
        "overflow" => "hidden",
        "padding" => "0px 5px 10px 10px",
        "height" => "auto",
    ]
],true);

$chronologyContainer = $view->show("inc.div",[
    "type" => "row",
    "content" => $chronologyButton. $chronologyBox,
    "style" => [
        "position" => "absolute",
        "top" => "calc(50vh - 135px)",
        "right" => "0px"
    ]
],true);


/*Отображение--------------------------------------------------*/

//$view->show("inc.div",[
//    "type" => "row",
//    "content" => $view->show("inc.div",[
//        "type" => "column",
//        "style" => [
//            "width" => "65%",
//            "padding" => "0px 10px 50px 0px",
//            "border-right" => "1px var(--modColor_darkest) dashed"
//        ],
//        "content" => $paramsBlock. $docListBlock. $commentBlock
//    ],true). $view->show("inc.div",[
//        "type" => "column",
//        "style" => [
//            "width" => "35%",
//            "padding" => "0px 0px 50px 20px",
//        ],
//        "content" => $view->show("inc.div",[
//            "type" => "row",
//            "content" => $contactsBlock,
//            "style" => [
//                "max-width" => "450px"
//            ]
//        ],true). $view->show("inc.div",[
//            "type" => "row",
//            "content" => $chronologyBlock,
//            "style" => [
//                "margin-top" => "40px"
//            ]
//        ],true)
//    ],true)
//]);

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "overflow-x" => "auto",
        "padding-right" => "50px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        
        
        "content" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "flex-grow" => "1"
            ],
            "content" => $paramsBlock. $docListBlock
        ],true).$contactsBlock
    ],true).$commentBlock. $chronologyContainer
]);



/*Переменные--------------------------------------------------*/

$view->show("inc.var",[
    "key" => "title",
    "value" => "Карточка клиента"
]);
$tabTitle = (isset($params["dnum"]) && ($params["dnum"])) ? $params["dnum"] : "###";
$view->show("inc.var",[
    "key" => "tabTitle",
    "value" => $tabTitle
]);

$view->show("inc.var",[
    "key" => "cur_dnum",
    "value" => $params["dnum"]
]);

$view->show("inc.var",[
    "key" => "cur_clientId",
    "value" => $params["clientId"]
]);





