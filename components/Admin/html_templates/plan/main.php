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

$tableHeader = [
    "month" => "Ìåñÿö",
    "salary" => "Îêëàä",
    "min" => "Ìèíèìóì",
    "mid" => "Áàçîâûé",
    "max" => "Ìàêñèìóì"
];

$tableWidth = [
    "month" => "Ìåñÿö",
    "salary" => "Îêëàä",
    "min" => "Ìèíèìóì",
    "mid" => "Áàçîâûé",
    "max" => "Ìàêñèìóì"
];

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
    "manager" => "Ìåíåäæåğ",
    "leader" => "Ğóêîâîäèòåëü",
    "assistant" => "Ïîìîùíèê áóõãàëòåğà",
    "marketer" => "Ìàğêåòîëîã",
    "salary" => "Îêëàä",
    "prepayment" => "Àâàíñ",
    "punishment" => "Øòğàô",
    "orkk" => "ÎĞÊÊ",
    "orfl" => "ÎĞÔË",
    "debtorCalling" => "Îáçâîí äîëæíèêîâ"
];

/*--------------------------------------------------*/

$getEditBlock = function(
        $id,
        $value,
) use ($view,$textStyle){
    $onclick = "Admin.plan.save(this)";
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.text",[
            "id" => $id,
            "class" => "plan_param",
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
                "text" => "Ğåäàêòèğîâàòü",
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

/*Èíèöèàëèçàöèÿ--------------------------------------------------*/

$years = getYearMonthList()["years"];
$months = getYearMonthList()["months"];

$header = $view->get("inc.div",[
    "type" => "row",
    "class" => "yearSelectBlock",
    "style" => [
        "margin" => "0px 0px 15px 10px"
    ],
    "content" => $view->get("inc.text",[
        "text" => "Ãîä:",
        "style" => [
            "margin-right" => "10px",
            "font-size" => "20px"
        ]
    ]). $view->get("inc.input.select",[
        "id" => "plan_year",
        "values" => $years,
        "value" => $year,
        "class" => " noSwapDisable"
    ]). $view->get("buttons.normal",[
        "text" => "Îáíîâèòü",
        "onclick" => "Admin.plan.update(this)",
        "style" => [
            "height" => "25px",
            "min-width" => "10px",
            "margin-left" => "15px"
        ]
    ])
]);


$tableHeaderContent = "";
foreach($tableHeader as $key => $value){
    $tableHeaderContent .= $view->show("table.th",[
        "content" => $value,
        "style" => [
            "width" => $tableWidth[$key]
        ]
    ],true);
}

$detailButton = $view->get("buttons.normal",[
    
]);

$body = "";



foreach($plan as $month => $params){
    $vars = $view->get("inc.vars",[
        "vars" => [
            "plan_month" => $month,
            "plan_year" => $year
        ]
    ]);
    $tr = [
        "month" => $months[$month],
        "salary" => $getEditBlock("salary",$params["salary"]),
        "min" => $getEditBlock("min",$params["min"]),
        "mid" => $getEditBlock("mid",$params["mid"]),
        "max" => $getEditBlock("max",$params["max"])
    ];
    $trContent = "";
    foreach($tr as $key => $value){
        $trContent .= $view->get("table.td",[
            "content" => $value.$vars,
            "style" => $tdStyle
        ]);
    }
    
    $body .= $view->get("table.tr",[
        "content" => $trContent
    ]);
}

$thead = $view->show("table.tr",[
    "content" => $tableHeaderContent
],true);

$table = $view->get("table.main",[
    "thead" => $thead,
    "tbody" => $body,
    "class" => "supportTable"
]);

/*Îòîáğàæåíèå--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $table
]);



