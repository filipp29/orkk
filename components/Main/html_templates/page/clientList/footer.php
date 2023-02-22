<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "font-size" => "12px"
];
$labelStyle = [
    "font-size" => "14px",
    "font-weight" => "bolder"
];



/*�������������--------------------------------------------------*/

$activeList = [
    "connected" => "���������",
    "connectedFl" => "�����������",
    "connectedGu" => "�����������",
    "connected_kzbi" => "����",
    "connected_ksk" => "���",
    "connected_center" => "�����"
];

$activeListDistrict = [
    "kzbi" => "����",
    "ksk" => "���",
    "center" => "�����"
];

$inactiveList = [
    "disconnected" => "�����",
    "disconnectedFl" => "�������",
    "disconnectedGu" => "�������",
    "disconnected_kzbi" => "����",
    "disconnected_ksk" => "���",
    "disconnected_center" => "�����"
];

$activeCountContent = "";
$activeSumContent = "";
$inactiveCountContent = "";
$inactiveSumContent = "";
$activeCountDistrictContent = "";
$activeSumDistrictContent = "";


/*--------------------------------------------------*/

foreach($activeListDistrict as $key => $value){
    $activeCountDistrictContent .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => \Settings\Main::statusColor()["���������"],
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Count"
            ]
        ],true)
    ],true);
    $activeSumDistrictContent .= $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Sum"
            ]
        ],true)
    ],true);
}



$activeCountDistrict = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "20px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "",
            "style" => [
                "margin-right" => "8px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "activeCount"
            ]
        ],true)
    ],true). $activeCountDistrictContent
],true);

$activeSumDistrict = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "50px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "",
            "style" => [
                "margin-right" => "8px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "activeSum"
            ]
        ],true)
    ],true). $activeSumDistrictContent
],true);



/*--------------------------------------------------*/
foreach($activeList as $key => $value){
    $activeCountContent .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => \Settings\Main::statusColor()["���������"],
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Count"
            ]
        ],true)
    ],true);
    $activeSumContent .= $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Sum"
            ]
        ],true)
    ],true);
}

/*--------------------------------------------------*/

foreach($inactiveList as $key => $value){
    $inactiveCountContent .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => \Settings\Main::statusColor()["��������"],
                "margin-right" => "8px"
            ] + $labelStyle,
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Count"
            ]
        ],true)
    ],true);
    $inactiveSumContent .= $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => $key."Sum"
            ]
        ],true)
    ],true);
}

/*--------------------------------------------------*/
$activeCount = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "20px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "����� ����������",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "activeCount"
            ]
        ],true)
    ],true). $activeCountContent
],true);

$activeSum = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "50px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "activeSum"
            ]
        ],true)
    ],true). $activeSumContent
],true);

/*--------------------------------------------------*/

$inactiveCount = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "20px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "�� ��������",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "inactiveCount"
            ]
        ],true)
    ],true). $inactiveCountContent
],true);

$inactiveSum = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "50px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "inactiveSum"
            ]
        ],true)
    ],true). $inactiveSumContent
],true);

/*--------------------------------------------------*/

$blockCount = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "20px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "�������������",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "blockCount"
            ]
        ],true)
    ],true)
],true);

$blockSum = $view->show("inc.div",[
    "type" => "column",
    "style" => [
        "margin-right" => "50px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin" => "20px 0px"
        ],
        "content" => $view->show("inc.text",[
            "text" => "�� �����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => $textStyle,
            "attribute" => [
                "id" => "blockSum"
            ]
        ],true)
    ],true)
],true);

$allcount = $view->show("inc.div",[
    "type" => "row",
    "content" => $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "����� � ����",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => [
                "margin-right" => "40px"
            ] + $textStyle,
            "attribute" => [
                "id" => "allClientsCount"
            ]
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => "������� ���� ����� ��������",
            "style" => [
                "margin-right" => "8px"
            ] + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => "",
            "style" => [
                "margin-right" => "40px"
            ] + $textStyle,
            "attribute" => [
                "id" => "averageLife"
            ]
        ],true)
    ],true)
],true);


/*--------------------------------------------------*/





/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "padding" => "20px",
        "background-color" => "#eaefea",
        "margin-top" => "40px"
    ],
    "content" =>  $allcount. $view->show("inc.div",[
        "type" => "row",
        "content" => $activeCount. $activeSum. $inactiveCount. $inactiveSum. $blockCount. $blockSum
    ],true)
]);







