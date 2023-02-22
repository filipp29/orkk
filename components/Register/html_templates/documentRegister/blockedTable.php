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
    "dnum" => "Договор",
    "name" => "Наименование",
    "blockType" => "Тип",
    "blockStart" => "Дата начала",
    "blockEnd" => "Дата окончания",
    "manager" => "Менеджер"
];

$tableWidth = [
    "dnum" => "80px",
    "name" => "Наименование",
    "blockType" => "30px",
    "blockStart" => "120px",
    "blockEnd" => "120px",
    "manager" => "180px"
];
$tableVertical = [
    "dnum" => "center",
    "name" => "center",
    "blockType" => "center",
    "blockStart" => "center",
    "blockEnd" => "center",
    "manager" => "center"
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
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
];

/*--------------------------------------------------*/

$getBlockButton = function(
        $block,
        $type
)use($view,$textStyle){
    $timeStamp = ($type == "start") ? $block["blockStart"] : $block["blockEnd"];
    if ($timeStamp){
        $text = date("d.m.Y",$timeStamp);
    }
    else{
        $text = "Бессрочно";
    }
    $document = true;
    $fileStart = $block["filePath"];
    $fileEnd = $block["filePathEnd"];
    $twoDocs = $block["twoDocs"];
    if (($type == "start") || (!$twoDocs)){
        if (!$fileStart){
            $document = false;
        }
    }
    else{
        if (!$fileEnd){
            $document = false;
        }
    }
    $color = $document ? "normal" : "red";
    return $view->get("buttons.{$color}",[
        "text" => $text,
        "onclick" => "DocumentRegister.showClientCard(this)",
        "style" => [
            "padding" => "0px",
            "min-width" => "10px",
            "height" => "25px"
        ] + $textStyle
    ]);
};

/*--------------------------------------------------*/

$getTypeBlock = function(
        $block
){
    if ($block["twoDocs"]){
        return "II";
    }
    else{
        return "I";
    }
};

/*Инициализация--------------------------------------------------*/


$headerContent = "";
foreach($tableHeader as $key => $value){
    $headerContent .= $view->show("table.th",[
        "content" => $view->show("inc.text",[
            "text" => $value,
            "style" => [
                "color" => "var(--modBGColor)",
                "height" => "auto"
            ],
            "class" => "clietListSortButton",
        ],true),
        "style" => [
            "text-align" => "center",
            "padding" => "0px 5px",
            "height" => "auto",
            "width" => $tableWidth[$key],
            "border" => "1px solid var(--modColor_light)"
        ]
    ],true);
}

$body = "";
$trColor = 0;

foreach($accountList as $value){
    $info = $value["info"];
    $blockList = $value["blockList"];
    $dnum = $info["dnum"];
    
    $nameBlock = $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => $info["clientType"],
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true). $view->show("inc.text",[
            "text" => "\"{$info["name"]}\"",
            "style" => [
                "margin-right" => "4px"
            ] + $textStyle
        ],true)
    ],true);
    
    $accountKeys = [
        "dnum",
        "name",
    ];
    $clientKeys = [
        "address",
        "contacts",
        "remark",
        "hardware",
        "loginList",
        "speed",
        "staticIp",
        "manager"
    ];
    $varsBlock = $view->get("inc.vars",[
        "vars" => [
            "clientId" => $blockList[0]["clientId"]
        ]
    ]);
    $typeBlock = $getTypeBlock($blockList[0]). $varsBlock;
    $startBlock = $getBlockButton($blockList[0],"start");
    $endBlock = $getBlockButton($blockList[0],"end");
    $manager = profileGetUsername($blockList[0]["manager"]);
    $tr = [
        "dnum" => $dnum,
        "name" => $nameBlock,
        "blockType" => $typeBlock,
        "blockStart" => $startBlock,
        "blockEnd" => $endBlock,
        "manager" => $manager
    ];
    $trContent = "";
    $count = count($blockList);
    foreach($tr as $key => $value){
        if (in_array($key, $accountKeys)){
            $attribute = [
                "rowspan" => $count
            ];
        }
        else{
            $attribute = [
            ];
        }
        
        $trContent .= $view->show("table.td",[
            "content" => $value,
            "attribute" => $attribute,
            "style" => $tdStyle
        ],true);
    }
    $trColor++;
    $body .= $view->show("table.tr",[
        "content" => $trContent,
        "class" => ($trColor % 2 != 0) ? "odd" : "even"
    ],true); 
    for($i = 1; $i < $count; $i++){
        $varsBlock = $view->get("inc.vars",[
            "vars" => [
                "clientId" => $blockList[$i]["clientId"]
            ]
        ]);
        $typeBlock = $getTypeBlock($blockList[$i]). $varsBlock;
        $startBlock = $getBlockButton($blockList[$i],"start");
        $endBlock = $getBlockButton($blockList[$i],"end");
        $manager = profileGetUsername($blockList[$i]["manager"]);
        $tr = [
            "blockType" => $typeBlock,
            "blockStart" => $startBlock,
            "blockEnd" => $endBlock,
            "manager" => $manager
        ];
        $trContent = "";
        foreach($tr as $key => $value){
            $trContent .= $view->show("table.td",[
                "content" => $value,
                "style" => $tdStyle
            ],true);
        }
        $body .= $view->show("table.tr",[
            "content" => $trContent,
            "class" => ($trColor % 2 != 0) ? "odd" : "even"
        ],true);
    }
}


$thead = $view->show("table.tr",[
    "content" => $headerContent
],true);




/*Отображение--------------------------------------------------*/


$view->show("table.main",[
    "thead" => $thead,
    "tbody" => $body,
    "class" => "supportTable"
]);


/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/



















