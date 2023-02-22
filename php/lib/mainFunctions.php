<?php

function makeStyle(
        $ar
){
    $result = "";
    foreach($ar as $key => $value){
        $result .= "{$key}: {$value};";
    }
    return $result;
}

/*--------------------------------------------------*/

function sortByTimeStamp(
        $a,
        $b
){
    if ((int)$a["timeStamp"] > (int)$b["timeStamp"]){
        return 1;
    }
    if ((int)$a["timeStamp"] < (int)$b["timeStamp"]){
        return -1;
    } 
    return 0;
}

/*--------------------------------------------------*/

function sortByTimeStampReverse(
        $a,
        $b
){
    if ((int)$a["timeStamp"] < (int)$b["timeStamp"]){
        return 1;
    }
    if ((int)$a["timeStamp"] > (int)$b["timeStamp"]){
        return -1;
    } 
    return 0;
}

/*--------------------------------------------------*/

function sortByKey(
        $arr,
        $key,
        $reverse = false
){
    $sort = function($a,$b) use ($key,$reverse){
        $valueA = isset($a[$key]) ? (int)$a[$key] : 0;
        $valueB = isset($b[$key]) ? (int)$b[$key] : 0;
        
        if ($reverse){
            return $valueB - $valueA;
        }
        else{
            return $valueA - $valueB;
        }
    };
    usort($arr, $sort);
    return $arr;
    
}

/*--------------------------------------------------*/

function makeAttribute(
        $ar
){
    $result = "";
    foreach($ar as $key => $value){
        $result .= " {$key}='{$value}'";
    }
    return $result;
}

/*--------------------------------------------------*/

function makeId(){
    $rand1 = (rand() % 8900)+1000;
    $rand2 = (rand() % 8900)+1000;
    $timeStamp = substr(time(), -6, 6);
    return $timeStamp.$rand1.$rand2;
}

/*--------------------------------------------------*/

function getAddress(
        $value
){
    $buf = [];
    if (($value["city"])){
        $buf[] = "{$value["city"]}";
    }
    if (($value["street"])){
        $buf[] = "{$value["streetType"]} {$value["street"]}";
    }
    if (($value["building"])){
        $buf[] = "дом {$value["building"]}";
    }
    if (($value["flat"])){
        $buf[] = "{$value["flatType"]} {$value["flat"]}";
    }
    return implode(", ", $buf);
}

/*--------------------------------------------------*/

function getTariff(
        $value
){
    $result = "";
    if (!$value["amount"]){
        $result = $value["speed"];
    }
    else{
        $result = $value["amount"];
        if ($value["speed"]){
            $result .= " - ". $value["speed"];
        }
    }
    return $result;
}

/*--------------------------------------------------*/

function getFullName(
        $value
){
    $result = $value["name"];
    if ($value["remark"]){
        $result .= " - \"{$value["remark"]}\"";
    }
    return $result;
}

/*--------------------------------------------------*/

function getPhoneTemplate(
        $value
){
    $template = "+_(___) ___-__-__";
    $n = 0;
    $phone = "";
    for($i = 0; $i < strlen($template); $i++){
        if (($template[$i] == "_") && ($n < strlen($value))){
            $phone .= $value[$n];
            $n++;
        }
        else{
            $phone .= $template[$i];
        }
    }
    return $phone;
}

/*--------------------------------------------------*/

function arrayCompareRecursive($a,$b){
    $is_a = is_array($a) ? 1 : 0;
    $is_b = is_array($b) ? 1 : 0;
    $res = $is_a + $is_b;
    switch($res):
        case 0:
            if ($a == $b){
                return 0;
            }
            else{
                return 1;
            }
            break;
        case 1:
            return 1;
            break;
        case 2:
            $buf = array_udiff_assoc($a,$b,"arrayCompareRecursive");
            if ($buf){
                return 1;
            }
            else{
                return 0;
            }
            break;
    endswitch;
}

/*--------------------------------------------------*/

function getImgPath(
        $name
){
    $path = \Settings\Main::globalPath()."/img/{$name}.png";
    return $path;
}

/*--------------------------------------------------*/

function getRole(){
    $profile = $_COOKIE["login"];
    $admin = new \Admin\Controller();
    $roleList = $admin->getProfileList("all");
    $role = "none";
    foreach($roleList as $key => $profileList){
        if (in_array($profile, $profileList)){
            $role = $key;
            break;
        }
    }
    return $role;
}

/*--------------------------------------------------*/

function getYearMonthList(){
    $years = [
        "2016" => "2016",
        "2017" => "2017",
        "2018" => "2018",
        "2019" => "2019",
        "2020" => "2020",
        "2021" => "2021",
        "2022" => "2022" ,
        "2023" => "2023"
    ];
    $months = [
        "01" => "Январь",
        "02" => "Февраль",
        "03" => "Март",
        "04" => "Апрель",
        "05" => "Май",
        "06" => "Июнь",
        "07" => "Июль",
        "08" => "Август",
        "09" => "Сентябрь",
        "10" => "Октябрь",
        "11" => "Ноябрь",
        "12" => "Декабрь"
    ];
    return compact("years","months");
}

/*--------------------------------------------------*/












