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
    "text" => "Комментарий",
    "style" => [
        "margin" => "20px 0px 0px 0px"
    ] + $textStyle
],true);

$textArea = $view->show("inc.input.area",[
    "id" => "blockComment",
    "style" => [
        "width" => "100%",
        "height" => "140px",
        "margin-top" => "5px"
    ],
    "value" => $comment
],true);

$dateBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("inc.text",[
        "text" => "с",
        "style" => $textStyle
    ],true). $view->show("inc.input.date",[
        "id" => "blockStart",
        "value" => $blockStart
    ],true). $view->show("inc.text",[
        "text" => "по",
        "style" => $textStyle
    ],true). $view->show("inc.input.date",[
        "id" => "blockEnd",
        "value" => $blockEnd
    ],true)
],true);
$fileBlock = $view->get("inc.div",[
    "type" => "row",
    "class" => "clientBlockFileBlock",
    "content" => $view->get("inc.input.checkbox",[
        "id" => "blockTwoDocs",
        "text" => "2 документа",
        "checked" => $twoDocs ? true : false,
        "onclick" => "blockFormTwoDocsClick(this)",
        "style" => [
            "margin-right" => "10px"
        ]
    ]). $view->get("inc.div",[
        "type" => "column",
        "content" => $view->show("inc.input.file",[
            "filePathId" => "blockFilePath",
            "fileNameId" => "blockFileName",
            "filePathValue" => $filePath,
            "fileNameValue" => $fileName
        ],true). $view->get("inc.div",[
            "type" => "row",
            "class" => "blockFileEnd". ($twoDocs ? "" : " hidden"),
            "style" => [
                "margin-top" => "6px"
            ],
            "content" => $view->show("inc.input.file",[
                "filePathId" => "blockFilePathEnd",
                "fileNameId" => "blockFileNameEnd",
                "filePathValue" => $filePathEnd,
                "fileNameValue" => $fileNameEnd,

            ],true)
        ])
    ])
]);

$paramsBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between",
        "margin-top" => "15px"
    ],
    "content" => $dateBlock. $fileBlock
],true);
$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
        "margin-top" => "15px"
    ],
    "content" => $view->show("buttons.closeSquare",[
        "onclick" => "deleteClientBlock(this)",
        "style" => [
            "margin-right" => "10px"
        ]
    ],true). 
//    $view->show("buttons.lockSquare",[
//        "onclick" => "closeClientBlock(this)",
//        "style" => [
//            "margin-right" => "10px"
//        ]
//    ],true). 
    $view->show("buttons.acceptSquare",[
        "onclick" => "saveClientBlock(this)",
    ],true)
],true);

$vars = [
    "block_clientId" => $clientId,
    "blockTimeStamp" => $timeStamp,
];

$varBlock = $view->show("inc.vars",[
    "vars" => $vars
],true);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "class" => "clientBlockContainer",
    "style" => [
        "padding" => "10px"
    ],
    "content" => $title. $textArea. $paramsBlock. $buttonBlock. $varBlock
]);








