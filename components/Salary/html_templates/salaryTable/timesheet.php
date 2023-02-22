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
$tableHeaderFirst = [
    "name" => "���",
    "role" => "���������"
];
$tableHeaderLast = [
    "hours" => "����",
    "salary" => "�����",
    "bonus" => "�����",
    "punishment" => "�����",
    "reward" => "������",
    "sum" => "�����"
];

$tableWidth = [
    "name" => "���",
    "role" => "���������",
    "hours" => "����",
    "salary" => "�����",
    "bonus" => "�����",
    "punishment" => "�����",
    "reward" => "������",
    "sum" => "�����",
    "day" => "����"
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "newConnect" => "center",
    "salarySum" => "center",
    "salaryBonus" => "center",
    "attractType" => "center",
    "salaryComment" => "center",
    "action" => "center"
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px",
    
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

/*�������������--------------------------------------------------*/

$dayList = [
    '��', 
    '��', 
    '��', 
    '��', 
    '��', 
    '��', 
    '��' 
];

$day = DateTimeImmutable::createFromFormat("Y-m-d", "{$year}-{$month}-01");
$dayOfWeek = date("N",$day->getTimestamp());
$dayCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$headerContent = "";

foreach($tableHeaderFirst as $key => $value){
    $headerContent .= $view->get("table.th",[
        "content" => $value,
        "style" => [
            "width" => $width[$key]
        ],
        "attribute" => [
            "rowspan" => "2"
        ]
    ]);
}

for($i = 1; $i <= (int)$dayCount; $i++){
    $headerContent .= $view->get("table.th",[
        "content" => $value,
        "style" => [
            "width" => $width["day"]
        ]
    ]);
}

foreach($tableHeaderLast as $key => $value){
    $headerContent .= $view->get("table.th",[
        "content" => $value,
        "style" => [
            "width" => $width[$key]
        ],
        "attribute" => [
            "rowspan" => "2"
        ]
    ]);
}





















