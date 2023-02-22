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
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "0px 10px"
];

$extraDocList = \Settings\Account::placementKeys();

/*--------------------------------------------------*/

$getPlacementButton = function(
        $key
)use($view,$textStyle,$info){
    
    $paramList = \Settings\Main::docPlacementButtonParams();
    $vars = [
        "doc_placement" => ($info[$key]) ? $info[$key] : "Нет",
        "doc_docType" => $key,
        "doc_docId" => $info["docId"],
        "doc_dnum" => $info["dnum"]
    ];
    $param = $paramList[$vars["doc_placement"]];
    return $view->get("inc.div",[
        "type" => "row",
        "attribute" => [
            "onclick" => "DocumentRegister.placementButtonClick(this)"
        ],
        "style" => [
            "justify-content" => "center",
            "align-items" => "center",
        ],
        "class" => "placementButton",
        "content" => $view->get("inc.text",[
            "text" => $param["text"],
            "attribute" => [
                "id" => "button_text"
            ],
            "style" => [
                "background-color" => $param["bgColor"],
                "color" => $param["color"],
                "cursor" => "pointer",
                "width" => "auto",
                "height" => "auto",
                "padding" => "3px",
                "border" => "1px var(--modColor_darkest) solid"
            ] + $textStyle
        ]). $view->get("inc.vars",[
            "vars" => $vars
        ])
    ]);
};



/*--------------------------------------------------*/

$getRow = function(
        $key,
)use($view,$textStyle,$info,$extraDocList,$getPlacementButton){
    $vars = $view->get("inc.vars",[
        "vars" => [
            "placementType" => $key
        ]
    ]);
    return $view->get("inc.div",[
        "type" => "row",
        "style" => [
            "align-items" => "center",
            "margin" => "10px 0px"
        ],
        "id" => "{$key}_block",
        "content" => $view->get("inc.text",[
            "text" => $extraDocList[$key],
            "style" => [
                "margin-right" => "15px",
                "justify-content" => "flex-end",
                "width" => "240px"
            ] + $textStyle
        ]). $getPlacementButton($key). $vars
    ]);
};

/*Инициализация--------------------------------------------------*/

//$body  = $view->get("inc.div",[
//    "type" => "row",
//    "content" => $view->get("inc.text",[
//        "text" => "Документ",
//        "style" => [
//            "margin-right" => "15px"
//        ] + $textStyle
//    ]). $getPlacementButton($placementList["docPlacement"])
//]);
$body = "";
foreach($extraDocList as $key => $unused){
    $body .= $getRow($key);
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "align-items" => "flex-start",
        "justify-content" => "center"
    ],
    "content" => $body
]);













