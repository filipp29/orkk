<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
$viewClient = $client->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$buttonStyle = [
    "width" => "auto",
    "margin-bottom" => "10px"
];




/*�������������--------------------------------------------------*/
$buttons = [
    [
        "text" => "������ ��������",
        "onclick" => "showClientList()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������ ���������",
        "onclick" => "showAgreementRegister()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "�������� ��������",
        "onclick" => "showMonthSupportTable()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������ ���������",
        "onclick" => "Debtor.showDebtorTable()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������ ��� ����������",
        "onclick" => "showSupportRegister()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "none",
            "admin"
        ]
    ],
    [
        "text" => "������ ����������",
        "onclick" => "DocumentRegister.showRegister()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "�����������������",
        "onclick" => "Admin.showMain()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "��������",
        "onclick" => "Salary.showSalaryTable()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������",
        "onclick" => "Timesheet.show();",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������ ������ ����",
        "onclick" => "ClientList.showOldClientList()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "������",
        "onclick" => "Report.showMain()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
];
$role = getRole();
$buttonContent = "";
foreach($buttons as $value){
    if (!in_array($role, $value["profileList"])){
        continue;
    }
    $buttonContent .= $view->show("buttons.normal",[
        "text" => $value["text"],
        "onclick" => $value["onclick"],
        "style" => $buttonStyle
    ],true);
}

$buttonList = $view->show("inc.div",[
    "type" => "column",
    "content" => $buttonContent,
    
],true);


/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $buttonList
]);

/*����������--------------------------------------------------*/

$vars = [
    "tabTitle" => "�������",
];

$view->show("inc.vars",[
    "vars" => $vars
]);


