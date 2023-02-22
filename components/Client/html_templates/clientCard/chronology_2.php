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
    "font-size" => "10px"
];

$headerList = [
    "Документ",
    "Тип документа",
    "Адрес точки",
    "Статус клиента",
    "Статус местонахождения документа"
];



/*Инициализация--------------------------------------------------*/

$widthList = [
    "150px",
    "120px",
    "calc(100% - 200px)",
    "80px",
    "130px"
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
            "width" => "calc(100% - 280px)"
        ]
    ],true). $view->show("inc.text",[
        "text" => $headerList[4],
        "style" => [
            "width" => $widthList[4]
        ] + $headerStyle
    ],true)
],true);

$body = "";

foreach($docList as $value){
    $posText = "";
    if (isset($value["forPayment"])){
        $forPayment = ($value["forPayment"] == "1") ? "checked" : "";
    }
    else{
        $forPayment = "";
    }
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
            ],true). $view->show("inc.input.checkbox",[
                "text" => "в ЗП",
                "id" => "forPayment",
                "checked" => $forPayment,
                "onclick" => "changeForPayment(this)",
                "style" => [
                    "margin" => "0px 0px 0px 8px",
                    "border-radius" => "2px",
                    "height" => "calc(var(--modLineHeight) - 7px)",
                    "width" => "25px",
                    "min-width" => "25px",
                    "padding" => "0px"
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
    foreach($value["posList"] as $pos){
        $thisPage = ($currentClientId == $pos["clientId"]) ? true : false;
        $posType = $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => $widthList[1],
                "justify-content" => "center"
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
                    "margin-left" => "8px"
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
        ],true); 
        $buf = $posType. $view->show("inc.div",[
            "type" => "row",
            "content" => $address,
            "style" => [
                "width" => $widthList[2],
                "justify-content" => "space-between"
            ]
        ],true). $view->show("inc.text",[
            "text" => $pos["clientStatus"],
            "style" => [
                "width" => $widthList[3],
                "justify-content" => "center",
                "text-align" => "center"
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
                    "width" => "calc(100% - 280px)"
                ]
            ],true). $docPlacement. $vars
        ],true);
}




/*Отображение--------------------------------------------------*/

if (!$dnum){
    $view->show("buttons.normal",[
        "text" => "Заключить договор",
        "onclick" => "showContractForm(this)",
        "style" => [
            "width" => "180px"
        ]
    ]);
    
}
else{
    $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "100%"
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
                "align-items" => "center",
                "margin-top" => "40px",
                "border-bottom" => "1px var(--modColor_darkest) dashed",
                "width" => "75px",
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
                "text" => "Добавить",
                "style" => $textStyle
            ],true)

        ],true)   
    ]);
}

/*Переменные-----------------*/

