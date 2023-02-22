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
    "contractDate" => "���� ��������",
    "activateDate" => "���� ���������",
    "name" => "������������",
    "dnum" => "����� ��������",
    "amount" => "����������� �����",
    "speed" => "��������",
    "yearSum" => "����� �� ���",
    "comment" => "����������",
];

$tableWidth = [
    "contractDate" => "���� ��������",
    "activateDate" => "���� ���������",
    "name" => "������������",
    "dnum" => "����� ��������",
    "amount" => "����������� �����",
    "speed" => "��������",
    "yearSum" => "����� �� ���",
    "comment" => "����������",
];



/*�������������--------------------------------------------------*/


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




/*�����������--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");
$view->show("buttons.normal",[
    "text" => "��������",
    "onclick" => "reloadIndex(this);"
]);

$view->show("table.main",[
    "thead" => $thead,
    "tbody" => ""
]);


/*����������--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "������ ���������",
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























