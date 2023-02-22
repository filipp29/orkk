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
    "dnum" => "Договор",
    "name" => "Наименование",
    "emailList" => "Email"
];

$tableWidth = [
    "dnum" => "Договор",
    "name" => "Наименование",
    "emailList" => "Email"
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

$getTableHeader = function(
        $tableHeader,
        $tableWidth
)use($view,$tdStyle,$textStyle){
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
                "padding" => "5px 5px",
                "height" => "auto",
                "width" => $tableWidth[$key],
                "border" => "1px solid var(--modColor_light)",
            ]
        ],true);
    }
    
    return $view->show("table.tr",[
        "content" => $headerContent
    ],true);
    
};


/*Инициализация--------------------------------------------------*/


$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;
foreach($report as $key => $value){
    
    $dnum = (strpos($key, "noDnum") === false) ? $key : "#";
    $name = $value["name"];
    if ($value["emailList"]){
        $email = implode("<br>", $value["emailList"]);
    }
    else{
        continue;
        $email = "";
    }
    $tr = [
        "dnum" => $dnum,
        "name" => $name,
        "email" => $email
    ];
    $trContent = "";
    foreach($tr as $k => $v){
        $trContent .= $view->get("table.td",[
            "content" => $v,
            "style" => [
                
            ] + $tdStyle
        ]);
    }
    $even = !$even;
    $tbody .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
}

/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $tbody,
    "style" => [
        "width" => "fit-content"
    ]
]);

