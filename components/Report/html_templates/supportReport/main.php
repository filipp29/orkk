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
    "contractDate" => "Дата договора",
    "activateDate" => "Дата активации",
    "name" => "Наименование",
    "dnum" => "Номер договора",
    "amount" => "Ежемесячная плата",
    "speed" => "Скорость",
    "yearSum" => "Сумма за год",
    "comment" => "Примечание",
];

$tableWidth = [
    "contractDate" => "Дата договора",
    "activateDate" => "Дата активации",
    "name" => "Наименование",
    "dnum" => "Номер договора",
    "amount" => "Ежемесячная плата",
    "speed" => "Скорость",
    "yearSum" => "Сумма за год",
    "comment" => "Примечание",
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

//$compareType = [
//    "connectReport" => [
//        "period_type" => "Сравнение",
//        "city_type" => "Сравнение",
//        "manager_type" => "Сравнение"
//    ],
//    "clientReport" => [
//        "period_type" => "Сравнение",
//        "city_type" => "Отбор",
//        "manager_type" => "Отбор"
//    ],
//    "amountReport" => [
//        "period_type" => "Сравнение",
//        "city_type" => "Отбор",
//        "manager_type" => "Отбор"
//    ]
//][$reportType];

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
)use($view,$textStyle){
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "15px"
        ],
        "content" => $view->get("inc.input.select",[
            "id" => "{$id}_month",
            "values" => getYearMonthList()["months"],        
            "style" => [
                "margin-right" => "8px",
                "width" => "115px"
            ]        
        ]). "-". $view->get("inc.input.select",[
            "id" => "{$id}_year",
            "values" => getYearMonthList()["years"],        
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
)use($view){
    return $view->get("inc.input.radio",[
        "divType" => "row",
        "id" => $id,
        "values" => [
            "all" => "Общий",
            "compare" => "Сравнение"
        ],
        "onclick" => "Report.SupportReport.changeParamsType(this)"
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

/*Инициализация--------------------------------------------------*/



$periodBlockContent = $view->get("inc.div",[
    "type" => "column",
    "content" => $getRadioBlock("period_type"). $view->get("inc.div",[
        "type" => "column",
        "content" => $getPeriodBlock("first"). $getPeriodBlock("second",true)
    ])
]);

$periodBlock = $getParamsFrame($periodBlockContent,"Период");

$buttonBlock = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("buttons.normal",[
        "text" => "Обновить",
        "onclick" => "Report.SupportReport.showTable(this)"
    ])
]);


$header = $view->get("inc.div",[
    "type" => "row",
    "content" => $periodBlock. $buttonBlock
]);

/*Отображение--------------------------------------------------*/

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


