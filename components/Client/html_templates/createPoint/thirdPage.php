<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$buf = new \Client\Controller();
$viewClient = $buf->getView();
unset($buf);
$settings = new \Settings\Main();



$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "justify-content" => "space-between",
        "align-items" => "center",
        "margin-top" => "10px",
        "width" => "700px"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("buttons.normal",[
            "text" => "????????",
            "onclick" => "cancelCreateThird(this,true)",
            "style" => [
                "margin-right" => "15px"
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "?????????",
            "onclick" => "saveClientWithFile(this,true,onCloseTab(this),{$getDoc})"
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.input.file",[
            "filePathId" => "filePath",
            "fileNameId" => "fileName",
        ],true)
    ],true)
],true);

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "width" => "100%",
        "height" => "100%",
        "align-items" => "center",
        "justify-content" => "center"
    ],
    "content" => $view->show("inc.div",[
        "type" => "column",
        "class" => "renewCommentContainer hidden",
            "style" => [
            "width" => "100%",
            "height" => "100%",
            "align-items" => "center",
            "justify-content" => "center"
        ],
        "content" => $view->show("inc.text",[
            "text" => "??????????? ? ?????????? ?????:",
        ],true). $view->show("inc.input.area",[
            "id" => "renew_comment",
            "style" => [
                "width" => "700px"
            ] 
        ],true)
    ],true). $view->show("inc.text",[
        "text" => "???????????:",
    ],true). $view->show("inc.input.area",[
        "id" => "comment",
        "style" => [
            "width" => "700px"
        ] 
    ],true). $buttonBlock
]);




