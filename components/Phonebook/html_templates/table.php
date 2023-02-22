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
$actionText = [
    "activate" => "Дата активации",
    "disconnect" => "Дата отключения"
];

$tableHeader = [
    "organization" => "Организация",
    "name" => "Имя",
    "role" => "Должность",
    "contacts" => "Контакты",
    "phoneList" => "Примечание",
    "action" => ""
];

$tableWidth = [
    "organization" => "Организация",
    "name" => "Имя",
    "role" => "Должность",
    "contacts" => "200px",
    "phoneList" => "Примечание",
    "action" => "150px"
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

/*--------------------------------------------------*/

$getPhoneRow = function($value) use($view,$textStyle){
    $phone = getPhoneTemplate($value);
    return $view->show("inc.div",[
        "type" => "row",
        "class" => "phoneRow",
        "style" => [
            "align-items" => "center",
            "margin" => "5px 0px"
        ],
        "content" => $view->show("buttons.phone",[
            "onclick" => "orkkDoCall(this,`.phoneRow`,`phoneNumber`)",
            "style" => [
                "width" => "auto",
                "height" => "18px",
            ],
            "class" => "hiddenElement"
        ],true). $view->show("buttons.close",[
            "onclick" => "Phonebook.deletePhone(this)",
            "style" => [
                "width" => "auto",
                "height" => "18px"
            ],
            "class" => "phoneDeleteButton hidden hiddenElement"
        ],true). $view->show("inc.input.phone",[
            "class" => "contactPhoneNumber",
            "id" => "phoneNumber",
            "value" => $phone,
            "style" => [
                "margin-left" => "8px"
            ] + $textStyle
        ],true)
    ],true);
};

/*Инициализация--------------------------------------------------*/

$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $value,
        "style" => [
            "width" => $tableWidth[$key]
        ]
    ],true);
}

$body = "";
$even = true;
foreach($contactList as $value){
    $even = !$even;
    $buf = [
        "organization" => "",
        "name" => "",
        "role" => ""
    ];
    foreach($buf as $key => $unused){
        $buf[$key] = $view->show("inc.input.text",[
            "id" => "contact_{$key}",
            "value" => $value[$key],
            "style" => [
                "width" => "100%"
            ] + $textStyle
        ],true);
    }
    $organization = $buf["organization"];
    $name = $buf["name"];
    $role = $buf["role"];
    $contactBlockContent = "";
    foreach($value["phoneList"] as $phone){
        if ($phone){
            $contactBlockContent .= $getPhoneRow($phone);
        }
    }
    $contactBlock = $view->show("inc.div",[
        "type" => "column",
        "class" => "phoneNumberList",
        "style" => [
            "align-items" => "flex-start"
        ],
        "content" => $contactBlockContent. $view->show("buttons.plus",[
            "onclick" => "Phonebook.addPhone(this)",
            "style" => [
                "width" => "auto",
                "height" => "14px",
                "margin" => "5px 0px"
            ],
            "class" => "hidden hiddenElement"
        ],true)
    ],true);
    $comment = $view->show("inc.input.area_stretch",[
        "id" => "contact_comment",
        "class" => "inputAreaAutosize",
        "style" => [
            "max-width" => "300px",
            "min-width" => "300px",
            "height" => "auto"
        ] + $textStyle,
        "value" => $value["comment"]
    ],true);
    $vars = [
        "contact_id" =>  $value["id"]
    ];
    $actionBlock = $view->show("inc.div",[
        "type" => "column",
        "content" => $view->show("buttons.normal",[
            "onclick" => "Phonebook.editContact(this)",
            "text" => "Редактировать",
            "style" => [
                "width" => "auto",
                "margin-top" => "5px"
            ],
            "class" => "hiddenElement"
        ],true). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "width" => "100%",
                "justify-content" => "center",
                "align-items" => "center",
                "height" => "100%"
            ],
            "class" => "acceptBlock hidden hiddenElement",
            "content" => $view->show("buttons.close",[
                "onclick" => "Phonebook.reloadContactTable(this)",
                "style" => [
                    "margin-right" => "25px"
                ]
            ],true).$view->show("buttons.accept",[
                "onclick" => "Phonebook.saveContact(this,`tr`)",
            ],true)
        ],true). $view->show("buttons.red",[
            "onclick" => "Phonebook.deleteContact(this)",
            "text" => "Удалить",
            "style" => [
                "width" => "auto",
                "margin" => "5px 0px"
            ],
            "class" => "hiddenElement"
        ],true). $view->show("inc.vars",[
            "vars" => $vars
        ],true)
    ],true);
    $tr = [
        "organization" => $organization,
        "name" => $name,
        "role" => $role,
        "phoneList" => $contactBlock,
        "comment" => $comment,
        "action" => $actionBlock
    ];
    $trContent = "";
    foreach($tr as $key => $v){
        $trContent .= $view->show("table.td",[
            "content" => $v,
            "style" => $tdStyle
        ],true);
    }
    $body .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
}

$thead = $view->show("table.tr",[
    "content" => $headerContent
],true);

/*Отображение--------------------------------------------------*/
$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $body,
    "style" => [
    ],
    "class" => "supportTable"
]);




