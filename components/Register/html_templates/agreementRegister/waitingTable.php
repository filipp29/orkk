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
    "contractDate" => "Дата договора",
    "name" => "Наименование",
    "dnum" => "Номер договора",
    "amount" => "Ежемесячная плата",
    "speed" => "Скорость",
    "connectSum" => "Сумма подключения",
    "yearSum" => "Сумма за год",
    "comment" => "Примечание",
];

$tableWidth = [
    "contractDate" => "100px",
    "name" => "30%",
    "dnum" => "100px",
    "yearSum" => "100px",
    "amount" => "100px",
    "speed" => "60px",
    "connectSum" => "100px",
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
$commentButtonBlock = $view->show("inc.div",[
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
$body = "";
$trColor = 0;
foreach($accountList as $key => $posList){
    $trColor++;
    $clientId = $posList[0];
    $info = $clientList[$clientId];
 
    $tr = [
        "contractDate" => (($info["contractDate"])) ? date("d.m.Y",$info["contractDate"]) : "",
        "name" => "{$info["clientType"]} \"{$info["name"]}\"",
        "dnum" => $info["dnum"],
        "amount" => ((int)$info["fullAmount"]). " тг",
        "speed" => ((int)$info["fullSpeed"]). " мбит/с",
        "connectSum" => ((int)$info["connectSum"]). " тг.",      
        "yearSum" => ((int)$sumList[$info["dnum"]] * 12). " тг",
        "comment" => $view->show("inc.div",[
            "type" => "column",
            "content" => $getCommentButtonBlock(). $view->show("inc.text",[
                "text" => nl2br($info["docComment"]),
                "attribute" => [
                    "id" => "hiddenText"
                ],
                "style" => [
                    "height" => "auto"
                ] + $textStyle,
//                    "class" => "hidden"
            ],true). $view->show("inc.var",[
                "key" => "tr_clientId",
                "value" => $clientId
            ],true),
            "class" => "hiddenTextContainer"
        ],true),
    ];
    $count = count($posList);
    $clientKeys = [
        "contractDate",
        "name",
        "dnum",
        "yearSum",
    ];
    $posKeys = [
        "amount",
        "speed",
        "connectSum",
        "comment"
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
        $clientId = $posList[$i];
        $info = $clientList[$clientId];
        if ($action == "activate"){
            $activateDate = date("d.m.Y",$info["activateDate"]);
        }
        else if ($action == "disconnect"){
            if ($info["disconnectDate"]){
                $activateDate = date("d.m.Y",$info["disconnectDate"]);
            }
            else if ($info["renewDate"]){
                $activateDate = date("d.m.Y",$info["renewDate"]);
            }
        }
        switch ($info["registerType"]):
            case "disconnected":
                $filePath = $info["disconnectFilePath"];
                break;
            case "renew":
                $filePath = $info["renewFilePath"];
                break;
            default:
                $filePath = $info["filePath"];
                break;
        endswitch;
        $tr = [
            "amount" => ((int)$info["fullAmount"]). " тг",
            "speed" => ((int)$info["fullSpeed"]). " мбит/с",
            "connectSum" => ((int)$info["connectSum"]). " тг.",      
            "yearSum" => ((int)$sumList[$info["dnum"]] * 12). " тг",
            "comment" => $view->show("inc.div",[
                "type" => "column",
                "content" => $getCommentButtonBlock(). $view->show("inc.text",[
                    "text" => nl2br($info["docComment"]),
                    "attribute" => [
                        "id" => "hiddenText"
                    ],
                    "style" => [
                        "height" => "auto"
                    ] + $textStyle,
//                    "class" => "hidden"
                ],true). $view->show("inc.var",[
                    "key" => "tr_clientId",
                    "value" => $clientId
                ],true),
                "class" => "hiddenTextContainer"
            ],true),
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
    "content" => $headerContent
],true);




/*Отображение--------------------------------------------------*/


$view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("table.main",[
        "thead" => $thead,
        "tbody" => $body
    ],true),
    "class" => "agreementTable"
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/


























