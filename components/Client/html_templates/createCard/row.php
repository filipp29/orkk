<?php
global $globalPath;
$view = \MainController::getViewSt("Main");
if (!isset($style)){
    $style = [];
}


$padding = "8px 0px";

if ($column == 2){
    $col1 = $view->show("inc.div", [
        "type" => "row",
        "content" => $content1,
        "style" => [
            "justify-content" => "flex-end",
            "width" => "32%",
        ]
    ],true);
    $col2 = $view->show("inc.div", [
        "type" => "row",
        "content" => $content2,
        "style" => [
            "flex-grow" => "1",
        ]
    ],true);
    $view->show("inc.div", [
        "type" => "row",
        "content" => $col1.$col2,
        "style" => $style + [
            "width" => "100%",
           "padding" => $padding
        ], 
        "class" =>isset($class) ? $class : "" ,
        "id" => isset($id) ? $id : ""
    ]);
}

if ($column == 3){
    $col1 = $view->show("inc.div", [
        "type" => "row",
        "content" => $content1,
        "style" => [
            "justify-content" => "flex-end",
            "width" => "32%",
        ]
    ],true);
    $col2 = $view->show("inc.div", [
        "type" => "row",
        "content" => $content2,
        "style" => [
            "width" => "32%",
        ]
    ],true);
    $col3 = $view->show("inc.div", [
        "type" => "row",
        "content" => $content3,
        "style" => [
            "width" => "32%",
        ]
    ],true);
    $view->show("inc.div", [
        "type" => "row",
        "content" => $col1.$col2.$col3,
        "style" => $style + [
            "width" => "100%",
            "padding" => $padding
        ], 
        "class" =>isset($class) ? $class." rowContainer" : " rowContainer",
        "id" => isset($id) ? $id : ""
    ]);
}




