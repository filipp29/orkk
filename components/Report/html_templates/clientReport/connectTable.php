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
    "period" => "Период",
    "city" => "Город",
    "manager" => "Менеджер",
    "count" => "Количество",
    "allPercent" => "% от подключенных",
    "sumPercent" => "% от всех карточек",
    "delta" => "Разница"
];

$tableWidth = [
    "period" => "Период",
    "city" => "Город",
    "manager" => "Менеджер",
    "count" => "Количество",
    "allPercent" => "% от подключенных",
    "sumPercent" => "% от всех карточек",
    "delta" => "Разница"
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
                "padding" => "0px 5px",
                "height" => "auto",
                "width" => $tableWidth[$key],
                "border" => "1px solid var(--modColor_light)"
            ]
        ],true);
    }
    
    return $view->show("table.tr",[
        "content" => $headerContent
    ],true);
    
};

/*Инициализация--------------------------------------------------*/

$periodNameList = [
    "first" => "I",
    "second" => "II"
];

$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;
foreach($result as $period => $unused){
    foreach($params["city"] as $city){
        if (!in_array("all",$params["manager"])){
            $all = $result[$period][$city]["all"];
            $sum = $result[$period][$city]["sum"];
        }
        else if(!in_array("all",$params["city"])){
            $all = $result[$period]["all"]["all"];
            $sum = $result[$period]["all"]["sum"];
        }
        else{
            $sum = 0;
            $all = 0;
            foreach($result as $per => $unused){
                $sum += $result[$per]["all"]["sum"];
                $all += $result[$per]["all"]["all"];
            }
        }
        
        foreach($params["manager"] as $manager){
            $periodName = $periodNameList[$period];
            $cityName = ($city == "all") ? "Общий" : $city;
            $managerName = ($manager == "all") ? "Общий" : profileGetUsername($manager);
            $count = $result[$period][$city][$manager];
            $tr = [
                "period" => $periodName,
                "city" => $cityName,
                "manager" => $managerName,
                "count" => $count,
                "allPercent" => ((int)$all > 0) ? (int)((int)$count * 100 / (int)$all) : 0,
                "sumPercent" => ((int)$sum > 0) ? (int)((int)$count * 100 / (int)$sum) : 0,
                "delta" => $delta[$period][$city][$manager]
            ];
            $trContent = "";
            foreach($tr as $key => $value){
                $trContent .= $view->get("table.td",[
                    "content" => $value,
                    "style" => [
                        "width" => $tableWidth[$key]
                    ] + $tdStyle
                ]);
            }
            $even = !$even;
            $tbody .= $view->get("table.tr",[
                "content" => $trContent,
                "class" => $even ? "even" : "odd"
            ]);
        }
    }
}


/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "tbody" => $tbody,
    "thead" => $thead
]);



