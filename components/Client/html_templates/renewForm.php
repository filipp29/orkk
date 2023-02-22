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
    "text" => "Переоформить",
    "style" => [
        "margin-top" => "20px"
    ] + $textStyle
],true);
if ($dnum){
    $readonly = [
        "attribute" => [
            "readonly" => "readonly",
            "disabled" => "disabled"
        ]
    ];
}
else{
    $readonly = [];
}
$params = [
    "№ Договора" => $view->show("inc.input.text",[
        "id" => "renew_dnum",
        "style" => [
            "width" => "30%"
        ],
        "value" => $dnum
    ] + $readonly,true),
    "Тип документа" => $view->show("inc.input.select",[
        "id" => "renew_docType",
        "style" => [
            "width" => "30%"
        ],
        "values" => [
            "specification" => "Спецификация",
            "additional" => "Дополнительное соглашение"
        ],
    ] + $readonly,true),
    "Дата" => $view->show("inc.input.date",[
        "id" => "renew_date",
        "style" => [
            "width" => "30%"
        ],
        "value" => $date
    ] + $readonly,true)
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

if (!$dnum){
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "20px"
        ],
        "content" => $view->show("inc.input.file",[
            "filePathId" => "renew_filePath",
            "fileNameId" => "renew_fileName",
            "filePathValue" => $filePath,
            "fileNameValue" => $fileName
        ],true)
    ],true); 
}
if ($dnum){
    $buttonBlock = "";
//    $buttonBlock = $view->show("inc.div",[
//        "type" => "row",
//        "style" => [
//            "justify-content" => "center",
//            "margin-top" => "20px"
//        ],
//        "content" => $view->show("buttons.lockSquare",[
//            "onclick" => "renewClientLock(this)",
//        ],true)
//    ],true); 
}
else{
    $buttonBlock = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "center",
            "margin-top" => "20px"
        ],
        "content" => $view->show("buttons.acceptSquare",[
            "onclick" => "renewClient(this)",
        ],true)
    ],true);        
}        

$radioBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "center",
        "margin-top" => "15px"
    ],
    "content" => $view->show("inc.input.radio",[
        "id" => "renewType",
        "divType" => "row",
        "values" => [
            "Договор" => "Договор",
            "Точка" => "Точка"
        ],
        "value" => $type
    ],true)
],true);



$vars = [
    "renew_clientId" => $clientId
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
    "content" => $title. $body. $radioBlock. $buttonBlock.  $varsBlock
]);

/*--------------------------------------------------*/








