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
    "dnum" => "¹ Äîãîâîğà",
    "clientType" => "Òèï",
    "name" => "Íàèìåíîâàíèå",
    "clientStatus" => "Ñòàòóñ",
    "tarif" => "Òàğèô",
    "activateDate" => "Ïîäêëş÷åí",
    "address" => "Àäğåñ",
    "bin" => "ÁÈÍ/ÈÈÍ",
    "changeDate" => "Èçìåíåí",
    "createDate" => "Ñîçäàí",
];

$tableWidth = [
    "dnum" => "110px",
    "clientType" => "45px",
    "name" => "",
    "clientStatus" => "175px",
    "tarif" => "120px",
    "activateDate" => "100px",
    "address" => "",
    "bin" => "100px",
    "changeDate" => "85px",
    "createDate" => "100px",
];

/*Èíèöèàëèçàöèÿ--------------------------------------------------*/


//$clientList = $client->clientList();
$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => "var(--modBGColor)",
                "cursor" => "pointer",
            ],
            "class" => "clietListSortButton",
            "attribute" => [
                "onclick" => "clientListSort(this)"
            ]
        ],true),
        "style" => [
            "width" => $tableWidth[$key],
            "text-align" => "left",
            "padding-right" => "10px"
        ]
    ],true);
}

$thead = $view->show("table.tr",[
    "content" => $headerContent
],true);
$tbody = "";
$class = 1;
foreach($clientList as $value){
    $class++;
    if ($value["remark"]){
        $remark = $view->show("inc.text",[
            "text" => "{$value["remark"]}",
            "style" => [
                "font-size" => "12px"
            ]
        ],true);
    }
    else{
        $remark = "";
    }
    
    $name = $view->show("inc.text",[
        "text" => "\"{$value["name"]}\"",
        "style" => [
            "font-size" => "12px",
            "margin-right" => "15px",
            "height" => "auto"
        ]
    ],true);
    $paramsBlock = $view->show("inc.var",[
        "key" => "paramsJson",
        "value" => $value["paramsJson"]
    ],true);
    $value["name"] = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "space-between"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "content" => $name. $remark. $paramsBlock
        ],true). $view->show("inc.text",[
            "text" => $value["docName"],
            "style" => [
                "font-size" => "12px",
                "margin-right" => "15px"
            ]
        ],true)
    ],true);
    $colorList = \Settings\Main::statusColor();
    $rowContent = "";
    foreach($tableHeader as $key => $v){
        
        if ($key == "clientStatus"){
            $color = isset($colorList[$value[$key]]) ? $colorList[$value[$key]] : "";
        }
        else{
            $color = "var(--modColor_darkest)";
        }
        $rowContent .= $view->show("table.td",[
            "content" => (isset($value[$key]) && ($value[$key])) ? $value[$key] : "---",
            "style" => [
                "padding-right" => "10px",
                "font-size" => "12px",
                "color" => $color
            ]
        ],true);
    }
    
    $tbody .= $view->show("table.tr",[
        "content" => $rowContent,
        "class" => "hoverable ". (($class % 2 == 1) ? "odd" : "even"),
        "style" => [
            "border-bottom" => "2px white solid",
            "border-top" => "2px white solid"
        ],
        "attribute" => [
            "onclick" => "showClientCard(`{$value["id"]}`)"
        ]
    ],true);
}



/*Îòîáğàæåíèå--------------------------------------------------*/
$view->show("page.clientList.menu");
$view->show("buttons.normal",[
    "text" => "Äîáàâèòü",
    "onclick" => "createClientPage()"
]). $view->show("buttons.normal",[
    "text" => "Îáíîâèòü",
    "onclick" => "reloadIndex(this);"
]);

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $tbody
]);

$view->show("page.clientList.footer");

/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Êëèåíòû",
];

$view->show("inc.vars",[
    "vars" => $vars
]);



















                                                                                        





