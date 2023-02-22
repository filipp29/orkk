<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();

unset($buf);
$buf = new \Salary\Controller();
$salaryView = $buf->getView();

unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$rowPath = "page.clientList.row";
$tableLabelStyle = [
    "font-size" => "20px",
    "font-wight" => "600",
    "margin" => "20px 0px 10px 30px"
];

/*--------------------------------------------------*/

$getCloseBlock = function(
        $profile
)use($view,$paramList){
    if (isset($paramList[$profile]["closed"]) && ($paramList[$profile]["closed"])){
        return $view->get("inc.text",[
            "text" => "Ğååñòğ çàêğûò",
            "style" => [
                "background-color" => "#eaefea",
                "padding" => "8px 15px",
                "margin-top" => "25px",
                "width" => "fit-content"
            ]
        ]);
    }
    else{
        return "";
    }
};

/*Èíèöèàëèçàöèÿ--------------------------------------------------*/

$years = [
    "2021" => "2021",
    "2022" => "2022" ,
    "2023" => "2023"
];
$months = [
    "01" => "ßíâàğü",
    "02" => "Ôåâğàëü",
    "03" => "Ìàğò",
    "04" => "Àïğåëü",
    "05" => "Ìàé",
    "06" => "Èşíü",
    "07" => "Èşëü",
    "08" => "Àâãóñò",
    "09" => "Ñåíòÿáğü",
    "10" => "Îêòÿáğü",
    "11" => "Íîÿáğü",
    "12" => "Äåêàáğü"
];

$menu = "";
$selected = "menuItem_selected";
foreach($menuList as $key => $value){
    $menu .= $view->show("inc.div",[
        "type" => "row",
        "class" => "menuItem {$selected}",
        "content" => $value,
        "id" => $key,
        "attribute" => [
            "onclick" => "supportRegisterMenuSelect(this)"
        ],
        "style" => [
            "cursor" => "pointer",
            "font-size" => "20px",
            "color" => "var(--modColor_darkest)",
            "opacity" => "0.4",
            "transform" => "scale(0.92)",
            "width" => "fit-content",
            "margin-right" => "10px"
        ]
    ],true);
    $selected = "";    
}

$dateBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-bottom" => "10px"
    ],
    "content" => $view->show("inc.text",[
        "text" => "Ãîä:",
        "style" => [
            "margin-right" => "4px"
        ]
    ],true). $view->show("inc.input.select",[
        "id" => "updateYear",
        "values" => $years,
        "value" => $year,
        "style" => [
            "margin-right" => "8px"
        ]
    ],true). $view->show("inc.text",[
        "text" => "Ìåñÿö:",
        "style" => [
            "margin-right" => "4px"
        ]
    ],true). $view->show("inc.input.select",[
        "id" => "updateMonth",
        "values" => $months,
        "value" => $month,
        "style" => [
            "margin-right" => "8px"
        ]
    ],true)
],true);

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "content" => $dateBlock. $view->show("buttons.normal",[
        "text" => "Îáíîâèòü",
        "onclick" => "Salary.reloadSalaryTable(this)",
        "style" => [
            "height" => "25px",
            "font-size" => "14px",
            "width" => "auto",
            "min-width" => "auto",
            "margin-left" => "15px"
        ]
    ],true). $view->get("buttons.red",[
        "text" => "Ñîõğàíèòü",
        "onclick" => "Salary.saveAll(this,'.page')",
        "style" => [
            "height" => "25px",
            "font-size" => "14px",
            "width" => "auto",
            "min-width" => "auto",
            "margin-left" => "15px",
            "padding" => "0px 10px"
        ]
    ]). $view->get("inc.text",[
        "text" => "Ñîõğàíåí: ".($reportDate ? $reportDate : "íèêîãäà"),
        "style" => [
            "height" => "25px",
            "font-size" => "14px",
            "width" => "auto",
            "min-width" => "auto",
            "margin-left" => "15px",
            "opacity" => "0.7"
        ]
    ]),
    "style" => [
        "margin" => "10px 0px"
    ]
],true);

$yearProgressBar = $salaryView->get("salaryTable.yearProgressbar",[
    "currentYearPlan" => $currentYearPlan,
    "yearPlanPercent" => $yearPlanPercent,
    "yearPlan" => $yearPlan
] + $yearProgress);

$header = $view->show("inc.div",[
    "type" => "column",
    "content" => $buttonBlock. $yearProgressBar . $view->show("inc.div",[
        "type" => "row",
        "style" => [
            "height" => "40px",
            "align-items" => "flex-end",
            "margin" => "10px",
            "padding" => "0px 10px",
            "justify-content" => "space-between",
            "width" => "100%"
        ],
        "content" => $view->show("inc.div",[
            "type" => "row",
            "class" => "menuBlock",
            "content" => $menu
        ],true)
    ],true)
],true);


$body = "";
$hidden = "";
foreach($menuList as $profile => $unused){
    if ($profile == "unmarked"){
        $body .= $view->show("inc.div",[
            "type" => "column",
            "class" => "tableContainer {$hidden}",
            "id" => "{$profile}_table",
            "content" => $salaryView->show("salaryTable.unmarkedTable",[
                "docList" => isset($docList[$profile]) ? $docList[$profile] : [],
                "payManager" => $profile,
                "changeList" => isset($changeList[$profile]) ? $changeList[$profile] : [],
                "currentPlan" => isset($planList[$profile]) ? $planList[$profile] : "0",
                "paramList" => isset($paramList[$profile]) ? $paramList[$profile] : [],
                "salaryParams" => isset($salaryParams["manager"]) ? $salaryParams["manager"] : [],
                "salaryList" => isset($salaryList[$profile]) ? $salaryList[$profile] : [],
                "bonusSum" => isset($bonusList[$profile]) ? $bonusList[$profile] : "",
            ],true)     
        ],true);
    }
    else if ($profileList[$profile] == "manager"){
        $body .= $view->show("inc.div",[
            "type" => "column",
            "class" => "tableContainer {$hidden}",
            "id" => "{$profile}_table",
            "content" => $salaryView->show("salaryTable.managerTable",[
                "docList" => isset($docList[$profile]) ? $docList[$profile] : [],
                "payManager" => $profile,
                "changeList" => isset($changeList[$profile]) ? $changeList[$profile] : [],
                "currentPlan" => isset($planList[$profile]) ? $planList[$profile] : "0",
                "currentRate" => isset($rateList[$profile]) ? $rateList[$profile] : "0.0",
                "percentList" => $percentList[$profile],
                "paramList" => isset($paramList[$profile]) ? $paramList[$profile] : [],
                "salaryParams" => isset($salaryParams["manager"]) ? $salaryParams["manager"] : [],
                "salaryList" => isset($salaryList[$profile]) ? $salaryList[$profile] : [],
                "bonusSum" => isset($bonusList[$profile]) ? $bonusList[$profile] : "",
                "sum" => $sumList[$profile],
                "progress" => $progress[$profile],
                "balanceTable" => $balanceTable
            ],true). $getCloseBlock($profile)      
        ],true);
        $hidden = "hidden";    
    }
    else if($profileList[$profile] == "leader"){
        $body .= $view->show("inc.div",[
            "type" => "column",
            "class" => "tableContainer {$hidden}",
            "id" => "{$profile}_table",
            "content" => $salaryView->show("salaryTable.leaderTable",[
                "manager" => $profile,
                "changeList" => isset($changeList[$profile]) ? $changeList[$profile] : [],
                "currentPlan" => isset($planList[$profile]) ? $planList[$profile] : "0",
                
                "paramList" => isset($paramList[$profile]) ? $paramList[$profile] : [],
                "salaryParams" => isset($salaryParams["manager"]) ? $salaryParams["manager"] : [],
                "salaryList" => isset($salaryList[$profile]) ? $salaryList[$profile] : [],
                "bonusSum" => isset($bonusList[$profile]) ? $bonusList[$profile] : "",
                "sum" => $sumList[$profile]
            ],true). $getCloseBlock($profile)        
        ],true);
        $hidden = "hidden";
    }
    else{
        $body .= $view->show("inc.div",[
            "type" => "column",
            "class" => "tableContainer {$hidden}",
            "id" => "{$profile}_table",
            "content" => $salaryView->show("salaryTable.otherTable",[
                "manager" => $profile,
                "changeList" => isset($changeList[$profile]) ? $changeList[$profile] : [],
                "currentPlan" => isset($planList[$profile]) ? $planList[$profile] : "0",
                "paramList" => isset($paramList[$profile]) ? $paramList[$profile] : [],
                "salaryParams" => isset($salaryParams["manager"]) ? $salaryParams["manager"] : [],
                "salaryList" => isset($salaryList[$profile]) ? $salaryList[$profile] : [],
                "bonusSum" => isset($bonusList[$profile]) ? $bonusList[$profile] : "",
                "sum" => $sumList[$profile],
                "otherBonus" => $otherBonusList[$profile]
            ],true). $getCloseBlock($profile)       
        ],true);
        $hidden = "hidden";
    }
}




/*Îòîáğàæåíèå--------------------------------------------------*/
//$view->show("page.agreementRegister.menu");


$view->show("inc.div",[
    "type" => "column",
    "content" => $header. $body,
    "style" => [
        "margin-bottom" => "50px"
    ]
]);


/*Ïåğåìåííûå--------------------------------------------------*/
/*--------------------------------------------------*/
$vars = [
    "tabTitle" => "Çàğïëàòà",
    "year" => $year,
    "month" => $month
];

$view->show("inc.vars",[
    "vars" => $vars
]);

























