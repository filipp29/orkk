<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Phonebook\Controller();
$phoneView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$actionText = [
    "activate" => "���� ���������",
    "disconnect" => "���� ����������"
];

$tableHeader = [
    "organization" => "�����������",
    "name" => "���",
    "role" => "���������",
    "contacts" => "��������",
    "phoneList" => "����������",
    "action" => ""
];

$tableWidth = [
    "organization" => "�����������",
    "name" => "���",
    "role" => "���������",
    "contacts" => "��������",
    "phoneList" => "����������",
    "action" => ""
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];


/*�������������--------------------------------------------------*/

$cityList = [
    "��������",
    "���������",
    "�����"
];

$result = "";

foreach($cityList as $city){
    $header = $view->get("inc.div",[
        "type" => "row",
        "content" => $view->get("inc.text",[
            "text" => $city,
            "style" => [
                "font-size" => "21px"
            ]
        ]). $view->get("buttons.plus",[
            "onclick" => "Phonebook.addContact(this)",
            "style" => [
                "width" => "auto",
                "margin-left" => "10px",
                "height" => "20px"
            ]
        ]). $view->get("inc.vars",[
            "vars" => [
                "contact_city" => $city
            ]
        ]),
        "style" => [
            "margin" => "25px 0px 8px 5px",
            "align-items" => "center"
        ]
    ]);
    $table = $phoneView->get("table",[
        "contactList" => isset($contactList[$city]) ? $contactList[$city] : [],
        "city" => $city
    ]);
    $result .= $view->get("inc.div",[
        "type" => "column",
        "content" => $header. $table,
        "class" => "phoneTableContainer",
        "style" => [
            "width" => "100%"
        ]
    ]);
}



/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $result,
    "style" => [
        "width" => "100%"
    ]
]);









