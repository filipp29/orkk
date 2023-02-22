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
if (count($period) == 1){
    $tableHeader = [
        "type" => "Тип",
        "first_start" => date("d.m.Y",$period["first"]["start"]),
        "first_income" => "Приток",
        "first_outcome" => "Отток",
        "first_delta" => "Разница",
        "first_end" => date("d.m.Y",$period["first"]["end"])
    ];
}
else{
    $tableHeader = [
        "type" => "Тип",
        "first_start" => date("d.m.Y",$period["first"]["start"]),
        "first_income" => "Приток",
        "first_outcome" => "Отток",
        "first_delta" => "Разница",
        "first_end" => date("d.m.Y",$period["first"]["end"]),
        "second_start" => date("d.m.Y",$period["second"]["start"]),
        "second_income" => "Приток",
        "second_outcome" => "Отток",
        "second_delta" => "Разница",
        "second_end" => date("d.m.Y",$period["second"]["end"]),
        "delta" => "Разница"
    ];
}

$tableWidth = [
    "type" => "",
    "first_start" => "",
    "first_income" => "Приток",
    "first_outcome" => "Отток",
    "first_delta" => "",
    "first_end" => "",
    "second_start" => "",
    "second_income" => "Приток",
    "second_outcome" => "Отток",
    "second_delta" => "",
    "second_end" => "",
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

$getCell = function(
        $value,
        $sum
){
    $percent = (int)((int)$value * 100 / (int)$sum);
    return "{$value} ($percent%)";
};

/*Инициализация--------------------------------------------------*/


$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;
$typeList = [
    "amount" => "Новый точки",
    "additional" => "Доп. соглашения"
];
foreach($report as $type => $value){
    $firstStart = $value["first"]["start"];
    $firstEnd = $value["first"]["end"];
    $firstIncome = $value["first"]["income"];
    $firstOutcome = $value["first"]["outcome"];
    $firstDelta = $value["first"]["delta"];
    $tr = [
        "type" => $typeList[$type],
        "first_start" => $firstStart,
        "first_income" => $firstIncome,
        "first_outcome" => $firstOutcome,
        "first_delta" => $firstDelta,
        "first_end" => $firstEnd
    ];
    if(count($period) > 1){
        $secondStart = $value["second"]["start"];
        $secondEnd = $value["second"]["end"];
        $secondIncome = $value["second"]["income"];
        $secondOutcome = $value["second"]["outcome"];
        $secondDelta = $value["second"]["delta"];
        $delta = $secondDelta - $firstDelta;
        $tr += [
            "second_start" => $secondStart,
            "second_income" => $secondIncome,
            "second_outcome" => $secondOutcome,
            "second_delta" => $secondDelta,
            "second_end" => $secondEnd,
            "delta" => $delta
        ];
    }
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

/*Отображение--------------------------------------------------*/

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $tbody,
    "style" => [
        "width" => "fit-content"
    ]
]);

