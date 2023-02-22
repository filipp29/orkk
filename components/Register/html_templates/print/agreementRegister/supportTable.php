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
$actionText = [
    "activate" => "Дата активации",
    "disconnect" => "Дата отключения"
];
//$tableHeader = [
//    "contractDate" => "Дата договора",
//    "name" => "Наименование",
//    "dnum" => "Номер договора",
//    "yearSum" => "Сумма за год",
//    "activateDate" => $actionText[$action],
//    "amount" => "Ежемесячная плата",
//    "speed" => "Скорость",
//    "comment" => "Примечание",
//];

$tableHeader = [
    "date" => "Дата договора",
    "name" => "Наименование",
    "number" => "Номер договора",
    "charge_date" => "Дата списания",
    "amount" => "Сумма",
    "comment" => "Примечание",
];

$tableWidth = [
    "date" => "100px",
    "name" => "",
    "number" => "100px",
    "charge_date" => "100px",
    "amount" => "100px",
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

/*--------------------------------------------------*/

$getCommentButtonBlock = function()use($view,$textStyle){
    return $view->show("inc.div",[
        "type" => "row",
        "content" => 
    //    $view->show("inc.text",[
    //        "text" => "Свернуть/Развернуть",
    //        "style" => [
    //            "paddin-bottom" => "3px",
    //            "cursor" => "pointer",
    //            "color" => "#FF6F00",
    //            "width" => "fit-content",
    //            "border-bottom" => "1px dashed var(--modColor_darkest)",
    //        ] + $textStyle,
    //        "attribute" => [
    //            "onclick" => "hiddenTextTilteClick(this)",
    //            "id" => "hiddenTextTitle"
    //        ]
    //    ],true). 
        $view->show("buttons.normal",[
            "text" => "Карточка",
            "onclick" => "showClientCardFromTableRow(this)",
            "style" => [
                "height" => "var(--modLineHeight)",
    //            "margin-left" => "30px"
            ] + $textStyle
        ],true)
    ],true);
};


/*Инициализация--------------------------------------------------*/



//$clientList = $client->clientList();
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
$clientCardButton = $view->show("buttons.normal",[
    "text" => "Карточка",
    "onclick" => "showClientCardFromTableRow(this)",
    "style" => [
        "height" => "var(--modLineHeight)",
//            "margin-left" => "30px"
    ] + $textStyle
],true);
$body = "";
$trColor = 0;
foreach($accountList as $key => $posList){
    $trColor++;
    $info = $posList[0];
    

    $tr = [
        "date" => $info["date"],
        "name" => "{$info["name"]}",
        "number" => $info["number"],
        "charge_date" => $info["charge_date"],
        "amount" => ($info["amount"]). " тг",
        "comment" => nl2br($info["comment"])
    ];
    $count = count($posList);
    $clientKeys = [
        "date",
        "name",
        "number",
    ];
    $posKeys = [
        "charge_date",
        "amount",
        "comment",
    ];
    $trContent = "";
    foreach($tr as $key => $value){
        
        if (in_array($key, $clientKeys)){
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
    $body .= $view->show("table.tr",[
        "content" => $trContent,
        "class" => ($trColor % 2 != 0) ? "odd" : "even"
    ],true);    
    for($i = 1; $i < $count; $i++){
        $info = $posList[$i];
        $tr = [
            "charge_date" => $info["charge_date"],
            "amount" => ($info["amount"]). " тг",
            "comment" => nl2br($info["comment"])
        ];
        $trContent = "";
        foreach($tr as $key => $value){
            
            $trContent .= $view->show("table.td",[
                "content" => $value,
                "style" => $tdStyle,
            ],true);
        }
        $body .= $view->show("table.tr",[
            "content" => $trContent,
            "class" => ($trColor % 2 != 0) ? "odd" : "even"
        ],true);
    }
}

$thead = $view->show("table.tr",[
    "content" => $view->show("table.th",[
        "style" => [
            "text-align" => "left",
            "padding" => "0px 5px",
            "height" => "auto",
            "border" => "1px solid var(--modColor_light)",
            "margin-top" => "20px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Списания",
            "style" => [
                "font-size" => "20px"
            ]
        ],true),
        "attribute" => [
            "colspan" => "10"
        ]
    ],true)
],true). $view->show("table.tr",[
    "content" => $headerContent
],true);




/*Отображение--------------------------------------------------*/


$view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("table.main",[
        "thead" => $thead,
        "tbody" => $body
    ],true),
    "style" => [
        "margin-top" => "20px"
    ],
    "class" => "agreementTable"
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/


























