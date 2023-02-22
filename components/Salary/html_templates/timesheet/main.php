<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$buf = new \Salary\Controller();
$salaryView = $buf->getView();

unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableHeaderFirst = [
    "name" => "Ф.И.О",
    "role" => "Должность",
];

$tableHeaderLast = [
    "hours" => "Часы",
    "salary" => "Оклад",
    "bonus" => "Бонус",
    "reward" => "Премия",
    "punishment" => "Штраф",
    "prepayment" => "Аванс",
    "total" => "Итого",
//    "comment" => "Примечание"
];

$tableWidth = [
    "name" => "Ф.И.О",
    "role" => "Должность",
    "hours" => "Часы",
    "salary" => "Оклад",
    "bonus" => "Бонус",
    "reward" => "Премия",
    "punishment" => "Штраф",
    "prepayment" => "Аванс",
    "total" => "Итого",
    "comment" => "Примечание"
];
$tableVertical = [
    
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

$dayColors = [
    "w" => "",
    "h" => "#FFFF00"
];

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

$getHeaderBlock = function(
        $value,
        $width = ""
)use($view,$tdStyle,$csvData){
    return $view->get("table.th",[
        "content" => $value. $csvData($value),
        "style" => [
            "width" => $width,
            "border" => "none"
        ] + $tdStyle
    ]);
};

/*--------------------------------------------------*/

$getFirstRowBlock = function(
        $value,
        $day = "",
        $type = "w",
        $button = false
)use($view,$tdStyle,$year,$month,$csvData){
    if ($button){
        $style = [
            "cursor" => "pointer"
        ];
        $attribute = [
            "onclick" => "Timesheet.changeCalendarDay(this)"
        ];
        $vars = [
            "day" => $day,
            "month" => $month,
            "year" => $year,
            "type" => $type
        ];
        $class = "hoverableCell";
    }
    else{
        $style = [];
        $attribute = [];
        $vars = [];
        $class = "";
    }
    return $view->get("table.td",[
        "content" => $value. $view->get("inc.vars",[
            "vars" => $vars
        ]). $csvData($value),
        "style" => [
            "background-color" => ($type == "h") ? "#FFFF00" : ""
        ] + $style + $tdStyle,
        "attribute" => [
            "id" => "day_{$day}"
        ] + $attribute,
        "class" => $class
    ]);
};

/*--------------------------------------------------*/

$getProfileRowBlock = function(
        $value,
        $profile,
        $day = "",
        $type = "w",
)use($view,$tdStyle,$year,$month,$csvData){
    $vars = [
        "day" => $day,
        "month" => $month,
        "year" => $year,
        "type" => $type,
        "profile" => $profile
    ];
    $valueBlock = $view->get("inc.div",[
        "type" => "row",
        "content" => $value. $csvData($value),
        "class" => "valueBlock"
    ]);
    return $view->get("table.td",[
        "content" => $valueBlock. $view->get("inc.vars",[
            "vars" => $vars
        ]),
        "style" => [
            "background-color" => ($type == "h") ? "#FFFF00" : "",
            "cursor" => "pointer"
        ] + $tdStyle,
        "attribute" => [
            "id" => "day_{$day}",
            "onclick" => "Timesheet.showProfileDayForm(this)"        
        ],
        "class" => "hoverableCell"            
    ]);
};

/*--------------------------------------------------*/

$getWeekDayBlock = function(
        $number
)use($calendar,$view,$textStyle,$dayColors,$csvData){
    $name = $calendar[$number]["name"]["short"];
    $type = $calendar[$number]["type"];
    return $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => $name,
            "style" => $textStyle
        ]),
        "style" => [
            "width" => "100%",
            "height" => "100%",
            "justify-content" => "center",
            "align-items" => "center",
            "background-color" => $dayColors[$type]
        ]
    ]). $csvData($name);
};

/*Инициализация--------------------------------------------------*/
$buf = getYearMonthList();
$years = $buf["years"];
$months = $buf["months"];
$dateBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-bottom" => "10px"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Год:",
        "style" => [
            "margin-right" => "4px"
        ]
    ],true). $view->show("inc.input.select",[
        "id" => "updateYear",
        "values" => $years,
        "value" => $year,
        "style" => [
            "margin-right" => "8px"
        ]
    ],true). $view->show("inc.text",[
        "text" => "Месяц:",
        "style" => [
            "margin-right" => "4px"
        ]
    ],true). $view->show("inc.input.select",[
        "id" => "updateMonth",
        "values" => $months,
        "value" => $month,
        "style" => [
            "margin-right" => "8px"
        ]
    ],true)
],true);

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "class" => "updateBlock",
    "content" => $dateBlock. $view->show("buttons.normal",[
        "text" => "Обновить",
        "onclick" => "Timesheet.reload(this)",
        "style" => [
            "height" => "25px",
            "font-size" => "14px",
            "width" => "auto",
            "min-width" => "auto",
            "margin-left" => "15px"
        ]
    ],true). $view->show("buttons.normal",[
        "text" => "Скачать",
        "onclick" => "CsvTable.download(this.closest(`.page`))",
        "style" => [
            "height" => "25px",
            "font-size" => "14px",
            "width" => "auto",
            "min-width" => "auto",
            "margin-left" => "15px"
        ]
    ],true),
    "style" => [
        "margin" => "10px 0px"
    ]
],true);


$tableHeaderContent = "";
$firstRowContent = "";
foreach($tableHeaderFirst as $key => $value){
    $tableHeaderContent .= $getHeaderBlock($value,$tableWidth[$key]);
    $firstRowContent .= $getFirstRowBlock("");
}
foreach($calendar as $day => $params){
    $tableHeaderContent .= $getHeaderBlock($day);
    $firstRowContent .= $getFirstRowBlock($params["name"]["short"],$day,$params["type"],true);
}
foreach($tableHeaderLast as $key => $value){
    $tableHeaderContent .= $getHeaderBlock($value,$tableWidth[$key]);
    $firstRowContent .= $getFirstRowBlock("");
}





$body = $view->get("table.tr",[
    "content" => $firstRowContent
]);

foreach($profileList as $profile => $value){
    $params = $value["params"];
    $sheet = $value["sheet"];
    $trContent = "";
    foreach($tableHeaderFirst as $key => $unused){
        $trContent .= $view->get("table.td",[
            "content" => $params[$key]. $csvData($params[$key]),
            "style" => $tdStyle
        ]);
    }
    foreach($sheet as $day => $value){
        $type = $calendar[$day]["type"];
        $trContent .= $getProfileRowBlock($value,$profile,$day,$type);
    }
    foreach($tableHeaderLast as $key => $unused){
        $trContent .= $view->get("table.td",[
            "content" => $params[$key]. $csvData($params[$key]),
            "style" => $tdStyle
        ]);
    }
    $body .= $view->get("table.tr",[
        "content" => $trContent
    ]);
}

$thead = $view->get("table.tr",[
    "content" => $tableHeaderContent
]);

$table = $view->get("table.main",[
    "tbody" => $body,
    "thead" => $thead,
    "style" => [
        "width" => "auto"
    ],
    "attribute" => [
        "data_csvname" => "Табель"
    ],
    "class" => "csvTable"
]);

$workHours = $workDays * 8;
$tableData = [
    [
        "Смены". $csvData("Смены"),
        $workDays. $csvData($workDays)
    ],
    [
        "Часы". $csvData("Часы"),
        $workHours. $csvData($workHours)
    ]
];
$footerTbody = "";
foreach($tableData as $row){
    $tr = "";
    foreach($row as $cell){
        $tr .= $view->get("table.td",[
            "content" => $cell,
            "style" => $tdStyle
        ]);
    }
    $footerTbody .= $view->get("table.tr",[
        "content" => $tr
    ]);
}
$footer = $view->get("inc.text",[
    "text" => "Норма:",
    "style" => [
        "margin-top" => "40px"
    ]        
]). $view->get("table.main",[
    "tbody" => $footerTbody,
    "style" => [
        "width" => "fit-content"
    ],
    "attribute" => [
        "data_csvname" => "Норма"
    ],
    "class" => "csvTable" 
]);

/*Отображение--------------------------------------------------*/


echo $view->get("inc.div",[
    "type" => "column",
    "content" => $buttonBlock. $table. $footer,
    "style" => [
        "width" => "100%",
        "overflow-x" => "auto",
        "padding" => "0px 15px"
    ]
]). $view->get("inc.vars",[
    "vars" => [
        "tabTitle" => "Табель"
    ]    
]);








