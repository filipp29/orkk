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

$buf = [
    "comment_text" => [
        "manager",
        "assistant"
    ],
    "commentDeleteButton" => [
        "manager",
        "assistant"
    ]
];

$banList = [];
$role = $_COOKIE["orkkrole"];
foreach($buf as $key => $roleList){
    if (in_array($role, $roleList)){
        $banList[] = $key;
    }
}

/*Инициализация--------------------------------------------------*/
$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "class" => "buttonContainer",
    "style" => [
        "justify-content" => "space-between",
        "align-items" => "center",
        "margin-top" => "10px",
        "width" => "100%"
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "id" => "buttonBlock",
        "content" => $view->show("buttons.save",[
            "onclick" => "saveComment(this,`normal`)",
            "style" => [
                "margin-right" => "15px",
                "border" => "1px var(--modBGColor) solid"
            ]
        ],true). $view->show("buttons.clock",[
            "onclick" => "eventButtonClick(this)",
            "style" => [
                "margin-right" => "15px",
                "border" => "1px var(--modBGColor) solid"
            ]
        ],true)
    ],true).$view->show("inc.div",[
        "type" => "row",
        "id" => "dateBlock",
        "class" => "hidden",
        "content" => $view->show("inc.text",[
            "text" => "Дата: ",
            "style" => [
                "color" => "var(--modBGColor)",
                "margin-right" => "8px",
            ]
        ],true). $view->show("inc.input.dateTime",[
            "id" => "comment_eventDate",
            "style" => [
                "margin-right" => "8px",
                "width" => "165px",
                "background-color" => "var(--modBGColor)"
            ]
        ],true). $view->show("buttons.acceptSquare",[
            "onclick" => "saveComment(this,`event`)",
            "style" => [
                "margin-right" => "15px",
                "border" => "1px var(--modBGColor) solid"
            ]
        ],true). $view->show("buttons.closeSquare",[
            "onclick" => "eventButtonCancel(this)",
            "style" => [
                "border" => "1px var(--modBGColor) solid"
            ]
        ],true)
    ],true). $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.input.file_background",[
            "filePathId" => "comment_filePath",
            "fileNameId" => "comment_fileName",
            "textColor" => "var(--modBGColor)"
        ],true)
    ],true)
],true);


$vars = $view->show("inc.var",[
    "key" => "comment_clientId",
    "value" => $params["id"]
],true);


$comments = "";

foreach($commentList as $value){
    $imgBlock = $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "border-radius" => "50%",
            "width" => "48px",
            "height" => "48px",
            "margin-right" => "10px"
        ],
        "content" => $view->show("inc.img",[
            "src" => $value["img"],
            "style" => [
                "border-radius" => "50%",
                "width" => "100%",
                "height" => "auto",
                "margin-bottom" => "15px"
            ]
        ],true). $view->show("buttons.close",[
            "onclick" => "deleteComment(this)",
            "class" => "hidden",
            "attribute" => [
                "id" => "commentDeleteButton"
            ]
        ],true)
    ],true);
    if ($value["type"] == "event"){
        $eventDate = date("d.m.Y - H:i",$value["eventDate"]);
        $eventTitle = $view->show("inc.text",[
            "text" => "Запланировано на {$eventDate}",
            "attribute" => [
                "id" => "commentTitle"
            ],     
            "style" => [
                "width" => "100%",
                "margin-bottom" => "10px",
                "font-weight" => "bolder",
                "justify-content" => "center"
            ]
        ],true);
        $eventButtonBlock = $view->show("inc.div",[
            "type" => "row",
            "id" => "eventButtonBlock",
            "style" => [
                "margin" => "10px",
                "justify-content" => "center",
                "height" => "35px"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "Завершить",
                "onclick" => "closeEvent(this)",
                "style" => [
                    "margin-right" => "10px"
                ]
            ],true). $view->show("buttons.normal",[
                "text" => "Перенести",
                "onclick" => "moveEvent(this)",
                "style" => [
                    "margin-right" => "10px"
                ]
            ],true). $view->show("buttons.red",[
                "text" => "Удалить",
                "onclick" => "deleteComment(this)",
                
                "style" => [
                    "margin-right" => "10px",
                ]
            ],true)
        ],true);   
        $eventChangeDateBlock =  $view->show("inc.div",[
            "type" => "row",
            "id" => "eventDateBlock",
            "class" => "hidden",
            "style" => [
                "margin" => "10px",
                "justify-content" => "center",
                "height" => "35px",
                "align-items" => "center"
            ],
            "content" => $view->show("inc.text",[
                "text" => "Дата: ",
                "style" => [
                    "color" => "var(--modColor_darkest)",
                    "margin-right" => "8px",
                ]
            ],true). $view->show("inc.input.dateTime",[
                "id" => "comment_eventDate_old",
                "style" => [
                    "margin-right" => "8px",
                    "width" => "165px",
                    "background-color" => "var(--modBGColor)"
                ]
            ],true). $view->show("buttons.acceptSquare",[
                "onclick" => "editCommentAccept(this)",
                "style" => [
                    "margin-right" => "15px",
                    "border" => "1px var(--modBGColor) solid"
                ]
            ],true). $view->show("buttons.closeSquare",[
                "onclick" => "eventMoveCancel(this)",
                "style" => [
                    "border" => "1px var(--modBGColor) solid"
                ]
            ],true)
        ],true);
        $eventCloseBlock =  $view->show("inc.div",[
            "type" => "row",
            "id" => "eventCloseBlock",
            "class" => "hidden",
            "style" => [
                "margin" => "10px",
                "justify-content" => "center",
                "height" => "35px",
                "align-items" => "center"
            ],
            "content" => $view->show("buttons.acceptSquare",[
                "onclick" => "closeEventAccept(this)",
                "style" => [
                    "margin-right" => "15px",
                    "border" => "1px var(--modBGColor) solid"
                ]
            ],true). $view->show("buttons.closeSquare",[
                "onclick" => "closeEventCancel(this)",
                "style" => [
                    "border" => "1px var(--modBGColor) solid"
                ]
            ],true)
        ],true);
    }
    else{
        $eventTitle = "";
        $eventButtonBlock = "";
        $eventChangeDateBlock = "";
        $eventCloseBlock = "";
    }
    if ($value["type"] == "clientDoc"){
        $clientDocBlock = $view->show("inc.input.date",[
            "id" => "comment_date",
            "style" => [
                "margin-right" => "10px"
            ],
            "value" => $value["date"]
        ],true);
    }
    else{
        $clientDocBlock = "";
    }
    if (($value["type"] == "block") && (isset($value["twoDocs"]) && $value["twoDocs"])){
        $endFileButton = $view->show("inc.input.file",[
            "filePathId" => "comment_filePathEnd",
            "fileNameId" => "comment_fileNameEnd",
            "filePathValue" => $value["filePathEnd"],
            "fileNameValue" => $value["fileNameEnd"]
        ],true);
        if ($value["filePathEnd"]){
            $fileText = "Файл<br>загружен";
            $fileButton = $view->show("buttons.fileAccept",[
                "onclick" => "getCommentFile(this,`filePathEnd`)",
                "style" => [
                    "height" => "64px",
                    "margin-bottom" => "10px" 
                ]        
            ],true);
        }
        else {
            $fileText = "Файл<br>не загружен";
            $fileButton = $view->show("buttons.fileCancel",[
                "style" => [
                    "height" => "64px",
                    "margin-bottom" => "10px" 
                ]
            ],true);
        }
        $endFileBlock = $view->show("inc.div",[
            "type" => "column",
            "id" => "commentFileBlockEnd",
            "style" => [
                "align-items" => "center",
                "justify-content" => "center",
                "height" => "100%",
                "width" => "150px",
                "padding" => "15px 0px"
            ],
            "content" => $fileButton.$view->show("inc.text",[
                "text" => $fileText,
                "style" => [
                    "height" => "auto",
                    "text-align" => "center",
                    "font-size" => "13px"
                ]
            ],true)
        ],true);
         
    }
    else{
        $endFileBlock = "";
        $endFileButton = "";
    }
    $textBlock = $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "flex-grow" => "1",
            "padding-left" => "10px",
//            "justify-content" => "space-between"
        ],
        "content" => $view->show("inc.text",[
            "text" => $value["title"],
            "style" => [
                "font-size" => "12px",
                "font-weight" => "bolder",
                "padding" => "8px 0px"
            ]
        ],true). $eventTitle. $view->show("inc.input.area",[
            "id" => "comment_text",
            "value" => $value["text"],
            "class" => "readonly inputAreaAutosize",
            "attribute" => [
                "readonly" => "readonly"
            ],
            "style" => [
                "background-color" => "inherit",
                "font-size" => "13px",
                "height" => "auto",
                "width" => "100%"
            ]
        ],true). $view->show("inc.div",[
            "type" => "row",
            "id" => "commentFileInputContainer",
            "style" => [
                "margin" => "10px",
                "margin-top" => "20px"
            ],
            "class" => "hidden",
            "content" => $clientDocBlock. $view->show("inc.input.file",[
                "filePathId" => "comment_filePath",
                "fileNameId" => "comment_fileName",
                "filePathValue" => $value["filePath"],
                "fileNameValue" => $value["fileName"]
            ],true). $endFileButton 
        ],true).$eventButtonBlock. $eventChangeDateBlock. $eventCloseBlock
    ],true);
    if ($value["filePath"]){
        $fileText = "Файл<br>загружен";
        $fileButton = $view->show("buttons.fileAccept",[
            "onclick" => "getCommentFile(this)",
            "style" => [
                "height" => "64px",
                "margin-bottom" => "10px" 
            ]        
        ],true);
    }
    else {
        $fileText = "Файл<br>не загружен";
        $fileButton = $view->show("buttons.fileCancel",[
            "style" => [
                "height" => "64px",
                "margin-bottom" => "10px" 
            ]
        ],true);
    }
    $fileBlock = $view->show("inc.div",[
        "type" => "column",
        "id" => "commentFileBlock",
        "style" => [
            "align-items" => "center",
            "justify-content" => "center",
            "height" => "100%",
            "width" => "150px",
            "padding" => "15px 0px"
        ],
        "content" => $fileButton.$view->show("inc.text",[
            "text" => $fileText,
            "style" => [
                "height" => "auto",
                "text-align" => "center",
                "font-size" => "13px"
            ]
        ],true)
    ],true);
    
    $glPath = \Settings\Main::globalPath();
    
    if (($value["type"] == "fixed") || ($value["type"] == "clientDoc") || ($value["type"] == "important")){
        $typeContent = $view->show("inc.img",[
            "src" => $glPath. "/img/fixed_comment.png",
            "style" => [
                "height" => "20px"
            ]
        ],true);
    }else {
        $typeContent = "";
    }
    
    $editBlock = $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "justify-content" => "space-between",
            "align-items" => "flex-end",
            "width" => "40px",
            "height" => "100%"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "content" => $typeContent,
            "style" => [
                "justify-content" => "flex-end",
                "margin" => "10px"
            ]
        ],true). $view->show("buttons.editComment",[
            "onclick" => "editComment(this)",
            "style" => [
                "height" => "40px",
                "background-color" => "#9c9c9c",
                "transform-origin" => "bottom right"
            ],
            "attribute" => [
                "id" => "commentEditButton"
            ]
        ],true). $view->show("buttons.editCommentAccept",[
            "onclick" => "editCommentAccept(this)",
            "style" => [
                "height" => "40px",
                "background-color" => "#9c9c9c",
                "transform-origin" => "bottom right"
            ],
            "class" => "hidden",
            "attribute" => [
                "id" => "commentEditAcceptButton"
            ]
        ],true)
    ],true);
    
    
    
    $commentVars = $view->show("inc.vars",[
        "vars" => [
            "comment_clientId" => $params["id"],
            "comment_author" => $value["author"],
            "comment_timeStamp" => $value["timeStamp"],
            "comment_type" => $value["type"],
            "comment_type_old" => "normal",
            "comment_eventDate" => (isset($value["eventDate"])) ? $value["eventDate"] : "",
            "comment_dnum" => (isset($value["dnum"])) ? $value["dnum"] : "",
            "comment_docId" => (isset($value["docId"])) ? $value["docId"] : "",
            "comment_posId" => (isset($value["posId"])) ? $value["posId"] : "",
            "comment_blockType" => isset($value["blockType"]) ? $value["blockType"] : "",
            "comment_blockStart" => isset($value["blockStart"]) ? $value["blockStart"] : "",
            "comment_blockEnd" => isset($value["blockEnd"]) ? $value["blockEnd"] : "",
            "_banList_" => Json_u::encode($banList)
        ]
    ],true);
    
    $comments .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "10px",
            "padding-top" => "10px",
            "border-top" => "1px var(--modColor_darkest) dashed",
            "width" => "100%"
        ],
        "class" => "commentContainer",
        "content" => $imgBlock. $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "flex-grow" => "1",
                "background-color" => "#dddddd",
                "border-radius" => "10px"
            ],
            "content" => $textBlock. $fileBlock. $endFileBlock. $editBlock. $commentVars
        ],true)
    ],true);
    
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "class" => "commentContainer",
    "attribute" => [
        "id" => "createCommentContainer"
    ],
    "style" => [
        "width" => "100%",
//        "height" => "100%",
        "align-items" => "flex-start",
        "justify-content" => "flex-start",
        "margin-top" => "10px",
        "padding" => "10px 20px 20px 20px",
        "background-color" => "var(--modColor)"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Сообщение в журнал",
        "style" => [
            "font-size" => "18px",
            "font-weight" => "bolder",
            "margin-bottom" => "10px",
            "color" => "var(--modBGColor)",
            "width" => "100%",
            "text-align" => "center",
            "justify-content" => "center",
            "align-items" => "center"
        ]
    ],true).$view->show("inc.input.area",[
        "id" => "comment_text",
        "style" => [
            "width" => "100%",
            "height" => "135px",
            "background-color" => "var(--modBGColor)",
            "resize" => "vertical"
        ] 
    ],true). $buttonBlock. $vars
]);

echo $comments;










