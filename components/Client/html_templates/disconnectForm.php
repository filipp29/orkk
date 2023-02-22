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
    "id" => "disconnectComment",
    "style" => [
        "width" => "100%",
        "height" => "140px",
        "margin-top" => "5px"
    ],
    "value" => isset($disconnectComment) ? $disconnectComment : ""
],true);

$radioBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
    ],
    "content" => $view->show("inc.input.radio",[
        "id" => "disconnectMethod",
        "divType" => "row",
        "values" => [
            "Договор" => "Договор",
            "Точка" => "Точка"
        ],
        "value" => $disconnectMethod ? $disconnectMethod : "Точка"
    ],true)
],true);

$dateBl = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("inc.text",[
        "text" => "Дата",
        "style" => [
            "width" => "37px"
        ] + $textStyle
    ],true). $view->show("inc.input.date",[
        "id" => "disconnectDate",
        "value" => isset($disconnectDate) ? $disconnectDate : ""
    ],true)
],true);

$dateBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between",
        "margin-top" => "15px",
        "align-items" => "center"
    ],
    "content" => $dateBl. $radioBlock. $view->show("inc.input.file",[
        "filePathId" => "disconnectFilePath",
        "fileNameId" => "disconnectFileName",
        "filePathValue" => isset($disconnectFilePath) ? $disconnectFilePath : "",
        "fileNameValue" => isset($disconnectFileName) ? $disconnectFileName : ""
    ],true)
],true);




$reasonBlock = $view->show("inc.input.select",[
    "id" => "unilaterally_disconnectReason",
    "class" => "disconnectReasonSelect",
    "style" => [
        "width" => "200px"
    ],
    "values" => [
        "Дебиторская задолженность" => "Дебиторская задолженность",
        "Не выходит на связь" => "Не выходит на связь"
    ],
    "value" => isset($disconnectReason) ? $disconnectReason : "",
    "attribute" => [
        "onchange" => "disconnectReasonChange(this)"
    ]
],true). $view->show("inc.input.select",[
    "id" => "statement_disconnectReason",
    "class" => "disconnectReasonSelect",
    "style" => [
        "width" => "200px"
    ],
    "values" => [
        "Закрытие бизнеса" => "Закрытие бизнеса",
        "Отключение одной из точек" => "Отключение одной из точек",
        "Уход к конкуренту" => "Уход к конкуренту",
        "Переезд вне МЁ" => "Переезд вне МЁ",
        "Нет потребности" => "Нет потребности"
    ],
    "value" => isset($disconnectReason) ? $disconnectReason : "",
    "attribute" => [
        "onchange" => "disconnectReasonChange(this)"
    ]
],true). $view->show("inc.input.select",[
    "id" => "other_disconnectReason",
    "class" => "disconnectReasonSelect",
    "values" => [
    ],
    "value" => isset($disconnectReason) ? $disconnectReason : "",
    "attribute" => [
        "onchange" => "disconnectReasonChange(this)"
    ]
],true);

$typeBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between",
        "margin" => "10px 0px 0px 0px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "Тип",
            "style" => [
                "width" => "37px"
            ] + $textStyle
        ],true). $view->show("inc.input.select",[
            "id" => "disconnectType",
            "values" => \Settings\Main::disconnectType(),
            "value" => isset($disconnectType) ? $disconnectType : "",
            "attribute" => [
                "onchange" => "disconnectTypeChange(this)"
            ]
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "Причина",
            "style" => $textStyle
        ],true). $reasonBlock
    ],true)
],true);

if ($disconnectReason == "Уход к конкуренту"){
    $disconnectCompetitor = $disconnectReasonDesc;
}
else{
    $disconnectCompetitor = "";
}

if ($disconnectReason == "Переезд вне МЁ"){
    $disconnectAddress = $disconnectReasonDesc;
}
else{
    $disconnectAddress = "";
}

$descBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "flex-end",
        "padding-right" => "210px",
        "margin-top" => "25px"
        
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "class" => "disconnectDescBlock disconnectCompetitorBlock",
        "style" => [
            "align-items" => "flex-end"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Конткурент",
            "style" => $textStyle
        ],true). $view->show("inc.input.select",[
            "id" => "disconnectCompetitor",
            "style" => [
                "width" => "260px"
            ],
            "values" => \Settings\Main::competitor(),
            "value" => $disconnectCompetitor
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "class" => "disconnectDescBlock disconnectAddressBlock",
        "style" => [
            "align-items" => "flex-end"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Адрес",
            "style" => $textStyle
        ],true). $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "id" => "disconnectAddressTemplate",
                "style" => [
                    "width" => "260px",
                    "margin-bottom" => "8px"
                ],
                "values" => [
                    "" => "",
                    "Неизвестно" => "Неизвестно",
                    "Переезд в другую страну" => "Переезд в другую страну"
                ],
                "attribute" => [
                    "onchange" => "addSelectToInput(this.closest(`.fmsgBlock_data`),this,`disconnectAddress`)"
                ]
            ],true). $view->show("inc.input.text",[
                "id" => "disconnectAddress",
                "style" => [
                    "width" => "260px"
                ],
                "value" => $disconnectAddress
            ],true)
        ],true)
    ],true)
],true);

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
        "margin-top" => "15px"
    ],
    "content" => $view->show("buttons.closeSquare",[
        "onclick" => "deleteClientDisconnect(this)",
        "style" => [
            "margin-right" => "10px"
        ]
    ],true). 
//    $view->show("buttons.lockSquare",[
//        "onclick" => "lockClientDisconnect(this)",
//        "style" => [
//            "margin-right" => "10px"
//        ]
//    ],true). 
    $view->show("buttons.acceptSquare",[
        "onclick" => "saveClientDisconnect(this)",
    ],true)
],true);

$vars = [
    "disconnect_clientId" => isset($clientId) ? $clientId : "",
];

$varBlock = $view->show("inc.vars",[
    "vars" => $vars
],true);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "class" => "clientDisconnectContainer",
    "style" => [
        "padding" => "10px"
    ],
    "content" => $title. $textArea. $dateBlock. $typeBlock. $descBlock. $buttonBlock. $varBlock
]);








