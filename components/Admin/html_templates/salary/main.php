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

$nameList = [
    "manager" => "��������",
    "leader" => "������������",
    "assistant" => "�������� ����������",
    "marketer" => "����������",
    "salary" => "�����",
    "prepayment" => "�����",
    "punishment" => "�����",
    "orkk" => "����",
    "orfl" => "����",
    "debtorCalling" => "������ ���������"
];

/*--------------------------------------------------*/

$getEditBlock = function(
        $id,
        $value,
        $bonusTable = false
//        $width
) use ($view,$textStyle){
    if ($bonusTable){
        $onclick = "Admin.salary.bonusTable.save(this)";
    }
    else{
        $onclick = "Admin.salary.save(this)";
    }
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.text",[
            "id" => $id,
            "class" => "salary_param",
            "style" => [
//                "max-width" => $width,
                "height" => "auto",
                "text-align" => "center"
//                "min-width" => $width,
            ] + $textStyle,
            "value" => $value
        ],true). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "�������������",
                "onclick" => "tableCellEditClick(this)",
                "class" => "editButton",
                "style" => [
                    "font-size" => "12px",
                    "height" => "18px",
                    "width" => "100%"
                ]
            ],true). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "100%",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "height" => "18px"
                ],
                "class" => "acceptBlock hidden",
                "content" => $view->show("buttons.close",[
                    "onclick" => "tableCellEditClose(this,false)",
                    "style" => [
                        "margin-right" => "25px",
                        "height" => "18px"
                    ]
                ],true).$view->show("buttons.accept",[
                    "onclick" => $onclick,
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true);
};

/*--------------------------------------------------*/

$getTable = function(
        $tableName,
        $title,
        $width,
        $keyList,
        $headerSize = "22px",
        $percentList = []
)use($salary,$view,$tdStyle,$textStyle,$getEditBlock){
    $header = $view->get("table.tr",[
        "content" => $view->get("table.th",[
            "content" => $title,
            "style" => [
                "font-size" => $headerSize
            ] + $tdStyle,
            "attribute" => [
                "colspan" => count($keyList)
            ]
        ])
    ]);
    $body = "";
    
    $headerContent = "";
    $trContent = "";
    foreach($keyList as $key => $value){
        $headerContent .= $view->get("table.td",[
            "content" => $value,
            "style" => [
                "width" => $width,
                "font-size" => "16px"
            ] + $tdStyle
        ]);
//        if (!in_array($key, $percentList)){
//            $trContent .= $view->get("table.td",[
//                "content" => $getEditBlock("{$tableName}.{$key}",$salary[$tableName][$key]),
//                "style" => [
//                    "width" => $width
//                ] + $tdStyle
//            ]);
//        }   
//        else{
//            $trContent .= $view->get("table.td",[
//                "content" => "����� ��������� ���������� <br>�� ������� ��������������",
//                "style" => [
//                    "width" => $width
//                ] + $tdStyle
//            ]);
//        }
        $trContent .= $view->get("table.td",[
            "content" => $getEditBlock("{$tableName}.{$key}",$salary[$tableName][$key]),
            "style" => [
                "width" => $width
            ] + $tdStyle
        ]);
    }
    $body .= $view->get("table.tr",[
        "content" => $headerContent
    ]). $view->get("table.tr",[
        "content" => $trContent
    ]);
    return $view->get("table.main",[
        "tbody" => $body,
        "thead" => $header,
        "style" => [
            "width" => "fit-content"
        ]
    ]);
};

/*--------------------------------------------------*/

$getBonusTable = function(
        $role
)use($view,$tdStyle,$textStyle,$getEditBlock,$bonusList,$buttonStyle,$nameList){
    $headerContent = [
        "name" => "������������",
        "sum" => "�����",
        "action" => $view->get("buttons.normal",[
            "text" => "��������",
            "onclick" => "Admin.salary.bonusTable.showAddForm(this)",
            "style" => $buttonStyle
        ]).$view->get("inc.vars",[
            "vars" => [
                "role" => $role
            ]
        ])
    ];
    $tableWidth = [
        "name" => "200px",
        "sum" => "100px",
        "action" => ""
    ];
    $thead = $view->get("table.tr",[
        "content" => $view->get("table.th",[
            "content" => "{$nameList[$role]}. ������",
            "attribute" => [
                "colspan" => "3"
            ]
        ])
    ]);
    $tr = "";
    foreach($headerContent as $key => $value){
        $tr .= $view->get("table.td",[
            "content" => $value,
            "style" => [
                "width" => $tableWidth[$key]
            ] + $tdStyle
        ]);
    }
    $tbody = $view->get("table.tr",[
        "content" => $tr
    ]);
    
    $buf = isset($bonusList[$role]) ? $bonusList[$role] : [];
    foreach($buf as $key => $value){
        $tr = "";
        $tdList =[
            $key,
            $getEditBlock("bonusValue",$value,true),
            $view->get("buttons.red",[
                "text" => "�������",
                "onclick" => "Admin.salary.bonusTable.remove(this)",
                "style" => $buttonStyle
            ]).$view->get("inc.vars",[
                "vars" => [
                    "role" => $role,
                    "bonusKey" => $key
                ]
            ]),
        ];
        foreach($tdList as $val){
            $tr .= $view->get("table.td",[
                "content" => $val,
                "style" => $tdStyle
            ]);
        }
        $tbody .= $view->get("table.tr",[
            "content" => $tr
        ]);
    }
    return $view->get("table.main",[
        "tbody" => $tbody,
        "thead" => $thead,
        "class" => "bonusTable",
        "style" => [
            "margin-top" => "20px"
        ]
    ]);
};

/*�������������--------------------------------------------------*/
$table = [];
$table["amount"] = $getTable("amount","��������� ���������","180px",[
    "1" => "������ (��)",
    "2" => "������ (��)",
    "3" => "������ (��)",
    "4" => "��������� (��)",
]);
$table["plan"] = $getTable("plan","����","180px",[
    "min" => "������� (��)",
    "mid" => "������� (��)",
    "max" => "�������� (��)",
    "percent" => "����.������� (%)",
    "monthPlan" => "���� �� ����� (��)"
]);


$title = "����� �� �������� ������������ ���������� �� ������ � �������������� ���������<br>
���� {$salary["amount"]["1"]} ��. ��� ������� ���������� ����� ����������� ��������� �������";
$width = "250px";
$keyList = [
    "min" => "���������� ����� �������<br>(������������� ��������� %)",
    "mid" => "���������� �������� �����<br>(������������� ��������� %)",
    "max" => "���������� ����� ��������<br>(������������� ��������� %)",
];
$tableName = "reward1";
$table["reward1"] = $getTable($tableName,$title,$width,$keyList,"14px");

for($i = 2; $i <= 4; $i++){
    $index = (string)$i;
    $prev = (string)($i - 1);
    $min = (int)$salary["amount"][$prev] + 1;
    $max = $salary["amount"][$index];
    $title = "����� �� �������� ������������ ���������� �� ������ � �������������� ���������<br>
    �� {$min} �� {$max} ��. ��� ������� ���������� ����� ����������� ��������� �������";
    $width = "250px";
    $keyList = [
        "min" => "���������� ����� �������<br>(������������� ��������� %)",
        "mid" => "���������� �������� �����<br>(������������� ��������� %)",
        "max" => "���������� ����� ��������<br>(������������� ��������� %)",
    ];
    $tableName = "reward{$index}";
    $table["reward{$index}"] = $getTable($tableName,$title,$width,$keyList,"14px");
}

$title = "����� �� �������� ������������ ���������� �� ������ � �������������� ���������<br>
���� {$salary["amount"]["4"]} ��. ��� ������� ���������� ����� ����������� ��������� �������";
$width = "250px";
$keyList = [
    "min" => "���������� ����� �������<br>(������������� ��������� %)",
    "mid" => "���������� �������� �����<br>(������������� ��������� %)",
    "max" => "���������� ����� ��������<br>(������������� ��������� %)",
];
$tableName = "reward5";
$table["reward5"] = $getTable($tableName,$title,$width,$keyList,"14px");

//
//$buf = (int)$salary["amount"]["min"] + 1;
//$title = "����� �� �������� ������������ ���������� �� ������ � �������������� ���������<br>
//�� {$buf} �� {$salary["amount"]["mid"]} ��. ��� ������� ���������� ����� ����������� ��������� �������";
//$width = "250px";
//$keyList = [
//    "min" => "���������� ����� �������<br>(������������� ��������� %)",
//    "mid" => "���������� �������� �����<br>(������������� ��������� %)",
//    "max" => "���������� ����� ��������<br>(������������� ��������� %)",
//];
//$tableName = "rewardMid";
//$table["rewardMid"] = $getTable($tableName,$title,$width,$keyList,"14px");
//
//$buf = (int)$salary["amount"]["mid"] + 1;
//$title = "����� �� �������� ������������ ���������� �� ������ � �������������� ���������<br>
//�� {$buf} �� {$salary["amount"]["max"]} ��. ��� ������� ���������� ����� ����������� ��������� �������";
//$width = "250px";
//$keyList = [
//    "min" => "���������� ����� �������<br>(������������� ��������� %)",
//    "mid" => "���������� �������� �����<br>(������������� ��������� %)",
//    "max" => "���������� ����� ��������<br>(������������� ��������� %)",
//];
//$tableName = "rewardMax";
//$table["rewardMax"] = $getTable($tableName,$title,$width,$keyList,"14px");
$bonusRoleList = [
    "assistant",
    "marketer"
];

foreach($bonusList as $role => $unused){
    $tableName = $role;
    $title = $nameList[$role];
    $keyList = [];
    foreach($salary[$role] as $key => $unused){
        $keyList[$key] = $nameList[$key];
    }
    $width = "250px";
    if (in_array($role, $bonusRoleList)){
        $bonusTable = $getBonusTable($role);
    }
    else{
        $bonusTable = "";
    }
    $table[$role] = $view->get("inc.div",[
        "type" => "column",
        "content" => $getTable($tableName,$title,$width,$keyList,"22px"). $bonusTable
    ]);
}

$content = "";
foreach($table as $key => $value){
    $content .= $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "padding" => "20px 0px",
            "border-bottom" => "1px var(--modColor_darkest) dashed"
        ],
        "content" => $value
    ]);
}

/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $content
]);





