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
$vars = [
    "dnum" => $doc["dnum"],
    "docId" => $doc["docId"]
]; 
$managerSelectRoleGroup = [
    "leader",
    "admin"
];

$lockRoleGroup = [
    "leader",
    "admin"
];

$profile = $doc["payManager"];
$role = getRole();
$forPayment = $doc["forPayment"];
$error = "";

if (!in_array($role, $lockRoleGroup) && ($forPayment)){
    $error = "Отказано в доступе";
}

$dateBlock = $view->get("inc.div",[
    "type" => "row",
    "style" => [
        "margin" => "15px 0px"
    ],
    "content" => $view->get("inc.text",[
        "text" => "Год:",
        "style" => [
            "margin-right" => "10px"
        ] + $textStyle
    ]). $view->get("inc.input.select",[
        "id" => "year",
        "value" => $yearValue ? $yearValue : "",
        "values" => $years
    ]). $view->get("inc.text",[
        "text" => "Месяц:",
        "style" => [
            "margin-left" => "25px",
            "margin-right" => "10px"
        ] + $textStyle
    ]). $view->get("inc.input.select",[
        "id" => "month",
        "value" => $monthValue ? $monthValue : "",
        "values" => $months
    ])
]);

if (in_array($role, $managerSelectRoleGroup)){
    $managerBlock = $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "margin-bottom" => "15px"
        ],
        "content" => $view->get("inc.text",[
            "text" => "Менеджер:",
            "style" => [
                "margin-right" => "10px"
            ] + $textStyle
        ]). $view->get("inc.input.select",[
            "id" => "manager",
            "value" => $profile,
            "values" => $managerList
        ])
    ]);
}
else{
    $login = $_COOKIE["login"];
    $vars["manager"] = $login;
    
    $managerBlock = "";
    if ($doc["payManager"] && ($doc["payManager"] != $login)){
        $profileName = profileGetUsername($doc["payManager"]);
        $error = $error ? $error : "Документ закрыт менеджером:<br>{$profileName}";
    }
}

if (in_array($role, $lockRoleGroup)){
    $lockButton = $view->get("buttons.lockSquare",[
        "onclick" => "changePayment.lock(this)"
    ]);
}
else{
    $lockButton = "";
}

$buttonBlock = $view->get("inc.div",[
    "type" => "row",
    "content" => $view->get("buttons.closeSquare",[
        "onclick" => "changePayment.delete(this)",
        "style" => [
            "margin-right" => "15px"
        ]
    ]). $view->get("buttons.acceptSquare",[
        "onclick" => "changePayment.save(this)",
        "style" => [
            "margin-right" => "15px"
        ]
    ]). $lockButton
]);

$varBlock = $view->get("inc.vars",[
    "vars" => $vars
]);

/*Отображение--------------------------------------------------*/
if ($error){
    $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "100%",
            "width" => "100%",
            "justify-content" => "center",
            "align-items" => "center"
        ],
        "content" => $view->get("inc.text",[
            "text" => $error,
            "style" => [
                "font-weight" => "bolder",
                "font-size" => "20px",
                "text-align" => "center"
            ]
        ])
    ]);
}
else{
    $view->show("inc.div",[
        "type" => "column",
        "content" => $dateBlock. $managerBlock. $buttonBlock. $varBlock,
        "style" => [
            "align-items" => "center"
        ]
    ]);
}






