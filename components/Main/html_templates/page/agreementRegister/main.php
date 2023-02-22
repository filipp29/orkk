<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
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


//$clientList = $client->clientList();
$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => "var(--modBGColor)",
                "cursor" => "pointer",
            ],
            "class" => "clietListSortButton",
            "attribute" => [
                "onclick" => "clientListSort(this)"
            ]
        ],true),
        "style" => [
            "width" => $tableWidth[$key],
            "text-align" => "left",
            "padding-right" => "10px"
        ]
    ],true);
}

$thead = $view->show("table.tr",[
    "content" => $headerContent
],true);




/*Îòîáğàæåíèå--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");
$view->show("buttons.normal",[
    "text" => "Îáíîâèòü",
    "onclick" => "reloadIndex(this);"
]);

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => ""
]);


/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Ğååñòğ äîãîâîğîâ",
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























