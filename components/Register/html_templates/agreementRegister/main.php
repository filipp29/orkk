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

$buf = getYearMonthList();

$years = $buf["years"];
$months = $buf["months"];
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
            "text" => "���:",
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
            "text" => "�����:",
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
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("buttons.normal",[
            "text" => "��������",
            "onclick" => "reloadAgreementRegister(this);"
        ],true). $view->show("buttons.normal",[
            "text" => "�������",
            "onclick" => "checkAccess('onlyLeader',closeAgreementRegister,this);",
            "style" => [
                "margin-left" => "15px",
                "background-color" => $closed ? "orange" : ""
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "������ ��� ������",
            "onclick" => "checkAccess('onlyLeader',printAgreementRegister,this);",
            "style" => [
                "margin-left" => "15px",
                "width" => "auto"
            ]
        ],true). $view->show("inc.input.checkbox",[
            "id" => "flPrint",
            "text" => "��",
            "checked" => "0",
            "style" => [
                "font-size" => "11px",
                "height" => "fit-content",
                "margin-left" => "6px"
            ]
        ],true)
    ],true)
    
],true);

$connectedTable = $view->show("inc.text",[
    "text" => "������������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $connected,
    "sumList" => $sumList["connected"],
    "action" => "activate",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$connectedGuTable = $view->show("inc.text",[
    "text" => "��������������� �������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $connectedGu,
    "sumList" => $sumList["connected"],
    "action" => "activate",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$disconnectedTable = $view->show("inc.text",[
    "text" => "�����������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.connectedTable",[
    "clientList" => $clientList,
    "accountList" => $disconnected,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$changedTable = $view->show("inc.text",[
    "text" => "���������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.changedTable",[
    "clientList" => $clientList,
    "accountList" => $changed,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$blockTable = $view->show("inc.text",[
    "text" => "������������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.blockTable",[
    "clientList" => $clientList,
    "accountList" => $blocked,
    "sumList" => $sumList["disconnected"],
    "action" => "disconnect",
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$waitingTable = $view->show("inc.text",[
    "text" => "������� �����������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.waitingTable",[
    "clientList" => $clientList,
    "accountList" => $waiting,
    "sumList" => $sumList["waiting"],
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$supportTable = $view->show("inc.text",[
    "text" => "��������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.supportTable",[
    "accountList" => $serviceTable,
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

$clientDocTable = $view->show("inc.text",[
    "text" => "������ ���������",
    "style" => $tableLabelStyle
],true).$regView->show("agreementRegister.clientDocTable",[
    "accountList" => $clientDocList,
    "clientList" => $clientList,
    "currentRegister" => $currentRegister,
    "closed" => $closed,
    "year" => $year,
    "month" => $month
],true);

/*�����������--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $waitingTable. $connectedTable. $changedTable. $connectedGuTable. $disconnectedTable. $blockTable. $supportTable. $clientDocTable,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*����������--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "������ ���������",
    "current_month" => $month,
    "current_year" => $year
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























