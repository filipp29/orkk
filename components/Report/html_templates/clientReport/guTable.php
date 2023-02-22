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
        [
            "city" => "Город",
            "firstStartCount" => date("d.m.Y",$period["first"]["start"]),
            "firstStartSum" => "",
            "firstDeltaCount" => "Разница",
            "firstDeltaSum" => "",
            "firstEndCount" => date("d.m.Y",$period["first"]["end"]),
            "firstEndSum" => ""
        ],
        [
            "city" => "",
            "firstStartCount" => "Количество",
            "firstStartSum" => "Сумма",
            "firstDeltaCount" => "Количество",
            "firstDeltaSum" => "Сумма",
            "firstEndCount" => "Количество",
            "firstEndSum" => "Сумма"
        ]
    ];
}
else{
    $tableHeader = [
        [
            "city" => "Город",
            "firstStartCount" => date("d.m.Y",$period["first"]["start"]),
            "firstStartSum" => "",
            "firstDeltaCount" => "Разница",
            "firstDeltaSum" => "",
            "firstEndCount" => date("d.m.Y",$period["first"]["end"]),
            "firstEndSum" => "",
            "secondStartCount" => date("d.m.Y",$period["second"]["start"]),
            "secondStartSum" => "",
            "secondDeltaCount" => "Разница",
            "secondDeltaSum" => "",
            "secondEndCount" => date("d.m.Y",$period["second"]["end"]),
            "secondEndSum" => "",
            "deltaCount" => "Разница",
            "deltaSum" => ""
        ],
        [
            "city" => "",
            "firstStartCount" => "Количество",
            "firstStartSum" => "Сумма",
            "firstDeltaCount" => "Количество",
            "firstDeltaSum" => "Сумма",
            "firstEndCount" => "Количество",
            "firstEndSum" => "Сумма",
            "secondStartCount" => "Количество",
            "secondStartSum" => "Сумма",
            "secondDeltaCount" => "Количество",
            "secondDeltaSum" => "Сумма",
            "secondEndCount" => "Количество",
            "secondEndSum" => "Сумма",
            "deltaCount" => "Количество",
            "deltaSum" => "Сумма"
        ]
    ];
}

$tableWidth = [
    "city" => "",
    "firstStartCount" => "Количество",
    "firstStartSum" => "Сумма",
    "firstDeltaCount" => "Количество",
    "firstDeltaSum" => "Сумма",
    "firstEndCount" => "Количество",
    "firstEndSum" => "Сумма",
    "secondStartCount" => "Количество",
    "secondStartSum" => "Сумма",
    "secondDeltaCount" => "Количество",
    "secondDeltaSum" => "Сумма",
    "secondEndCount" => "Количество",
    "secondEndSum" => "Сумма",
    "deltaCount" => "Количество",
    "deltaSum" => "Сумма"
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
    foreach($tableHeader as $val){
        $trContent = "";
        foreach($val as $key => $value){
            $trContent .= $view->show("table.th",[
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
        $headerContent .= $view->get("table.tr",[
            "content" => $trContent
        ]);
    }
    
    return $headerContent;
    
};

/*Инициализация--------------------------------------------------*/

$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;




foreach($report as $city => $unused){
    $first = $report[$city]["first"];
    $startCount = (int)$first["start"]["count"];
    $startSum = (int)$first["start"]["sum"];
    $endCount = (int)$first["end"]["count"];
    $endSum = (int)$first["end"]["sum"];
    $firstDeltaCount = $endCount - $startCount;
    $firstDeltaSum = $endSum - $startSum;
    $tr = [
        "city" => ($city == "all") ? "Итого" : $city,
        "firstStartCount" => $startCount,
        "firstStartSum" => $startSum,
        "firstDeltaCount" => $firstDeltaCount,
        "firstDeltaSum" => $firstDeltaSum,
        "firstEndCount" => $endCount,
        "firstEndSum" => $endSum
    ];
    if (count($period) > 1){
        $second = $report[$city]["second"];
        $startCount = (int)$second["start"]["count"];
        $startSum = (int)$second["start"]["sum"];
        $endCount = (int)$second["end"]["count"];
        $endSum = (int)$second["end"]["sum"];
        $secondDeltaCount = $endCount - $startCount;
        $secondDeltaSum = $endSum - $startSum;
        $deltaCount = $secondDeltaCount - $firstDeltaCount;
        $deltaSum = $secondDeltaSum - $secondDeltaSum;
        $tr = array_merge($tr,[
            "secondStartCount" => $startCount,
            "secondStartSum" => $startSum,
            "secondDeltaCount" => $secondDeltaCount,
            "secondDeltaSum" => $secondDeltaSum,
            "secondEndCount" => $endCount,
            "secondEndSum" => $endSum,
            "deltaCount" => $deltaCount,
            "deltaSum" => $deltaSum
        ]);
    }
    $trContent = "";
    foreach($tr as $key => $value){
        $trContent .= $view->get("table.td",[
            "content" => $value,
            "style" => $tdStyle
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












