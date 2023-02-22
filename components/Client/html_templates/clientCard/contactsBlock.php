<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$inputStyle = [
    "font-size" => "14px",
    "height" => "21px",
    "flex-grow" => "1"
];


/*Инициализация--------------------------------------------------*/

$body = "";
$first = true;
foreach($contacts as $value){
    $pattern = "+_(___) ___-__-__";
    $n = 0;
    $phone = "";
    for($i = 0; $i < strlen($pattern); $i++){
        if ($pattern[$i] != "_"){
            $phone .= $pattern[$i];
        }
        else {
            if (isset($value["phone"][$n])){
                $phone .= $value["phone"][$n];
                $n++;
            }
            else{
                $phone .= "_";
            }
        }
    }
    if ($first){
        $phoneButton = $view->show("buttons.plus",[
            "onclick" => "addContact(this)",
            "class" => "hidden phoneButton",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true);
        $first = false;
    }
    else{
        $phoneButton = $view->show("buttons.minus",[
            "onclick" => "removeContact(this)",
            "class" => "hidden phoneButton",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true);
    }
//    $phone = $value["phone"];
    $role = ($value["role"]) ? $value["role"] : "";
    $name = $value["name"];
    $lpr = isset($value["lpr"]) ? $value["lpr"] : "";
    $main = isset($value["main"]) ? $value["main"] : "";
    $eavr = isset($value["eavr"]) ? $value["eavr"] : "";
    $firstRow = $view->show("inc.img",[
        "src" => "/_modules/orkkNew/img/phone.png",
        "class" => "phoneImage",
    ],true). $phoneButton. $view->show("inc.input.phone",[
        "id" => "contact_phone",
        "style" => [
            "width" => "170px",
            "margin-right" => "10px",
            "font-size" => "18px",
            "font-weight" => "bolder",
            "padding" => "4px"
        ],
        "class" => "readonly",
        "attribute" => [
            "readonly" => "readonly"
        ],
        "value" => $phone
    ],true).$view->show("inc.input.checkbox",[
        "id" => "contact_main",
        "text" => "Осн",
        "style" => [
            "margin-right" => "10px",
            "padding" => "0px 7px",
            "font-size" => "12px"
        ],
        "class" => "readonly",
        "attribute" => [
            "disabled" => "disabled"
        ],
        "checked" => isset($main) ? $main : ""
    ],true).$view->show("inc.input.checkbox",[
        "id" => "contact_lpr",
        "text" => "ЛПР",
        "style" => [
            "margin-right" => "10px",
            "padding" => "0px 7px",
            "font-size" => "12px"
        ],
        "class" => "readonly",
        "attribute" => [
            "disabled" => "disabled"
        ],
        "checked" => isset($lpr) ? $lpr : ""
    ],true).$view->show("inc.input.checkbox",[
        "id" => "contact_eavr",
        "text" => "ЭАВР",
        "style" => [
            "margin-right" => "10px",
            "padding" => "0px 7px",
            "font-size" => "12px"
        ],
        "class" => "readonly",
        "attribute" => [
            "disabled" => "disabled"
        ],
        "checked" => isset($eavr) ? $eavr : ""
    ],true);
    $secondRow = $view->show("inc.input.text_stretch",[
            "id" => "contact_name",
            "style" => [
                "margin" => "0px 10px 0px 35px",
                "font-size" => "18px",
                "font-weight" => "bolder",
            ],
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "value" => $name
        ],true). $view->show("inc.input.text_stretch",[
            "id" => "contact_role",
            "style" => [
                "margin-right" => "10px",
                "font-size" => "18px",
                "font-weight" => "bolder",
            ],
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "value" => $role
        ],true);
    $body .= $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "100%",
            "margin-bottom" => "5px",
            "padding-bottom" => "5px",
            "border-bottom" => "1px var(--modColor_darkest) dashed",
        ],
        "class" => "contactContainer",
        "content" => $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "margin-bottom" => "5px",
                "padding-bottom" => "5px"
            ],
            "content" => $firstRow
        ],true).$view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "margin-bottom" => "5px",
                "padding-bottom" => "5px"
            ],
            "content" => $secondRow
        ],true)
    ],true);
    
    
}

$body .= $view->show("inc.div",[
    "type" => "row",
    "id" => "contactsBottom",
    "class" => "hidden"
],true);

/*--------------------------------------------------*/

$keyList = [
    "emailMain" => "Основной email",
    "emailEavr" => "Email для ЭАВР",
    "iban" => "iban",
    "bik" => "Бик",
    "bank" => "Банк",
    "kbe" => "КБе",
//    "legalCity" => "Адрес: город",
//    "legalStreet" => "Юр. улица",
//    "legalBuilding" => "Юр. дом",
//    "legalFlat" => "Юр. квартира"
];

if (in_array($params["clientType"], [
    "ФЛ",
    "ИП"
])){
    $keyList += [
        "udoNumber" => "№ удостоверения",
        "udoGiver" => "Выдан",
        "udoDate" => "Дата выдачи"
    ];
}




foreach($keyList as $key => $val){
    
    switch ($key):
        case "bik":
            $input = $view->show("inc.div",[
                "type" => "column",
                "content" => $view->show("inc.input.select",[
                    "values" => ["" => ""] + \Settings\Client::bikTemplate(),
                    "class" => "inputTemplate hidden",
                    "attribute" => [
                        "onchange" => "bikSelect(this,`contactsForm`)",
                    ],
                    "style" => [
                            "margin" => "5px 0px"
                    ]
                ],true).$view->show("inc.input.text",[
                    "id" => "bik",
                    "style" => [
//                        "max-width" => "205px",
                        "height" => "21px",
                        "font-size" => "14px"
                    ],
                    "value" => $params[$key],
                    "class" => "readonly",
                    "attribute" => [
                        "readonly" => "readonly"
                    ]
                ],true)
            ],true);
            break;
        case "legalFlat":
            $input = $view->show("inc.input.select_stretch",[
                "id" => "legalFlatType",
                "values" => ["" => ""] + \Settings\Main::flatType(),
                "value" => $params["legalFlatType"],
                "class" => "readonly",
                "attribute" => [
                    "disabled" => "disabled"
                ],
                "style" => [
                    "font-size" => "14px",
                    "height" => "21px",
                ]
            ],true).$view->show("inc.input.text",[
                "id" => "legalFlat",
                "value" => $params[$key],
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ],
                "style" => [
//                    "max-width" => "205px"
                ] + $inputStyle
            ],true);
            break;
        case "legalBuilding":
            $input = $view->show("inc.input.select_stretch",[
                "id" => "legalBuildingType",
                "values" => ["" => ""] + \Settings\Main::buildingType(),
                "value" => $params["legalBuildingType"],
                "class" => "readonly",
                "attribute" => [
                    "disabled" => "disabled"
                ],
                "style" => [
                    "font-size" => "14px",
                    "height" => "21px",
                ]
            ],true).$view->show("inc.input.text",[
                "id" => "legalBuilding",
                "value" => $params[$key],
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ],
                "style" => [
//                    "max-width" => "205px"
                ] + $inputStyle
            ],true);
            break;
        case "legalStreet":
            $input = $view->show("inc.input.select_stretch",[
                "id" => "legalStreetType",
                "values" => ["" => ""] + \Settings\Main::streetType(),
                "value" => $params["legalStreetType"],
                "class" => "readonly",
                "attribute" => [
                    "disabled" => "disabled"
                ],
                "style" => [
                    "font-size" => "14px",
                    "height" => "21px",
                ]
            ],true).$view->show("inc.input.text",[
                "id" => "legalStreet",
                "value" => $params[$key],
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ],
                "style" => [
//                    "max-width" => "205px"
                ] + $inputStyle
            ],true);
            break;
        case "udoGiver":
            $input = $view->show("inc.input.select_stretch",[
                "id" => "udoGiver",
                "values" => ["" => ""] + \Settings\Main::udoGiver(),
                "value" => $params["udoGiver"],
                "class" => "readonly",
                "attribute" => [
                    "disabled" => "disabled"
                ],
                "style" => [
                    "font-size" => "14px",
                    "height" => "21px",
                ]
            ],true);
            break;
        case "udoDate":
            $input = $view->show("inc.input.date",[
                "id" => "udoDate",
                "style" => [
//                    "max-width" => "205px"
                ] + $inputStyle,
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ],
                "value" => $params["udoDate"]
            ],true);
            break;
        default:
            $input = $view->show("inc.input.text",[
                "id" => $key,
                "style" => [
//                    "max-width" => "205px"
                ] + $inputStyle,
                "class" => "readonly",
                "attribute" => [
                    "readonly" => "readonly"
                ],
                "value" => $params[$key]
            ],true);
            break;
    endswitch;
    
    
    $label = $view->show("inc.text",[
        "text" => $val,
        "style" => [
            "align-items" => "flex-end"
        ] + $textStyle
    ],true);
    
    $body .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "align-items" => "flex-end"
        ],
        "content" => $label. $input
    ],true);
}

$body .= $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "padding-left" => "10px",
        "height" => "var(--modLineHeight)",
        "flex-wrap" =>"wrap",
        "align-items" => "flex-end"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Адрес:",
        "style" => [
            "align-items" => "flex-end",
            "margin" => "0px 10px 0px 0px"
        ] + $textStyle
    ],true). $view->show("inc.text",[
        "text" => "город",
        "style" => [
            "align-items" => "flex-end",
            "margin-right" => "5px"
        ]
    ],true). $view->show("inc.input.text_stretch",[
        "id" => "legalCity",
        "style" => [
            "flex-grow" => "0"
//                    "max-width" => "205px"
        ] + $inputStyle,
        "class" => "readonly",
        "attribute" => [
            "readonly" => "readonly"
        ],
        "value" => $params["legalCity"]
    ],true). $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "var(--modLineHeight)",
            "align-items" => "flex-end"
        ],
        "content" => $view->show("inc.input.select_stretch",[
            "id" => "legalStreetType",
            "values" => ["" => ""] + \Settings\Main::streetType(),
            "value" => $params["legalStreetType"],
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => [
                "font-size" => "14px",
                "height" => "21px",
                "margin-left" => "7px",
                "margin-right" => "3px"
            ]
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "legalStreet",
            "value" => $params["legalStreet"],
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "flex-grow" => "0"
    //                    "max-width" => "205px"
            ] + $inputStyle
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "var(--modLineHeight)",
            "align-items" => "flex-end"
        ],
        "content" =>  $view->show("inc.input.select_stretch",[
            "id" => "legalBuildingType",
            "values" => ["" => ""] + \Settings\Main::buildingType(),
            "value" => $params["legalBuildingType"],
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => [
                "font-size" => "14px",
                "height" => "21px",
                "margin-left" => "7px",
                "margin-right" => "3px"
            ]
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "legalBuilding",
            "value" => $params["legalBuilding"],
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "flex-grow" => "0"
    //                    "max-width" => "205px"
            ] + $inputStyle
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "var(--modLineHeight)",
            "align-items" => "flex-end"
        ],
        "content" =>  $view->show("inc.input.select_stretch",[
            "id" => "legalFlatType",
            "values" => ["" => ""] + \Settings\Main::flatType(),
            "value" => $params["legalFlatType"],
            "class" => "readonly",
            "attribute" => [
                "disabled" => "disabled"
            ],
            "style" => [
                "font-size" => "14px",
                "height" => "21px",
                "margin-left" => "7px",
                "margin-right" => "3px"
            ]
        ],true).$view->show("inc.input.text_stretch",[
            "id" => "legalFlat",
            "value" => $params["legalFlat"],
            "class" => "readonly",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "flex-grow" => "0"
    //                    "max-width" => "205px"
            ] + $inputStyle
        ],true) 
    ],true)
],true);

$title = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "border-bottom" => "1px var(--modColor_darkest) dashed",
        "margin-bottom" => "10px",
        "padding-bottom" => "10px",
        "justify-content" => "space-between"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Контакты"
    ],true). $view->show("buttons.normal",[
        "text" => "Редактировать",
        "onclick" => "modifyContactsForm(this)",
        "style" => [
            "width" => "112px",
            "font-size" => "12px",
            "height" => "25px",
            "min-width" => "50px",
            "border-radius" => "7px"
        ]
    ],true). $view->show("inc.div",[
        "type" => "row",
        "id" => "contactAcceptBlock",
        "style" =>  [
            "width" => "85px",
            "justify-content" => "space-between",
            "height" => "25px",
            "padding" => "0px 8px"
        ],
        "class" => "hidden",
        "content" => $view->show("buttons.close",[
            "onclick" => "modifyContactsFormCancel(this)"
        ],true). $view->show("buttons.accept",[
            "onclick" => "modifyContactsFormAccept(this)"
        ],true)
    ],true)
],true);


$vars = [
    "id" => $params["id"]
];

/*Отображение--------------------------------------------------*/


$view->show("inc.div",[
    "type" => "column",
    "class" => "contactsForm",
    "style" => [
        "margin-left" => "15px",
//        "border-left" => "1px var(--modColor_darkest) dashed",
        "background-color" => "#f2f2f2",
        "padding" => "10px",
        "width" => "400px"
    ],
    "content" => $title.$body. $view->show("inc.vars",[
        "vars" => $vars
    ],true)
]);



//$view->show("inc.div",[
//    "type" => "row",
//    "class" => "hidden",
//    "id" => "contactContainerTemplate",
//    "style" => [
//        "width" => "100%",
//        "margin-bottom" => "5px",
//        "border-bottom" => "1px var(--modColor_darkest) dashed",
//        "padding-bottom" => "5px"
//    ],
//    "content" => $view->show("buttons.minus",[
//        "onclick" => "removeContact(this)",
//        "class" => "phoneButton",
//        "style" => [
//            "margin-right" => "10px"
//        ]
//    ],true). $view->show("inc.input.phone",[
//        "id" => "contact_phone",
//        "style" => [
//            "width" => "170px",
//            "margin-right" => "10px",
//            "font-size" => "18px",
//            "font-weight" => "bolder",
//            "padding" => "4px"
//        ]
//        
//    ],true). $view->show("inc.input.text_stretch",[
//        "id" => "contact_name",
//        "style" => [
//            "margin-right" => "10px",
//            "font-size" => "18px",
//            "font-weight" => "bolder",
//            "padding" => "4px"
//        ]
//    ],true). $view->show("inc.input.text_stretch",[
//        "id" => "contact_role",
//        "style" => [
//            "margin-right" => "10px",
//            "font-size" => "18px",
//            "font-weight" => "bolder",
//            "paddnig" => "4px"
//        ]
//    ],true). $view->show("inc.input.select",[
//        "id" => "contact_type",
//        "divType" => "row",
//        "values" => [
//            "main" => "Основной",
//            "lpr" => "ЛПР"
//        ],
//
//        "style" => [
//            "font-size" => "18px",
//            "font-weight" => "bolder",
//            "paddnig" => "4px"
//        ],
//    ],true)
//]);


$phoneButton = $view->show("buttons.minus",[
    "onclick" => "removeContact(this)",
    "class" => "phoneButton",
    "style" => [
        "margin-right" => "10px"
    ]
],true);
//    $phone = $value["phone"];
$role = ($value["role"]) ? $value["role"] : "";
$name = $value["name"];
$firstRow =  $phoneButton. $view->show("inc.input.phone",[
    "id" => "contact_phone",
    "style" => [
        "width" => "170px",
        "margin-right" => "10px",
        "font-size" => "18px",
        "font-weight" => "bolder",
        "padding" => "4px"
    ],
],true).  $view->show("inc.input.checkbox",[
    "id" => "contact_main",
    "text" => "Осн",
    "style" => [
        "margin-right" => "10px",
        "padding" => "0px 7px",
        "font-size" => "12px"
    ],
    
],true).$view->show("inc.input.checkbox",[
    "id" => "contact_lpr",
    "text" => "ЛПР",
    "style" => [
        "margin-right" => "10px",
        "padding" => "0px 7px",
        "font-size" => "12px"
    ],
    
],true).$view->show("inc.input.checkbox",[
    "id" => "contact_eavr",
    "text" => "ЭАВР",
    "style" => [
        "margin-right" => "10px",
        "padding" => "0px 7px",
        "font-size" => "12px"
    ],
    
],true);
$secondRow = $view->show("inc.input.text_stretch",[
        "id" => "contact_name",
        "style" => [
            "margin" => "0px 10px 0px 35px",
            "font-size" => "18px",
            "font-weight" => "bolder",
        ],
    ],true). $view->show("inc.input.text_stretch",[
        "id" => "contact_role",
        "style" => [
            "margin-right" => "10px",
            "font-size" => "18px",
            "font-weight" => "bolder",
        ],
    ],true);




$view->show("inc.div",[
    "type" => "column",
    "class" => "hidden",
    "id" => "contactContainerTemplate",
    "style" => [
        "width" => "100%",
        "margin-bottom" => "5px",
        "border-bottom" => "1px var(--modColor_darkest) dashed",
        "padding-bottom" => "5px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "margin-bottom" => "5px",
            "padding-bottom" => "5px"
        ],
        "content" => $firstRow
    ],true).$view->show("inc.div",[
        "type" => "row",
        "style" => [
            "width" => "100%",
            "margin-bottom" => "5px",
            "padding-bottom" => "5px"
        ],
        "content" => $secondRow
    ],true)
]);


/*Переменные--------------------------------------------------*/



