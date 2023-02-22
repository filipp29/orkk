<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$client = new \Client\Controller();
$buf = new \Debtor\Controller();
$debtorView = $buf->getView();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();

$tdStyle = [
    "font-size" => "12px",
    "border" => "1px var(--modColor_darkest) solid",
    "padding" => "2px 5px",
    "text-align" => "center"
]; 
$textStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px"
];

$buttonStyle = [
    "font-size" => "12px",
    "padding" => "2px 5px",
    "width" => "auto",
    "height" => "auto",
    "min-width" => "10px"
]; 


/*--------------------------------------------------*/

$getEditBlock = function(
        $id,
        $value,
//        $width
) use ($view,$textStyle){
    return $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "margin-top" => "3px",
            "align-items" => "center"
        ],
        "class" => "editBlock",
        "content" => $view->show("inc.input.text",[
            "id" => $id,
            "class" => "salary_param readonly",
            "style" => [
//                "max-width" => $width,
                "height" => "auto",
                "text-align" => "center"
//                "min-width" => $width,
            ] + $textStyle,
            "attribute" => [
                "readonly" => "readonly"
            ],
            "value" => $value
        ],true). $view->show("inc.div",[
            "type" => "row",
            "style" => [
                "margin" => "3px 0px"
            ],
            "content" => $view->show("buttons.normal",[
                "text" => "Редактировать",
                "onclick" => "tableCellEditClick(this)",
                "class" => "editButton",
                "style" => [
                    "font-size" => "12px",
                    "height" => "18px",
                    "width" => "100%"
                ]
            ],true). $view->show("inc.div",[
                "type" => "row",
                "style" => [
                    "width" => "100%",
                    "justify-content" => "center",
                    "align-items" => "center",
                    "height" => "18px"
                ],
                "class" => "acceptBlock hidden",
                "content" => $view->show("buttons.close",[
                    "onclick" => "tableCellEditClose(this,false)",
                    "style" => [
                        "margin-right" => "25px",
                        "height" => "18px"
                    ]
                ],true).$view->show("buttons.accept",[
                    "onclick" => "Admin.salary.bonusTable.save(this)",
                    "style" => [
                        "height" => "18px"
                    ]
                ],true)
            ],true)
        ],true)
    ],true);
};


/*--------------------------------------------------*/



$buf = isset($bonusList) ? $bonusList : [];
$tbody = "";
foreach($buf as $key => $value){
    $tr = "";
    $tdList =[
            $key,
            $getEditBlock("bonusValue",$value),
            $view->get("buttons.red",[
                "text" => "Удалить",
                "onclick" => "Admin.salary.bonusTable.remove(this)",
                "style" => $buttonStyle
            ]).$view->get("inc.vars",[
                "vars" => [
                    "role" => $role,
                    "bonusKey" => $key
                ]
            ]),
        ];
    foreach($tdList as $val){
        $tr .= $view->get("table.td",[
            "content" => $val,
            "style" => $tdStyle
        ]);
    }
    $tbody .= $view->get("table.tr",[
        "content" => $tr
    ]);
}
echo $tbody;