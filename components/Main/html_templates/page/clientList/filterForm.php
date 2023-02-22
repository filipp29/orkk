<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$inputStyle = [
    "width" => "270px"
];

/*Инициализация--------------------------------------------------*/

$label = [
    "param" => "Поле",
    "type" => "Условие",
    "value" => "Значение"
];

$input = [];
$input["param"] = $view->show("inc.input.select",[
    "id" => "filterFormParam",
    "values" => $paramValueList,
    "value" => $paramValue,
    "style" => $inputStyle,
    "attribute" => [
        "onchange" => "showAddClientListFilterItemForm(this,true)"
    ]
],true);

$input["type"] = $view->show("inc.input.radio",[
    "divType" => "row",
    "id" => "filterFormType",
    "values" => $paramInfo["type"],
    "style" => [
        
    ],
    "optionStyle" => [
        "min-width" => "45px"
    ]
],true); 

switch($paramInfo["valueType"]):
    case "select":
        $input["value"] = $view->show("inc.input.select",[
            "id" => "filterFormValue",
            "values" => $paramInfo["values"],
            "style" => $inputStyle
        ],true);
        break;
    case "text":
        $input["value"] = $view->show("inc.input.text",[
            "id" => "filterFormValue",
            "style" => $inputStyle
        ],true);
        break;
    case "date":
        $input["value"] = $view->show("inc.input.date",[
            "id" => "filterFormValue",
            "style" => $inputStyle
        ],true);
        break;
endswitch;

$content = "";

foreach($input as $key => $value){
    $content .= $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "margin-top" => "20px",
            "width" => "100%"
        ],
        "content" => $view->show("inc.text",[
            "text" => $label[$key],
            "style" => [
                "width" => "130px",
                "justify-content" => "flex-end",
                "margin-right" => "10px"
            ]
        ],true). $value
    ],true);
}

/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "align-items" => "center"
    ],
    "content" => $content. $view->show("buttons.acceptSquare",[
        "onclick" => "filterFormAcceptClick(this)",
        "style" => [
            "margin-top" => "20px"
        ]
    ],true)
]);














