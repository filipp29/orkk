<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Salary\Controller();
$salView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableHeader = [
    "dnum" => "Договор",
    "name" => "Наименование",
    "newConnect" => "Новое подключение",
    "salarySum" => "Сумма документа",
    "salaryBonus" => "Бонус документа",
    "attractType" => "Агитация",
    "salaryComment" => "Примечание",
    "action" => "Действия"
];

$tableWidth = [
    "dnum" => "100px",
    "name" => "Наименование",
    "newConnect" => "105px",
    "salarySum" => "200px",
    "salaryBonus" => "200px",
    "attractType" => "230px",
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
    "padding" => "2px 5px",
    "height" => "auto"
];
if (isset($paramList["closed"]) && ($paramList["closed"])){
    $registerClosed = true;
}
else{
    $registerClosed = false;
}



/*--------------------------------------------------*/

$getDocParamBlock = function(
        $id,
        $doc,
//        $width
) use ($view,$textStyle,$changeList,$registerClosed){
    if ($registerClosed){
        return $doc[$id];
    }
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
                "dnum" => $doc["dnum"],
                "csvData" => $doc[$id]
            ]
        ])
    ],true);
};

/*--------------------------------------------------*/

$getSalaryParamBlock = function(
        $id
//        $width
) use ($view,$textStyle,$changeList,$payManager,$paramList,$salaryList,$registerClosed){
    if ($registerClosed){
        return $salaryList[$id];
    }
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
    ],true). $view->get("inc.vars",[
        "vars" => [
            "csvData" => isset($salaryList[$id]) ? $salaryList[$id] : ""
        ]
    ]);
};

/*--------------------------------------------------*/

$getFooterTable = function()use(
        $view,
        $tdStyle,
        $bonusSum,
        $getSalaryParamBlock,
        $sum,
        $payManager
){
    $keyList = [
        "salary" => "Оклад",
        "punishment" => "Штраф",
        "reward" => "Премия",
        "prepayment" => "Аванс"
    ];
    $vars = [
        "manager" => $payManager,
        "bonusSum" => $bonusSum
    ];
    $tbody = $view->get("table.tr",[
        "content" => $view->get("table.td",[
            "content" => "Итого бонус". $view->get("inc.vars",[
                "vars" => [
                    "csvData" => "Итого бонус"
                ]
            ]),
            "style" => [
                "width" => "350px"
            ] + $tdStyle
        ]). $view->get("table.td",[
            "content" => $bonusSum. $view->get("inc.vars",[
                "vars" => $vars
            ]). $view->get("inc.vars",[
                "vars" => [
                    "csvData" => $bonusSum
                ]
            ]),
            "class" => "bonusSumBlock",
            "style" => [
                "width" => "170px",
                "padding" => "10px 10px"
            ] + $tdStyle
        ])
    ]);

    foreach($keyList as $key => $value){
        $tr = [
            "name" => $value,
            "value" => $getSalaryParamBlock($key)
        ];
        $width = [
            "name" => "350px",
            "value" => "170px"
        ];
        $trContent = "";
        foreach($tr as $k => $val){
            if ($k == "name"){
                $csvData = $view->get("inc.vars",[
                    "vars" => [
                        "csvData" => $val
                    ]
                ]);
            }
            else{
                $csvData = "";
            }
            $trContent .= $view->get("table.td",[
                "content" => $val. $csvData,
                "style" => [
                    "width" => $width[$k]
                ] + $tdStyle
            ]);
        }
        $tbody .= $view->get("table.tr",[
            "content" => $trContent
        ]);
    }

    $tbody .= $view->get("table.tr",[
        "content" => $view->get("table.td",[
            "content" => "Сумма". $view->get("inc.vars",[
                "vars" => [
                    "csvData" => "Сумма"
                ]
            ]),
            "style" => [
                "width" => "350px"
            ] + $tdStyle
        ]). $view->get("table.td",[
            "content" => $sum. $view->get("inc.vars",[
                "vars" => [
                    "csvData" => $sum
                ]
            ]),
            "style" => [
                "width" => "170px",
                "padding" => "10px 10px"
            ] + $tdStyle
        ])
    ]);
    return $tbody;
};

/*--------------------------------------------------*/

$getActionBlock = function()use($view,$registerClosed){
    $buttonStyle = [
        "height" => "20px",
        "width" => "auto"
    ];
    if ($registerClosed){
        return "";
    }
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
        ])
    ]). $view->get("inc.vars",[
        "vars" => [
            "csvData" => ""
        ]
    ]);
};

/*--------------------------------------------------*/

$getNameBlock = function(
        $doc
)use($view,$textStyle){
    $text = "{$doc["clientType"]} \"{$doc["clientName"]}\"";
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "space-between"
        ],
        "content" => $view->get("inc.text",[
            "text" => $text,
            "style" => $textStyle        
        ]). $view->get("inc.text",[
            "text" => $doc["name"],
            "style" => $textStyle        
        ])
    ]). $view->get("inc.vars",[
        "vars" => [
            "csvData" => $text
        ]
    ]);
};

/*--------------------------------------------------*/

$getAttractBlock = function(
        $attract
)use($view,$textStyle,$registerClosed){
    if ($registerClosed || !$attract){
        return $attract;
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
        "class" => "editBlock attractBlock",
        "content" => $view->get("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "id" => "attractValue",
                "class" => "salaryParamValue",
                "style" => [
    //                "max-width" => $width,
                    "height" => "auto",
                    "width" => "130px",
    //                "min-width" => $width,
                ] + $textStyle,
                "values" => \Settings\Main::attractType(),
                "value" => $attract,
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
                    "onclick" => "Salary.changeAttract(this)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true). $view->get("inc.vars",[
        "vars" => [
            "csvData" => $attract
        ]
    ]);
};

/*--------------------------------------------------*/

$getDnumBlock = function(
        $dnum
)use($balanceTable,$view,$textStyle){
    $balance = isset($balanceTable[$dnum]) ? ((string)(int)$balanceTable[$dnum])." тг" : "нет данных";
    $text = "{$dnum} ({$balance})";
    return $view->get("inc.text",[
        "text" => "{$dnum}<br>({$balance})",
        "style" => $textStyle
    ]). $view->get("inc.vars",[
        "vars" => [
            "csvData" => $text
        ]
    ]);
};

/*--------------------------------------------------*/

$getNewConnectBlock = function(
        $newConnect
)use($view,$textStyle){
    $text = $newConnect ? "Да" : "Нет";
    return $view->get("inc.text",[
        "text" => $text,
        "style" => $textStyle
    ]). $view->get("inc.vars",[
        "vars" => [
            "csvData" => $text
        ]
    ]);
};

/*Инициализация--------------------------------------------------*/

$planBlock = $salView->get("salaryTable.progressbar",[
    "accept" => $progress["accept"],
    "text" => $progress["text"],
    "bar" => $progress["bar"]
]);

$header = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("inc.text",[
        "text" => "ЗП: {$sum}",
        "style" => [
            "margin" => "0px 25px 8px 0px",
            "font-size" => "20px",
            "height" => ""
        ] + $textStyle
    ]). $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => "План: {$currentPlan}тг",
            "style" => [
                "font-size" => "20px",
                "height" => ""
            ] + $textStyle        
        ]). $view->get("inc.text",[
            "text" => "{$percentList["percent"]}%",
            "style" => [
                "color" => $percentList["color"],
                "margin-left" => "6px",
                "font-size" => "20px",
                "height" => ""
            ] + $textStyle        
        ])
//                    . $view->get("inc.text",[
//            "text" => "Кэф: {$currentRate}",
//            "style" => [
//                "font-size" => "20px",
//                "height" => ""
//            ] + $textStyle        
//        ])
    ]). $planBlock
]);

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
        ],true). $view->get("inc.vars",[
            "vars" => [
                "csvData" => $value
            ]
        ]),
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
    $dnumBlock = $getDnumBlock($doc["dnum"]);
    $nameBlock = $getNameBlock($doc);
    $newConnectBlock = $getNewConnectBlock($doc["newConnect"]);
    $salarySumBlock = $getDocParamBlock("salarySum",$doc);
    $salaryBonusBlock = $getDocParamBlock("salaryBonus",$doc);
    $attractBlock = $getAttractBlock($doc["attractType"]);
    $salaryCommentBlock = $getDocParamBlock("salaryComment",$doc);
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
        "salaryBonus" => $salaryBonusBlock,
        "attractType" => $attractBlock,
        "salaryComment" => $salaryCommentBlock,
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
    "class" => "csvTable",
    "attribute" => [
        "data_csvname" => "Список договоров"
    ]
]);

$tbody = $getFooterTable();

$planBlock = $view->get("inc.div",[
    "type" => "row",
    "class" => "hidden salaryParamBlock",
    "content" => $view->get("inc.vars",[
        "vars" => [
            "manager" => $payManager,
        ]
    ]). $view->get("inc.input.text",[
        "id" => "plan",
        "value" => $currentPlan,
        "class" => "salaryParamValue"
    ])
]);
$footer = $view->get("table.main",[
    "tbody" => $tbody,
    "class" => "csvTable",
    "style" => [
        "width" => "fit-content",
        "margin-top" => "30px"
    ],
    "attribute" => [
        "data_csvname" => "Итого"
    ]
]). $view->get("buttons.normal",[
    "text" => "Скачать",
    "onclick" => "CsvTable.download(this.closest(`.tableContainer`))",
    "style" => [
        "margin-top" => "20px"
    ]
]);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "width" => "100%",
        "margin-right" => "10px"
    ],
    "content" => $header. $table. $footer. $planBlock
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/







