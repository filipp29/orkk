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
    "newConnect" => "Новое подключение",
    "salarySum" => "Сумма документа",
    "salaryBonus" => "Бонус документа",
    "attractType" => "Агитация",
    "salaryComment" => "Примечание",
    "action" => "Действия"
];

$tableWidth = [
    "dnum" => "100px",
    "name" => "Наименование",
    "newConnect" => "105px",
    "salarySum" => "200px",
    "salaryBonus" => "200px",
    "attractType" => "230px",
    "salaryComment" => "220px",
    "action" => "100px"
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
    "padding" => "2px 5px",
    "height" => "auto"
];

$getLimitBlock = function(
        $accept,
        $text,
        $left
)use($view,$textStyle){
    if ($accept){
        $img = $view->get("inc.div",[
            "content" => "",
            "type" => "row",
            "style" => [
                "width" => "18px",
                "height" => "18px",
                "border-radius" => "40px",
                "background-color" => "var(--modColor_dark)",
                "position" => "absolute",
                "top" => "2px",
                "left" => "2px",
            ]
        ]);
    }
    else{
        $img = "";
    }
    $textBlock = $view->get("inc.text",[
        "text" => $text,
        "style" => [
            "margin-top" => "30px"
        ]
    ]);
    return $view->get("inc.div",[
        "type" => "column",
        "content" => $img. $textBlock,
        "style" => [
            "width" => "30px",
            "height" => "30px",
            "border-radius" => "40px",
            "border" => "4px solid var(--modColor_dark)",
            "background-color" => "var(--modBGColor)",
            "position" => "absolute",
            "left" => $left,
            "z-index" => "10",
            "align-items" => "center"
        ]
    ]);
};

/*Инициализация--------------------------------------------------*/

$leftList = [
    "min" => "10px",
    "mid" => "260px",
];

$barList = [
    "min" => 252,
    "mid" => 123,
];


$limits = "";
$bars = "";
foreach($leftList as $key => $left){
    $limits .= $getLimitBlock($accept[$key],$text[$key],$left);
    $bars .= $view->get("inc.div",[
        "type" => "row",
        "content" => "",
        "style" => [
            "width" => (int)($bar[$key] * $barList[$key] / 100),
            "height" => "16px",
            "border" => "1px solid var(--modBGColor)",
            "background-color" => "var(--modColor_dark)",
            "position" => "absolute",
            "border-radius" => "5px",
            "top" => "6px",
            "left" => $left,
            "margin-left" => "14px"
        ]
    ]);
}

$limits .= $getLimitBlock(false,"","380px");

$barContainer = $view->get("inc.div",[
    "type" => "row",
    "content" => "",
    "style" => [
        "width" => "370px",
        "height" => "24px",
        "border" => "6px solid var(--modColor_dark)",
        "background-color" => "var(--modBGColor)",
        "position" => "absolute",
        "top" => "3px",
        "left" => "25px",
        
    ]
]);

$progressBar = $view->get("inc.div",[
    "type" => "row",
    "content" => $limits. $barContainer. $bars,
    "style" => [
        "width" => "500px",
        "height" => "50px",
        "position" => "relative",
        "margin-bottom" => "5px"
    ]
    
]);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $view->get("inc.text",[
        "text" => "Годовой план: {$currentYearPlan}тг ({$yearPlanPercent}%)",
        "style" => [
            "font-size" => "22px",
            "margin" => "0px 10px 0px 15px"
        ]        
    ]). $progressBar
]);

