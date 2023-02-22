<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Debtor\Controller();
$debtorView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];



/*Èíèöèàëèçàöèÿ--------------------------------------------------*/


$menuList = [
    "debtor" => "Äîëæíèêè",
    "fl" => "ÔË",
    "gu" => "ÃÓ",
    "active" => "Àêòèâíûå",
//    "terminated" => "Îòêëş÷åííûå",
    "shift" => "Ïåğåíîñ",
    "wait" => "Îæèäàíèå",
    "forTerminate" => "Íà ğàñòîğæåíèå"
];
$menu = "";
$selected = "menuItem_selected";
foreach($menuList as $key => $value){
    $menu .= $view->show("inc.div",[
        "type" => "row",
        "class" => "menuItem {$selected}",
        "content" => $value,
        "id" => $key,
        "attribute" => [
            "onclick" => "Debtor.tableMenuSelect(this)"
        ],
        "style" => [
            "cursor" => "pointer",
            "font-size" => "20px",
            "color" => "var(--modColor_darkest)",
            "opacity" => "0.4",
            "transform" => "scale(0.92)",
            "width" => "fit-content",
            "margin-right" => "10px"
        ]
    ],true);
    $selected = "";    
}

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("buttons.normal",[
        "text" => "Îáíîâèòü",
        "onclick" => "Debtor.reloadDebtorTable(this)"
    ],true),
    "style" => [
        "margin" => "10px 0px"
    ]
],true);

$header = $view->show("inc.div",[
    "type" => "column",
    "content" => $buttonBlock. $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "40px",
            "align-items" => "flex-end",
            "margin" => "10px",
            "padding" => "0px 10px",
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "class" => "menuBlock",
            "content" => $menu
        ],true)
    ],true)
],true);



$body = "";
foreach($accountList as $type => $clientList){
    if ($type == "debtor"){
        $hidden = "";
    }
    else{
        $hidden = "hidden";
    }
    $body .= $view->show("inc.div",[
        "type" => "row",
        "class" => "tableContainer {$hidden}",
        "id" => "{$type}_table",
        "content" => $debtorView->show("debtorTable.table",[
            "accountList" => $clientList,
            "tableType" => $type
        ],true)        
    ],true);
}
//foreach($clientList as $city => $value){
//    foreach($value as $type => $v){
//        if ("{$city}-{$type}" == "kst-all"){
//            $hidden = "";
//        }
//        else{
//            $hidden = "hidden";
//        }
//        $body .= $view->show("inc.div",[
//            "type" => "row",
//            "class" => "tableContainer {$hidden}",
//            "id" => "{$city}-{$type}_table",
//            "content" => $regView->show("supportRegister.table",[
//                "accountList" => $clientList[$city][$type],
//                "renewList" => $renewList,
//                "searchList" => $searchList
//            ],true)        
//        ],true);
//    }
//}

$amountSumList = [
    "all" => "Îáùàÿ ñóììà äîãîâîğîâ",
    "flGu" => "ÔË/ÃÓ",
    "work" => "Èòîãî ñóììà â ğàáîòó",
];
$debtSumList = [
    "all" => "Îáùèé äîëã",
    "forTerminate" => "Íà ğàñòîğæåíèå â îäíîñòîğîííåì",
    "wait" => "Ãàğàíòèéíîå ïèñüìî",
    "work" => "Äîëã"
];
$footerContent = "";

foreach($amountSum as $key => $value){
    
    $footerContent .= $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => $amountSumList[$key].":",
            "style" => [
                "font-weight" => "bolder",
                "margin-right" => "8px"
            ]
        ]). $view->get("inc.text",[
            "text" => $value
        ])
    ]); 
}



foreach($debtSum as $key => $value){
    if ($key == "work"){
        $percentBlock = $view->get("inc.text",[
            "text" => "{$percent}%",
            "style" => [
                "font-weight" => "bolder",
                "margin-left" => "10px"
            ]
        ]);
    }
    else{
        $percentBlock = "";
    }
    $footerContent .= $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => $debtSumList[$key].":",
            "style" => [
                "font-weight" => "bolder",
                "margin-right" => "8px"
            ]
        ]). $view->get("inc.text",[
            "text" => $value
        ]). $percentBlock
    ]); 
}

$footer = $view->get("inc.div",[
    "type" => "column",
    "content" => $footerContent,
    "style" => [
        "margin" => "15px 0px",
        "padding" => "15px",
        "background-color" => "#eaefea",
        "width" => "100%"
    ]
]);


/*Îòîáğàæåíèå--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $body. $footer,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);

/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Îáçâîí äîëæíèêîâ",
];

$view->show("inc.vars",[
    "vars" => $vars
]);


