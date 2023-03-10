<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$buf = new \Register\Controller();
$regView = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableLabelStyle = [
    "font-size" => "20px",
    "font-wight" => "600",
    "margin" => "20px 0px 10px 30px"
];
$tableHeader = [
    "contractDate" => "???? ????????",
    "activateDate" => "???? ?????????",
    "name" => "????????????",
    "dnum" => "????? ????????",
    "amount" => "??????????? ?????",
    "speed" => "????????",
    "yearSum" => "????? ?? ???",
    "comment" => "??????????",
];

$tableWidth = [
    "contractDate" => "???? ????????",
    "activateDate" => "???? ?????????",
    "name" => "????????????",
    "dnum" => "????? ????????",
    "amount" => "??????????? ?????",
    "speed" => "????????",
    "yearSum" => "????? ?? ???",
    "comment" => "??????????",
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

$compareType = [
    "connectReport" => [
        "period_type" => "?????????",
        "city_type" => "?????????",
        "manager_type" => "?????????"
    ],
    "clientReport" => [
        "period_type" => "?????????",
        "city_type" => "?????",
        "manager_type" => "?????"
    ],
    "amountReport" => [
        "period_type" => "?????????",
        "city_type" => "?????",
        "manager_type" => "?????"
    ],
    "connectSumReport" => [
        "period_type" => "?????????",
        "city_type" => "?????",
        "manager_type" => "?????"
    ],
    "guReport" => [
        "period_type" => "?????????",
        "city_type" => "?????",
        "manager_type" => "?????"
    ],
    "districtReport" => [
        "period_type" => "?????????",
        "city_type" => "?????",
        "manager_type" => "?????"
    ],
][$reportType];

/*--------------------------------------------------*/

$getParamsFrame = function(
        $content,
        $label
)use($view,$textStyle){
    return $view->get("inc.div",[
        "type" => "row",
        "class" => "paramsFrame",
        "style" => [
            "position" => "relative",
            "padding" => "20px 10px 10px 10px",
            "border" => "1px solid var(--modColor_darkest)",
            "margin" => "0px 10px",
            "height" => "fit-content"
        ],
        "content" => $content. $view->get("inc.text",[
            "text" => $label,
            "style" => [
                "background-color" => "var(--modBGColor)",
                "padding" => "5px",
                "position" => "absolute",
                "top" => "-10px",
                "left" => "20px"
            ] 
        ])
    ]);
};

/*--------------------------------------------------*/

$getPeriodBlock = function(
        $id,
        $disable = false
)use($view,$textStyle){
    if ($disable){
        $attribute = [
            "readonly" => "readonly"
        ];
    }
    else{
        $attribute = [];
    }
    return $view->get("inc.div",[
        "type" => "row",
        "class" => $disable ? "hidden forHide" : "",
        "style" => [
            "margin-top" => "15px"
        ],
        "content" => $view->get("inc.input.date",[
            "id" => "{$id}_start",
            "style" => [
                "margin-right" => "8px",
                "width" => "115px"
            ]        
        ]). "-". $view->get("inc.input.date",[
            "id" => "{$id}_end",
            "style" => [
                "margin-left" => "8px",
                "width" => "115px"
            ] 
        ])
    ]);
};

/*--------------------------------------------------*/

$getRadioBlock = function(
        $id
)use($view,$compareType){
    return $view->get("inc.input.radio",[
        "divType" => "row",
        "id" => $id,
        "values" => [
            "all" => "?????",
            "compare" => $compareType[$id]
        ],
        "onclick" => "Report.ClientReport.changeParamsType(this)"
    ]);
};

/*--------------------------------------------------*/

$getCheckboxList = function(
        $list,
        $id,
        $name,
        $listId = false
)use($view,$getRadioBlock,$getParamsFrame){
    $listContent = "";
    $index = 0;
    foreach($list as $key => $el){
        $index++;
        if ($listId){
            $checkboxId = $key;
        }
        else{
            $checkboxId = "checkbox_{$index}";
        }
        $listContent .= $view->get("inc.input.checkbox",[
            "id" => $checkboxId,
            "text" => $el,
            "style" => [
                "margin-top" => "10px"
            ],
            "class" => "{$id}_checkBox"
        ]);
    }

    $cityBlockContent = $view->get("inc.div",[
        "type" => "column",
        "content" => $getRadioBlock($id."_type"). $view->get("inc.div",[
            "type" => "column",
            "content" => $listContent,
            "class" => "hidden forHide"
        ])
    ]);

    return $getParamsFrame($cityBlockContent,$name);
};

/*?????????????--------------------------------------------------*/



$periodBlockContent = $view->get("inc.div",[
    "type" => "column",
    "content" => $getRadioBlock("period_type"). $view->get("inc.div",[
        "type" => "column",
        "content" => $getPeriodBlock("firstPeriod"). $getPeriodBlock("secondPeriod",true)
    ])
]);

$periodBlock = $getParamsFrame($periodBlockContent,"??????");

$cityList = \Settings\Main::cityList();
$cityBlock = $getCheckboxList($cityList,"city","?????");

$buf = \Settings\Main::profileList()["manager"];
$managerList = [];
foreach($buf as $profile){
    $managerList[$profile] = profileGetUsername($profile);
}
$managerBlock = $getCheckboxList($managerList,"manager","????????",true);

$onclick = [
    "clientReport" => "Report.ClientReport.Client.showTable(this)",
    "connectReport" => "Report.ClientReport.Connect.showTable(this)",
    "amountReport" => "Report.ClientReport.Amount.showTable(this)",
    "connectSumReport" => "Report.ClientReport.ConnectSum.showTable(this)",
    "guReport" => "Report.ClientReport.Gu.showTable(this)",
    "districtReport" => "Report.ClientReport.District.showTable(this)"
][$reportType];

$buttonBlock = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("buttons.normal",[
        "text" => "????????",
        "onclick" => $onclick
    ])
]);


$header = $view->get("inc.div",[
    "type" => "row",
    "content" => $periodBlock. $cityBlock. $managerBlock. $buttonBlock
]);

/*???????????--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $view->get("inc.div",[
        "type" => "row",
        "id" => "reportTable",
        "content" => "",
        "style" => [
            "margin-top" => "20px"
        ]
    ])
]);


