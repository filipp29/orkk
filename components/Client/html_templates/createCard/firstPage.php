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
    "Обход/Диалог" => "Обход/Диалог",
    "Ведутся переговоры" => "Ведутся переговоры",
    "Отказ НЦ" => "Отказ НЦ",
    "Не известен" => "Не известен"
];

$rows = [
    [
        "column" => "3",
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Тип учреждения:",
            "style" => $textStyle
        ],true). $view->show("inc.input.select",[
            "id" => "clientType",
            "values" => $settings::clientType()
        ],true). $view->show("inc.text",[
            "text" => "Наименование:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => "name",
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "Название компании или предпринимателя",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.text",[
            "text" => "Примечание:",
            "style" => $textStyle
        ],true).$view->show("inc.div",[
            "type" => "column",
            /*--------------------------------------------------*/
            "content" => $view->show("inc.input.text",[
                "id" => "remark",
                "style" => [
                    "width" => "100%"
                ]
            ],true). $view->show("inc.text",[
                "text" => "Наименование о компании или предпринимателя",
                "style" => $commentStyle
            ],true),
            /*--------------------------------------------------*/
        ],true)
    ],
    
    /*--------------------------------------------------*/
    
    [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Адрес:",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "city",
            "values" => $settings::cityList()
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "streetType",
            "values" => $settings::streetType()
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => "street",
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "Фактический адрес организации, по которому предполагается оказание услуги",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.input.select",[
            "id" => "buildingType",
            "values" => $settings::buildingType(),
            "style" => [
                "margin-left" => "8px"
            ]
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "building",
            "style" => [
                "width" => "70px"
            ]
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "flatType",
            "values" => $settings::flatType()
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "flat",
            "style" => [
                "width" => "70px"
            ]
        ],true),
    ],
    
    [
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
                "values" => $settings::district()
            ],true).$view->show("inc.text",[
                "text" => "Район",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    
    /*--------------------------------------------------*/
    
    
    
    [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("buttons.plus",[
            "onclick" => "addContact(this)",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true).$view->show("inc.text",[
            "text" => "Контакты",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "row",
            "content" => $view->show("inc.input.phone",[
                "id" => "contact_phone",
                "style" => [
                    "flex-grow" => "1",
                    "margin-right" => "10px"
                ]
            ],true).$view->show("inc.input.checkbox",[
                "id" => "contact_main",
                "text" => "Основной",
                "style" => [
                    "margin-right" => "10px",
                    "padding" => "0px 15px"
                ]
            ],true).$view->show("inc.input.checkbox",[
                "id" => "contact_lpr",
                "text" => "ЛПР",
                "style" => [
                    "margin-right" => "10px",
                    "padding" => "0px 15px"
                ]
            ],true).$view->show("inc.input.checkbox",[
                "id" => "contact_eavr",
                "text" => "ЭАВР",
                "style" => [
                    "margin-right" => "10px",
                    "padding" => "0px 15px"
                ]
            ],true),
            "style" => [
                "width" => "100%",
                "justify-content" => "space-between"
            ]
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.text",[
            "text" => "ФИО:",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "contact_name",
            "style" => [
            ]
        ],true).$view->show("inc.text",[
            "text" => "Должность:",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "contact_role",
            "style" => [
            ]
        ],true),
        "class" => "contactContainer"
    ],
    
    /*--------------------------------------------------*/
    
    [
        "column" => 3,
        "content1" => "",
        "content2" => "",
        "content3" => "",
        "class" => "hidden",
        "id" => "contactsBottom"
    ],
    
    /*--------------------------------------------------*/
    
    [
        "column" => "3",
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "Электронная почта:",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => "emailMain",
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "Адрес электронной почты(если имеется)",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => "Статус клиента:",
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "id" => "clientStatus",
                "values" => \Settings\Main::clientStatusFirst(),
                "style" => [
                    "width" => "100%"
                ],
            ],true).$view->show("inc.text",[
                "text" => "Текущий статус клиента, указывающий на его отношение к компании",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => $view->show("inc.input.file",[
            "filePathId" => "filePath",
            "fileNameId" => "fileName",
            "style" => [
                "margin-left" => "10px"
            ]
        ],true)
    ],
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => "Конкурент:",
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.select",[
                "id" => "competitor",
                "values" => $settings::competitor(),
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "Оператор, услугами которого в данный момент пользуется клиент",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
    ],
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => "Тариф конкурента:",
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "content" => $view->show("inc.input.text",[
                "id" => "competitorAmount",
                "style" => [
                    "width" => "100%"
                ],
            ],true).$view->show("inc.text",[
                "text" => "Сумма тарифа конткурента",
                "style" => $commentStyle
            ],true),
            "style" => [
                "width" => "100%"
            ]
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
    ],
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => "Дополнительные услуги:",
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.input.select",[
            "values" => [
                "0" => "Не требуются",
                "1" => "Требуются",
                
            ],
            "attribute" => [
                "onchange" => "extraServiceFlagToggle(this)"
            ],
            "style" => [
                "width" => "100%"
            ]
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
        
    ]
];

foreach($settings::extraService() as $key => $value){
    $rows[] = [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => $value,
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.input.radio",[
            "id" => $key,
            "divType" => "row",
            "values" => [
                "0" => "Не требуется",
                "1" => "Требуется"
            ],
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => "",
        
        /*--------------------------------------------------*/
        
        "class" => "extraService hidden"
        
    ];
}



$rows = array_merge($rows,[
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => $view->show("inc.text",[
            "text" => "Ответственный менеджер",
            "style" => $textStyle
        ],true),
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.select",[
                "id" => "manager",
                "style" => [
                    "width" => "100%"
                ],
                "values" => $settings::managerList(),
                "value" => $_COOKIE["login"]
            ],true).$view->show("inc.text",[
                "text" => "Менеджер, ответственный за ведение клиента",
                "style" => $commentStyle
            ],true)
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
    ],
    [
        "column" => 3,
        
        /*--------------------------------------------------*/
        
        "content1" => "",
        
        /*--------------------------------------------------*/
        
        "content2" => $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "justify-content" => "flex-end",
                "width" => "100%"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "Продолжить",
                "onclick" => "createSecondPage(this)"
            ],true)
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
        
    ],
    [
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
                "text" => "Отмена",
                "onclick" => "closeTab(this)",
                "style" => [
                    "margin-right" => "10px"
                ]
            ],true).$view->show("buttons.normal",[
                "text" => "Сохранить",
//                "onclick" => "saveClientWithoutComment(this,true,onCloseTab(this))",
                "onclick" => "saveClientWithoutComment(this,true,onCreateClient(this))",
            ],true)
        ],true),
        
        /*--------------------------------------------------*/
        
        "content3" => ""
        
    ]
]);

//$view->show("inc.div",[
//    "type" => "row",
//    "style" => [
//        "margin" => "15px 0px 45px 15px"
//    ],
//    "content" => $view->show("buttons.close",[
//        "style" => [
//            "margin-right" => "15px",
//            "height" => "30px;"
//        ],
//        "onclick" => "back()"
//    ],true). $view->show("inc.text",[
//        "text" => "Создание карточки",
//        "style" => [
//            "height" => "30px",
//            "font-size" => "24px"
//        ] + $textStyle
//    ],true)
//]);

foreach($rows as $row){
    $viewClient->show("createCard.row",$row);
}


$viewClient->show("createCard.row",[
    "column" => 3,
    /*--------------------------------------------------*/
    "content1" => $view->show("buttons.minus",[
        "onclick" => "removeContact(this)",
        "style" => [
            "margin-right" => "10px"
        ]
    ],true),
    /*--------------------------------------------------*/
    "content2" => $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.input.phone",[
            "id" => "contact_phone",
            "style" => [
                "flex-grow" => "1",
                "margin-right" => "10px"
            ]
        ],true).$view->show("inc.input.checkbox",[
            "id" => "contact_main",
            "text" => "Основной",
            "style" => [
                "margin-right" => "10px",
                "padding" => "0px 15px"
            ]
        ],true).$view->show("inc.input.checkbox",[
            "id" => "contact_lpr",
            "text" => "ЛПР",
            "style" => [
                "margin-right" => "10px",
                "padding" => "0px 15px"
            ]
        ],true).$view->show("inc.input.checkbox",[
            "id" => "contact_eavr",
            "text" => "ЭАВР",
            "style" => [
                "margin-right" => "10px",
                "padding" => "0px 15px"
            ]
        ],true),
        "style" => [
            "width" => "100%",
            "justify-content" => "space-between"
        ]
    ],true),
    /*--------------------------------------------------*/
    "content3" => $view->show("inc.text",[
        "text" => "ФИО:",
        "style" => $textStyle
    ],true).$view->show("inc.input.text",[
        "id" => "contact_name",
        "style" => [
        ]
    ],true).$view->show("inc.text",[
        "text" => "Должность:",
        "style" => $textStyle
    ],true).$view->show("inc.input.text",[
        "id" => "contact_role",
        "style" => [
        ]
    ],true),
    /*--------------------------------------------------*/
    "class" => "hidden",
    "id" => "contactContainerTemplate"
]);

$viewClient->show("script");
$view->show("inc.var",[
    "key" => "title",
    "value" => "Создание карточки"
]);
$view->show("inc.var",[
    "key" => "tabTitle",
    "value" => "Создание карточки"
]);


















