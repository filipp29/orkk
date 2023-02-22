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


$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px",
    "height" => "auto"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px",
    "min-width" => "120px"
];

/*--------------------------------------------------*/

$getPlacementButton = function(
        $docPlacement
)use($view,$textStyle){
    
    $paramList = [
        "У менеджера" =>  [
            "bgColor" => "#FFD700",
            "color" => "var(--modColor_darkest)",
            "text" => "МЕН"
        ],
        "У клиента" => [
            "bgColor" => "#FF4500",
            "color" => "var(--modColor_darkest)",
            "text" => "КЛНТ"
        ],
        "У офис менеджера" => [
            "bgColor" => "#00FFFF",
            "color" => "var(--modColor_darkest)",
            "text" => "ОМЕН"
        ] ,
        "В бухгалтерии" => [
            "bgColor" => "#00FF00",
            "color" => "var(--modColor_darkest)",
            "text" => "БУХ"
        ],
        "Есть" =>  [
            "bgColor" => "var(--modColor)",
            "color" => "white",
            "text" => "ЕСТЬ"
        ],
        "Нет" => [
            "bgColor" => "#B22222",
            "color" => "white",
            "text" => "НЕТ"
        ],
    ];
    $param = $paramList[$docPlacement];
    $vars = [
        "doc_docPlacement" => $docPlacement
    ];
    return $view->get("inc.div",[
        "type" => "row",
        "attribute" => [
        ],
        "style" => [
            "justify-content" => "center",
            "align-items" => "center",
            "margin" => "10px 10px 0px 0px"
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



/*Инициализация--------------------------------------------------*/

$content = "";

foreach($params as $value){
    $content .= $getPlacementButton($value);
}




/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "row",
    "style" => [
        "flex-wrap" => "wrap",
        "width" => "100%",
        "justify-content" => "center"
    ],
    "content" => $content
]);

/*Переменные--------------------------------------------------*/
/*--------------------------------------------------*/





















