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
            "text" => "Отменить",
            "onclick" => "cancelCreateThird(this)",
            "style" => [
                "margin-right" => "15px"
            ]
        ],true). $view->show("buttons.normal",[
            "text" => "Сохранить",
            "onclick" => "saveClientWithFile(this,true,onCreateClient(this),getNewSpecificationDoc)"
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.input.file",[
            "filePathId" => "filePathOld",
            "fileNameId" => "fileNameOld",
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
    "content" => $view->show("inc.text",[
        "text" => "Комментарий:",
    ],true). $view->show("inc.input.area",[
        "id" => "comment",
        "style" => [
            "width" => "700px"
        ] 
    ],true). $buttonBlock
]);




