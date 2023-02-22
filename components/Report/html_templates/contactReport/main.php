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

$compareType = [
    "emailReport" => [
        "city_type" => "Отбор",
        "status_type" => "Отбор",
        "emailType_type" => "Отбор",
        "clientType_type" => "Отбор"
    ],
    "contactReport" => [
        "city_type" => "Отбор",
        "status_type" => "Отбор",
        "emailType_type" => "Отбор",
        "clientType_type" => "Отбор"
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
            "all" => "Общий",
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

/*Инициализация--------------------------------------------------*/



$cityList = \Settings\Main::cityList();
$cityBlock = $getCheckboxList($cityList,"city","Город");

$statusList = \Settings\Main::clientStatus();
$statusBlock = $getCheckboxList($statusList,"status","Статус");

$emailTypeList = [
    "ЭАВР" => "ЭАВР",
    "Основная" => "Основная"
];
if ($reportType == "emailReport"){
    $emailTypeBlock = $getCheckboxList($emailTypeList,"emailType","Тип email");
}
else{
    $emailTypeBlock = "";
}

$clientTypeList = \Settings\Main::clientType();
$clientTypeBlock = $getCheckboxList($clientTypeList,"clientType","Тип клиента");

$onclick = [
    "emailReport" => "Report.ContactReport.Email.showTable(this)",
    "contactReport" => "Report.ContactReport.Contact.showTable(this)"
][$reportType]; 

$buttonBlock = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("buttons.normal",[
        "text" => "Обновить",
        "onclick" => $onclick
    ])
]);


$header = $view->get("inc.div",[
    "type" => "row",
    "content" => $cityBlock. $statusBlock. $emailTypeBlock. $clientTypeBlock. $buttonBlock
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





