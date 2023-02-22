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
    "dnum" => "Договор",
    "name" => "Наименование",
    "newConnect" => "Новое подключение",
    "salarySum" => "Сумма документа",
    "attractType" => "Агитация",
    "action" => "Действия"
];

$tableWidth = [
    "dnum" => "100px",
    "name" => "Наименование",
    "newConnect" => "105px",
    "salarySum" => "200px",
    "salaryBonus" => "200px",
    "attractType" => "165px",
    "salaryComment" => "220px",
    "action" => "100px"
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "newConnect" => "center",
    "salarySum" => "center",
    "salaryBonus" => "center",
    "attractType" => "center",
    "salaryComment" => "center",
    "action" => "center"
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px",
    
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

/*--------------------------------------------------*/

$getDocParamBlock = function(
        $id,
        $doc,
//        $width
) use ($view,$textStyle,$changeList){
    $payManager = $doc["payManager"];
    $docId = $doc["docId"];
    if (isset($changeList[$docId][$id])){
        $placeHolder = $changeList[$docId][$id];
        $color = "#000080";
    }
    else{
        $placeHolder = "";
        $color = "var(--modColor_darkest)";
    }
    if ($id == "salaryComment"){
        $placeHolder = "";
        $color = "var(--modColor_darkest)";
        $content = $view->show("inc.input.area_stretch",[
            "id" => $id,
            "class" => "docParamValue inputAreaAutosize",
            "style" => [
//                "max-width" => $width,
                "height" => "auto",
                "min-width" => "150px",
                "max-width" => "150px",
                "color" => $color
//                "min-width" => $width,
            ] + $textStyle,
            "value" => $doc[$id],
        ],true);
        
    }
    
    else{
        $content = $view->show("inc.input.text",[
            "id" => $id,
            "class" => "docParamValue",
            "style" => [
//                "max-width" => $width,
                "height" => "auto",
                "text-align" => "center",
                "width" => "100px",
                "color" => $color
//                "min-width" => $width,
            ] + $textStyle,
            "value" => $doc[$id],
            "attribute" => [
                "placeholder" => $placeHolder
            ]
        ],true);
    }
    return $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center",
//            "height" => "40px",
            "justify-content" => "space-between",
            "padding-right" => "8px",
            "width" => "100%"
        ],
        "class" => "editBlock docParamBlock",
        "content" => $view->get("inc.div",[
            "type" => "column",
            "content" => $content
        ]). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->get("buttons.image",[
                "onclick" => "tableCellEditClick(this)",
                "img" => "debtor_edit",
                "style" => [
                    "margin-left" => "10px"
                ],
                "class" => "editButton",
            ]). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "auto",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "height" => "18px"
                ],
                "class" => "acceptBlock hidden",
                "content" => $view->show("buttons.close",[
                    "onclick" => "tableCellEditClose(this,false)",
                    "style" => [
                        "margin-right" => "15px",
                        "height" => "18px"
                    ]
                ],true).$view->show("buttons.accept",[
                    "onclick" => "Salary.saveDoc(this,`td`,true)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true). $view->get("inc.vars",[
            "vars" => [
                "docId" => $doc["docId"],
                "manager" => $doc["payManager"],
                "dnum" => $doc["dnum"]
            ]
        ])
    ],true);
};

/*--------------------------------------------------*/

$getSalaryParamBlock = function(
        $id
//        $width
) use ($view,$textStyle,$changeList,$payManager,$paramList,$salaryList){
    if (isset($changeList["params"][$id])){
        $placeHolder = $changeList["params"][$id];
        $color = "#000080";
    }
    else{
        $placeHolder = "";
        $color = "var(--modColor_darkest)";
    }
    return $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center",
            "height" => "40px",
            "justify-content" => "space-between",
            "padding-right" => "8px",
            "width" => "100%"
        ],
        "class" => "editBlock salaryParamBlock",
        "content" => $view->get("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => $id,
                "class" => "salaryParamValue",
                "style" => [
    //                "max-width" => $width,
                    "height" => "auto",
                    "width" => "100px",
                    "color" => $color
    //                "min-width" => $width,
                ] + $textStyle,
                "value" => isset($salaryList[$id]) ? $salaryList[$id] : "",
                "attribute" => [
                    "placeholder" => $placeHolder
                ]
            ],true)
        ]). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->get("buttons.image",[
                "onclick" => "tableCellEditClick(this)",
                "img" => "debtor_edit",
                "style" => [
                    "margin-left" => "10px"
                ],
                "class" => "editButton",
            ]). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "auto",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "height" => "18px"
                ],
                "class" => "acceptBlock hidden",
                "content" => $view->show("buttons.close",[
                    "onclick" => "tableCellEditClose(this,false)",
                    "style" => [
                        "margin-right" => "15px",
                        "height" => "18px"
                    ]
                ],true).$view->show("buttons.accept",[
                    "onclick" => "Salary.saveParams(this,`td`,true)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true). $view->get("inc.vars",[
            "vars" => [
                "manager" => $payManager
            ]
        ])
    ],true);
};

/*--------------------------------------------------*/

$getActionBlock = function()use($view){
    $buttonStyle = [
        "height" => "20px",
        "width" => "auto"
    ];
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "center"
        ],
        "content" => $view->get("buttons.blueArrow",[
            "onclick" => "Salary.showChangeManagerForm(this)",
            "style" => [
                "margin-right" => "15px"
            ] + $buttonStyle
        ]). $view->get("buttons.close",[
            "onclick" => "Salary.addForPayment(this)",
            "style" => [
                
            ] + $buttonStyle
        ])
    ]);
};

/*--------------------------------------------------*/

$getNameBlock = function(
        $doc
)use($view,$textStyle){
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "space-between"
        ],
        "content" => $view->get("inc.text",[
            "text" => "{$doc["clientType"]} \"{$doc["clientName"]}\"",
            "style" => $textStyle        
        ]). $view->get("inc.text",[
            "text" => $doc["name"],
            "style" => $textStyle        
        ])
    ]);
};

/*Инициализация--------------------------------------------------*/


$tableHeaderContent = "";
foreach($tableHeader as $key => $value){
    $tableHeaderContent .= $view->show("table.th",[
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

$tbody = "";
$even = true;
foreach($docList as $docId => $doc){
    $doc["payManager"] = $payManager;
    $dnumBlock = $doc["dnum"];
    $nameBlock = $getNameBlock($doc);
    $newConnectBlock = $doc["newConnect"] ? "Да" : "Нет";
    $salarySumBlock = $doc["salarySum"];
    $attractBlock = $doc["attractType"];
    $actionBlock = $getActionBlock(). $view->get("inc.div",[
        "type" => "row",
        "class" => "docParamList hidden",
        "content" => $view->get("inc.vars",[
            "vars" => $doc
        ])
    ]);
    
    $tr = [
        "dnum" => $dnumBlock,
        "name" => $nameBlock,
        "newConnect" => $newConnectBlock,
        "salarySum" => $salarySumBlock,
        "attractType" => $attractBlock,
        "action" => $actionBlock
    ];
    $trContent = "";
    foreach($tr as $key => $value){
        $trContent .= $view->get("table.td",[
            "content" => $value,
            "style" => $tdStyle
        ]);
    }
    $even = !$even;
    $tbody .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
}


$thead = $view->show("table.tr",[
    "content" => $tableHeaderContent
],true);

$table = $view->get("table.main",[
    "thead" => $thead,
    "tbody" => $tbody,
]);


/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "width" => "100%",
        "margin-right" => "10px"
    ],
    "content" => $table
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/







