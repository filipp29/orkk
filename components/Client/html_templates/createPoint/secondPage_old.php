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
    "�� ��������" => "�� ��������",
    "�����/������" => "�����/������",
    "������� ����������" => "������� ����������",
    "����� ��" => "����� ��",
    
];

$rows = [
    "������������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "������ �������:",
            "style" => $textStyle
        ],true). $view->show("inc.text",[
            "text" => "������� �����������",
            "style" => [
                "color" => "#1E90FF"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "������������",
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
                "text" => "�������� �������� ��� ���������������",
                "style" => $commentStyle,
                
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => $view->show("inc.div",[
            "type" => "column",
            
            "content" => $view->show("inc.div",[
                "type" => "row",
                
                "content" => $view->show("inc.text",[
                    "text" => "� ��������",
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
                "text" => "����� ��������, ������������ � ��������",
                "style" => [
                    "margin-left" => "10px"
                ] + $commentStyle
            ],true)
            
        ],true)
        
    ],
    
    /*--------------------------------------------------*/
    
    "��.�����" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����������� �����:",
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
                "text" => "����������� ����� �����������",
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
    
    "��� ������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "��� ������:",
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
                "text" => "��� ������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "����� ������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����� ������:",
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
                "text" => "����� �������� �� ������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "������ ������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "������ ������:",
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
                "text" => "������ ������, ���������������� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "����� �����������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����� �����������:",
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
                "text" => "����� �����������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
        
    ],
    
    /*--------------------------------------------------*/
    
    "������������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "������������:",
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
                "text" => "����������� ������������ ��� �����������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "���� ����������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���� ���������� ��������:",
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
                "text" => "���� ���������� ��������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "���" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���/���:",
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
                "text" => "���/��� �������",
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
                "text" => "IBAN �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "���" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���:",
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
                "text" => "��� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "���" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���:",
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
                "text" => "��� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "����" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����:",
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
                "text" => "���� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "�������� �����" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "�������� �����:",
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
                    "0" => "���",
                    "1" => "��",
                ],
                "value" => ($default) ? $nightWork  : "",
            ],true).$view->show("inc.text",[
                "text" => "�������� �����",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "�����" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "�����:",
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
                "text" => "�����",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "����������� ip" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����������� IP:",
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
                    "0" => "�� ���������",
                    "1" => "���������"
                ],
                "value" => ($default) ? $staticIp  : "",
            ],true).$view->show("inc.text",[
                "text" => "��������� �� ������� ����������� IP",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "��� �������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "��� ��������:",
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
                "text" => "��� ��������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "���� ���������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���� ���������:",
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
                "text" => "���� ���������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "��� �����������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "��� �����������:",
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
                "text" => "��� ����������� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "����� �����������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����� �����������:",
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
                "text" => "����� ����������� �������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
    /*--------------------------------------------------*/
    
    "��������������� ����������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "��������������� ����������:",
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
                "text" => "��������������� ����������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "����������� ����� ��� ����" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����������� ����� ��� ����:",
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
                "text" => "Email ��� �������� ����",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "�������������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "������������� �������� �:",
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
                    "text" => "�����",
                    "style" => $textStyle
                ],true).$view->show("inc.input.select",[
                    "id" => "udoGiver",
                    "values" => \Settings\Main::udoGiver(),
                    "style" => [
                        "width" => "120px"
                    ],
                    "value" => $udoGiver
                ],true). $view->show("inc.text",[
                    "text" => "��",
                    "style" => $textStyle
                ],true).$view->show("inc.input.date",[
                    "id" => "udoDate",
                    "style" => [
                        "width" => "120px"
                    ],
                    "value" => $udoDate
                ],true)
            ],true).$view->show("inc.text",[
                "text" => "������������� ��������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ]
    
];

$keys = [
    "������������",
    "��.�����",
    "��� ������",
    "��� �����������",
    "����� ������",
    "������ ������",
    "����� �����������",
    "������������",
    "���� ����������",
    "���",
    "iban",
    "���",
    "���",
    "����",
    "�������������",
    "�������� �����",
    "�����",
    "����������� ip",
    "��� �������",
    "���� ���������",
    "����� �����������",
    "��������������� ����������",
    "����������� ����� ��� ����"
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
            "text" => "��������",
            "onclick" => "cancelSecondPage(this,true)",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "����������",
            "onclick" => "createThirdPage(this,{$newPoint})"
        ],true)
    ],true),
    /*--------------------------------------------------*/
    "content3" => ""
]);

/*--------------------------------------------------*/

$vars = [
    "clientStatus" => isset($new_clientStatus) ? $new_clientStatus : "������� �����������",
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

















