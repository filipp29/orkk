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
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];


/*Инициализация--------------------------------------------------*/

$result = "";
foreach($history as $key => $value){
    if ($value["type"] == "change"){
        $author = profileGetUsername($value["author"]);
        $date = date("d.m.Y - H:i:s",$value["timeStamp"]);
        $title = $view->show("inc.text",[
            "text" => "{$date} {$author}",
            "style" => [
                "paddin-bottom" => "3px",
                "border-bottom" => "1px dashed var(--modColor_darkest)",
                "cursor" => "pointer",
                "color" => "var(--modColor_darkest)"
            ],
            "attribute" => [
                "onclick" => "hiddenTextTilteClick(this)",
                "id" => "hiddenTextTitle"
            ],
        ],true);
        $tbody = $view->show("table.tr",[
            "content" => $view->show("table.th",[
                "content" => "Параметр",
                "style" => [
                    "text-align" => "left",
                ]
            ],true). $view->show("table.th",[
                "content" => "Предыдущее значение",
                "style" => [
                    "text-align" => "left",
                    "width" => "30%"
                ]
            ],true). $view->show("table.th",[
                "content" => "Новое значение",
                "style" => [
                    "text-align" => "left",
                    "width" => "30%"
                ]
            ],true)
        ],true);
        $i = 0;
        foreach($value["changeList"] as $k => $val){
            $i++;
            if ($i % 2 == 0){
                $class = "even";
            }
            else{
                $class = "odd";
            }
            $tbody .= $view->show("table.tr",[
                "class" => $class,
                "content" => $view->show("table.td",[
                    "content" => $val["name"],
                ],true). $view->show("table.td",[
                    "content" => $val["prev"],
                ],true). $view->show("table.td",[
                    "content" => $val["next"],
                ],true)
            ],true);
        }
        $table = $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "10px",
                "margin-left" => "25px",
                "border" => "1px solid var(--modColor)"
            ],
            "id" => "hiddenText",
            "class" => "hidden",
            "content" => $view->show("table.main",[
                "tbody" => $tbody
            ],true)
        ],true);

        $result .= $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "padding" => "8px",
            ],
            "class" => "hiddenTextContainer",
            "content" => $title. $table
        ],true);
    }
    else if($value["type"] == "removeContract"){
        $author = profileGetUsername($value["author"]);
        $date = date("d.m.Y - H:i:s",$value["timeStamp"]);
        $title = $view->show("inc.text",[
            "text" => "{$date} {$author} - откат",
            "style" => [
                "paddin-bottom" => "3px",
                "color" => "#A60000"
            ],
            "attribute" => [
                "id" => "hiddenTextTitle"
            ],
        ],true);
            
        $result .= $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "padding" => "8px",
            ],
            "class" => "hiddenTextContainer",
            "content" => $title
        ],true);
    }
    else if($value["type"] == "block"){
        $blockStart = date("d.m.Y",$value["blockStart"]);
        $blockEnd = date("d.m.Y",$value["blockEnd"]);
        $author = profileGetUsername($value["author"]);
        $date = date("d.m.Y - H:i:s",$value["timeStamp"]);
        $title = $view->show("inc.text",[
            "text" => "{$date} {$author} - блок",
            "style" => [
                "paddin-bottom" => "3px",
                "cursor" => "pointer",
                "color" => "#FF6F00",
                "border-bottom" => "1px dashed var(--modColor_darkest)",
            ],
            "attribute" => [
                "onclick" => "hiddenTextTilteClick(this)",
                "id" => "hiddenTextTitle"
            ],
        ],true);
        $hiddenText = $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "10px",
                "margin-left" => "25px",
            ],
            "id" => "hiddenText",
            "class" => "hidden",
            "content" => $view->show("inc.text",[
                "text" => "Блок с {$blockStart} по {$blockEnd}"
            ],true)
        ],true);    
        $result .= $view->show("inc.div",[
            "type" => "column",
            "style" => [
                "padding" => "8px",
            ],
            "class" => "hiddenTextContainer",
            "content" => $title. $hiddenText
        ],true);
    }
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "width" => "800px"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Развернуть все",
        "style" => [
            "paddin-bottom" => "3px",
            "border-bottom" => "1px dashed var(--modColor_darkest)",
            "cursor" => "pointer",
            "color" => "var(--modColor_darkest)"
        ],
        "attribute" => [
            "onclick" => "hiddenHistoryClick(this)"
        ]
    ],true).$result
]);













