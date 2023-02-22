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
    "font-size" => "25px",
    "font-weight" => "600"
];
$labelStyle = [
    "margin" => "0px 10px",
    "font-weight" => "700"
];
$textStyle = [
    "font-size" => "14px",
    "height" => "18px"
];

$inputStyle = [
    "font-size" => "14px",
    "height" => "21px"
];

$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
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
$show["Адрес"] = $view->show("inc.input.select_stretch",[
    "id" => "city",
    
    "values" => $settings::cityList(),
    "value" => $params["city"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true).$view->show("inc.input.select_stretch",[
    "id" => "streetType",
    
    "values" => $settings::streetType(),
    "value" => $params["streetType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => [
        "margin-left" => "7px",
        "margin-right" => "3px"
    ] + $inputStyle
],true).$view->show("inc.input.text_stretch",[
    "id" => "street",
    "value" => $params["street"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => [
        "max-width" => "205px"
    ] + $inputStyle
],true).$view->show("inc.input.select_stretch",[
    "id" => "buildingType",
    
    "values" => $settings::buildingType(),
    "value" => $params["buildingType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => [
        "margin-left" => "7px",
        "margin-right" => "3px"
    ] + $inputStyle
],true).$view->show("inc.input.text_stretch",[
    "id" => "building",
    "value" => $params["building"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => [
        "max-width" => "60px"
    ] + $inputStyle
],true). $view->show("inc.input.select_stretch",[
    "id" => "flatType",
    
    "values" => $settings::flatType(),
    "value" => $params["flatType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => [
        "margin-left" => "7px",
        "margin-right" => "3px"
    ] + $inputStyle
],true). $view->show("inc.input.text_stretch",[
    "id" => "flat",
    "value" => $params["flat"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => [
        "max-width" => "60px"
    ] + $inputStyle
],true);

/*--------------------------------------------------*/
if (isset($parentDoc["forPayment"]) && ($parentDoc["forPayment"])){
    $forPayment = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "21px",
            "justify-content" => "center",
            "align-items" => "center",
            "margin-left" => "8px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "в ЗП",
            "style" => [
                "margin" => "0px",
                "border-radius" => "2px",
                "height" => "calc(var(--modLineHeight) - 7px)",
                "font-size" => "12px",
                "background-color" => "blue",
                "color" => "var(--modBGColor)",
                "padding" => "4px"
            ]
        ],true)
    ],true);
}
else{
    $forPayment = "";
}
if ($params["oldTarif"] && ($params["clientStatus"] == "Переоформлен")){
    $oldTarif = "({$params["oldTarif"]})";
}
else{
    $oldTarif = "";
}
$show["Тариф"] = $view->show("inc.text",[
    "text" => "{$params["fullAmount"]} тг - {$params["fullSpeed"]} мбит/с ",
    "style" => $inputStyle
],true);
$show["Район"] = $view->show("inc.input.select_stretch",[
    "id" => "district",
    "values" => $settings::district(),
    "value" => $params["district"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => [
    ] + $inputStyle
],true);
$show["Начальный тариф"] = $view->show("inc.input.text_stretch",[
    "id" => "amount",
    "value" => $params["amount"],
    "class" => "hiddenInput",
    "style" => $inputStyle
],true). $view->show("inc.text",[
    "text" => "тг -",
    "style" => [
        "margin" => "0px 10px 0px 0px"
    ] + $textStyle
],true). $view->show("inc.input.text_stretch",[
    "id" => "speed",
    "value" => $params["speed"],
    "class" => "noSwapDisable",
    "style" => $inputStyle
],true). $view->show("inc.text",[
    "text" => "мбит/c",
    "style" => $textStyle
],true);
$show["Статический IP адрес"] = $view->show("inc.input.select_stretch",[
    "id" => "staticIp",
    "divType" => "row",
    "value" => $params["staticIp"],
    "values" => [
        "0" => "Не требуется",
        "1" => "Требуется"
    ],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true);
//$show["Вид услуги"] = $view->show("inc.input.select_stretch",[
//    "id" => "serviceType",
//    "divType" => "row",
//    "value" => $params["serviceType"],
//    "values" => \Settings\Main::serviceType(),
//    "class" => "readonly",
//    "attribute" => [
//        "disabled" => "disabled"
//    ],
//    "style" => $inputStyle
//],true);

$show["Подключение"] = $view->show("inc.input.text_stretch",[
    "id" => "connectSum",
    "value" => $params["connectSum"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => $inputStyle
],true);
$show["Тип расчета"] = $view->show("inc.input.select_stretch",[
    "id" => "payType",
    "divType" => "row",
    "values" => \Settings\Main::payType(),
    "value" => $params["payType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true);
$show["Оборудование"] = $view->show("inc.input.area_stretch",[
    "id" => "hardware",
    "value" => $params["hardware"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => [
        "max-width" => "250px"
    ] + $inputStyle
],true);
$show["Дата договора"] = $view->show("inc.input.date",[
    "id" => "contractDate",
    "value" => $params["contractDate"],
    "class" => "readonly",
    "attribute" => [
        "readonly" => "readonly"
    ],
    "style" => $inputStyle
],true);
if ((int)$params["activateDate"] > time()){
    $clockIcon = $view->show("inc.img",[
        "src" => \Settings\Main::globalPath()."/img/clock.png",
        "style" => [
            "height" => "14px",
            "width" => "14px"
        ]
    ],true);
    $clockIcon = $view->show("inc.div",[
        "type" => "row",
        "content" => $clockIcon,
        "style" => [
            "height" => "21px",
            "justify-content" => "center",
            "align-items" => "center",
            "margin-left" => "8px"
        ]
    ],true);
}
else{
    $clockIcon = "";
}
//$show["Дата активации"] = $view->show("inc.input.date",[
//    "id" => "activateDate",
//    "value" => $params["activateDate"],
//    "class" => "readonly",
//    "attribute" => [
//        "readonly" => "readonly"
//    ],
//    "style" => $inputStyle
//],true). $clockIcon;
$show["Дата активации"] = $view->show("inc.text",[
    "text" => ($params["activateDate"]) ? date("d. m. Y",$params["activateDate"]) : "",
    "style" => $inputStyle
],true). $clockIcon;
$statusColor = \Settings\Main::statusColor();
if (isset($statusColor[$params["clientStatusShow"]])){
    $statusColor = $statusColor[$params["clientStatusShow"]];
}
else{
    $statusColor = "var(--modColor_darkest)";
}
if (!$params["dnum"]){
    
    $show["Статус клиента"] = $view->show("inc.input.select_stretch",[
        "id" => "clientStatus",
        "values" => \Settings\Main::clientStatusFirst(),
        "value" => $params["clientStatus"],
        "class" => "readonly",
        "attribute" => [
            "disabled" => "disabled"
        ],
        "style" => [
            "color" => $statusColor
        ] + $inputStyle
    ],true);
}
else{
    if ($params["clientStatus"] == "Переоформлен"){
        $statusText = "Переоформлен на № {$params["renewDnum"]}";
    }
    else{
        $statusText = $params["clientStatusShow"];
    }
    $show["Статус клиента"] = $view->show("inc.text",[
        "id" => "",
        "text" => $statusText,
        "style" => [
            "color" => $statusColor
        ] + $inputStyle
    ],true);
}
$show["Привлечение"] = $view->show("inc.input.select_stretch",[
    "id" => "attractType",
    "divType" => "row",
    "values" => $settings::attractType(),
    "value" => $params["attractType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true);
$competitorAmount = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "center",
        "align-items" => "flex-start"
    ],
    "class" => "nullableDiv hidden",
    "content" => $view->show("inc.text",[
        "text" => "(",
        "style" => [
            "align-items" => "flex-start",
            "font-size" => $fontSize
        ]
    ],true). $view->show("inc.input.text_stretch",[
        "id" => "competitorAmount",
        "value" => $params["competitorAmount"],
        "class" => "readonly nullableInput",
        "attribute" => [
            "readonly" => "readonly",
            "data_null" => ""
        ],
        "style" => $inputStyle
    ],true). $view->show("inc.text",[
        "text" => " тг/мес)",
        "style" => [
            "align-items" => "flex-start",
            "font-size" => $fontSize
        ]
    ],true)
],true);
$show["Конкурент"] = $view->show("inc.input.select_stretch",[
    "id" => "competitor",
    "value" => $params["competitor"],
    "values" => $settings::competitor(),
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true). $competitorAmount;
$show["Тип подключения"] = $view->show("inc.input.select_stretch",[
    "id" => "connectType",
    "divType" => "row",
    "values" => $settings::connectType(),
    "value" => $params["connectType"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => $inputStyle
],true);
$show["IP телефония"] = $view->show("inc.input.select_stretch",[
    "id" => "ipPhone",
    "divType" => "row",
    "values" => [
        "0" => "Не требуется",
        "1" => "Требуется"
    ],
    "value" => $params["ipPhone"],
    "class" => "readonly nullableInput",
    "attribute" => [
        "disabled" => "disabled",
        "data_null" => "0"
    ],
    "style" => $inputStyle
],true);
$show["Кабельное ТВ"] = $view->show("inc.input.select_stretch",[
    "id" => "kTv",
    "divType" => "row",
    "values" => [
        "0" => "Не требуется",
        "1" => "Требуется"
    ],
    "value" => $params["kTv"],
    "class" => "readonly nullableInput",
    "attribute" => [
        "disabled" => "disabled",
        "data_null" => "0"
    ],
    "style" => $inputStyle
],true);
$show["Видео наблюдение"] = $view->show("inc.input.select_stretch",[
    "id" => "cameras",
    "divType" => "row",
    "values" => [
        "0" => "Не требуется",
        "1" => "Требуется"
    ],
    "value" => $params["cameras"],
    "class" => "readonly nullableInput",
    "attribute" => [
        "disabled" => "disabled",
        "data_null" => "0"
    ],
    "style" => $inputStyle
],true);
$show["Сервисное обслуживание"] = $view->show("inc.input.select_stretch",[
    "id" => "service",
    "divType" => "row",
    "values" => [
        "0" => "Не требуется",
        "1" => "Требуется"
    ],
    "value" => $params["service"],
    "class" => "readonly nullableInput",
    "attribute" => [
        "disabled" => "disabled",
        "data_null" => "0"
    ],
    "style" => $inputStyle
],true);
$show["Менеджер"] = $view->show("inc.input.select_stretch",[
    "id" => "manager",
    "values" => $settings::managerList(),
    "value" => $params["manager"],
    "class" => "readonly",
    "attribute" => [
        "disabled" => "disabled"
    ],
    "style" => [
        "height" => "25px"
    ] + $inputStyle
],true);
if ($currentBlock["blockStart"]){
    $start = date("d.m.Y",$currentBlock["blockStart"]);
    if ($currentBlock["blockEnd"]){
        $end = " по ". date("d.m.Y",$currentBlock["blockEnd"]);
    }
    else{
        $end = "";
    }
    $show["Блокировка"] = $view->show("inc.text",[
        "id" => "clientStatus",
        "text" => "с {$start}{$end}",
        "style" => $inputStyle
    ],true);
}

if ($params["renewDate"]){
    $bufDate = date("d.m.Y",$params["renewDate"]);
    $show["Переоформлен"] = $view->show("inc.text",[
        "text" => "с {$bufDate} на № {$params["renewDnum"]}",
        "style" => $textStyle        
    ],true);
}

if ($params["disconnectDate"]){
    $bufDate = date("d.m.Y",$params["disconnectDate"]);
    $show["Отключение"] = $view->show("inc.text",[
        "text" => "с {$bufDate}",
        "style" => $textStyle        
    ],true);
}
$leftSide = ($params["dnum"]) ? [
    "Адрес",
    "Район",
    "Тариф",
    "Начальный тариф",
    "Статический IP адрес",
    "Подключение",
    "Тип расчета",
    "Дата договора",
    "Дата активации",
    "Статус клиента",
    "Оборудование"
] : [
    "Адрес",
    "Район",
//    "Вид услуги",
    "Привлечение",
    "Конкурент",
    "Статус клиента",
    "Менеджер"
];

$rightSide = ($params["dnum"]) ? [
//    "Вид услуги",
    "Привлечение",
    "Конкурент",
    "Тип подключения",
    "IP телефония",
    "Кабельное ТВ",
    "Видео наблюдение",
    "Сервисное обслуживание",
    "Менеджер"
] : [];
$nullable = [
    "IP телефония",
    "Кабельное ТВ",
    "Видео наблюдение",
    "Сервисное обслуживание",
];
$hidden = [
    "Начальный тариф"
];
if (isset($show["Блокировка"])){
    $rightSide[] = "Блокировка";
}
if (isset($show["Переоформлен"])){
    $rightSide[] = "Переоформлен";
}
if (isset($show["Отключение"])){
    $rightSide[] = "Отключение";
}
$leftContent = "";
$rightContent = "";
foreach($leftSide as $key){
    if (in_array($key, $nullable)){
        $nullableDiv = "nullableDiv";
    }
    else{
        $nullableDiv = "";
    }
    if (in_array($key, $hidden)){
        $hiddenClass = " hidden hiddenInputContainer ";
    }
    else{
        $hiddenClass = "";
    }
    $leftContent .= $view->show("inc.div",[
        "type" => "row",
        "class" => $nullableDiv.$hiddenClass,
        "style" => [
            "margin-bottom" => "2px",
            "align-items" => "flex-start"
        ],
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle
        ],true). $view->show("inc.div",[
            "type" => "row",
            "content" => $show[$key],
            "style" => [
                "flex-wrap" => "wrap",
                "align-items" => "flex-start"
            ]
        ],true)
    ],true);
}

foreach($rightSide as $key){
    if (in_array($key, $nullable)){
        $nullableDiv = "nullableDiv hidden";
    }
    else{
        $nullableDiv = "";
    }
    $rightContent .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-bottom" => "2px",
            "align-items" => "felx-start"
        ],
        "class" => $nullableDiv,
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle 
        ],true). $view->show("inc.div",[
            "type" => "row",
            "content" => $show[$key],
            "style" => [
                "flex-wrap" => "wrap",
                "align-items" => "flex-start"
            ]
        ],true)
    ],true);
}

//$rightContent .= $view->show("inc.div",[
//    "type" => "row",
//    "style" => [
//        "margin-top" => "20px",
//        "align-items" => "center"
//    ],
//    "content" => $view->show("inc.text",[
//        "text" => "Менеджер". ":",
//        "style" => [
//            "font-size" => "20px"
//        ]+$textStyle + $labelStyle
//    ],true). $view->show("inc.div",[
//        "type" => "row",
//        "content" => $show["Менеджер"],
//        "style" => [
//            "flex-wrap" => "wrap"
//        ]
//    ],true)
//],true);

$number = $view->show("inc.text",[
    "text" => "№",
    "style" => [
        "height" => "100%",
        "justify-content" => "center",
        "align-items" => "flex-end",
        "font-size" => "17px",
        "margin-right" => "4px"
    ] + $textStyle
],true);

//$title = $view->show("inc.div",[
//    "type" => "row",
//    "style" => [
//        "margin-bottom" => "20px",
//        "flex-wrap" => "wrap"
//    ],
//    "content" => $view->show("inc.text",[
//        "text" => ($params["dnum"]) ? $number.$params["dnum"] : "",
//        "style" => [
//            "height" => "35px",
//            "align-items" => "space-between"
//        ] + $titleStyle + $textStyle + $labelStyle
//    ],true). $view->show("inc.text",[
//        "text" => $params["clientType"],
//        "style" =>[
//            "height" => "35px",
//            "align-items" => "center"
//        ] + $titleStyle + $textStyle + $labelStyle
//    ],true). $view->show("inc.text",[
//        "text" => "\"{$params["name"]}\"",
//        "style" =>[
//            "height" => "35px",
//            "align-items" => "center"
//        ] + $titleStyle + $textStyle + $labelStyle
//    ],true). $view->show("inc.text",[
//        "text" => "{$params["remark"]}",
//        "style" =>[
//            "height" => "35px",
//            "align-items" => "center"
//        ] + $titleStyle + $textStyle + $labelStyle
//    ],true)
//],true);
        
$title = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-bottom" => "20px",
        "flex-wrap" => "wrap"
    ],
    "content" => $view->show("inc.text",[
        "text" => ($params["dnum"]) ? $number.$params["dnum"] : "",
        "style" => [
            "height" => "35px",
            "align-items" => "space-between"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.input.select_stretch",[
        "id" => "clientType",
        "values" => \Settings\Main::clientType(),
        "value" => $params["clientType"],
        "class" => "readonly",
        "attribute" => [
            "readonly" => "readonly"
        ],
        "style" =>[
            "height" => "35px",
            "align-items" => "center"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "\"",
        "style" => [
            "height" => "35px",
            "align-items" => "space-between",
            "margin" => "0px"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.input.area_stretch",[
        "id" => "name",
        "value" => $params["name"],
        "class" => "readonly",
        "attribute" => [
            "readonly" => "readonly"
        ],
        "style" =>[
//            "height" => "fit-content",
            "max-width" => "500px",
            "align-items" => "center",
            "margin" => "0px"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "\"",
        "style" => [
            "height" => "35px",
            "align-items" => "space-between",
            "margin" => "0px"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.input.text_stretch",[
        "id" => "remark",
        "value" => $params["remark"],
        "class" => "readonly",
        "attribute" => [
            "readonly" => "readonly"
        ],
        "style" =>[
            "height" => "35px",
            "align-items" => "center"
        ] + $titleStyle + $textStyle + $labelStyle
    ],true)
],true);
        
        
if ($posName == "ОСНОВНАЯ"){
    $posType = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "background-color" => "var(--modColor)",
            "border-radius" => "4px",
            "padding" => "3px 8px",
            "color" => "var(--modBGColor)",
            "justify-content" => "center",
            "align-items" => "center",
            "text-align" => "center",
            "margin-right" => "20px",
            "height" => "30px"
        ],
        "content" => $posName
    ],true);
}   
else{
    $posType = $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "border-radius" => "4px",
            "padding" => "3px 8px",
            "color" => "var(--modColor)",
            "justify-content" => "center",
            "align-items" => "center",
            "text-align" => "center",
            "margin-right" => "20px",
            "height" => "30px",
            "font-size" => "20px"
        ] + $textStyle,
        "content" => $posName
    ],true);
}

        
$titleContainer = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between"
    ],
    "content" => $title. $posType
],true);

$body =  $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
    ],
    "content" => $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "flex-grow" => "1"
        ],
        "content" => $leftContent
    ],true). $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "400px"
        ],
        "content" => $rightContent
    ],true)
],true);       
        
$acceptBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "130px",
        "justify-content" => "flex-end",
        "align-items" => "center",
        "height" => "35px"
    ] + $buttonStyle,
    "class" => "acceptBlock hidden",
    "content" => $view->show("buttons.close",[
        "onclick" => "modifyClientCancel(this)",
        "style" => [
            "margin-right" => "25px"
        ]
    ],true).$view->show("buttons.accept",[
        "onclick" => "modifyClientAccept(this)",
    ],true)
],true);

if (!$params["dnum"]){
    $contractButton = $view->show("buttons.normal",[
        "text" => "Заключить договор",
        "onclick" => "showContractForm(this)",
        "style" => [
            "width" => "180px"
        ] + $buttonStyle
    ],true);
    $changeDnumButton = "";
    $removeContractButton = "";
    $userInfoButton = "";
}
else{
    $removeContractButton = $view->show("buttons.normal",[
        "text" => "Откат",
        "onclick" => "acceptMsg(removeContract,this,`{$params["id"]}`)",
        "style" => [
            "width" => "130px"
        ] + $buttonStyle
    ],true);
    $changeDnumButton = $view->show("buttons.normal",[
        "text" => "Изменить номер",
        "onclick" => "showChangeDnumForm(this)",
        "style" => $buttonStyle
    ],true);
    $userInfoButton = $view->show("buttons.normal",[
        "text" => "Карточка абонента",
        "onclick" => "showUserCard(this)",
        "style" => $buttonStyle
    ],true);
    $contractButton = "";
    
}

if ($currentBlock["blockStart"]){
    $blockImage = "/_modules/orkkNew/img/lock.png";
    $blockColor = "orange";
}
else{
    $blockImage = "/_modules/orkkNew/img/unlock.png";
    $blockColor = "normal";
}
if (($params["dnum"]) && ($params["clientStatus"] == "Подключен")){
    $blockButton = $view->show("buttons.{$blockColor}",[
        "text" => $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "justify-content" => "center"
            ],
            "content" => $view->show("inc.text",[
                "text" => "Блок",
                "style" => [
                    "margin-right" => "5px",
                    "height" => "100%",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "text-align" => "center",
                    "color" => "inherit"
                ]
            ],true). $view->show("inc.img",[
                "src" => $blockImage,
                "style" => [
                ],

            ],true)
        ],true),
        "onclick" => "showClientBlockForm(this)",
        "style" => $buttonStyle
    ],true);
}
else{
    $blockButton = "";
}

if ($params["renewDate"]){
    $renewButton = "orange";
}
else{
    $renewButton = "normal";
}

if (($params["dnum"]) && ($params["clientStatus"] != "Переоформлен") &&($params["clientStatus"] == "Подключен")){
    $renewButton = $view->show("buttons.{$renewButton}",[
        "text" => "Переоформить",
        "onclick" => "showRenewClientForm(this)",
        "style" => $buttonStyle
    ],true);
}
else{
    $renewButton = "";
}
if ($params["clientStatus"] == "Ожидает подключение"){
    $connectButton = $view->show("buttons.normal",[
        "text" => "Подключить",
        "onclick" => "showConnectClientForm(this)",
        "style" => $buttonStyle
    ],true);
}
else{
    $connectButton = "";
}

if (isset($params["disconnectType"]) && ($params["disconnectType"])){
    $buf = "orange";
}
else{
    $buf = "normal";
}

if ($params["clientStatus"] == "Подключен"){
    $disconnectButton = $view->show("buttons.{$buf}",[
        "text" => "Отключить",
        "onclick" => "showDisconnectClientForm(this)",
        "style" => $buttonStyle
    ],true);
    
}
else{
    $disconnectButton = "";
    
}

$serviceTypeButton = $view->show("buttons.normal",[
    "text" => "Виды услуг",
    "onclick" => "showServiceTypeForm(this)",
    "style" => $buttonStyle
],true);


$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "flex-end",
        "margin-top" => "30px",
        "flex-wrap" => "wrap"
    ],
    "content" => $view->show("buttons.normal",[
        "text" => "Редактировать",
        "onclick" => "modifyClient(this)",
        "style" => [
            "width" => "130px"
        ] + $buttonStyle
    ],true).  $acceptBlock. $serviceTypeButton. $userInfoButton. $changeDnumButton. $connectButton. $disconnectButton. $contractButton. $removeContractButton. $blockButton. $renewButton. $view->show("buttons.red",[
        "text" => $view->show("inc.div",[
            "type" => "row",
            "content" => $view->show("inc.text",[
                "text" => "Удалить",
                "style" => [
                    "margin-right" => "5px",
                    "height" => "100%",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "text-align" => "center",
                    "color" => "var(--modBGColor)"
                ]
            ],true). $view->show("inc.img",[
                "src" => "/_modules/orkkNew/img/lock.png",
                "style" => [
                ],
                
            ],true)
        ],true),
        "onclick" => "checkAccess('onlyLeader',acceptMsg,deleteClient,this)",
        "style" => $buttonStyle
    ],true)
],true);   



$vars = $view->show("inc.vars",[
    "vars" => [
        "id" => $params["id"],
        "dnum" => $params["dnum"]
    ]    
],true);



/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $titleContainer. $body. $buttonBlock. $vars,
    "class" => "editedContainer",
    "style" => [
        "flex-grow" => "1"
    ]
]);


/*Переменные--------------------------------------------------*/



/*--------------------------------------------------*/





















                                                                                        

