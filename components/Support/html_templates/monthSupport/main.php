<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$buf = new \Support\Controller();
$supView = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableLabelStyle = [
    "font-size" => "20px",
    "font-wight" => "600",
    "margin" => "20px 0px 10px 30px"
];




/*Инициализация--------------------------------------------------*/

$years = [
    "2021" => "2021",
    "2022" => "2022" 
];
$months = [
    "1" => "Январь",
    "2" => "Февраль",
    "3" => "Март",
    "4" => "Апрель",
    "5" => "Май",
    "6" => "Июнь",
    "7" => "Июль",
    "8" => "Август",
    "9" => "Сентябрь",
    "10" => "Октябрь",
    "11" => "Ноябрь",
    "12" => "Декабрь"
];

$header = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-top" => "10px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-bottom" => "10px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Год:",
            "style" => [
                "margin-right" => "4px"
            ]
        ],true). $view->show("inc.input.select",[
            "id" => "year",
            "values" => $years,
            "value" => $year,
            "style" => [
                "margin-right" => "8px"
            ]
        ],true). $view->show("inc.text",[
            "text" => "Месяц:",
            "style" => [
                "margin-right" => "4px"
            ]
        ],true). $view->show("inc.input.select",[
            "id" => "month",
            "values" => $months,
            "value" => $month,
            "style" => [
                "margin-right" => "8px"
            ]
        ],true)
    ],true). $view->get("inc.div",[
        "type" => "row",
        "content" => $view->show("buttons.normal",[
            "text" => "Обновить",
            "onclick" => "reloadMonthSupportTable(this);"
        ],true). $view->show("buttons.normal",[
            "text" => "Сохранить",
            "onclick" => "downloadMonthSupportTable(this);",
            "style" => [
                "margin-left" => "15px"
            ]
        ],true)
    ])
],true);

$supportTable = $view->show("inc.text",[
    "text" => "Заявки",
    "style" => $tableLabelStyle
],true).$supView->show("monthSupport.supportTable",[
    "clientList" => $clientList,
    "accountList" => $accountList
],true);

$footer = $view->show("inc.text",[
    "text" => "Количество заявок: {$fullCount}<br>Средний балл: {$averageRate}",
    "style" => [
        "margin" => "20px",
        "font-size" => "20px",
        "font-weight" => "600"
    ]        
],true);



/*Отображение--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $supportTable. $footer,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Контроль качества",
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























