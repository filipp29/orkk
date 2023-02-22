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
    "docName" => "Наименование документа",
    "scan" => "Скан",
    "original" => "Оригинал",
    "manager" => "Менеджер",
];

$tableWidth = [
    "dnum" => "80px",
    "name" => "Наименование",
    "address" => "Адрес",
    "docName" => "Наименование документа",
    "scan" => "80px",
    "original" => "80px",
    "manager" => "180px"
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "address" => "center",
    "scan" => "center",
    "original" => "center",
    "manager" => "center",
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

$getScanBlock = function(
        $path
)use($view,$textStyle){
    if ($path){
        $background = "var(--modColor)";
        $text = "Залит";
    }
    else{
        $background = "#B22222";
        $text = "Не залит";
    }
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "center",
            "align-items" => "center"
        ],
        "content" => $view->get("inc.text",[
            "text" => $text,
            "attribute" => [
                "id" => "button_text",
                "onclick" => "DocumentRegister.showClientCard(this)"
            ],
            "style" => [
                "background-color" => $background,
                "color" => "white",
                "width" => "auto",
                "height" => "auto",
                "padding" => "3px",
                "cursor" => "pointer",
                "border" => "1px var(--modColor_darkest) solid"
            ] + $textStyle
        ])
    ]);
};

/*--------------------------------------------------*/

$getPlacementButton = function(
        $info
)use($view,$textStyle){
    
    $paramList = [
        "У менеджера" =>  [
            "bgColor" => "#FFD700",
            "color" => "var(--modColor_darkest)",
            "text" => "МЕН"
        ],
        "У клиента" => [
            "bgColor" => "#FF4500",
            "color" => "var(--modColor_darkest)",
            "text" => "КЛНТ"
        ],
        "У офис менеджера" => [
            "bgColor" => "#00FFFF",
            "color" => "var(--modColor_darkest)",
            "text" => "ОМЕН"
        ] ,
        "В бухгалтерии" => [
            "bgColor" => "#00FF00",
            "color" => "var(--modColor_darkest)",
            "text" => "БУХ"
        ],
        "Есть" =>  [
            "bgColor" => "var(--modColor)",
            "color" => "white",
            "text" => "ЕСТЬ"
        ],
        "Нет" => [
            "bgColor" => "#B22222",
            "color" => "white",
            "text" => "НЕТ"
        ],
    ];
    $docPlacement = ($info["placement"]) ? $info["placement"] : "Нет";
    $param = $paramList[$docPlacement];
    $vars = [
        "doc_docId" => $info["id"],
        "doc_docPlacement" => $docPlacement,
        "doc_clientId" => $info["clientId"],
        "doc_docType" => "clientDoc",
        "clientId" => $info["clientId"]
    ];
    return $view->get("inc.div",[
        "type" => "row",
        "attribute" => [
            "onclick" => "DocumentRegister.placementButtonClick(this)",
        ],
        "class" => "placementButton",
        "style" => [
            "justify-content" => "center",
            "align-items" => "center"
        ],
        "content" => $view->get("inc.text",[
            "text" => $param["text"],
            "attribute" => [
                "id" => "button_text",
                
            ],
            "style" => [
                "background-color" => $param["bgColor"],
                "color" => $param["color"],
                "cursor" => "pointer",
                "width" => "auto",
                "height" => "auto",
                "padding" => "3px",
                
                "border" => "1px var(--modColor_darkest) solid"
            ] + $textStyle
        ]). $view->get("inc.vars",[
            "vars" => $vars
        ])
    ]);
};

/*Инициализация--------------------------------------------------*/


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

foreach($accountList as $value){
    $info = $value["info"];
    $clientList = $value["docList"];
    $dnum = $info["dnum"];
    $nameBlock = $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => $info["clientType"],
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "\"{$info["name"]}\"",
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true)
    ],true);
    
    $accountKeys = [
        "dnum",
        "name",
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
    $addressBlock = $clientList[0]["address"];
    $docNameBlock = $clientList[0]["docName"];
    $scanBlock = $getScanBlock($clientList[0]["filePath"]);
    $originalBlock = $getPlacementButton($clientList[0]);
    $manager = profileGetUsername($clientList[0]["manager"]);
    $tr = [
        "dnum" => $dnum,
        "name" => $nameBlock,
        "address" => $addressBlock,
        "docName" => $docNameBlock,
        "scan" => $scanBlock,
        "original" => $originalBlock,
        "manager" => $manager,
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
            "style" => $tdStyle
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
        $addressBlock = $clientList[$i]["address"];
        $scanBlock = $getScanBlock($clientList[$i]["filePath"]);
        $originalBlock = $getPlacementButton($clientList[$i]);
        $manager = profileGetUsername($clientList[$i]["manager"]);
        $tr = [
            "address" => $addressBlock,
            "docName" => $docNameBlock,
            "scan" => $scanBlock,
            "original" => $originalBlock,
            "manager" => $manager,
        ];
        $trContent = "";
        foreach($tr as $key => $value){
            $trContent .= $view->show("table.td",[
                "content" => $value,
                "style" => $tdStyle
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



















