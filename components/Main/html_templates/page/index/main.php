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




/*Èíèöèàëèçàöèÿ--------------------------------------------------*/
$buttons = [
    [
        "text" => "Ñïèñîê êëèåíòîâ",
        "onclick" => "showClientList()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ğååñòğ äîãîâîğîâ",
        "onclick" => "showAgreementRegister()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Êîíòğîëü êà÷åñòâà",
        "onclick" => "showMonthSupportTable()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Îáçâîí äîëæíèêîâ",
        "onclick" => "Debtor.showDebtorTable()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ğååñòğ äëÿ äèñïåò÷åğà",
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
        "text" => "Ğååñòğ äîêóìåíòîâ",
        "onclick" => "DocumentRegister.showRegister()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Àäìèíèñòğèğîâàíèå",
        "onclick" => "Admin.showMain()",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Çàğïëàòà",
        "onclick" => "Salary.showSalaryTable()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Òàáåëü",
        "onclick" => "Timesheet.show();",
        "profileList" => [
            "leader",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ñïèñîê ñòàğîé áàçû",
        "onclick" => "ClientList.showOldClientList()",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Îò÷åòû",
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


/*Îòîáğàæåíèå--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $buttonList
]);

/*Ïåğåìåííûå--------------------------------------------------*/

$vars = [
    "tabTitle" => "Ãëàâíàÿ",
];

$view->show("inc.vars",[
    "vars" => $vars
]);


