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
    "contactList" => "Контакты"
];

$tableWidth = [
    "dnum" => "Договор",
    "name" => "Наименование",
    "contactList" => "Контакты"
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

/*--------------------------------------------------*/

$getTableHeader = function(
        $tableHeader,
        $tableWidth
)use($view,$tdStyle,$textStyle){
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
                "padding" => "5px 5px",
                "height" => "auto",
                "width" => $tableWidth[$key],
                "border" => "1px solid var(--modColor_light)",
            ]
        ],true);
    }
    
    return $view->show("table.tr",[
        "content" => $headerContent
    ],true);
    
};

/*--------------------------------------------------*/

$getContactList = function($contacts) use ($view,$textStyle){
    $contactList = "";
    foreach($contacts as $value){
        if (strlen($value["phone"]) == 1){
            continue;
        }
        $phone = getPhoneTemplate($value["phone"]);
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
    return $contactList ? $contactList : $view->show("inc.text",[
        "text" => "Список пуст",
        "style" => $textStyle
    ],true);
};

/*Инициализация--------------------------------------------------*/


$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;
foreach($report as $key => $value){
    
    $dnum = (strpos($key, "noDnum") === false) ? $key : "#";
    $name = $value["name"];
    if ($value["contactList"]){
        $contactList = $getContactList($value["contactList"]);
    }
    else{
        continue;
        $contactList = "";
    }
    $tr = [
        "dnum" => $dnum,
        "name" => $name,
        "contactList" => $contactList
    ];
    $trContent = "";
    foreach($tr as $k => $v){
        $trContent .= $view->get("table.td",[
            "content" => $v,
            "style" => [
                
            ] + $tdStyle
        ]);
    }
    $even = !$even;
    $tbody .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
}

/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $tbody,
    "style" => [
        "width" => "fit-content"
    ]
]);

