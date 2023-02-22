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
    "contractDate" => "Дата договора",
    "name" => "Наименование",
    "dnum" => "Номер договора",
    "blockStart" => "Дата начала",
    "blockEnd" => "Дата окончания",
    "comment" => "Примечание",
    "registerComment" => "Комментарий"
];

$tableWidth = [
    "contractDate" => "100px",
    "name" => "",
    "dnum" => "100px",
    "blockStart" => "100px",
    "blockEnd" => "100px",
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
        $buf = isset($currentRegister["block"][$posList[$k]["dnum"]]) ? $currentRegister["block"][$posList[$k]["dnum"]] : [];
        if(!$buf){
            $sameColor = "#66CDAA";
        }
        else{
            $sameColor = (arrayCompareRecursive($posList, $buf) == 1) ? "#FFA07A" : "";
            
        }
        
    }
    if (isset($currentRegister["block"][$posList[$k]["dnum"]][$clientId]["registerComment"])){
        $registerComment = $currentRegister["block"][$posList[$k]["dnum"]][$clientId]["registerComment"];
    }
    else{
        $registerComment = "";
    }
    $vars = [
        "reg_tableKey" => $clientId,
        "reg_tableType" => "block",
        "reg_year" => $year,
        "reg_month" => $month,
        "reg_dnum" => $posList[$k]["dnum"]
    ];
    $tr = [
        "contractDate" => $posList[$k]["contractDate"] ? date("d.m.Y",$posList[$k]["contractDate"]) : "",
        "name" => "{$posList[$k]["clientType"]} {$posList[$k]["clientName"]}",
        "dnum" => $posList[$k]["dnum"],
        "blockStart" => date("d.m.Y",$posList[$k]["blockStart"]),        
        "blockEnd" => ($posList[$k]["blockEnd"]) ? date("d.m.Y",$posList[$k]["blockEnd"]) : "бессрочно",
        "comment" => nl2br($posList[$k]["comment"]),
        "registerComment" => $registerComment        
    ];
    $count = count($posList);
    $clientKeys = [
        "contractDate",
        "name",
        "dnum",
    ];
    $posKeys = [
        "blockStart",
        "blockEnd",
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
        $info = $clientList[$clientId];
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
            "blockStart" => date("d.m.Y",$posList[$i]["blockStart"]),        
            "blockEnd" => ($posList[$i]["blockEnd"]) ? date("d.m.Y",$posList[$i]["blockEnd"]) : "бессрочно",
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
            "text" => "Приостановки",
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


























