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
    "dnum" => "Номер договора",
    "name" => "Наименование",
    "address" => "Адрес",
    "contacts" => "Контакты",
    "bin" => "БИН",
    "speed" => "Скорость",
    "staticIp" => "Статика",
    "manager" => "Менеджер",
    "hardware" => "Оборудование",
    "nightWork" => "Работают ночью",
    "loginList" => "Логины и пароли",
    "remark" => "Примечание",
];

$tableWidth = [
    "dnum" => "75px",
    "name" => "Наименование",
    "manager" => "100px",
    "address" => "180px",
    "contacts" => "180px",
    "remark" => "180px",
    "hardware" => "180px",
    "loginList" => "220px",
    "speed" => "80px",
    "staticIp" => "80px",
    "bin" => "80px",
    "nightWork" => "80px",
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "manager" => "center",
    "address" => "center",
    "contacts" => "center",
    "remark" => "center",
    "hardware" => "center",
    "loginList" => "center",
    "speed" => "center",
    "staticIp" => "center",
    "bin" => "center",
    "nightWork" => "center",
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
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
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

$getHiddenContainer = function(
        $content,
        $name
) use ($view,$textStyle){
    if (!$content){
        $content = $view->show("inc.text",[
            "text" => "Данные отсутствуют",
            "style" => $textStyle
        ],true);
    }
    $hiddenContainer = $view->show("inc.div",[
        "type" => "column",
        "class" => "hiddenTextContainer",
        "style" => [
            "height" => "100%",
            "justify-content" => "flex-start"
        ],
        "content" => 
//        $view->show("inc.text",[
//            "text" => $name,
//            "style" => [
//                "paddin-bottom" => "3px",
//                "cursor" => "pointer",
//                "color" => "#FF6F00",
//                "width" => "fit-content",
//                "border-bottom" => "1px dashed var(--modColor_darkest)",
//            ] + $textStyle,
//            "attribute" => [
//                "onclick" => "hiddenTextTilteClick(this,true,`tr`)",
//                "id" => "hiddenTextTitle"
//            ]
//        ],true). 
        $view->show("inc.div",[
            "type" => "column",
            "id" => "hiddenText",
//            "class" => "hidden",
            "content" => $content
        ],true)
    ],true);
    return $hiddenContainer;
};

/*--------------------------------------------------*/

$getEditBlock = function($id,$value,$width) use ($view,$textStyle){
    if (($id == "loginList") && (!$value)){
        $value = "Логин от роутера: \nПароль от роутера: \nЛогин от WI-FI: \nПароль от WI-FI: \nРасположение оборудования: \n";
    }
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.area_stretch",[
            "id" => $id,
            "class" => "inputAreaAutosize",
            "style" => [
                "max-width" => $width,
                "height" => "auto",
                "min-width" => $width,
            ] + $textStyle,
            "value" => $value
        ],true). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "Редактировать",
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
                    "onclick" => "saveClientSupportInfo(this,`td`)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true);
};

/*--------------------------------------------------*/

$getAddressBlock = function(
        $address,
        $clientInfo
) use ($textStyle,$view,$renewList){
    $remark = $clientInfo["remark"];
    $district = $clientInfo["district"];
    $clientStatus = $clientInfo["clientStatus"];
    if ($remark){
        $remarkText = $view->show("inc.text",[
            "text" => "{$remark}",
            "style" => [
                "border-bottom" => "1px dashed var(--modColor_darkest)",
                "padding-bottom" => "5px",
                "margin-bottom" => "5px"
            ] + $textStyle 
        ],true);
    }
    else{
        $remarkText = "";
    }
    if ($district != "Не назначен"){
        $districtText = $view->show("inc.text",[
            "text" => "Район: {$district}",
            "style" => [
                "border-bottom" => "1px dashed var(--modColor_darkest)",
                "padding-bottom" => "5px",
                "margin-bottom" => "5px",
                "height" => "auto"
            ] + $textStyle 
        ],true);
    }
    else{
        $districtText = "";
    }
    if ($clientStatus == "Переоформлен"){
        if ($clientInfo["renewType"] == "Договор"){
            $typeText = "договора";
        }
        else{
            $typeText = "точки";
        }
        $newText = "№ {$clientInfo["renewDnum"]} {$clientInfo["newName"]}";
        $renewText = $view->show("inc.text",[
            "text" => "Переоформление {$typeText}<br>на {$newText}",
            "style" => [
                "border-bottom" => "1px dashed var(--modColor_darkest)",
                "padding-bottom" => "5px",
                "margin-bottom" => "5px",
                "color" => "red",
                "height" => "auto"
            ] + $textStyle        
        ],true);
    }
    else {
        $dnum = $clientInfo["dnum"];
        if (isset($renewList[$dnum])){
            $buf = $renewList[$dnum];
            if ($buf["type"] == "Договор"){
                $typeText = "договор был оформлен";
            }
            else{
                $typeText = "точка была оформлена";
            }
            $newText = "№ {$buf["dnum"]} {$buf["name"]}";
            $renewText = $view->show("inc.text",[
                "text" => "Ранее {$typeText}<br>на {$newText}",
                "style" => [
                    "border-bottom" => "1px dashed var(--modColor_darkest)",
                    "padding-bottom" => "5px",
                    "margin-bottom" => "5px",
                    "color" => "red",
                    "height" => "auto"
                ] + $textStyle        
            ],true);
        }
        else{
            $renewText = "";
        }
    }
    return $view->show("inc.div",[
        "type" => "column",
        "content" => $remarkText. $renewText . $districtText. $view->show("inc.text",[
            "text" => "{$address}",
            "style" => [
                "height" => "auto"
            ] + $textStyle
        ],true)
    ],true);
};

/*--------------------------------------------------*/


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

foreach($accountList as $clientList){
    $dnum = $clientList[0]["dnum"];
    $nameBlock = $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => $clientList[0]["clientType"],
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "\"{$clientList[0]["name"]}\"",
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true). $view->show("inc.vars",[
            "vars" => [
                "searchText" => $searchList[$clientList[0]["dnum"]]
            ]
        ],true)
    ],true);
    
    $accountKeys = [
        "dnum",
        "name",
        "bin"
    ];
    $clientKeys = [
        "address",
        "contacts",
        "remark",
        "hardware",
        "loginList",
        "speed",
        "staticIp",
        "manager"
    ];
    $supClientId = $view->show("inc.var",[
        "key" => "sup_clientId",
        "value" => $clientList[0]["id"]
    ],true);
    $address = getAddress($clientList[0]);
    $addressBlock = $getAddressBlock($address,$clientList[0]);
    $contactList = $getContactList($clientList[0]["contactList"]);
    $contactBlock = $getHiddenContainer($contactList,"Свернуть/Развернуть");
    $remark = $getEditBlock("remark",($clientList[0]["supportInfo"]["remark"]),$tableWidth["remark"]). $supClientId;
    $remarkBlock = $getHiddenContainer($remark,"Свернуть/Развернуть");
    $hardware = nl2br($clientList[0]["hardware"]);
    $hardwareBlock = $getHiddenContainer($hardware,"Свернуть/Развернуть");
    $loginList = $getEditBlock("loginList",($clientList[0]["supportInfo"]["loginList"]),$tableWidth["loginList"]). $supClientId;
    $loginListBlock = $getHiddenContainer($loginList,"Свернуть/Развернуть");
    $speed = $clientList[0]["speed"];
    $staticIpBlock = $clientList[0]["staticIp"] ? "Да" : "Нет";
    $nightWorkBlock = $clientList[0]["nightWork"] ? "Да" : "Нет";
    $binBlock = $clientList[0]["bin"];
    $manager = profileGetUsername($clientList[0]["manager"]);
    $tr = [
        "dnum" => $dnum,
        "name" => $nameBlock,
        "address" => $addressBlock,
        "contacts" => $contactBlock,
        "bin" => $binBlock,
        "speed" => $speed,
        "staticIp" => $staticIpBlock,
        "manager" => $manager,
        "hardware" => $hardwareBlock,
        "nightWork" => $nightWorkBlock,
        "loginList" => $loginListBlock,
        "remark" => $remarkBlock,
    ];
    $trContent = "";
    $count = count($clientList);
    foreach($tr as $key => $value){
        if (in_array($key, $accountKeys)){
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
            "style" => [
                "vertical-align" => $tableVertical[$key]
            ] + $tdStyle
        ],true);
    }
    $trColor++;
    $body .= $view->show("table.tr",[
        "content" => $trContent,
        "class" => ($trColor % 2 != 0) ? "odd" : "even"
    ],true); 
    for($i = 1; $i < $count; $i++){
        $supClientId = $view->show("inc.var",[
            "key" => "sup_clientId",
            "value" => $clientList[$i]["id"]
        ],true);
        $address = getAddress($clientList[$i]);
        $addressBlock = $getAddressBlock($address,$clientList[$i]);
        $contactList = $getContactList($clientList[$i]["contactList"]);
        $contactBlock = $getHiddenContainer($contactList,"Развернуть");
        $remark = $getEditBlock("remark",($clientList[$i]["supportInfo"]["remark"]),$tableWidth["remark"]). $supClientId;
        $remarkBlock = $getHiddenContainer($remark,"Развернуть");
        $hardware = nl2br($clientList[$i]["hardware"]);
        $hardwareBlock = $getHiddenContainer($hardware,"Развернуть");
        $loginList = $getEditBlock("loginList",($clientList[$i]["supportInfo"]["loginList"]),$tableWidth["loginList"]). $supClientId;
        $loginListBlock = $getHiddenContainer($loginList,"Развернуть");
        $speed = $clientList[$i]["speed"];
        $nightWorkBlock = $clientList[$i]["nightWork"] ? "Да" : "Нет";
        $staticIpBlock = $clientList[$i]["staticIp"] ? "Да" : "Нет";
        $manager = profileGetUsername($clientList[$i]["manager"]);
        $tr = [
            "address" => $addressBlock,
            "contacts" => $contactBlock,
            "speed" => $speed,
            "staticIp" => $staticIpBlock,
            "manager" => $manager,
            "hardware" => $hardwareBlock,
            "nightWork" => $nightWorkBlock,
            "loginList" => $loginListBlock,
            "remark" => $remarkBlock,
        ];
        $trContent = "";
        foreach($tr as $key => $value){
            $trContent .= $view->show("table.td",[
                "content" => $value,
                "style" => [
                    "vertical-align" => $tableVertical[$key]
                ] + $tdStyle
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



















