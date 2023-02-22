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
    "balance" => "Баланс",
    "tarif" => "Тариф",
    "contacts" => "Телефон",
    "comment" => "Комментарий",
];

$tableWidth = [
    "dnum" => "65px",
    "name" => "200px",
    "balance" => "100px",
    "tarif" => "150px",
    "contacts" => "100px",
    "comment" => "300px",
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "5px 7px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
];



/*Инициализация--------------------------------------------------*/
$actionBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        
    ],
    "content" => $view->show("buttons.normal",[
        "text" => "Редактировать",
        "onclick" => "supportRowEdit(this)",
        "class" => "editButton",
        "style" => [
            
        ] + $textStyle
    ],true). $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "center",
            "align-items" => "center",
            "height" => "100%"
        ],
        "class" => "acceptBlock hidden",
        "content" => $view->show("buttons.close",[
            "onclick" => "reloadMonthSupportTable(this)",
            "style" => [
                "margin-right" => "25px"
            ]
        ],true).$view->show("buttons.accept",[
            "onclick" => "saveAccountSupport(this,`tr`)",
        ],true)
    ],true)
],true);

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

foreach($accountList as $dnum => $supportList){
    $trColor++;
    $info = $clientList[$dnum];
    
    $rate = isset($info["support"][$supportList[0]["inc_time"]]) ? $info["support"][$supportList[0]["inc_time"]]["rate"] : "";
    $comment = isset($info["support"][$supportList[0]["inc_time"]]) ? $info["support"][$supportList[0]["inc_time"]]["comment"] : "";
    $callDate = isset($info["support"][$supportList[0]["inc_time"]]["callDate"]) ? $info["support"][$supportList[0]["inc_time"]]["callDate"] : "";
    $vars = [
        "tr_dnum" => $dnum,
        "tr_support" => $supportList[0]["inc_time"]
    ];
    $incTime = $supportList[0]["inc_time"] ? date("d.m.y H:s:i",$supportList[0]["inc_time"]) : "";
    $endTime = $supportList[0]["end_time"] ? date("d.m.y H:s:i",$supportList[0]["end_time"]) : "";
    $dateBlock = $view->show("inc.text",[
        "text" => "{$incTime}<br>{$endTime}",
        "style" => $textStyle        
    ],true);
    $contactList = "";    
    foreach($info["contactList"] as $value){
        $phone = getPhoneTemplate($value["phone"]);
        $name = $view->show("inc.text",[
            "text" => $value["name"],
            "style" => [
                "margin-right" => "10px"
            ]
        ],true);
        $role = $value["role"];
        $contactList .= $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "border-bottom" => "1px var(--modColor_darkest) dashed",
                "padding-bottom" => "3px",
                "margin-top" => "3px"
            ],
            "content" => $view->show("inc.div",[
                "type" => "row",
                "class" => "phoneContainer",
                "style" => [
                    "align-items" => "center"
                ],
                "content" => $view->show("buttons.phone",[
                    "onclick" => "orkkDoCall(this,`.phoneContainer`)",
                    "style" => [
                        "height" => "15px",
                        "width" => "auto"
                    ]
                ],true). $view->show("inc.text",[
                    "text" => $phone,
                    "style" => $textStyle
                ],true). $view->show("inc.var",[
                    "key" => "phoneNumber",
                    "value" => $value["phone"]
                ],true)
            ],true). $view->show("inc.div",[
                "type" => "row",
                "content" => $view->show("inc.text",[
                    "text" => $value["name"],
                    "style" => [
                        "margin-right" => "3px"
                    ] + $textStyle
                ],true). $view->show("inc.text",[
                    "text" => $value["role"],
                    "style" => [
                        "margin-right" => "3px"
                    ] + $textStyle
                ],true)
            ],true)
        ],true);
    }
    $nameBlock = $view->show("inc.div",[
        "type" => "column",
        "class" => "hiddenTextContainer",
        "content" => $view->show("inc.div",[
            "type" => "row",
            "content" => $view->show("inc.text",[
                "text" => isset($info["clientType"]) ? $info["clientType"] : "",
                "style" => [
                    "margin-right" => "3px"
                ] + $textStyle
            ],true). $view->show("inc.text",[
                "text" => isset($info["name"]) ? $info["name"] : "",
                "style" => [
                    "margin-right" => "3px"
                ] + $textStyle
            ],true)
        ],true). $view->show("inc.text",[
            "text" => "Контакты",
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
        ],true). $view->show("inc.div",[
            "type" => "column",
            "id" => "hiddenText",
            "class" => "hidden",
            "content" => $contactList
        ],true)
    ],true);
    $tr = [
        "dnum" => $dnum,
        "name" => $nameBlock,
        "manager" => isset($info["manager"]) ? profileGetUsername($info["manager"]) : "",
        "dateBlock" => $dateBlock,
        "executed" => $supportList[0]["executed"] ? profileGetUsername($supportList[0]["executed"]) : "",
        "text" => $supportList[0]["text"],
        "resolution" => $supportList[0]["resolution"],
        "rate" => $view->show("inc.input.select",[
            "id" => "rate",
            "values" => [
                "0" => "Нет",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4",
                "5" => "5"
            ],
            "value" => $rate
        ],true),
        "comment" => $view->show("inc.input.area_stretch",[
            "id" => "comment",
            "class" => "inputAreaAutosize",
            "style" => [
                "max-width" => "300px",
                "min-width" => "300px",
                "height" => "auto"
            ] + $textStyle,
            "value" => $comment
        ],true),
        "callDate" => $view->show("inc.input.dateTime",[
            "id" => "callDate",
            "value" => $callDate,
            "style" => [
                "background-color" => $supportList[0]["today"] ? "#FFA07A" : ""
            ]
        ],true), 
        "action" => $actionBlock. $view->show("inc.vars",[
            "vars" => $vars
        ],true)
    ];
    $count = count($supportList);
    $clientKeys = [
        "dnum",
        "name",
        "manager"
    ];
    $posKeys = [
        "dateBlock",
        "executed",
        "text",
        "resolution",
        "rate",
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
        "class" => ($trColor % 2 != 0) ? "odd" : "even"
    ],true);    
    for($i = 1; $i < $count; $i++){
        $info = $clientList[$dnum];
        $rate = isset($info["support"][$supportList[$i]["inc_time"]]) ? $info["support"][$supportList[$i]["inc_time"]]["rate"] : "";
        $comment = isset($info["support"][$supportList[$i]["inc_time"]]) ? $info["support"][$supportList[$i]["inc_time"]]["comment"] : "";
        $callDate = isset($info["support"][$supportList[$i]["inc_time"]]["callDate"]) ? $info["support"][$supportList[$i]["inc_time"]]["callDate"] : "";
        $vars = [
            "tr_dnum" => $dnum,
            "tr_support" => $supportList[$i]["inc_time"]
        ];
        $incTime = $supportList[$i]["inc_time"] ? date("d.m.y H:s:i",$supportList[$i]["inc_time"]) : "";
        $endTime = $supportList[$i]["end_time"] ? date("d.m.y H:s:i",$supportList[$i]["end_time"]) : "";
        $dateBlock = $view->show("inc.text",[
            "text" => "{$incTime}<br>{$endTime}",
            "style" => $textStyle        
        ],true);
        $tr = [
            "dateBlock" => $dateBlock,
            "executed" => $supportList[$i]["executed"] ? profileGetUsername($supportList[$i]["executed"]) : "",
            "text" => $supportList[$i]["text"],
            "resolution" => $supportList[$i]["resolution"],
            "rate" => $view->show("inc.input.select",[
                "id" => "rate",
                "values" => [
                    "0" => "Нет",
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5"
                ],
                "value" => $rate
            ],true),
            "comment" => $view->show("inc.input.area_stretch",[
                "id" => "comment",
                "class" => "inputAreaAutosize",
                "style" => [
                    "max-width" => "300px",
                    "height" => "auto",
                    "min-width" => "300px",
                ] + $textStyle,
                "value" => $comment
            ],true),
            "callDate" => $view->show("inc.input.dateTime",[
                "id" => "callDate",
                "value" => $callDate,
                "style" => [
                    "background-color" => $supportList[$i]["today"] ? "#FFA07A" : ""
                ]
            ],true),
            "action" => $actionBlock. $view->show("inc.vars",[
                "vars" => $vars
            ],true)
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
            "class" => ($trColor % 2 != 0) ? "odd" : "even"
        ],true);
    }
}

$thead = $view->show("table.tr",[
    "content" => $headerContent
],true);




/*Отображение--------------------------------------------------*/


$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $body,
    "class" => "supportTable"
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/


























