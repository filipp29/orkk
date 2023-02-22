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
    "name" => "������������",
];

$tableWidth = [
    "name" => "������������",
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
//                "width" => $tableWidth[$key],
                "border" => "1px solid var(--modColor_light)",
            ]
        ],true);
    }
    
    return $view->show("table.tr",[
        "content" => $headerContent
    ],true);
    
};


/*�������������--------------------------------------------------*/
$monthNameList = [
    "01" => "���",
    "02" => "���",
    "03" => "����",
    "04" => "���",
    "05" => "���",
    "06" => "����",
    "07" => "����",
    "08" => "���",
    "09" => "���",
    "10" => "���",
    "11" => "����",
    "12" => "���"
];

$tr = [
    "count" => [
        "���������� �����"
    ],
    "amount" => [
        "����� �����"
    ],
    "rateCount" => [
        "���������� ������"
    ],
    "rateAvarage" => [
        "������� ����"
    ]
];

foreach($report as $year => $monthList){
    foreach($monthList as $month => $value){
        $tr["count"][] = $value["count"];
        $tr["amount"][] = $value["amount"];
        $tr["rateCount"][] = $value["rateCount"];
        $tr["rateAvarage"][] = ((int)$value["rateCount"] > 0) ? number_format($value["rateSum"] / $value["rateCount"], 2) : 0;
        $tableHeader[] = "{$monthNameList[$month]}<br>{$year}";
    }
}

$thead = $getTableHeader($tableHeader,$tableWidth);
$tbody = "";
$even = true;


foreach($tr as $type => $tdList){
    $trContent = "";
    foreach($tdList as $value){
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



/*�����������--------------------------------------------------*/

$view->show("table.main",[
    "tbody" => $tbody,
    "thead" => $thead,
    "style" => [
        "width" => "fit-content"
    ]
]);

