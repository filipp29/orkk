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
    "manager" => "Менеджер",
    "dateBlock" => "Дата открытия<br>Дата закрытия",
    "executed" => "Инженер",
    "text" => "Тескт заявки",
    
    "resolution" => "Текст закрытия",
    "rate" => "Оценка",
    "comment" => "Примечание",
    "callDate" => "Планирование звонка",
    "action" => "Действия"
];

$tableWidth = [
    "dnum" => "65px",
    "name" => "200px",
    "manager" => "100px",
    "dateBlock" => "150px",
    "executed" => "100px",
    "text" => "300px",
    
    "resolution" => "300px",
    "rate" => "80px",
    "comment" => "300px",
    "callDate" => "165px",
    "action" => "100px"
];

$tdStyle = [
    "font-size" => "18px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "5px 7px"
];
$textStyle = [
    "font-size" => "18px",
    "padding" => "2px 5px"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
];






$contactList = "";
foreach($contacts as $value){
    $phone = getPhoneTemplate($value["phone"]);
    $name = $view->show("inc.text",[
        "text" => $value["name"],
        "style" => [
            "margin-right" => "10px"
        ]
    ],true);
    $role = $value["role"];
    $contactList .= $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "border-bottom" => "1px var(--modColor_darkest) dashed",
            "padding-bottom" => "3px",
            "margin-top" => "3px"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "class" => "phoneContainer",
            "style" => [
                "align-items" => "center"
            ],
            "content" => $view->show("buttons.phone",[
                "onclick" => "orkkDoCall(this,`.phoneContainer`)",
                "style" => [
                    "height" => "20px",
                    "width" => "auto"
                ]
            ],true). $view->show("inc.text",[
                "text" => $phone,
                "style" => $textStyle
            ],true). $view->show("inc.var",[
                "key" => "phoneNumber",
                "value" => $value["phone"]
            ],true)
        ],true). $view->show("inc.div",[
            "type" => "row",
            "content" => $view->show("inc.text",[
                "text" => $value["name"],
                "style" => [
                    "margin-right" => "3px"
                ] + $textStyle
            ],true). $view->show("inc.text",[
                "text" => $value["role"],
                "style" => [
                    "margin-right" => "3px"
                ] + $textStyle
            ],true)
        ],true)
    ],true);
}


$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "align-items" => "center"
    ],
    "content" => $contactList
]);
