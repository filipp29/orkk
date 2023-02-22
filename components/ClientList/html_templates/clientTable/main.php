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
    "cncdnum" => "�",
    "name" => "������������",
    "addr" => "�����",
    "status" => "������",
    "rrate" => "�����",
    "doneBlock" => "�������",
];

$tableWidth = [
    "cncdnum" => "� ��������",
    "name" => "���",
    "addr" => "������������",
    "status" => "������",
    "rrate" => "�����",
    "doneBlock" => "���������",
];

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "5px 5px"
];
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

/*--------------------------------------------------*/

$getDoneBlock = function(
    $id    
)use($view,$doneList,$textStyle){
    if (isset($doneList[$id])){
        return profileGetUsername($doneList[$id]);
    }
    else{
        return $view->get("buttons.normal",[
            "text" => "��������",
            "onclick" => "ClientList.addMark(this)",
            "style" => [
                "height" => "auto"
            ] + $textStyle
        ]). $view->get("inc.vars",[
            "vars" => [
                "id" => $id,
                "manager" => $_COOKIE["login"],
                "managerName" => profileGetUsername($_COOKIE["login"])
            ]
        ]);
    }
};

/*�������������--------------------------------------------------*/

$header = $view->get("inc.div",[
    "type" => "column",
    "style" => [
        "margin" => "15px"
    ],
    "content" => $view->get("inc.text",[
        "text" => "����������: {$count}"
    ]).$view->get("inc.text",[
        "text" => "�������� ������������: {$markedCount}"
    ]). $view->get("inc.text",[
        "text" => "�������� �����: {$allMarkedCount}"
    ]). $view->get("inc.text",[
        "text" => "�����: {$sum}"
    ]). $view->get("inc.text",[
        "text" => "����� ��������: {$markedSum}"
    ])
]);

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
                "onclick" => "ClientList.sortTable(this,`{$key}`)"
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

$tbody = "";
$even = true;
foreach($oldTable as $id => $info){
    $tr = [
        "cncdnum" => isset($info["cncdnum"]) ? $info["cncdnum"] : "",
        "name" => isset($info["name"]) ? $info["name"] : "",
        "addr" => isset($info["addr"]) ? $info["addr"] : "",
        "status" => isset($info["statusValue"]) ? $info["statusValue"] : "",
        "rrate" => isset($info["rrate"]) ? $info["rrate"] : "",
        "doneBlock" => $getDoneBlock($id),
    ];
    $trContent = "";
    foreach($tr as $key => $value){
        $trContent .= $view->get("table.td",[
            "content" => $value,
            "style" => $tdStyle
        ]);
    }
    $even = !$even;
    $tbody .= $view->get("table.tr",[
        "content" => $trContent,
        "class" => $even ? "even" : "odd"
    ]);
}

$table = $view->get("table.main",[
    "thead" => $thead,
    "tbody" => $tbody
]);

/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $table
]);



