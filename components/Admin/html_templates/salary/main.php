<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Debtor\Controller();
$debtorView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px",
    "text-align" => "center"
]; 
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

$buttonStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px",
    "width" => "auto",
    "height" => "auto",
    "min-width" => "10px"
]; 

$nameList = [
    "manager" => "Менеджер",
    "leader" => "Руководитель",
    "assistant" => "Помощник бухгалтера",
    "marketer" => "Маркетолог",
    "salary" => "Оклад",
    "prepayment" => "Аванс",
    "punishment" => "Штраф",
    "orkk" => "ОРКК",
    "orfl" => "ОРФЛ",
    "debtorCalling" => "Обзвон должников"
];

/*--------------------------------------------------*/

$getEditBlock = function(
        $id,
        $value,
        $bonusTable = false
//        $width
) use ($view,$textStyle){
    if ($bonusTable){
        $onclick = "Admin.salary.bonusTable.save(this)";
    }
    else{
        $onclick = "Admin.salary.save(this)";
    }
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.text",[
            "id" => $id,
            "class" => "salary_param",
            "style" => [
//                "max-width" => $width,
                "height" => "auto",
                "text-align" => "center"
//                "min-width" => $width,
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
                    "onclick" => $onclick,
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true);
};

/*--------------------------------------------------*/

$getTable = function(
        $tableName,
        $title,
        $width,
        $keyList,
        $headerSize = "22px",
        $percentList = []
)use($salary,$view,$tdStyle,$textStyle,$getEditBlock){
    $header = $view->get("table.tr",[
        "content" => $view->get("table.th",[
            "content" => $title,
            "style" => [
                "font-size" => $headerSize
            ] + $tdStyle,
            "attribute" => [
                "colspan" => count($keyList)
            ]
        ])
    ]);
    $body = "";
    
    $headerContent = "";
    $trContent = "";
    foreach($keyList as $key => $value){
        $headerContent .= $view->get("table.td",[
            "content" => $value,
            "style" => [
                "width" => $width,
                "font-size" => "16px"
            ] + $tdStyle
        ]);
//        if (!in_array($key, $percentList)){
//            $trContent .= $view->get("table.td",[
//                "content" => $getEditBlock("{$tableName}.{$key}",$salary[$tableName][$key]),
//                "style" => [
//                    "width" => $width
//                ] + $tdStyle
//            ]);
//        }   
//        else{
//            $trContent .= $view->get("table.td",[
//                "content" => "Сумма договоров умножается <br>на процент перевыполнения",
//                "style" => [
//                    "width" => $width
//                ] + $tdStyle
//            ]);
//        }
        $trContent .= $view->get("table.td",[
            "content" => $getEditBlock("{$tableName}.{$key}",$salary[$tableName][$key]),
            "style" => [
                "width" => $width
            ] + $tdStyle
        ]);
    }
    $body .= $view->get("table.tr",[
        "content" => $headerContent
    ]). $view->get("table.tr",[
        "content" => $trContent
    ]);
    return $view->get("table.main",[
        "tbody" => $body,
        "thead" => $header,
        "style" => [
            "width" => "fit-content"
        ]
    ]);
};

/*--------------------------------------------------*/

$getBonusTable = function(
        $role
)use($view,$tdStyle,$textStyle,$getEditBlock,$bonusList,$buttonStyle,$nameList){
    $headerContent = [
        "name" => "Наименование",
        "sum" => "Сумма",
        "action" => $view->get("buttons.normal",[
            "text" => "Добавить",
            "onclick" => "Admin.salary.bonusTable.showAddForm(this)",
            "style" => $buttonStyle
        ]).$view->get("inc.vars",[
            "vars" => [
                "role" => $role
            ]
        ])
    ];
    $tableWidth = [
        "name" => "200px",
        "sum" => "100px",
        "action" => ""
    ];
    $thead = $view->get("table.tr",[
        "content" => $view->get("table.th",[
            "content" => "{$nameList[$role]}. Бонусы",
            "attribute" => [
                "colspan" => "3"
            ]
        ])
    ]);
    $tr = "";
    foreach($headerContent as $key => $value){
        $tr .= $view->get("table.td",[
            "content" => $value,
            "style" => [
                "width" => $tableWidth[$key]
            ] + $tdStyle
        ]);
    }
    $tbody = $view->get("table.tr",[
        "content" => $tr
    ]);
    
    $buf = isset($bonusList[$role]) ? $bonusList[$role] : [];
    foreach($buf as $key => $value){
        $tr = "";
        $tdList =[
            $key,
            $getEditBlock("bonusValue",$value,true),
            $view->get("buttons.red",[
                "text" => "Удалить",
                "onclick" => "Admin.salary.bonusTable.remove(this)",
                "style" => $buttonStyle
            ]).$view->get("inc.vars",[
                "vars" => [
                    "role" => $role,
                    "bonusKey" => $key
                ]
            ]),
        ];
        foreach($tdList as $val){
            $tr .= $view->get("table.td",[
                "content" => $val,
                "style" => $tdStyle
            ]);
        }
        $tbody .= $view->get("table.tr",[
            "content" => $tr
        ]);
    }
    return $view->get("table.main",[
        "tbody" => $tbody,
        "thead" => $thead,
        "class" => "bonusTable",
        "style" => [
            "margin-top" => "20px"
        ]
    ]);
};

/*Инициализация--------------------------------------------------*/
$table = [];
$table["amount"] = $getTable("amount","Категории договоров","180px",[
    "1" => "Первая (тг)",
    "2" => "Вторая (тг)",
    "3" => "Третья (тг)",
    "4" => "Четвертая (тг)",
]);
$table["plan"] = $getTable("plan","План","180px",[
    "min" => "Минимум (тг)",
    "mid" => "Базовый (тг)",
    "max" => "Максимум (тг)",
    "percent" => "Макс.процент (%)",
    "monthPlan" => "План на месяц (тг)"
]);


$title = "Сумма по договору заключенному менеджером по работе с корпоративными клиентами<br>
ниже {$salary["amount"]["1"]} тг. при условии выполнения плана премируется следующим образом";
$width = "250px";
$keyList = [
    "min" => "Выполнение плана минимум<br>(Выплачивается менеджеру %)",
    "mid" => "Выполнение базового плана<br>(Выплачивается менеджеру %)",
    "max" => "Выполнение плана максимум<br>(Выплачивается менеджеру %)",
];
$tableName = "reward1";
$table["reward1"] = $getTable($tableName,$title,$width,$keyList,"14px");

for($i = 2; $i <= 4; $i++){
    $index = (string)$i;
    $prev = (string)($i - 1);
    $min = (int)$salary["amount"][$prev] + 1;
    $max = $salary["amount"][$index];
    $title = "Сумма по договору заключенному менеджером по работе с корпоративными клиентами<br>
    от {$min} до {$max} тг. при условии выполнения плана премируется следующим образом";
    $width = "250px";
    $keyList = [
        "min" => "Выполнение плана минимум<br>(Выплачивается менеджеру %)",
        "mid" => "Выполнение базового плана<br>(Выплачивается менеджеру %)",
        "max" => "Выполнение плана максимум<br>(Выплачивается менеджеру %)",
    ];
    $tableName = "reward{$index}";
    $table["reward{$index}"] = $getTable($tableName,$title,$width,$keyList,"14px");
}

$title = "Сумма по договору заключенному менеджером по работе с корпоративными клиентами<br>
выше {$salary["amount"]["4"]} тг. при условии выполнения плана премируется следующим образом";
$width = "250px";
$keyList = [
    "min" => "Выполнение плана минимум<br>(Выплачивается менеджеру %)",
    "mid" => "Выполнение базового плана<br>(Выплачивается менеджеру %)",
    "max" => "Выполнение плана максимум<br>(Выплачивается менеджеру %)",
];
$tableName = "reward5";
$table["reward5"] = $getTable($tableName,$title,$width,$keyList,"14px");

//
//$buf = (int)$salary["amount"]["min"] + 1;
//$title = "Сумма по договору заключенному менеджером по работе с корпоративными клиентами<br>
//от {$buf} до {$salary["amount"]["mid"]} тг. при условии выполнения плана премируется следующим образом";
//$width = "250px";
//$keyList = [
//    "min" => "Выполнение плана минимум<br>(Выплачивается менеджеру %)",
//    "mid" => "Выполнение базового плана<br>(Выплачивается менеджеру %)",
//    "max" => "Выполнение плана максимум<br>(Выплачивается менеджеру %)",
//];
//$tableName = "rewardMid";
//$table["rewardMid"] = $getTable($tableName,$title,$width,$keyList,"14px");
//
//$buf = (int)$salary["amount"]["mid"] + 1;
//$title = "Сумма по договору заключенному менеджером по работе с корпоративными клиентами<br>
//от {$buf} до {$salary["amount"]["max"]} тг. при условии выполнения плана премируется следующим образом";
//$width = "250px";
//$keyList = [
//    "min" => "Выполнение плана минимум<br>(Выплачивается менеджеру %)",
//    "mid" => "Выполнение базового плана<br>(Выплачивается менеджеру %)",
//    "max" => "Выполнение плана максимум<br>(Выплачивается менеджеру %)",
//];
//$tableName = "rewardMax";
//$table["rewardMax"] = $getTable($tableName,$title,$width,$keyList,"14px");
$bonusRoleList = [
    "assistant",
    "marketer"
];

foreach($bonusList as $role => $unused){
    $tableName = $role;
    $title = $nameList[$role];
    $keyList = [];
    foreach($salary[$role] as $key => $unused){
        $keyList[$key] = $nameList[$key];
    }
    $width = "250px";
    if (in_array($role, $bonusRoleList)){
        $bonusTable = $getBonusTable($role);
    }
    else{
        $bonusTable = "";
    }
    $table[$role] = $view->get("inc.div",[
        "type" => "column",
        "content" => $getTable($tableName,$title,$width,$keyList,"22px"). $bonusTable
    ]);
}

$content = "";
foreach($table as $key => $value){
    $content .= $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "padding" => "20px 0px",
            "border-bottom" => "1px var(--modColor_darkest) dashed"
        ],
        "content" => $value
    ]);
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $content
]);





