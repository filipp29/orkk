<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
$buf = new \Phonebook\Controller();
$phoneView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$actionText = [
    "activate" => "Дата активации",
    "disconnect" => "Дата отключения"
];

$tableHeader = [
    "dnum" => "№ договора",
    "name" => "Наименование абонента",
    "balance" => "Баланс",
    "amount" => "Тариф",
    "contacts" => "Телефон",
    "comment" => "Комментарий"
];

$tableWidth = [
    "dnum" => "100px",
    "name" => "Имя",
    "balance" => "100px",
    "amount" => "100px",
    "contacts" => "180px",
    "comment" => "300px"
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

$getButton = function(
        $img,
        $onclick
)use($view){
    return $view->get("buttons.image",[
        "onclick" => $onclick,
        "img" => $img,
        "style" => [
            "margin-left" => "10px"
        ],
        "attribute" => [
            "id" => $img."_button"
        ]
    ]);
};

/*--------------------------------------------------*/


$buttons = [
    "edit" => $getButton("debtor_edit","Debtor.edit(this)"),
    "shift" => $getButton("debtor_shift","Debtor.shift(this,`shift`)"),
    "lock" => $getButton("debtor_lock","Debtor.save(this,`lock`)"),
    "exclude" => $getButton("debtor_exclude","Debtor.save(this,`exclude`)"),
    "forTerminate" => $getButton("debtor_terminate","Debtor.save(this,`forTerminate`)"),
    "wait" => $getButton("debtor_wait","Debtor.save(this,`wait`)"),
    "debtor" => $getButton("refresh","Debtor.save(this,`debtor`)")
];

$buttonList = [
    "debtor" => [
        "shift",
        "lock",
        "exclude",
        "forTerminate",
        "wait"
    ],
    "fl" => [
        "shift",
        "lock",
        "exclude",
        "forTerminate",
        "wait"
    ],
    "gu" => [
        "shift",
        "lock",
        "exclude",
        "forTerminate",
        "wait"
    ],
    "active" => [],
    "terminated" => [],
    "shift" => [
        "debtor",
        "forTerminate",
        "wait"
    ],
    "wait" => [
        "shift",
        "forTerminate",
        "debtor"
    ],
    "forTerminate" => [
        "shift",
        "debtor",
        "wait"
    ]
];

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

/*--------------------------------------------------*/

$getCommentBlock = function(
        $debt
)use($view,$textStyle,$tableType,$buttons,$buttonList){
    $content = $buttons["edit"]. $view->get("inc.div",[
        "type" => "row",
        "class" => "hidden acceptBlock",
        "content" => $view->get("buttons.closeSquare",[
            "onclick" => "Debtor.editCancel(this)",
            "style" => [
                "margin-left" => "10px"
            ]    
        ]). $view->get("buttons.acceptSquare",[
            "onclick" => "Debtor.editAccept(this)",
            "style" => [
                "margin-left" => "10px"
            ]
        ])
    ]);
    foreach($buttonList[$tableType] as $type){
        $content .= $buttons[$type];
    }
    if ($tableType == "shift"){
        $hidden = "";
    }
    else{
        $hidden = "hidden";
    }
    
    $dateBlock = $view->get("inc.input.date",[
        "id" => "debtor_date",
        "value" => $debt["date"],
        "style" => [
            "margin-top" => "8px",
            "width" => "100px",
            
        ],
        "class" => $hidden
    ]);
    return $view->get("inc.div",[
        "type" => "column",
        "content" => $view->get("inc.input.area_stretch",[
            "id" => "debtor_comment",
            "class" => "inputAreaAutosize",
            "style" => [
                "max-width" => "300px",
                "min-width" => "300px",
                "height" => "auto"
            ] + $textStyle,
            "value" => $debt["comment"]
        ]). $dateBlock. $view->get("inc.div",[
            "type" => "row",
            "content" => $content,
            "style" => [
                "margin" => "8px 0px 3px 0px",
                "justify-content" => "flex-end",
                "padding-right" => "10px"
            ]
        ]). $view->get("inc.vars",[
            "vars" => [
                "debtor_dnum" => $debt["dnum"],
                "debtor_type" => $debt["type"],
                "debtor_lock" => $debt["lock"],
                "debtor_exclude" => $debt["exclude"]
            ]
        ])
    ]);
};

/*--------------------------------------------------*/

$getNameBlock = function(
        $info
)use($view,$textStyle){
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "space-between",
            "align-items" => "center"
        ],
        "content" => $view->get("inc.text",[
            "text" => "{$info["clientType"]} \"{$info["name"]}\"",
            "style" => $textStyle        
        ]). $view->get("buttons.normal",[
            "onclick" => "showUserCard(this,'tr','debtor_dnum')",
            "text" => "...",
            "style" => [
                "margin" => "0px",
                "min-width" => "10px",
                "width" => "25px",
                "height" => "25px",
                "padding" => "0px"
            ]
        ])
    ]);
};

/*--------------------------------------------------*/


/*Инициализация--------------------------------------------------*/

$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $value,
        "style" => [
            "width" => $tableWidth[$key]
        ]
    ],true);
}

$detailButton = $view->get("buttons.normal",[
    
]);

$body = "";
$even = true;
foreach($accountList as $info){
    $dnum = $info["dnum"];
    $name = $getNameBlock($info);
    $balance = isset($info["balance"]) ? $info["balance"] : "";
    $amount = isset($info["amount"]) ? $info["amount"] : "";
    $contactBlock = $getContactList($info["contactList"]);
    $commentBlock = $getCommentBlock($info["debt"]);
    $tr = [
        "dnum" => $dnum,
        "name" => $name,
        "balance" => $balance,
        "amount" => $amount,
        "contacts" => $contactBlock,
        "comment" => $commentBlock
    ];
    $trContent = "";
    foreach($tr as $value){
        $trContent .= $view->get("table.td",[
            "content" => $value,
            "style" => $tdStyle
        ]);
    }
    if ((in_array($tableType, ["fl","gu","debtor"])) && (isset($info["debt"]["lock"]) && ($info["debt"]["lock"]))){
        $bgColor = "#DAA520";
    }
    else if ((in_array($tableType, ["fl","gu","debtor"])) && (isset($info["debt"]["exclude"]) && ($info["debt"]["exclude"]))){
        $bgColor = "#7FFFD4";
    }
    else{
        $bgColor = "";
    }
    $body .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd",
        "style" => [
            "background-color" => $bgColor
        ]
    ]);
    $even = !$even;
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



