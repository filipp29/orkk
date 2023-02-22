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
    "padding" => "2px 5px",
    "text-align" => "center"
]; 
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

$buttonStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px",
    "width" => "auto",
    "height" => "auto",
    "min-width" => "10px"
]; 

/*Инициализация--------------------------------------------------*/

$paramList = [
    "key" => "Наименование",
    "value" => "Сумма"
];

$content = "";

foreach($paramList as $key => $value){
    $content .= $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%"
        ],
        "content" => $view->get("inc.text",[
            "text" => "{$value}:",
            "style" => [
                "width" => "43%",
                "margin-right" => "8px",
                "justify-content" => "flex-end"
            ]
        ]). $view->get("inc.input.text",[
            "id" => $key,
        ])
    ]);
}

$content .= $view->get("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "center"
    ],
    "content" => $view->get("buttons.acceptSquare",[
        "class" => "adminSalaryBonusSaveButton"
    ])
]);

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $content,
    "style" => [
        "width" => "100%",
        "align-items" => "flex-start",
        "height" => "100%",
        "justify-content" => "space-around"
    ]
]);








