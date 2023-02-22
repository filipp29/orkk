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
    "Тип документа" => $view->show("inc.input.select",[
        "id" => "clientDocType",
        "style" => [
            "width" => "65%"
        ],
        "values" => [
            "" => ""
        ] + \Settings\Main::clientDocType(),
        "attribute" => [
            "onchange" => "addSelectToInput(this.closest(`.fmsgBlock_data`),this,`clientDocName`)",
        ],
    ],true),
    "Название" => $view->show("inc.input.text",[
        "id" => "clientDocName",
        "style" => [
            "width" => "65%"
        ],
    ],true),
    "Дата" => $view->show("inc.input.date",[
        "id" => "clientDocDate",
        "style" => [
            "width" => "65%"
        ],
    ],true),
    "Комментарий" => $view->show("inc.input.area",[
        "id" => "clientDocComment",
        "style" => [
            "width" => "65%",
            "height" => "140px"
        ],
        
    ],true),
    "Местонахождение документа" => $view->show("inc.input.select",[
        "id" => "clientDocPlacement",
        "style" => [
            "width" => "47%"
        ],
        "values" => \Settings\Main::docPlacement(),
    ],true)
];
$body = "";
foreach($params as $label => $value){
    if ($label == "Местонахождение документа"){
        $labelWidth = "38%";
    }
    else{
        $labelWidth = "20%";
    }
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "flex-start",
            "align-items" => "flex-start",
            "margin-top" => "20px"
        ],
        "content" => $view->show("inc.text",[
            "text" => $label,
            "style" => [
                "width" => $labelWidth,
                "justify-content" => "flex-end"
            ] + $textStyle
        ],true). $value
    ],true);
}

$body .= $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-top" => "20px",
    ],
    "content" => $view->show("inc.input.checkbox",[
        "id" => "clientDocRegister",
        "text" => "В реестр",
        "style" => [
            "margin-right" => "25px",
            "padding" => "0px 15px"
        ]
    ],true).$view->show("inc.input.file",[
        "filePathId" => "clientDocFilePath",
        "fileNameId" => "clientDocFileName",
    ],true)
],true); 
$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
        "margin-top" => "20px"
    ],
    "content" => $view->show("buttons.acceptSquare",[
        "onclick" => "createClientDoc(this)",
    ],true)
],true);        
        

$vars = [
    "clientDoc_clientId" => $clientId
];

$varsBlock = $view->show("inc.vars",[
    "vars" => $vars
],true);


/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "align-items" => "center"
    ],
    "content" => $title. $body. $buttonBlock. $varsBlock
]);

/*--------------------------------------------------*/








