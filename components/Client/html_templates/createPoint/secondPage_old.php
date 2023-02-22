<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$buf = new \Client\Controller();
$viewClient = $buf->getView();
unset($buf);
$settings = new \Settings\Main();

$commentStyle = [
    "font-style" => "italic",
    "font-size" => "13px",
    "height" => "auto"
];

$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];

$statusList = [
    "Не известен" => "Не известен",
    "Обход/Диалог" => "Обход/Диалог",
    "Ведутся переговоры" => "Ведутся переговоры",
    "Отказ НЦ" => "Отказ НЦ",
    
];

$rows = [
    "наименование" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Статус клиента:",
            "style" => $textStyle
        ],true). $view->show("inc.text",[
            "text" => "ожидает подключения",
            "style" => [
                "color" => "#1E90FF"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "Наименование",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "nameOld",
                "style" => [
                    "width" => "100%"
                ]
            ],true). $view->show("inc.text",[
                "text" => "Название компании или предпринимателя",
                "style" => $commentStyle,
                
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.div",[
            "type" => "column",
            
            "content" => $view->show("inc.div",[
                "type" => "row",
                
                "content" => $view->show("inc.text",[
                    "text" => "№ договора",
                    "style" => $textStyle,
                ],true). $view->show("inc.input.text",[
                    "id" => "dnum",
                    "style" => [
                        "width" => "150px"
                    ],
                    "attribute" => [
                        "readonly" => "readonly"
                    ],
                    "value" => $dnum
                ],true)
                
            ],true). $view->show("inc.text",[
                "text" => "Номер договора, заключенного с клиентом",
                "style" => [
                    "margin-left" => "10px"
                ] + $commentStyle
            ],true)
            
        ],true)
        
    ],
    
    /*--------------------------------------------------*/
    
    "юр.адрес" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Юридический адрес:",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "legalCity",
            "value" => $legalCity
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "legalStreetType",
            "values" => $settings::streetType(),
            "value" => $legalStreetType
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => "legalStreet",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $legalStreet
            ],true).$view->show("inc.text",[
                "text" => "Юридический адрес организации",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.input.select",[
            "id" => "legalBuildingType",
            "values" => $settings::buildingType(),
            "style" => [
                "margin-left" => "8px"
            ],
            "value" => $legalBuildingType
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "legalBuilding",
            "style" => [
                "width" => "70px"
            ],
            "value" => $legalBuilding
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "legalFlatType",
            "values" => $settings::flatType(),
            "value" => $legalFlatType
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "legalFlat",
            "style" => [
                "width" => "70px"
            ],
            "value" => $legalFlat
        ],true),
    ],
    
    /*--------------------------------------------------*/
    
    "Вид услуги" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Вид услуги:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "serviceType",
                "values" => \Settings\Main::serviceType(),
                "value" => ($default) ? $serviceType : "",
            ],true).$view->show("inc.text",[
                "text" => "Вид услуги",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "сумма тарифа" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Сумма тарифа:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "amount",
                "style" => [
                    "width" => "100%"
                ],
                "value" => ($default) ? $amount  : "",
            ],true).$view->show("inc.text",[
                "text" => "Сумма списаний по тарифу",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "ширина канала" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Ширина канала:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "speed",
                "style" => [
                    "width" => "100%"
                ],
                "value" => ($default) ? $speed  : "",
            ],true).$view->show("inc.text",[
                "text" => "Ширина канала, предоставляемого клиенту",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "сумма подключения" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Сумма подключения:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "connectSum",
                "style" => [
                    "width" => "100%"
                ],
                "value" => ($default) ? $connectSum  : "",
            ],true).$view->show("inc.text",[
                "text" => "Сумма подключения",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "оборудование" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Оборудование:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "values" => ["" => ""] + \Settings\Client::hardwareTemplate(),
                "attribute" => [
                    "onchange" => "addSelectToInput(this.closest(`.page`),this,`hardware`)",
                ],
                "style" => [
                    "margin-bottom" => "10px"
                ]
            ],true).$view->show("inc.input.area",[
                "id" => "hardware",
                "style" => [
                    "width" => "100%",
                    "height" => "25px"
                ],
                "attribute" => [
                    "onkeyup" => "inputAreaAutoSize(this)"
                ],
                "value" => ($default) ? $hardware  : "",
            ],true).$view->show("inc.text",[
                "text" => "Указывается оборудование при подключении",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "дата заключения" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Дата заключения договора:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.date",[
                "id" => "contractDate",
                "style" => [
                    "width" => "100%"
                ],
                "value" => isset($contractDate) ? $contractDate : ""
            ],true).$view->show("inc.text",[
                "text" => "Дата заключения договора",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "бин" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "БИН/ИИН:",
            "style" => $textStyle,
            "attribute" => [
                "id" => "binType",
                
            ]
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "bin",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $bin,
                "readonly" => "readonly",
                "attribute" => [
                    "onkeyup" => "checkBin(this)"
                ]
            ],true).$view->show("inc.text",[
                "text" => "БИН/ИИН клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "iban" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "IBAN:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "iban",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $iban,
                "readonly" => "readonly",
                "attribute" => [
                    "onkeyup" => "checkIban(this)"
                ]
            ],true).$view->show("inc.text",[
                "text" => "IBAN клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "бик" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "БИК:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "values" => ["" => ""] + \Settings\Client::bikTemplate(),
                "attribute" => [
                    "onchange" => "bikSelect(this,`page`)",
                ],
                "style" => [
                    "margin-bottom" => "10px"
                ]
            ],true).$view->show("inc.input.text",[
                "id" => "bik",
                "style" => [
                    "width" => "100%",
                    "height" => "25px"
                ],
                "value" => $bik
            ],true).$view->show("inc.text",[
                "text" => "БИК клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "кбе" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "КБе:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "kbe",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $kbe,
                "readonly" => "readonly"
            ],true).$view->show("inc.text",[
                "text" => "КБе клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "банк" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Банк:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "bank",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $bank,
                "readonly" => "readonly"
            ],true).$view->show("inc.text",[
                "text" => "Банк клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "работают ночью" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Работают ночью:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "nightWork",
                "values" => [
                    "0" => "Нет",
                    "1" => "Да",
                ],
                "value" => ($default) ? $nightWork  : "",
            ],true).$view->show("inc.text",[
                "text" => "Работают ночью",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "район" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Район:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "district",
                "divType" => "row",
                "values" => $settings::district(),
                "value" => ($default) ? $district  : "",
            ],true).$view->show("inc.text",[
                "text" => "Район",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "статический ip" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Статический IP:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "staticIp",
                "divType" => "row",
                "values" => [
                    "0" => "Не требуется",
                    "1" => "Требуется"
                ],
                "value" => ($default) ? $staticIp  : "",
            ],true).$view->show("inc.text",[
                "text" => "Требуется ли клиенту статический IP",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "тип расчета" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Тип рассчета:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "payType",
                "divType" => "row",
                "values" => \Settings\Main::payType(),
                "value" => ($default) ? $payType  : "",
            ],true).$view->show("inc.text",[
                "text" => "Тип рассчета",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "дата активации" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Дата активации:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.date",[
                "id" => "activateDate",
                "style" => [
                    "width" => "100%"
                ],
                "value" => ($default) ? $activateDate  : "",
            ],true).$view->show("inc.text",[
                "text" => "Дата активации",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "тип подключения" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Тип подключения:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "connectType",
                "divType" => "row",
                "values" => $settings::connectType(),
                "value" => ($default) ? $connectType  : "",
            ],true).$view->show("inc.text",[
                "text" => "Тип подключения клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "канал привлечения" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Канал привлечения:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "attractType",
                "divType" => "row",
                "values" => $settings::attractType(),
                "value" => ($default) ? $attractType  : "",
            ],true).$view->show("inc.text",[
                "text" => "Канал привлечения клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "местонахождение документов" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Местонахождение документов:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "docPlacement",
                "divType" => "row",
                "values" => $settings::docPlacement()
            ],true).$view->show("inc.text",[
                "text" => "Местонахождение документов",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "электронная почта для эавр" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Электронная почта для ЭАВР:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "emailEavr",
                "style" => [
                    "width" => "100%"
                ],
                "value" => $emailEavr
            ],true).$view->show("inc.text",[
                "text" => "Email для отправки ЭАВР",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "удостоверение" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Удостоверение личности №:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "100%"
                ],
                "content" => $view->show("inc.input.text",[
                    "id" => "udoNumber",
                    "style" => [
                        "width" => "120px",
                        "flex-grow" => "1"
                    ],
                    "attribute" => [
                        "oninput" => "checkUdoNumber(this)"
                    ],
                    "value" => $udoNumber
                ],true). $view->show("inc.text",[
                    "text" => "выдан",
                    "style" => $textStyle
                ],true).$view->show("inc.input.select",[
                    "id" => "udoGiver",
                    "values" => \Settings\Main::udoGiver(),
                    "style" => [
                        "width" => "120px"
                    ],
                    "value" => $udoGiver
                ],true). $view->show("inc.text",[
                    "text" => "от",
                    "style" => $textStyle
                ],true).$view->show("inc.input.date",[
                    "id" => "udoDate",
                    "style" => [
                        "width" => "120px"
                    ],
                    "value" => $udoDate
                ],true)
            ],true).$view->show("inc.text",[
                "text" => "Удостоверение личности",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ]
    
];

$keys = [
    "наименование",
    "юр.адрес",
    "Вид услуги",
    "тип подключения",
    "сумма тарифа",
    "ширина канала",
    "сумма подключения",
    "оборудование",
    "дата заключения",
    "бин",
    "iban",
    "бик",
    "кбе",
    "банк",
    "удостоверение",
    "работают ночью",
    "район",
    "статический ip",
    "тип расчета",
    "дата активации",
    "канал привлечения",
    "местонахождение документов",
    "электронная почта для эавр"
];

foreach($keys as $key){
    $viewClient->show("createCard.row",$rows[$key]);
}


$viewClient->show("createCard.row",[
    "column" => 3,
    /*--------------------------------------------------*/
    "content1" => "",
    /*--------------------------------------------------*/
    "content2" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "justify-content" => "flex-end"
        ],
        "content" => $view->show("buttons.normal",[
            "text" => "Отменить",
            "onclick" => "cancelSecondPage(this,true)",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "Продолжить",
            "onclick" => "createThirdPage(this,{$newPoint})"
        ],true)
    ],true),
    /*--------------------------------------------------*/
    "content3" => ""
]);

/*--------------------------------------------------*/

$vars = [
    "clientStatus" => isset($new_clientStatus) ? $new_clientStatus : "Ожидает подключение",
    "renewFlag" => isset($renewFlag) ? $renewFlag : "",
    "renew_date" => isset($renew_date) ? $renew_date : "",
    "renew_dnum" => isset($renew_dnum) ? $renew_dnum : "",
    "renew_filePath" => isset($renew_filePath) ? $renew_filePath : "",
    "renew_fileName" => isset($renew_fileName) ? $renew_fileName : "",
    "renew_type" => isset($renew_type) ? $renew_type : "",
    "oldId" => isset($oldId) ? $oldId : "",
    "oldDnum" => isset($oldDnum) ? $oldDnum : "",
    "oldName" => isset($oldName) ? $oldName : "",
    "oldClientType" => isset($oldClientType) ? $oldClientType : ""
];            
            
$view->show("inc.vars",[
    "vars" => $vars
]);

















