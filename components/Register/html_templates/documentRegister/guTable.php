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
    "scan" => "Скан",
//    "specification" => "Оригинал",
    "additional" => "Дополнительные соглашения",
//    "other" => "Прочие документы",
    "disclaimer" => "Отказ от притензий",
    "manager" => "Менеджер",
];


$tableWidth = [
    "dnum" => "80px",
    "name" => "Наименование",
    "scan" => "70px",
    "specification" => "50px",
    "additional" => "Дополнительные соглашения",
    "other" => "Прочие документы",
    "disclaimer" => "50px",
    "manager" => "200px",
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "scan" => "center",
    "specification" => "center",
    "additional" => "center",
    "other" => "center",
    "disclaimer" => "center",
    "manager" => "center",
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "6px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px",
    "height" => "auto"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
];

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
    

/*--------------------------------------------------*/

$getPlacementButton = function(
        $info,
        $type,
        $additional = false
)use($view,$textStyle,$paramList){
    
    
    $vars = [
        "doc_dnum" => $info["dnum"],
        "doc_docType" => $type,
        "doc_docId" => isset($info["docId"]) ? $info["docId"] : "",
        "doc_docPlacement" => $info[$type] ? $info[$type] : "Нет"
    ];
    $param = $paramList[$vars["doc_docPlacement"]];
    return $view->get("inc.div",[
        "type" => "row",
        "attribute" => [
            "onclick" => $additional ? "showDocumentPlacementForm(this,`.placementButton`,`doc`)" : "DocumentRegister.placementButtonClick(this)",
        ],
        "style" => [
            "justify-content" => "center",
            "align-items" => "center"
        ],
        "class" => "placementButton",
        "content" => $view->get("inc.text",[
            "text" => $param["text"],
            "attribute" => [
                "id" => "button_text"
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

/*--------------------------------------------------*/

$getAdditionalBlock = function(
        $info,
        $even
)use($view,$textStyle,$getPlacementButton,$paramList){
    $number = explode("№",$info["name"])[1];
    $keyList = [
        "safekeepingPlacement",
        "transferActPlacement",
        "disclaimerPlacement"
    ];
    $extraContent = "";
    foreach($keyList as $key){
        $placement = $info[$key] ? $info[$key] : "Нет";
        $extraContent .= $view->get("inc.div",[
            "type" => "row",
            "style" => [
                "border" => "1px var(--modColor_darkest) solid",
                "background-color" => $paramList[$placement]["bgColor"],
                "width" => "12px",
                "height" => "8px"
            ],
            "id" => $key
        ]);
    }
    $extraBlock = $view->get("inc.div",[
        "type" => "column",
        "style" => [
            "height" => "100%",
            "justify-content" => "space-between",
            "margin-left" => "8px"
        ],
        "content" => $extraContent
    ]);
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "position" => "relative",
            "padding" => "10px 4px 4px 4px",
            "border" => "1px var(--modColor_darkest) solid",
            "margin" => "10px 5px 0px 0px"
        ],
        "class" => "additionalBlock",
        "content" => $view->get("inc.text",[
            "text" => "№{$number}",
            "style" => [
                "padding" => "0px 4px",
                "background-color" => $even ? "#eaefea" : "white",
                "justify-content" => "center",
                "align-items" => "center",
                "position" => "absolute",
                "top" => "-8px",
                "font-size" => "12px",
                "height" => "auto"
            ]        
        ]). $getPlacementButton($info,"docPlacement",true). $extraBlock
    ]);
};

/*--------------------------------------------------*/

$getManagerBlock = function(
        $managerList
)use($view,$textStyle){
    $content = "";
    foreach($managerList as $manager){
        $content .= $view->get("inc.text",[
            "text" => profileGetUsername($manager),
            "style" => [
                "margin-top" => "4px"
            ] + $textStyle
        ]);
    }
    return $view->get("inc.div",[
        "type" => "column",
        "content" => $content
    ]);
};

/*--------------------------------------------------*/

$getScanBlock = function(
        $info
)use($view,$textStyle){
    $path = $info["filePath"];
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
        ]). $view->get("inc.vars",[
            "vars" => [
                "clientId" => $info["clientId"]
            ]
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
$even = true;


foreach($accountList as $dnum => $value){
    $dnumBlock = $dnum;
    $even = !$even;
    $registerData = $value["registerData"];
    $nameBlock = $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => $value["info"]["clientType"],
            "style" => $textStyle
        ]). $view->get("inc.text",[
            "text" => $value["info"]["name"],
            "style" => [
                "margin-left" => "8p"
            ] + $textStyle
        ])
    ]);
    $getRegisterDoc = function(
            $docId,
            $docPlacement
    )use($dnum){
        return [
            "docType" => "register",
            "dnum" => $dnum,
            "docId" => $docId,
            "docPlacement" => $docPlacement
        ];
    };
    $scanBlock = $getScanBlock($value["specification"]["posList"][0]);
    $specificationBlock = $getPlacementButton($value["specification"],"docPlacement");
    $additionalContent = "";
    foreach($value["additional"] as $info){
        $additionalContent .= $getAdditionalBlock($info,$even);
    }
    $additionalBlock = $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "flex-wrap" => "wrap"
        ],
        "content" => $additionalContent
    ]);
    $disclaimerBlock = $getPlacementButton($value["specification"],"disclaimerPlacement");
    $tr = [
        "dnum" => $dnumBlock,
        "name" => $nameBlock,
        "scan" => $scanBlock,
//        "specification" => $specificationBlock,
        "additional" => $additionalBlock,
        "disclaimer" => $disclaimerBlock,
        "manager" => $getManagerBlock(array_keys($managerList[$dnum])),
    ];
    
    $trContent = "";
    foreach($tr as $k => $val){
        $trContent .= $view->get("table.td",[
            "content" => $val,
            "style" => [
                "width" => $tableWidth[$k]
            ] + $tdStyle
        ]);
    }
    $body .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
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














