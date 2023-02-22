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
    "name" => "Наименование",
    "bonus" => "Ставка",
    "percent" => "Процент плана",
    "sum" => "Бонус"
];

$tableWidth = [
    "name" => "200px",
    "bonus" => "180px",
    "percent" => "180px",
    "sum" => "100px"
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "newConnect" => "Новое подключение",
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
if (isset($paramList["closed"]) && ($paramList["closed"])){
    $registerClosed = true;
}
else{
    $registerClosed = false;
}

/*--------------------------------------------------*/

$csvData = function(
        $data
)use($view){
    return $view->get("inc.vars",[
        "vars" => [
            "csvData" => $data
        ]
    ]);
};

/*--------------------------------------------------*/

$getSalaryParamBlock = function(
        $id
//        $width
) use ($view,$textStyle,$changeList,$manager,$paramList,$salaryList,$registerClosed,$csvData){
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
                "manager" => $manager
            ]
        ])
    ],true). $csvData(isset($salaryList[$id]) ? $salaryList[$id] : "");
};

/*--------------------------------------------------*/

$getFooterTable = function()use(
        $view,
        $tdStyle,
        $bonusSum,
        $getSalaryParamBlock,
        $sum,
        $manager,
        $csvData
){
    
    $keyList = [
        "salary" => "Оклад",
        "punishment" => "Штраф",
        "reward" => "Премия",
        "prepayment" => "Аванс"
    ];
    $vars = [
        "manager" => $manager,
        "bonusSum" => $bonusSum
    ];
    $tbody = $view->get("table.tr",[
        "content" => $view->get("table.td",[
            "content" => "Итого бонус". $csvData("Итого бонус"),
            "style" => [
                "width" => "350px"
            ] + $tdStyle
        ]). $view->get("table.td",[
            "content" => $bonusSum. $view->get("inc.vars",[
                "vars" => $vars
            ]). $csvData($bonusSum),
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
                $csv = $csvData($val);
            }
            else{
                $csv = "";
            }
            $trContent .= $view->get("table.td",[
                "content" => $val. $csv,
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
            "content" => "Сумма". $csvData("Сумма"),
            "style" => [
                "width" => "350px"
            ] + $tdStyle
        ]). $view->get("table.td",[
            "content" => $sum. $csvData($sum),
            "style" => [
                "width" => "170px",
                "padding" => "10px 10px"
            ] + $tdStyle
        ])
    ]);
    return $tbody;
};

/*Инициализация--------------------------------------------------*/

$header = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("inc.text",[
        "text" => "ЗП: {$sum}",
        "style" => [
            "margin" => "0px 25px 8px 0px",
            "font-size" => "20px"
        ] + $textStyle
    ])
]);

$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->get("table.th",[
        "content" => $value. $csvData($value),
        "style" => [
            "width" => $tableWidth[$key]
        ]
    ]);
}

$thead = $view->get("table.tr",[
    "content" => $headerContent
]);

$keyList = [
    "orkk" => "ОРКК",
    "orfl" => "ОРФЛ"
];
$tbody = "";
foreach($keyList as $key => $value){
    $tr = [
        "name" => $value. $csvData($value),
        "bonus" => $getSalaryParamBlock($key),
        "percent" => $getSalaryParamBlock("{$key}Percent"),
        "sum" => $salaryList["{$key}Sum"]. $csvData($salaryList["{$key}Sum"])        
    ];
    $trContent = "";
    foreach($tr as $k => $val){
        $trContent .= $view->get("table.td",[
            "content" => $val,
            "style" => [] + $tdStyle
        ]);
    }
    $tbody .= $view->get("table.tr",[
        "content" => $trContent
    ]);
}

$table = $view->get("table.main",[
    "tbody" => $tbody,
    "thead" => $thead,
    "style" => [
        "width" => "fit-content"
    ],
    "attribute" => [
        "data_csvname" => "Бонус руководителя"
    ],
    "class" => "csvTable"
]);

$tbody = $getFooterTable();

$footer = $view->get("table.main",[
    "tbody" => $tbody,
    "style" => [
        "margin-top" => "30px",
        "width" => "fit-content"
    ],
    "attribute" => [
        "data_csvname" => "Итого"
    ],
    "class" => "csvTable"
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
    "content" => $header. $table. $footer
]);


