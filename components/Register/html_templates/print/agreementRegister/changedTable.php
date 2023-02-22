<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableHeader = [
    "mainContractDate" => "Дата договора",
    "contractDate" => "Дата дкумента",
    "activateDate" => "Дата активации",
    "name" => "Наименование",
    "dnum" => "Номер договора",
    "posType" => "Тип документа",
    "amount" => "Ежемесячная плата",
    "connectSum" => "Сумма подключения",
    "comment" => "Примечание",
    "registerComment" => "Комментарий"
];

$tableWidth = [
    "mainContractDate" => "100px",
    "contractDate" => "100px",
    "name" => "",
    "dnum" => "100px",
    "posType" => "200px",
    "activateDate" => "100px",
    "amount" => "100px",
    "connectSum" => "100px",
    "comment" => "Примечание",
    "registerComment" => "200px"
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];


/*--------------------------------------------------*/

$getCommentButtonBlock = function(
        $filePath
)use($view,$textStyle){
    if (!$filePath){
        $docStyle = [
            "background-color" => "red"
        ];
        $docOnclick = "";
    }
    else{
        $docStyle = [];
        $docOnclick = "getFile(`{$filePath}`)";
    }
    return $view->show("inc.div",[
        "type" => "row",
        "content" => 
    //    $view->show("inc.text",[
    //        "text" => "Свернуть/Развернуть",
    //        "style" => [
    //            "paddin-bottom" => "3px",
    //            "cursor" => "pointer",
    //            "color" => "#FF6F00",
    //            "width" => "fit-content",
    //            "border-bottom" => "1px dashed var(--modColor_darkest)",
    //        ] + $textStyle,
    //        "attribute" => [
    //            "onclick" => "hiddenTextTilteClick(this)",
    //            "id" => "hiddenTextTitle"
    //        ]
    //    ],true). 
        $view->show("buttons.normal",[
            "text" => "Карточка",
            "onclick" => "showClientCardFromTableRow(this)",
            "style" => [
                "height" => "var(--modLineHeight)",
    //            "margin-left" => "30px"
            ] + $textStyle
        ],true). $view->show("buttons.normal",[
            "text" => "Документ",
            "onclick" => $docOnclick,
            "style" => $docStyle + [
                "height" => "var(--modLineHeight)",
    //            "margin-left" => "30px"
            ] + $textStyle
        ],true)
    ],true);
};

/*--------------------------------------------------*/

$getEditBlock = function($id,$value,$width) use ($view,$textStyle){
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.area_stretch",[
            "id" => $id,
            "class" => "inputAreaAutosize",
            "style" => [
                "max-width" => $width,
                "height" => "auto",
                "min-width" => $width,
            ] + $textStyle,
            "value" => $value
        ],true). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "Редактировать",
                "onclick" => "tableCellEditClick(this)",
                "class" => "editButton",
                "style" => [
                    "font-size" => "12px",
                    "height" => "18px",
                    "width" => "100%"
                ]
            ],true). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "100%",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "height" => "18px"
                ],
                "class" => "acceptBlock hidden",
                "content" => $view->show("buttons.close",[
                    "onclick" => "tableCellEditClose(this,false)",
                    "style" => [
                        "margin-right" => "25px",
                        "height" => "18px"
                    ]
                ],true).$view->show("buttons.accept",[
                    "onclick" => "saveAgreementUserComment(this,`td`)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true);
};
 
/*--------------------------------------------------*/

/*Инициализация--------------------------------------------------*/



//$clientList = $client->clientList();
$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => "var(--modBGColor)",
                "height" => "auto"
            ],
            "class" => "clietListSortButton",
        ],true),
        "style" => [
            "text-align" => "center",
            "padding" => "0px 5px",
            "height" => "auto",
            "width" => $tableWidth[$key],
            "border" => "1px solid var(--modColor_light)"
        ]
    ],true);
}

$body = "";
$trColor = 0;
$commentButtonBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("inc.text",[
        "text" => "Свернуть/Развернуть",
        "style" => [
            "paddin-bottom" => "3px",
            "cursor" => "pointer",
            "color" => "#FF6F00",
            "width" => "fit-content",
            "border-bottom" => "1px dashed var(--modColor_darkest)",
        ] + $textStyle,
        "attribute" => [
            "onclick" => "hiddenTextTilteClick(this)",
            "id" => "hiddenTextTitle"
        ]
    ],true). $view->show("buttons.normal",[
        "text" => "Карточка",
        "onclick" => "showClientCardFromTableRow(this)",
        "style" => [
            "height" => "var(--modLineHeight)",
            "margin-left" => "30px"
        ] + $textStyle
    ],true)
],true);
foreach($accountList as $key => $posList){
    $trColor++;
    $k = array_key_first($posList);
    $clientId = $posList[$k]["clientId"];
    
    if ($closed){
        $buf = isset($currentRegister["changed"][$posList[$k]["dnum"]]) ? $currentRegister["changed"][$posList[$k]["dnum"]] : [];
        if(!$buf){
            $sameColor = "#66CDAA";
        }
        else{
            $sameColor = (arrayCompareRecursive($posList, $buf) == 1) ? "#FFA07A" : "";
        }
    }
    
    if (isset($currentRegister["change"][$posList[$k]["dnum"]][$clientId]["registerComment"])){
        $registerComment = $currentRegister["change"][$posList[$k]["dnum"]][$clientId]["registerComment"];
    }
    else{
        $registerComment = "";
    }
    $vars = [
        "reg_tableKey" => $clientId,
        "reg_tableType" => "change",
        "reg_year" => $year,
        "reg_month" => $month,
        "reg_dnum" => $posList[$k]["dnum"]
    ];
    
    $tr = [
        "mainContractDate" => $posList[$k]["mainContractDate"] ? date("d.m.Y",$posList[$k]["mainContractDate"]) : "",
        "contractDate" => $posList[$k]["param_contractDate"] ? date("d.m.Y",$posList[$k]["param_contractDate"]) : "",
        "activateDate" => date("d.m.Y",$posList[$k]["param_activateDate"]),
        "name" => "{$posList[$k]["clientType"]} {$posList[$k]["clientName"]}",
        "dnum" => $posList[$k]["dnum"],
        "posType" => $posList[$k]["posType"],        
        "amount" => (isset($posList[$k]["param_amount"]) ? $posList[$k]["param_amount"] : "0"). " тг",
        "connectSum" => (isset($posList[$k]["param_connectSum"]) ? $posList[$k]["param_connectSum"] : "0"). " тг",        
        "comment" => nl2br($posList[$k]["comment"]),
        "registerComment" => $registerComment         
    ];
    $count = count($posList);
    $clientKeys = [
        "mainContractDate",
        "name",
        "dnum",
    ];
    $posKeys = [
        "contractDate",
        "posType",
        "activateDate",
        "amount",
        "connectSum",
        "speed",
        "comment"
    ];
    $trContent = "";
    foreach($tr as $key => $value){
        if (in_array($key, $clientKeys)){
            $attribute = [
                "rowspan" => $count
            ];
        }
        else{
            $attribute = [
            ];
        }
        
        $trContent .= $view->show("table.td",[
            "content" => $value,
            "attribute" => $attribute,
            "style" => $tdStyle
        ],true);
    }
    $body .= $view->show("table.tr",[
        "content" => $trContent,
        "class" => ($trColor % 2 != 0) ? "odd" : "even",
        "style" => [
            "background-color" => isset($sameColor) ? $sameColor : ""
        ]
    ],true);    
    unset($posList[$k]);
    foreach($posList as $i => $pos){
        $clientId = $posList[$i]["clientId"];
        
        if (isset($currentRegister["block"][$posList[$i]["dnum"]][$clientId]["registerComment"])){
            $registerComment = $currentRegister["block"][$posList[$i]["dnum"]][$clientId]["registerComment"];
        }
        else{
            $registerComment = "";
        }
        $vars = [
            "reg_tableKey" => $clientId,
            "reg_tableType" => "block",
            "reg_year" => $year,
            "reg_month" => $month,
            "reg_dnum" => $posList[$i]["dnum"]
        ];
        
        $tr = [
            "contractDate" => $posList[$i]["param_contractDate"] ? date("d.m.Y",$posList[$i]["param_contractDate"]) : "",
            "activateDate" => isset($posList[$i]["param_activateDate"]) ? date("d.m.Y",$posList[$i]["param_activateDate"]) : "",
            "posType" => $posList[$i]["posType"],        
            "amount" => (isset($posList[$i]["param_amount"]) ? $posList[$i]["param_amount"] : "0"). " тг",
            "connectSum" => (isset($posList[$i]["param_connectSum"]) ? $posList[$i]["param_connectSum"] : "0"). " тг",     
            "comment" => nl2br($posList[$i]["comment"]),
            "registerComment" => $registerComment
        ];
        $trContent = "";
        foreach($tr as $key => $value){
            
            $trContent .= $view->show("table.td",[
                "content" => $value,
                "style" => $tdStyle,
            ],true);
        }
        $body .= $view->show("table.tr",[
            "content" => $trContent,
            "class" => ($trColor % 2 != 0) ? "odd" : "even",
            "style" => [
                "background-color" => isset($sameColor) ? $sameColor : ""
            ]
        ],true);
    }
}

$thead = $view->show("table.tr",[
    "content" => $view->show("table.th",[
        "style" => [
            "text-align" => "left",
            "padding" => "0px 5px",
            "height" => "auto",
            "border" => "1px solid var(--modColor_light)",
            "margin-top" => "20px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Изменения",
            "style" => [
                "font-size" => "20px"
            ]
        ],true),
        "attribute" => [
            "colspan" => "10"
        ]
    ],true)
],true). $view->show("table.tr",[
    "content" => $headerContent
],true);




/*Отображение--------------------------------------------------*/


$view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("table.main",[
        "thead" => $thead,
        "tbody" => $body
    ],true),
    "style" => [
        "margin-top" => "20px"
    ],
    "class" => "agreementTable"
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/


























