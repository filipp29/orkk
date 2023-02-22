<?php
global $globalPath;
$buf = new \Main\Controller();
$view = $buf->getView();
unset($buf);
$client = new \Client\Controller();
unset($buf);
$settings = new \Settings\Main();
$settingsClient = new \Settings\Client();
$titleStyle = [
    "font-size" => "25px"
];
$labelStyle = [
    "margin" => "0px 10px",
    "font-weight" => "bolder"
];
$textStyle = [
    "font-size" => "14px",
    "height" => "18px"
];
$buttonStyle = [
    "width" => "auto",
    "padding" => "5px 10px",
    "margin-right" => "15px"
];
$fontSize = "14px";

function getCheckboxValue(
        $value
){
    return ($value == "0") ? "�� ���������" : "���������";
}


/*�������������--------------------------------------------------*/

$paramKeys = $settingsClient->paramKeys();
$show = [];
$show["�����"] = getAddress($params);
$show["�����"] = getTariff($params);
$show["����������� IP �����"] = getCheckboxValue($params["staticIp"]);
$show["�����������"] = $params["connectSum"];
$show["��� �������"] = ($params["payType"] == 1) ? "��������" : "�����������";
$show["������������"] = $params["hardware"];
$show["���� ��������"] = $params["contractDate"];
$show["���� ���������"] = $params["activateDate"];
$show["������ �������"] = $params["clientStatus"];
$show["�����������"] = $params["attractType"];
$show["���������"] = $params["competitor"];
$show["��� �����������"] = $params["connectType"];
$show["IP ���������"] = getCheckboxValue($params["ipPhone"]);
$show["��������� ��"] = getCheckboxValue($params["kTv"]);
$show["����� ����������"] = getCheckboxValue($params["cameras"]);
$show["��������� ������������"] = getCheckboxValue($params["service"]);
$show["��������"] = profileGetUsername($params["manager"]);

$leftSide = [
    "�����",
    "�����",
    "����������� IP �����",
    "�����������",
    "��� �������",
    "������������",
    "���� ��������",
    "���� ���������",
    "������ �������"
];
$rightSide = [
    "�����������",
    "���������",
    "��� �����������",
    "IP ���������",
    "��������� ��",
    "����� ����������",
    "��������� ������������",
];
$leftContent = "";
$rightContent = "";
foreach($leftSide as $key){
    $leftContent .= $view->show("inc.div",[
        "type" => "row",
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle
        ],true). $view->show("inc.text",[
            "text" => $show[$key],
            "style" => $textStyle
        ],true)
    ],true);
}
foreach($rightSide as $key){
    $rightContent .= $view->show("inc.div",[
        "type" => "row",
        
        "content" => $view->show("inc.text",[
            "text" => $key. ":",
            "style" => $textStyle + $labelStyle 
        ],true). $view->show("inc.text",[
            "text" => $show[$key],
            "style" => $textStyle
        ],true)
    ],true);
}

$rightContent .= $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "margin-top" => "20px"
    ],
    "content" => $view->show("inc.text",[
        "text" => "��������". ":",
        "style" => [
            "font-size" => "20px"
        ]+$textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => $show["��������"],
        "style" => [
            "font-size" => "20px"
        ] + $textStyle
    ],true)
],true);

$title = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "margin-bottom" => "20px"
    ],
    "content" => $view->show("inc.text",[
        "text" => $params["dnum"],
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => $params["clientType"],
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "'{$params["name"]}'",
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true). $view->show("inc.text",[
        "text" => "'{$params["remark"]}'",
        "style" => $titleStyle + $textStyle + $labelStyle
    ],true)
],true);

$body =  $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
    ],
    "content" => $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "50%"
        ],
        "content" => $leftContent
    ],true). $view->show("inc.div",[
        "type" => "column",
        "style" => [
            "width" => "50%"
        ],
        "content" => $rightContent
    ],true)
],true);       
        
$buttonBlock = $view->show("inc.div",[
    "type" => "row",
    "style" => [
        "width" => "100%",
        "justify-content" => "flex-start",
        "margin-top" => "30px"
    ],
    "content" => $view->show("buttons.normal",[
        "text" => "�������������",
        "onclick" => "modifyClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "����������",
        "onclick" => "openDocList(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "����������",
        "onclick" => "connectClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "��������� �����",
        "onclick" => "askCost(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "����",
        "onclick" => "blockClient(this)",
        "style" => $buttonStyle
    ],true). $view->show("buttons.normal",[
        "text" => "�������",
        "onclick" => "deleteClient(this)",
        "style" => $buttonStyle
    ],true)
],true);        

/*�����������--------------------------------------------------*/

$view->show("inc.div",[
    "type" => "column",
    "content" => $title. $body. $buttonBlock
]);


/*����������--------------------------------------------------*/
/*--------------------------------------------------*/





















                                                                                        





