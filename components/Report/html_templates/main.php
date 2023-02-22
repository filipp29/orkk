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
        "text" => "�����������",
        "onclick" => "Report.ClientReport.Connect.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "�������",
        "onclick" => "Report.ClientReport.Client.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "�����",
        "onclick" => "Report.ClientReport.Amount.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Email",
        "onclick" => "Report.ContactReport.Email.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "��������",
        "onclick" => "Report.ContactReport.Contact.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "��������� ��������",
        "onclick" => "Report.SupportReport.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "����� �����������",
        "onclick" => "Report.ClientReport.ConnectSum.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "���������� �� ��",
        "onclick" => "Report.ClientReport.Gu.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "���������� �� �������",
        "onclick" => "Report.ClientReport.District.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
];
$buttonContent = "";
foreach($buttons as $value){
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
    "tabTitle" => "������",
];

$view->show("inc.vars",[
    "vars" => $vars
]);