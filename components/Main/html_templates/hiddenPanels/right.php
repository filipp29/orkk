<?php
/*
 * height - высота кнопки,
 * buttonText - текст кнопки,
 * content - контент,
 * top - расстояние от верха
 * 
 *  */

global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$textStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$panelButtonStyle = [
    
    "background-color" => "var(--modColor_dark)",
    "color" => "var(--modBGColor)",
    "align-items" => "center",
    "justify-content" => "center",
    "height" => $height,
    "width" => "25px",
    "writing-mode" => "vertical-lr",
    "text-orientation" => "upright",
    "font-size" => "20px",
    "cursor" => "pointer",

];

/*Инициализация--------------------------------------------------*/

$panelButton = $view->show("inc.div",[
    "type" => "row",
    "content" => $buttonText,
    "style" => [
        "border-radius" => "50px 0px 0px 50px"
    ] + $panelButtonStyle,
    "attribute" => [
        "onclick" => "hiddenPanelToggle(this)"
    ]
],true);

$panelContent = $view->show("inc.div",[
    "type" => "row",
    "content" => $content,
    "style" => [
        "min-height" => $height,
        "min-width" => "250px",
        "border" => "1px var(--modColor_dark) solid",
        "background-color" => "var(--modBGColor)",
        "overflow" => "hidden",
        "padding" => "0px 5px 10px 10px",
        "height" => "auto",
        "overflow-y" => "auto",
        "max-height" => $maxHeight
    ]
],true);

$panelHiddenBlock = $view->show("inc.div",[
    "type" => "row",
    "id" => "hiddenPanelContent",
    "class" => "hidden",
    "content" => $panelContent. $view->show("inc.div",[
        "type" => "row",
        "content" => "",
        "style" => [
            "border-radius" => "0px 50px 50px 0px"
        ] + $panelButtonStyle,
        "attribute" => [
            "onclick" => "hiddenPanelToggle(this)"
        ]
    ],true)
],true);


/*Отображение--------------------------------------------------*/

$panelContainer = $view->show("inc.div",[
    "type" => "row",
    "class" => "hiddenPanel",
    "content" => $panelButton. $panelHiddenBlock,
    "style" => [
        "position" => "fixed",
        "top" => $top,
        "right" => "0px",
        
    ]
]);






