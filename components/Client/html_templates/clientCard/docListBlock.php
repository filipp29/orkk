<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$titleStyle = [
    "font-size" => "25px"
];
$labelStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$textStyle = [
    "font-size" => "14px",
    "height" => "18px"
];

$inputStyle = [
    "font-size" => "14px",
    "height" => "21px"
];

$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px"
];
$fontSize = "14px";

$typeList = [
    "streetType",
    "buildingType",
    "flatType",
    "legalStreetType",
    "legalBuildingType",
    "legalFlatType",
];

function getDocBody(
        $id,
        $value = "",
        $doc = []
){
    $titleStyle = [
        "font-size" => "25px"
    ];
    $labelStyle = [
        "margin" => "0px 10px",
        "font-weight" => "bolder"
    ];
    $textStyle = [
        "font-size" => "14px",
        "height" => "18px"
    ];

    $inputStyle = [
        "font-size" => "14px",
        "height" => "21px"
    ];

    $buttonStyle = [
        "width" => "auto",
        "padding" => "5px 10px",
        "margin-right" => "15px"
    ];
    $fontSize = "14px";
    
    $buf = new \Main\Controller();
    $view = $buf->getView();
    $label = [
        "contractDate" => "Дата документа",
        "connectSum" => "Сумма подключения",
        "hardware" => "Оборудование",
        "amount" => "Сумма тарифа",
        "speed" => "Скорость",
        "city" => "Город",
        "street" => "Улица",
        "building" => "Дом",
        "flat" => "Квартира/Офис",
        "bin" => "БИН",
        "iban" => "iban",
        "bik" => "БИК",
        "kbe" => "КБе",
        "bank" => "Банк",
        "legalCity" => "Юр. город",
        "legalStreet" => "Юр. улица",
        "legalBuilding" => "Юр. дом",
        "legalFlat" => "Юр. квартира/Офис",
        "activateDate" => "Дата активации",
        "attractType" => "Канал привлечения",
        "name" => "Наименование"
    ];
    $docBody = [
        "default" => $view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "activateDate" => $view->show("inc.input.date",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => $inputStyle
        ],true),
        "contractDate" => $view->show("inc.input.date",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => $inputStyle
        ],true),
        "attractType" => $view->show("inc.input.select_stretch",[
            "id" => "param_".$id,
            "values" => \Settings\Main::attractType(),
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true),
        "city" => $view->show("inc.input.select_stretch",[
            "id" => "param_".$id,
            "values" => \Settings\Main::cityList(),
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true),
        "street" => $view->show("inc.input.select_stretch",[
            "id" => "param_streetType",
            "values" => \Settings\Main::streetType(),
            "value" => isset($doc["param_streetType"]) ? $doc["param_streetType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "building" => $view->show("inc.input.select_stretch",[
            "id" => "param_buildingType",
            "values" => \Settings\Main::buildingType(),
            "value" => isset($doc["param_buildingType"]) ? $doc["param_buildingType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "flat" => $view->show("inc.input.select_stretch",[
            "id" => "param_flatType",
            "values" => \Settings\Main::flatType(),
            "value" => isset($doc["param_flatType"]) ? $doc["param_flatType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "legalStreet" => $view->show("inc.input.select_stretch",[
            "id" => "param_legalStreetType",
            "values" => ["" => ""] + \Settings\Main::streetType(),
            "value" => isset($doc["param_legalStreetType"]) ? $doc["param_legalStreetType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "legalBuilding" => $view->show("inc.input.select_stretch",[
            "id" => "param_legalBuildingType",
            "values" => ["" => ""] + \Settings\Main::buildingType(),
            "value" => isset($doc["param_legalBuildingType"]) ? $doc["param_legalBuildingType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "legalFlat" => $view->show("inc.input.select_stretch",[
            "id" => "param_legalFlatType",
            "values" => ["" => ""] + \Settings\Main::flatType(),
            "value" => isset($doc["param_legalFlatType"]) ? $doc["param_legalFlatType"] : "",
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => $inputStyle
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "param_".$id,
            "value" => $value,
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "max-width" => "205px"
            ] + $inputStyle
        ],true),
        "hardware" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "values" => ["" => ""] + \Settings\Client::hardwareTemplate(),
                "class" => "inputTemplate hidden",
                "attribute" => [
                    "onchange" => "addSelectToInput(this.closest(`.docContainer`),this,`param_hardware`)",
                ],
                "style" => [
                    "margin-bottom" => "5px"
                ]
            ],true).$view->show("inc.input.area",[
                "id" => "param_hardware",
                "style" => [
                    "width" => "100%",
                    "height" => "25px"
                ],
                "value" => $value,
                "class" => "readonly",
                "attribute" => [
                    "onkeyup" => "inputAreaAutoSize(this)",
                    "readonly" => "readonly"
                ]
            ],true)
        ],true),
        "bik" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "values" => ["" => ""] + \Settings\Client::bikTemplate(),
                "class" => "inputTemplate hidden",
                "attribute" => [
                    "onchange" => "bikSelect(this,`docContainer`,`param_bik`,`param_bank`)",
                ],
                "style" => [
//                    "margin-bottom" => "5px"
                ]
            ],true).$view->show("inc.input.text_stretch",[
                "id" => "param_bik",
                "style" => [
                    "max-width" => "205px",
                    "height" => "21px",
                    "font-size" => "14px"
                ],
                "value" => $value,
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ]
            ],true)
        ],true),
    ];
    
    if (isset($docBody[$id])){
        return $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin-bottom" => "2px"
            ],
            "content" => $view->show("inc.text",[
                "text" => $label[$id]. ":",
                "style" => $textStyle + $labelStyle
            ],true). $view->show("inc.div",[
                "type" => "row",
                "content" => $docBody[$id],
                "style" => [
                    "flex-wrap" => "wrap"
                ]
            ],true)
        ],true);
    }
    else {
        return $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin-bottom" => "2px"
            ],
            "content" => $view->show("inc.text",[
                "text" => $label[$id]. ":",
                "style" => $textStyle + $labelStyle
            ],true). $view->show("inc.div",[
                "type" => "row",
                "content" => $docBody["default"],
                "style" => [
                    "flex-wrap" => "wrap"
                ]
            ],true)
        ],true);
    }
}



$content = "";



/*Инициализация--------------------------------------------------*/

$acceptBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "center",
        "align-items" => "center",
        "height" => "35px"
    ] + $buttonStyle,
    "class" => "acceptBlock hidden",
    "content" => $view->show("buttons.closeSquare",[
        "onclick" => "modifyDocCancel(this)",
        "style" => [
            "margin-right" => "25px"
        ]
    ],true).$view->show("buttons.acceptSquare",[
        "onclick" => "modifyDocComment(this)",
    ],true)
],true);



$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "flex-end",
        "margin-top" => "30px"
    ],
    "class" => "buttonBlock",
    "content" => $view->show("buttons.normal",[
        "text" => "Редактировать",
        "onclick" => "modifyDoc(this)",
        "style" => [
            "width" => "130px"
        ] + $buttonStyle
    ],true). $acceptBlock. 
//    $view->show("buttons.normal",[
//        "text" => "Подключить",
//        "onclick" => "connectClient(this)",
//        "style" => $buttonStyle
//    ],true). 
    $view->show("buttons.red",[
        "text" => "Удалить",
        "onclick" => "checkAccess('onlyLeader',acceptMsg,deleteDoc,this)",
        "style" => $buttonStyle
    ],true)
],true); 

/*--------------------------------------------------*/

$commentButtonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between",
        "align-items" => "center",
        "margin-top" => "10px",
        "width" => "100%"
    ],
    
    "content" => $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("buttons.closeSquare",[
            "onclick" => "modifyDocCancel(this)",
            "style" => [
                "margin-right" => "15px"
            ]
        ],true). $view->show("buttons.acceptSquare",[
            "onclick" => "saveDoc(this)",
            "style" => [
                "margin-right" => "15px"
            ]
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.input.file",[
            "filePathId" => "comment_filePath",
            "fileNameId" => "comment_fileName",
        ],true)
    ],true)
],true);

$commentContainer = $view->show("inc.div",[
    "type" => "column",
    "class" => "commentContainer hidden",
    "attribute" => [
        "id" => "createCommentContainer"
    ],
    "style" => [
        "width" => "100%",
        "height" => "100%",
        "align-items" => "flex-start",
        "justify-content" => "flex-start"
    ],
    "content" => $view->show("inc.input.area",[
        "id" => "comment_text",
        "style" => [
            "width" => "100%",
            "height" => "135px"
        ] 
    ],true). $commentButtonBlock
],true);

/*--------------------------------------------------*/

foreach($docList as $doc){
    $title = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-bottom" => "10px"
        ],
        "content" => $view->show("inc.text",[
            "text" => $doc["docName"],
            "style" => [
                "border-bottom" => "1px var(--modColor_darkest) dashed",
                "cursor" => "pointer",
                "margin-right" => "8px",
                "font-size" => "18px"
            ],
            "attribute" => [
                "onclick" => "toggleDocCard(this)"
            ]
        ],true). $view->show("inc.text",[
            "text" => $doc["posType"],
            "style" => [
                "border" => "1px var(--modColor_darkest) solid",
                "border-radius" => "4px",
                "padding" => "3px 5px",
                "font-size" => "12px"
            ]
        ],true)
    ],true);
    
    $itemList = [];
    
    $vars = [
        "doc_posType" => $doc["posType"],
        "doc_docName" => $doc["docName"],
        "doc_dnum" => $doc["dnum"],
        "doc_docId" => $doc["docId"],
        "doc_posId" => $doc["posId"],
        "doc_clientId" => $doc["clientId"]
    ];
    
    $docVars = $view->show("inc.vars",[
        "vars" => $vars
    ],true);
    
    
    foreach($doc as $key => $value){
        $buf = explode("_",$key);
        
        if ($buf[0] == "param"){
            if (in_array($buf[1], $typeList)){
                continue;
            }
            $itemList[] = getDocBody($buf[1], $value,$doc);
        }
    }
    
    $count = count($itemList);
    
    $half = $count - (floor($count / 2));
    $left = "";
    for($i = 0; $i < $half; $i++){
        $left .= $itemList[$i];
    }
    $right = "";
    for($i = $half; $i < $count; $i++){
        $right .= $itemList[$i];
    }
    $paramsBlock = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "margin-top" => "10px"
        ],
        "class" => "paramsContainer",
        "content" => $view->show("inc.div",[
            "type" => "column",
            "content" => $left,
            "style" => [
                "width" => "50%"
            ]
        ],true). $view->show("inc.div",[
            "type" => "column",
            "content" => $right,
            "style" => [
                "width" => "50%"
            ]
        ],true)
    ],true);
    $body = $view->show("inc.div",[
        "type" => "column",
        "content" => $paramsBlock. $buttonBlock. $view->show("inc.div",[
            "type" => "column",
            "class" => "commentContainer hidden",
            "attribute" => [
                "id" => "createCommentContainer"
            ],
            "style" => [
                "width" => "100%",
                "height" => "100%",
                "align-items" => "flex-start",
                "justify-content" => "flex-start"
            ],
            "content" => $view->show("inc.input.area",[
                "id" => "comment_text",
                "style" => [
                    "width" => "100%",
                    "height" => "135px"
                ],
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ]
            ],true). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "justify-content" => "space-between",
                    "align-items" => "center",
                    "margin-top" => "10px",
                    "width" => "100%"
                ],
                "content" => $view->show("inc.div",[
                    "type" => "row",
                    "content" => $view->show("buttons.closeSquare",[
                        "onclick" => "modifyDocCancel(this)",
                        "style" => [
                            "margin-right" => "15px"
                        ]
                    ],true). $view->show("buttons.acceptSquare",[
                        "onclick" => "saveDoc(this)",
                        "style" => [
                            "margin-right" => "15px"
                        ]
                    ],true)
                ],true). $view->show("inc.div",[
                    "type" => "row",
                    "content" => $view->show("inc.input.file",[
                        "filePathId" => "comment_filePath",
                        "fileNameId" => "comment_fileName",
                        "filePathValue" => $doc["filePath"],
                        "fileNameValue" => $doc["fileName"]
                    ],true)
                ],true)
            ],true)
        ],true),
        "class" => "docBody hidden",
        "style" => [
            "margin-bottom" => "15px",
            "background-color" => "#f2f2f2",
            "padding" => "10px",
            "border" => "1px #dddddd solid"
        ]
    ],true);
    $content .= $view->show("inc.div",[
        "type" => "column",
        "content" => $title. $body. $docVars,
        "style" => [
            "width" => "100%"
        ],
        "class" => "docContainer"
    ],true);
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $content,
    "style" => [
        "margin" => "25px 0px 10px 0px"
    ]
]);

/*Переменные--------------------------------------------------*/











