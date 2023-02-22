<?php

global $globalPath;
$view = new \View2($globalPath. "/components/Main/");
if (!isset($value)){
    $value = "";
}
$content = "";
$first = true;

foreach($values as $k => $v){
    if ($first){
        $index = $k;
    }
    if ($value == $k){
        $index = $k;
    }
    $first = false;
}

if (!isset($onclick)){
    $onclick = "";
}
else{
    $onclick = "{$onclick};";
}

foreach($values as $k => $v){
    if ($k == $index){
        $check = true;
    }
    else{
        $check = false;
    }
    
    $content .= $view->show("inc.input.checkbox",[
        "text" => $v,
        "value" => $k,
        "checked" => $check,
        "onclick" => "{$onclick} inputRadioToggle(this)",
        "style" => (isset($optionStyle) ? $optionStyle : []) +[
            "min-width" => "100px",
            "margin-right" => "10px"
        ] 
    ],true);
}

$view->show("inc.div",[
    "id" => $id,
    "type" => $divType,
    "content" => $content,
    "class" => "inp inputRadio",
    "style" => isset($style) ? $style : []
]);

?>

