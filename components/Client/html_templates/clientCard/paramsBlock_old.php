<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$titleStyle = [
    "font-size" => "25px"
];
$labelStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$textStyle = [
    "font-size" => "14px",
    "height" => "18px"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px"
];
$fontSize = "14px";

function getCheckboxValue(
        $value
){
    return ($value == "0") ? "Не требуется" : "Требуется";
}


/*Инициализация--------------------------------------------------*/

$paramKeys = $settingsClient->paramKeys();
$show = [];
$show["Адрес"] = getAddress($params);
$show["Тариф"] = getTariff($params);
$show["Статический IP адрес"] = getCheckboxValue($params["staticIp"]);
$show["Подключение"] = $params["connectSum"];
$show["Тип расчета"] = ($params["payType"] == 1) ? "Наличный" : "Безналичный";
$show["Оборудование"] = $params["hardware"];
$show["Дата договора"] = $params["contractDate"];
$show["Дата активации"] = $params["activateDate"];
$show["Статус клиента"] = $params["clientStatus"];
$show["Привлечение"] = $params["attractType"];
$show["Конкурент"] = $params["competitor"];
$show["Тип подключения"] = $params["connectType"];
$show["IP телефония"] = getCheckboxValue($params["ipPhone"]);
$show["Кабельное ТВ"] = getCheckboxValue($params["kTv"]);
$show["Видео наблюдение"] = getCheckboxValue($params["cameras"]);
$show["Сервисное обслуживание"] = getCheckboxValue($params["service"]);
$show["Менеджер"] = profileGetUsername($params["manager"]);

$leftSide = [
    "Адрес",
    "Тариф",
    "Статический IP адрес",
    "Подключение",
    "Тип расчета",
    "Оборудование",
    "Дата договора",
    "Дата активации",
    "Статус клиента"
];
$rightSide = [
    "Привлечение",
    "Конкурент",
    "Тип подключения",
    "IP телефония",
    "Кабельное ТВ",
    "Видео наблюдение",
    "Сервисное обслуживание",
];
$leftContent = "";
$rightContent = "";
foreach($leftSide as $key){
    $leftContent .= $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => $show[$key],
            "style" => $textStyle
        ],true)
    ],true);
}
foreach($rightSide as $key){
    $rightContent .= $view->show("inc.div",[
        "type" => "row",
        
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle 
        ],true). $view->show("inc.text",[
            "text" => $show[$key],
            "style" => $textStyle
        ],true)
    ],true);
}

$rightContent .= $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-top" => "20px"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Менеджер". ":",
        "style" => [
            "font-size" => "20px"
        ]+$textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => $show["Менеджер"],
        "style" => [
            "font-size" => "20px"
        ] + $textStyle
    ],true)
],true);

$title = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "margin-bottom" => "20px"
    ],
    "content" => $view->show("inc.text",[
        "text" => $params["dnum"],
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => $params["clientType"],
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "'{$params["name"]}'",
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "'{$params["remark"]}'",
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true)
],true);

$body =  $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
    ],
    "content" => $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "50%"
        ],
        "content" => $leftContent
    ],true). $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "50%"
        ],
        "content" => $rightContent
    ],true)
],true);       
        
$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "flex-start",
        "margin-top" => "30px"
    ],
    "content" => $view->show("buttons.normal",[
        "text" => "Редактировать",
        "onclick" => "modifyClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "Развернуть",
        "onclick" => "openDocList(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "Подключить",
        "onclick" => "connectClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "Запросить смету",
        "onclick" => "askCost(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "Блок",
        "onclick" => "blockClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "Удалить",
        "onclick" => "deleteClient(this)",
        "style" => $buttonStyle
    ],true)
],true);        

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $title. $body. $buttonBlock
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/





















                                                                                        





