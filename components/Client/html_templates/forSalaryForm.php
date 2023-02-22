<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];

/*Инициализация--------------------------------------------------*/

$buf = getYearMonthList();
$months = $buf["months"];
$years = $buf["years"];
unset($buf);
$vars = []; 
$managerSelectRoleGroup = [
    "leader",
    "admin"
];

$lockRoleGroup = [
    "leader",
    "admin"
];
$profile = $_COOKIE["login"];
$role = getRole();
$buf = \Settings\Main::profileList();
$managerList = $buf["manager"];
unset($buf);

$dateBlock = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("inc.text",[
        "text" => "Год:",
        "style" => [
            "margin-right" => "10px"
        ] + $textStyle
    ]). $view->get("inc.select",[
        "id" => "year",
        "value" => $yearValue ? $yearValue : "",
        "values" => $years
    ]). $view->get("inc.text",[
        "text" => "Месяц:",
        "style" => [
            "margin-left" => "10px",
            "margin-right" => "10px"
        ] + $textStyle
    ]). $view->get("inc.select",[
        "id" => "month",
        "value" => $monthValue ? $monthValue : "",
        "values" => $months
    ])
]);

if (in_array($role, $managerSelectRoleGroup)){
    $managerBlock = $view>get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => "Менеджер:",
            "style" => [
                "margin-right" => "10px"
            ] + $textStyle
        ]). $view->get("inc.select",[
            "id" => "manager",
            "value" => $profile,
            "values" => $managerList
        ])
    ]);
}
else{
    $vars["manager"] = $profile;
    $managerBlock = "";
}

if (in_array($role, $lockRoleGroup)){
    $lockButton = $view->get("buttons.lockSquare",[
        "onclick" => ""
    ]);
}
else{
    $lockButton = "";
}

$buttonBlock = $view>get("inc.div",[
    "type" => "row",
    "content" => $view->get("buttons.closeSquare",[
        "onclick" => "",
        "style" => [
            "margin-right" => "15px"
        ]
    ]). $view->get("buttons.acceptSquare",[
        "onclick" => "",
        "style" => [
            "margin-right" => ""
        ]
    ]). $lockButton
]);



/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $dateBlock. $managerBlock. $buttonBlock,
    "style" => [
        "align-items" => "center"
    ]
]);





