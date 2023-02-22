<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$headerStyle = [
    "justify-content" => "center",
    "align-items" => "center",
    "text-align" => "center",
    "height" => "auto",
    "font-weight" => "bolder",
    "font-size" => "12px"
];

$textStyle = [
    "height" => "auto",
    "font-size" => "12px"
];

$headerList = [
    "Документ",
    "Тип документа",
    "Адрес точки",
    "Статус клиента",
    "Статус местонахождения документа"
];

/*--------------------------------------------------*/



$paramList = \Settings\Main::docPlacementButtonParams();
    

/*--------------------------------------------------*/

$getPlacementButton = function(
        $info,
        $type,
        $additional = false
)use($view,$textStyle,$paramList){
    
    
    $vars = [
        "doc_dnum" => $info["dnum"],
        "doc_docType" => $type,
        "doc_docId" => isset($info["docId"]) ? $info["docId"] : "",
        "doc_docPlacement" => $info[$type] ? $info[$type] : "Не требуется"
    ];
    $param = $paramList[$vars["doc_docPlacement"]];
    return $view->get("inc.div",[
        "type" => "row",
        "attribute" => [
            "onclick" => $additional ? "showDocumentPlacementForm(this,`.placementButton`,`doc`)" : "DocumentRegister.placementButtonClick(this)",
        ],
        "style" => [
            "justify-content" => "center",
            "align-items" => "center"
        ],
        "class" => "placementButton",
        "content" => $view->get("inc.text",[
            "text" => $param["text"],
            "attribute" => [
                "id" => "button_text"
            ],
            "style" => [
                "background-color" => $param["bgColor"],
                "color" => $param["color"],
                "cursor" => "pointer",
                "width" => "auto",
                "height" => "auto",
                "padding" => "3px",
                "border" => "1px var(--modColor_darkest) solid"
            ] + $textStyle
        ]). $view->get("inc.vars",[
            "vars" => $vars
        ])
    ]);
};

/*--------------------------------------------------*/

$getAdditionalBlock = function(
        $info,
)use($view,$textStyle,$getPlacementButton,$paramList){
    $keyList = [
        "safekeepingPlacement",
        "transferActPlacement",
        "disclaimerPlacement"
    ];
    $extraContent = "";
    foreach($keyList as $key){
        $placement = $info[$key] ? $info[$key] : "Не требуется";
        $extraContent .= $view->get("inc.div",[
            "type" => "row",
            "style" => [
                "border" => "1px var(--modColor_darkest) solid",
                "background-color" => $paramList[$placement]["bgColor"],
                "width" => "12px",
                "height" => "8px"
            ],
            "id" => $key
        ]);
    }
    $extraBlock = $view->get("inc.div",[
        "type" => "column",
        "style" => [
            "height" => "100%",
            "justify-content" => "space-between",
            "margin-left" => "8px"
        ],
        "content" => $extraContent
    ]);
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "flex-end",
            "padding-right" => "20px"
        ],
        "class" => "additionalBlock",
        "content" => $getPlacementButton($info,"docPlacement",true). $extraBlock
    ]);
};




/*Инициализация--------------------------------------------------*/

$widthList = [
    "280px",
    "150px",
    "calc(100% - 300px)",
    "150px",
    "200px"
];


$buf = $view->show("inc.text",[
    "text" => $headerList[1],
    "style" => [
        "width" => $widthList[1]
    ] + $headerStyle
],true). $view->show("inc.text",[
    "text" => $headerList[2],
    "style" => [
        "width" => $widthList[2]
    ] + $headerStyle
],true). $view->show("inc.text",[
    "text" => $headerList[3],
    "style" => [
        "width" => $widthList[3]
    ] + $headerStyle
],true);




$header = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "margin" => "0px 0px 20px 0px"
    ],
    "content" => $view->show("inc.text",[
        "text" => $headerList[0],
        "style" => [
            "width" => $widthList[0]
        ] + $headerStyle
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $buf,
        "style" => [
            "width" => "calc(100% - 400px)"
        ]
    ],true). $view->show("inc.text",[
        "text" => $headerList[4],
        "style" => [
            "width" => $widthList[4]
        ] + $headerStyle
    ],true)
],true);

$body = "";



foreach($clientDocList as $value){
    $docName = $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => $widthList[0],
            "padding-top" => "4px"
        ],
        "class" => "chronologyCommentBox",
        "content" => $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "align-items" => "center"
                
            ],
            "content" => $view->show("inc.text",[
                "text" => $value["name"],
                "style" => [
                    "color" => "var(--modColor_extra)",
                    "cursor" => "pointer",
                    "border-bottom" => "1px dashed var(--modColor_darkest)",
                ] + $textStyle,
                "attribute" => [
                    "onclick" => "openChronologyComment(this)"
                ]
            ],true). $view->show("inc.input.checkbox",[
                "text" => "Ре",
                "id" => "forPayment",
                "checked" => $value["register"],
                "onclick" => "changeClientDocRegister(this)",
                "style" => [
                    "margin" => "0px 0px 0px 8px",
                    "border-radius" => "2px",
                    "height" => "calc(var(--modLineHeight) - 7px)"
                ] + $textStyle
            ],true)
        ],true). $view->show("inc.text",[
            "text" => "{$value["comment"]}",
            "style" => [
                "width" => "100%",
                "margin" => "8px 0px 0px 0px",
                "padding" => "0px 5px 0px 5px"
            ] + $textStyle,
            "class" => "chronologyComment hidden"        
        ],true)
    ],true);
            
            
            
    $thisPage = ($currentClientId == $value["clientId"]) ? true : false;
    if ($value["filePath"]){
        $posBg = "#98FB98";
    }
    else{
        $posBg = "#FFA07A";
    }
    $posType = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => $widthList[1],
            "justify-content" => "center"
        ],
        "attribute" => [
            "onclick" => "getDocFile(this)"
        ],
        "content" => $view->show("inc.text",[
            "text" => $value["docType"],
            "style" => [
                "justify-content" => "center",
                "border" => "1px var(--modColor_darkest) solid",
                "border-radius" => "4px",
                "padding" => "3px 5px",
                "text-align" => "center",
                "width" => "max-content",
                "cursor" => "pointer",
                "background-color" => $posBg
            ] + $textStyle
        ],true)        
    ],true);
    if ($thisPage){
        $arrowButton = $view->show("buttons.greenArrow",[
            "onclick" => "",
            "style" => [
                "height" => "20px"
            ]
        ],true);
    } 
    else{
        $arrowButton = $view->show("buttons.blueArrow",[
            "onclick" => "showClientCard(`{$value["clientId"]}`)",
            "style" => [
                "height" => "20px"
            ]        
        ],true);
    }
    $address = $view->show("inc.text",[
        "text" => $value["address"],
        "style" => [
            "padding" => "0px 0px 0px 15px"
        ] + $textStyle
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $arrowButton,
        "style" => [
            "height" => "var(--modLineHeight)",
            "align-items" => "center",
            "justify-content" => "center",
            "padding-right" => "8px"
        ]
    ],true);
    $vars = $view->show("inc.var",[
        "key" => "chr_posId",
        "value" => $value["docId"]
    ],true). $view->show("inc.var",[
        "key" => "chr_clientId",
        "value" => $value["clientId"]
    ],true). $view->show("inc.var",[
        "key" => "chr_posFilePath",
        "value" => $value["filePath"]
    ],true); 
    $buf = $posType. $view->show("inc.div",[
        "type" => "row",
        "content" => $address,
        "style" => [
            "width" => $widthList[2],
            "justify-content" => "space-between"
        ]
    ],true). $view->show("inc.text",[
        "text" => $value["clientStatusShow"],
        "style" => [
            "width" => $widthList[3],
            "justify-content" => "center",
            "text-align" => "center",
            "color" => \Settings\Main::statusColor()[$value["clientStatusShow"]]
        ] + $textStyle
    ],true). $vars;

    $posText = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "margin-bottom" => "10px"
        ],
        "content" => $buf,
        "class" => "chronologyPosContainer"
    ],true);        
            
            
            
    $docPlacement = $view->show("inc.input.select",[
        "id" => "chr_docPlacement",
        "value" => $value["docPlacement"],
        "values" => \Settings\Main::docPlacement(),
        "style" => [
            "width" => "140",
            "border" => "none",
            "text-align" => "right",
        ] + $textStyle,
        "attribute" => [
            "onchange" => "changeDocPlacement(this)"
        ]            
    ],true);
    
    $docPlacement = $view->show("inc.div",[
        "type" => "row",
        "content" => $docPlacement,
        "style" => [
            "width" => "calc({$widthList[4]} - 20px)",
            "justify-content" => "center",
            "align-items" => "center",
            "height" => "var(--modLineHeight)"
        ]
    ],true);        
          
    $vars = [
        "chr_docId" => $value["docId"],
        "chr_clientDoc" => "1",
        "chr_clientId" => $value["clientId"]
    ];
    $varBlock = $view->show("inc.vars",[
        "vars" => $vars
    ],true). $view->show("inc.var",[
        "key" => "chr_docType",
        "value" => $value["docType"],
        "class" => "chr_docType"
    ],true);    
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%"
        ],
        "class" => "chronologyDocContainer",
        "content" => $docName. $view->show("inc.div",[
            "type" => "column",
            "content" => $posText,
            "style" => [
                "width" => "calc(100% - 400px)"
            ]
        ],true). $docPlacement. $varBlock
    ],true);
}

/*--------------------------------------------------*/
/*--------------------------------------------------*/
$paymenBlockParams = [
    "none" => [
        "background-color" => "var(--modBGColor)",
        "color" => "green",
        "border" => "1px solid green"
    ],
    "added" => [
        "background-color" => "green",
        "color" => "var(--modBGColor)",
        "border" => "1px solid green"
    ],
    "closed" => [
        "background-color" => "#FF4500",
        "color" => "var(--modBGColor)",
    ]
];
foreach($docList as $value){
    $posText = "";
    $payManager = isset($value["payManager"]) ? $value["payManager"] : "";
    if ((isset($value["forPayment"])) && ($value["forPayment"])){
        $type = "closed";
    }
    else if($payManager){
        $type = "added";
    }
    else{
        $type = "none";
    }
    
    $paymentBlock = $view->show("inc.text",[
        "text" => "в ЗП",
        "style" => [
            "margin" => "0px 0px 0px 8px",
            "border-radius" => "2px",
            "height" => "calc(var(--modLineHeight) - 7px)",
            "padding" => "0px 5px",
            "cursor" => "pointer"
        ] + $paymenBlockParams[$type] + $textStyle,
        "attribute" => [
            "onclick" => "changeForPayment(this)"
        ]
    ],true);
    
    if ($value["docType"] == "specification"){
        $onclick = "showNewSpecificationForm(this)";
    }
    else{
        $onclick = "showNewDocForm(this,`{$value["docId"]}`)";
    }
    $docName = $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => $widthList[0],
            "padding-top" => "4px"
        ],
        "class" => "chronologyCommentBox",
        "content" => $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "align-items" => "center"
                
            ],
            "content" => $view->show("buttons.plus",[
                "onclick" => $onclick,
                "style" => [
                    "margin-right" => "6px",
                    "height" => "calc(var(--modLineHeight) - 7px)"
                ]
            ],true).$view->show("inc.text",[
                "text" => $value["name"],
                "style" => [
                    "color" => "var(--modColor_extra)",
                    "cursor" => "pointer",
                    "border-bottom" => "1px dashed var(--modColor_darkest)",
                ] + $textStyle,
                "attribute" => [
                    "onclick" => "openChronologyComment(this)"
                ]
            ],true). $paymentBlock
        ],true). $view->show("inc.text",[
            "text" => "{$value["comment"]}",
            "style" => [
                "width" => "100%",
                "margin" => "8px 0px 0px 0px",
                "padding" => "0px 5px 0px 5px"
            ] + $textStyle,
            "class" => "chronologyComment hidden"        
        ],true)
    ],true);
    foreach($value["posList"] as $pos){
        $thisPage = ($currentClientId == $pos["clientId"]) ? true : false;
        if ($pos["filePath"]){
            $posBg = "#98FB98";
        }
        else{
            $posBg = "#FFA07A";
        }
        $posType = $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => $widthList[1],
                "justify-content" => "center"
            ],
            "attribute" => [
                "onclick" => "getDocFile(this)"
            ],
            "content" => $view->show("inc.text",[
                "text" => $pos["posType"],
                "style" => [
                    "justify-content" => "center",
                    "border" => "1px var(--modColor_darkest) solid",
                    "border-radius" => "4px",
                    "padding" => "3px 5px",
                    "text-align" => "center",
                    "width" => "max-content",
                    "cursor" => "pointer",
                    "background-color" => $posBg
                ] + $textStyle
            ],true)        
        ],true);
        if ($thisPage){
            $arrowButton = $view->show("buttons.greenArrow",[
                "onclick" => "",
                "style" => [
                    "height" => "20px"
                ]
            ],true);
        } 
        else{
            $arrowButton = $view->show("buttons.blueArrow",[
                "onclick" => "showClientCard(`{$pos["clientId"]}`)",
                "style" => [
                    "height" => "20px"
                ]        
            ],true);
        }
        $address = $view->show("inc.text",[
            "text" => $pos["address"],
            "style" => [
                "padding" => "0px 0px 0px 15px"
            ] + $textStyle
        ],true). $view->show("inc.div",[
            "type" => "row",
            "content" => $arrowButton,
            "style" => [
                "height" => "var(--modLineHeight)",
                "align-items" => "center",
                "justify-content" => "center",
                "padding-right" => "8px"
            ]
        ],true);
        $vars = $view->show("inc.var",[
            "key" => "chr_posId",
            "value" => $pos["posId"]
        ],true). $view->show("inc.var",[
            "key" => "chr_clientId",
            "value" => $pos["clientId"]
        ],true). $view->show("inc.var",[
            "key" => "chr_posFilePath",
            "value" => $pos["filePath"]
        ],true); 
        $buf = $posType. $view->show("inc.div",[
            "type" => "row",
            "content" => $address,
            "style" => [
                "width" => $widthList[2],
                "justify-content" => "space-between"
            ]
        ],true). $view->show("inc.text",[
            "text" => $pos["clientStatusShow"],
            "style" => [
                "width" => $widthList[3],
                "justify-content" => "center",
                "text-align" => "center",
                "color" => \Settings\Main::statusColor()[$pos["clientStatusShow"]]
            ] + $textStyle
        ],true). $vars;
        
        $posText .= $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "margin-bottom" => "10px"
            ],
            "content" => $buf,
            "class" => "chronologyPosContainer"
        ],true);
        
    }
//    $docPlacement = $view->show("inc.input.select",[
//        "id" => "chr_docPlacement",
//        "value" => $value["docPlacement"],
//        "values" => \Settings\Main::docPlacement(),
//        "style" => [
//            "width" => "140",
//            "border" => "none",
//            "text-align" => "right",
//        ] + $textStyle,
//        "attribute" => [
//            "onchange" => "changeDocPlacement(this)"
//        ]            
//    ],true);
//    $docPlacement = $view->get("buttons.normal",[
//        "text" => "Открыть",
//        "onclick" => "showDocumentPlacementForm(this)"
//    ]);
    $docPlacement = $getAdditionalBlock($value);
    
    $docPlacement = $view->show("inc.div",[
        "type" => "row",
        "content" => $docPlacement,
        "style" => [
            "width" => "calc({$widthList[4]} - 20px)",
            "justify-content" => "center",
            "align-items" => "center",
            "height" => "var(--modLineHeight)"
        ]
    ],true);        
            
    $vars = $view->show("inc.var",[
        "key" => "chr_docId",
        "value" => $value["docId"]
    ],true). $view->show("inc.var",[
        "key" => "chr_dnum",
        "value" => $value["dnum"]
    ],true). $view->show("inc.var",[
        "key" => "chr_docType",
        "value" => $value["docType"],
        "class" => "chr_docType"
    ],true). $view->get("inc.var",[
        "key" => "chr_payManager",
        "value" => $payManager
    ]);
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%"
        ],
        "class" => "chronologyDocContainer",
        "content" => $docName. $view->show("inc.div",[
            "type" => "column",
            "content" => $posText,
            "style" => [
                "width" => "calc(100% - 400px)"
            ]
        ],true). $docPlacement. $vars
    ],true);
}




/*Отображение--------------------------------------------------*/

//if (!$dnum){
//    $view->show("buttons.normal",[
//        "text" => "Заключить договор",
//        "onclick" => "showContractForm(this)",
//        "style" => [
//            "width" => "180px"
//        ]
//    ]);
//    
//}
//else{
    $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "100%",
            
        ],
        "content" => $view->show("inc.text",[
            "text" => "Хронология документации",
            "style" => [
                "width" => "100%",
                "padding" => "30px 0px 40px 0px",
                "font-size" => "24px",
                "font-weight" => "bolder"
            ]
        ],true). $header. $body. $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "padding-bottom" => "20px"
            ],
            "content" => $view->show("inc.div",[
                "type" => "row",
                    "style" => [
                    "align-items" => "center",
                    "margin-top" => "40px",
                    "border-bottom" => "1px var(--modColor_darkest) dashed",
                    "width" => "120px",
                    "cursor" => "pointer",
                ],
                "attribute" => [
                    "onclick" => "showNewDocForm(this)"
                ],
                "content" => $view->show("inc.img",[
                    "src" => "/_modules/orkkNew/img/button_plus.png",
                    "style" => [
                        "height" => "10px",
                        "margin-right" => "3px"
                    ]
                ],true). $view->show("inc.text",[
                    "text" => "Доп. соглашение",
                    "style" => $textStyle
                ],true)
            ],true). $view->show("inc.div",[
                "type" => "row",
                    "style" => [
                    "align-items" => "center",
                    "margin-top" => "40px",
                    "margin-left" => "15px",    
                    "border-bottom" => "1px var(--modColor_darkest) dashed",
                    "width" => "120px",
                    "cursor" => "pointer",
                ],
                "attribute" => [
                    "onclick" => "showNewClientDocForm(this)"
                ],
                "content" => $view->show("inc.img",[
                    "src" => "/_modules/orkkNew/img/button_plus.png",
                    "style" => [
                        "height" => "10px",
                        "margin-right" => "3px"
                    ]
                ],true). $view->show("inc.text",[
                    "text" => "Прочие документы",
                    "style" => $textStyle
                ],true)
            ],true)

        ],true)   
    ]);
//}

/*Переменные-----------------*/

