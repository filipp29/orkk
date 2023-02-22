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
        "text" => "Ïîäêëş÷åíèÿ",
        "onclick" => "Report.ClientReport.Connect.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Êëèåíòû",
        "onclick" => "Report.ClientReport.Client.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ñóììû",
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
        "text" => "Òåëåôîíû",
        "onclick" => "Report.ContactReport.Contact.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Êòîíòğîëü êà÷åñòâà",
        "onclick" => "Report.SupportReport.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ñóììà ïîäêëş÷åíèÿ",
        "onclick" => "Report.ClientReport.ConnectSum.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ñòàòèñòèêà ïî ÃÓ",
        "onclick" => "Report.ClientReport.Gu.showPage(this)",
        "profileList" => [
            "leader",
            "manager",
            "assistant",
            "admin"
        ]
    ],
    [
        "text" => "Ñòàòèñòèêà ïî ğàéîíàì",
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


/*Îòîáğàæåíèå--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "content" => $buttonList
]);

/*Ïåğåìåííûå--------------------------------------------------*/

$vars = [
    "tabTitle" => "Îò÷åòû",
];

$view->show("inc.vars",[
    "vars" => $vars
]);