<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();


/*Инициализация--------------------------------------------------*/

$hotFilterList = [
    "profile_{$_COOKIE["login"]}" => "Мои",
    "activeClients" => "Активные клиенты",
    "coldClients" => "Холодные клиенты",
    "inactiveClients" => "Отключенные",
    "reofferClients" => "Повторное предложение",
    "district_kzbi" => "КЖБИ",
    "district_ksk" => "КСК",
    "district_center" => "Центр",
];
$hotFilterClass = [
    "profile_{$_COOKIE["login"]}" => "filterParamHotProfile",
    "activeClients" => "filterParamHotStatus",
    "coldClients" => "filterParamHotStatus",
    "inactiveClients" => "filterParamHotStatus",
    "reofferClients" => "filterParamHotStatus",
    "district_kzbi" => "filterParamHotDistrict",
    "district_ksk" => "filterParamHotDistrict",
    "district_center" => "filterParamHotDistrict",
];
$query = [
    "profile_{$_COOKIE["login"]}"
];
foreach(\Settings\Main::managerList() as $profile => $name){
    
    if ($_COOKIE["login"] != $profile){
        $hotFilterClass["profile_{$profile}"] = "filterParamHotProfile";
        $hotFilterList["profile_{$profile}"] = explode(" ", $name)[0];
        $query[] = "profile_{$profile}";
    }
}
$buf = [
    "activeClients",
    "coldClients",
    "inactiveClients",
    "reofferClients",
    "district_kzbi",
    "district_ksk",
    "district_center",
];
foreach($buf as $key){
    $query[] = $key;
}
$hotFilterText = "";
foreach($query as $key){
    $value = $hotFilterList[$key];
    $hotFilterText .= $view->show("inc.input.checkbox",[
        "text" => $value,
        "id" => $key,
        "style" => [
            "margin" => "0px 0px 0px 8px",
        ],
        "class" => $hotFilterClass[$key],
        "attribute" => [
            "data_action" => "=",
        ]
    ],true);
}

$hotFilters = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        
    ],
    "content" => $view->show("inc.text",[
        "text" => "Горячие фильтры:",
        "style" => [
            
        ]
    ],true). $hotFilterText
],true);

$filterBlock = $view->show("inc.div",[
    "type" => "column",
    "style" => [
    ],
    "content" => $view->show("inc.div",[
        "type" => "row",
        "id" => "paramsContainer",
    ],true). $view->show("inc.div",[
        "type" => "row",
            "style" => [
            "align-items" => "center",
            "margin-top" => "10px",
            "border-bottom" => "1px var(--modColor_darkest) dashed",
            "width" => "120px",
            "cursor" => "pointer",
        ],
        "attribute" => [
            "onclick" => "showAddClientListFilterItemForm(this)"
        ],
        "content" => $view->show("inc.img",[
            "src" => "/_modules/orkkNew/img/button_plus.png",
            "style" => [
                "height" => "10px",
                "margin-right" => "3px"
            ]
        ],true). $view->show("inc.text",[
            "text" => "Добавить фильтр",
            "style" => [
                "height" => "auto",
                "font-size" => "12px"
            ]
        ],true)
    ],true)
],true);

$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin" => "15px 0px 0px 0px",
    ],
    "content" => $view->show("buttons.normal",[
        "text" => "Фильтр",
        "onclick" => "clientListFilter(this)",
        "style" => [
            "width" => "80px",
            "min-width" => "80px",
            "height" => "28px"
        ]
    ],true)
],true);




/*Отображение--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "style" => [
        "width" => "99%",
        "border" => "1px solid var(--modColor_dark)",
        "padding" => "10px",
        "margin" => "10px 0px"
    ],
    "content" => $hotFilters. $filterBlock. $buttonBlock
]);











