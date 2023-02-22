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
    "name" => "Наименование",
];

$tableWidth = [
    "name" => "Наименование",
    "first" => "",
    "second" => "",
    "delta" => ""
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

/*--------------------------------------------------*/

$getPeriodHeader = function(
        $start,
        $end
){
    $startDate = date("d.m.Y",$start);
    $endDate = date("d.m.Y",$end);
    return "{$startDate}<br>{$endDate}";
};

/*Инициализация--------------------------------------------------*/

$tableHeader["first"] = $getPeriodHeader($period["first"]["start"],$period["first"]["end"]);

$tr = [
    "name" => "Сумма подключений",
    "first" => $report["first"]
];

if (count($period) > 1){
    $tableHeader["second"] = $getPeriodHeader($period["second"]["start"],$period["second"]["end"]);
    $tableHeader["delta"] = "Разница";
    $tr["second"] = $report["second"];
    $tr["delta"] = (int)$report["second"] - (int)$report["first"];
}

$thead = $getTableHeader($tableHeader,$tableWidth);

$tbody = "";
$trContent = "";
foreach($tr as $key => $value){
    $trContent .= $view->get("table.td",[
        "content" => $value,
        "style" => $tdStyle
    ]);
}
$tbody .= $view->get("table.tr",[
    "content" => $trContent
]);


/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $tbody,
    "style" => [
        "width" => "fit-content"
    ]
]);

