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
    "dnum" => "����� ��������",
    "name" => "������������",
    "manager" => "��������",
    "address" => "�����",
    "contacts" => "��������",
    "remarm" => "����������",
    "hardware" => "������������",
    "loginList" => "������ � ������",
    "speed" => "��������",
    "staticIp" => "�������"
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

$menuList = [
    "kst-all" => "��������",
    "lsk-all" => "���������",
    "kchr-all" => "�����",
    "kst-gu" => "�� ���",
    "lsk-gu" => "�� ���",
    "kchr-gu" => "�� ���",
    "disconnected-all" => "�����",
//    "phonebook-all" => "����������"
];
$menu = "";
$selected = "menuItem_selected";
foreach($menuList as $key => $value){
    $menu .= $view->show("inc.div",[
        "type" => "row",
        "class" => "menuItem {$selected}",
        "content" => $value,
        "id" => $key,
        "attribute" => [
            "onclick" => "supportRegisterMenuSelect(this)"
        ],
        "style" => [
            "cursor" => "pointer",
            "font-size" => "20px",
            "color" => "var(--modColor_darkest)",
            "opacity" => "0.4",
            "transform" => "scale(0.92)",
            "width" => "fit-content",
            "margin-right" => "10px"
        ]
    ],true);
    $selected = "";    
}

$menu .= $view->show("inc.div",[
    "type" => "row",
    "class" => "menuItem ",
    "content" => "����������",
    "id" => "phonebook",
    "attribute" => [
        "onclick" => "supportRegisterMenuSelect(this)"
    ],
    "style" => [
        "cursor" => "pointer",
        "font-size" => "20px",
        "color" => "var(--modColor_darkest)",
        "opacity" => "0.4",
        "transform" => "scale(0.92)",
        "width" => "fit-content",
        "margin-right" => "10px"
    ]
],true);

$search = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "align-items" => "center"
    ],
    "content" => $view->show("inc.input.text",[
        "id" => "filterText",
        "attribute" => [
            "onkeypress" => "enterPress(event,supportRegisterFilter)"
        ]
    ],true). $view->show("buttons.normal",[
        "text" => "�����",
        "onclick" => "supportRegisterFilter(this)",
        "style" => [
            "width" => "auto",
            "min-width" => "10px",
            "height" => "25px",
            "padding" => "0px 10px",
            "margin" => "0px 10px"
        ]
    ],true)
],true);

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("buttons.normal",[
        "text" => "��������",
        "onclick" => "reloadSupportRegister(this)"
    ],true),
    "style" => [
        "margin" => "10px 0px"
    ]
],true);

$header = $view->show("inc.div",[
    "type" => "column",
    "content" => $buttonBlock. $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "40px",
            "align-items" => "flex-end",
            "margin" => "10px",
            "padding" => "0px 10px",
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "class" => "menuBlock",
            "content" => $menu
        ],true).  $search
    ],true)
],true);



$body = "";
foreach($clientList as $city => $value){
    foreach($value as $type => $v){
        if ("{$city}-{$type}" == "kst-all"){
            $hidden = "";
        }
        else{
            $hidden = "hidden";
        }
        $body .= $view->show("inc.div",[
            "type" => "row",
            "class" => "tableContainer {$hidden}",
            "id" => "{$city}-{$type}_table",
            "content" => $regView->show("supportRegister.table",[
                "accountList" => $clientList[$city][$type],
                "renewList" => $renewList,
                "searchList" => $searchList
            ],true)        
        ],true);
    }
}
$body .= $view->show("inc.div",[
    "type" => "row",
    "class" => "tableContainer {$hidden}",
    "id" => "phonebook_table",
    "content" => $phonebook       
],true);










/*�����������--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $body,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*����������--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "������ ��� ����������",
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























