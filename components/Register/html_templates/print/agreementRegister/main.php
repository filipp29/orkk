<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$buf = new \Register\Controller();
$regView = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableLabelStyle = [
    "font-size" => "20px",
    "font-wight" => "600",
    "margin" => "20px 0px 10px 30px"
];
$tableHeader = [
    "contractDate" => "Äàòà äîãîâîğà",
    "activateDate" => "Äàòà àêòèâàöèè",
    "name" => "Íàèìåíîâàíèå",
    "dnum" => "Íîìåğ äîãîâîğà",
    "amount" => "Åæåìåñÿ÷íàÿ ïëàòà",
    "speed" => "Ñêîğîñòü",
    "yearSum" => "Ñóììà çà ãîä",
    "comment" => "Ïğèìå÷àíèå",
];

$tableWidth = [
    "contractDate" => "Äàòà äîãîâîğà",
    "activateDate" => "Äàòà àêòèâàöèè",
    "name" => "Íàèìåíîâàíèå",
    "dnum" => "Íîìåğ äîãîâîğà",
    "amount" => "Åæåìåñÿ÷íàÿ ïëàòà",
    "speed" => "Ñêîğîñòü",
    "yearSum" => "Ñóììà çà ãîä",
    "comment" => "Ïğèìå÷àíèå",
];



/*Èíèöèàëèçàöèÿ--------------------------------------------------*/



$years = [
    "2021" => "2021",
    "2022" => "2022" 
];
$months = [
    "1" => "ßíâàğü",
    "2" => "Ôåâğàëü",
    "3" => "Ìàğò",
    "4" => "Àïğåëü",
    "5" => "Ìàé",
    "6" => "Èşíü",
    "7" => "Èşëü",
    "8" => "Àâãóñò",
    "9" => "Ñåíòÿáğü",
    "10" => "Îêòÿáğü",
    "11" => "Íîÿáğü",
    "12" => "Äåâàáğü"
];
$header = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-top" => "10px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-bottom" => "10px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "Ãîä:",
            "style" => [
                "margin-right" => "4px"
            ]
        ],true). $view->show("inc.input.select",[
            "id" => "year",
            "values" => $years,
            "value" => $year,
            "style" => [
                "margin-right" => "8px"
            ]
        ],true). $view->show("inc.text",[
            "text" => "Ìåñÿö:",
            "style" => [
                "margin-right" => "4px"
            ]
        ],true). $view->show("inc.input.select",[
            "id" => "month",
            "values" => $months,
            "value" => $month,
            "style" => [
                "margin-right" => "8px"
            ]
        ],true)
    ],true). $view->show("buttons.normal",[
        "text" => "Îáíîâèòü",
        "onclick" => "reloadAgreementRegister(this);"
    ],true). $view->show("buttons.normal",[
        "text" => "Çàêğûòü",
        "onclick" => "closeAgreementRegister(this);",
        "style" => [
            "margin-top" => "15px",
            "background-color" => $closed ? "orange" : ""
        ]
    ],true). $view->show("buttons.normal",[
        "text" => "Ïå÷àòü",
        "onclick" => "printAgreementRegister(this);",
        "style" => [
            "margin-top" => "15px",
        ]
    ],true)
],true);

$connectedTable = $regView->show("print.agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $connected,
    "sumList" => $sumList["connected"],
    "action" => "activate",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month,
    "headerText" => "Ïîäêëş÷åííûå"
],true);

$connectedGuTable = $regView->show("print.agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $connectedGu,
    "sumList" => $sumList["connected"],
    "action" => "activate",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month,
    "headerText" => "ÃÓ"
],true);

$disconnectedTable = $regView->show("print.agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $disconnected,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month,
    "headerText" => "Îòêëş÷åííûå"
],true);

$changedTable = $regView->show("print.agreementRegister.changedTable",[
    "clientList" => $clientList,
    "accountList" => $changed,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$blockTable = $regView->show("print.agreementRegister.blockTable",[
    "clientList" => $clientList,
    "accountList" => $blocked,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$supportTable = $regView->show("print.agreementRegister.supportTable",[
    "accountList" => $serviceTable,
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$clientDocTable = $regView->show("print.agreementRegister.clientDocTable",[
    "accountList" => $clientDocList,
    "clientList" => $clientList,
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

/*Îòîáğàæåíèå--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $connectedTable. $changedTable. $connectedGuTable. $disconnectedTable. $blockTable. $supportTable. $clientDocTable,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Ğååñòğ äîãîâîğîâ",
    "current_month" => $month,
    "current_year" => $year
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























