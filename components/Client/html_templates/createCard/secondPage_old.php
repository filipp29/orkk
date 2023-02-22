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
                    "style" => $textStyle
                ],true). $view->show("inc.input.text",[
                    "id" => "dnum",
                    "style" => [
                        "width" => "150px"
                    ],
//                    "attribute" => [
//                        "readonly" => "readonly"
//                    ]
                ],true)
                
            ],true). $view->show("inc.text",[
                "text" => "����� ��������, ������������ � ��������",
                "style" => [
                    "margin-left" => "10px"
                ] + $commentStyle
            ],true)
            
        ],true). $view->show("buttons.normal",[
            "text" => "�������������",
            "onclick" => "generateNewNumber(this,`dnum`)",
            "style" => [
                "min-width" => "10px",
                "font-size" => "12px",
                "padding" => "0px 6px",
                "height" => "25px",
                "margin-left" => "10px"
            ]
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
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "legalStreetType",
            "values" => $settings::streetType()
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
                ]
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
            ]
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "legalBuilding",
            "style" => [
                "width" => "70px"
            ]
        ],true).$view->show("inc.text",[
            "text" => ", ",
            "style" => $textStyle
        ],true).$view->show("inc.input.select",[
            "id" => "legalFlatType",
            "values" => $settings::flatType()
        ],true).$view->show("inc.text",[
            "text" => ":",
            "style" => $textStyle
        ],true).$view->show("inc.input.text",[
            "id" => "legalFlat",
            "style" => [
                "width" => "70px"
            ]
        ],true),
    ],
    
    
    
    /*--------------------------------------------------*/
    
    "��� ������" => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "���� ������:",
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
                "content" => $view->show("inc.input.checkbox",[
                    "id" => "internet_service",
                    "text" => "��������",
                    "checked" => "1",
                    "style" => [
                        "margin-right" => "10px"
                    ]
                ],true). $view->show("inc.input.checkbox",[
                    "id" => "esdi_service",
                    "text" => "����",
                    "style" => [
                        "margin-right" => "10px"
                    ]
                ],true). $view->show("inc.input.checkbox",[
                    "id" => "channel_service",
                    "text" => "�����",
                    "style" => [
                        "margin-right" => "10px"
                    ]
                ],true). $view->show("inc.input.checkbox",[
                    "id" => "lan_service",
                    "text" => "���"
                ],true)
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
                ]
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
                ]
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
                ]
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
                ]
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
                ]
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
                ]
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
                ]
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
                ]
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
                "values" => $settings::district()
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
                ]
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
                "values" => \Settings\Main::payType()
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
                ]
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
                "values" => $settings::connectType()
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
                "values" => $settings::attractType()
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
                ]
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
                    ]
                ],true). $view->show("inc.text",[
                    "text" => "�����",
                    "style" => $textStyle
                ],true).$view->show("inc.input.select",[
                    "id" => "udoGiver",
                    "values" => \Settings\Main::udoGiver(),
                    "style" => [
                        "width" => "120px"
                    ],
                ],true). $view->show("inc.text",[
                    "text" => "��",
                    "style" => $textStyle
                ],true).$view->show("inc.input.date",[
                    "id" => "udoDate",
                    "style" => [
                        "width" => "120px"
                    ],
                ],true)
            ],true).$view->show("inc.text",[
                "text" => "������������� ��������",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "����� ���. �����." => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����� �������� ���. �����.",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "gosDnum",
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "����� �������� ���. �����.",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    "����� ���. �����." => [
        "column" => 3,
        /*--------------------------------------------------*/
        "content1" => $view->show("inc.text",[
            "text" => "����� �� ��� ���. �����.",
            "style" => $textStyle
        ],true),
        /*--------------------------------------------------*/
        "content2" => $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "width" => "100%"
            ],
            "content" => $view->show("inc.input.text",[
                "id" => "gosSum",
                "style" => [
                    "width" => "100%"
                ]
            ],true).$view->show("inc.text",[
                "text" => "����� �� ��� ���. �����.",
                "style" => $commentStyle
            ],true)
        ],true),
        /*--------------------------------------------------*/
        "content3" => ""
    ],
    
];

$keys = [
    "������������",
    "����� ���. �����.",
    "��.�����",
    "��� ������",
    "��� �����������",
    "����� ������",
    "����� ���. �����.",
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
            "onclick" => "cancelSecondPage(this)",
            "style" => [
                "margin-right" => "10px"
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "����������",
            "onclick" => "createThirdPage(this)"
        ],true)
    ],true),
    /*--------------------------------------------------*/
    "content3" => ""
]);

/*--------------------------------------------------*/

$view->show("inc.var",[
    "key" => "clientStatusOld",
    "value" => "������� �����������"
]);
















