<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Report;

/**
 * Description of ClientReport
 *
 * @author Admin
 */
class ClientReport {
    
    public $managerList = [];
    public $cityList = [];
    
    
    public function getReportData(
            $timeStampList
    ){
        $clientStatusList = \Settings\Main::clientStatus();
        $result = [];
        $resultGu = [];
        $client = new \Client\Controller();
        $clientList = $client->getClientIdList();
        $cityList = \Settings\Main::cityList();
        $buf = \Settings\Main::profileList();
        $managerList = array_merge($buf["manager"],$buf["leader"]);
        foreach($timeStampList as $timeStamp){
            foreach($managerList as $manager){
                foreach($cityList as $city){
                    foreach($clientStatusList as $clientStatus){
                        $result[$timeStamp][$city][$manager][$clientStatus] = 0;
                        $resultGu[$timeStamp][$city][$manager][$clientStatus] = 0;
                    }
                }
            }
        }
        foreach($clientList as $clientId){
            $clientInfo = $client->clientInfo($clientId);
            $manager = $clientInfo["manager"];
            $city = $clientInfo["city"];
            foreach($timeStampList as $timeStamp){
                $status = $client->getClientStatus($clientId, $timeStamp);
                if (in_array($status, $clientStatusList)){
                    $result[$timeStamp][$city][$manager][$status]++;
                    if ($clientInfo["clientType"] == "ГУ"){
                        $resultGu[$timeStamp][$city][$manager][$status]++;
                    }
                }
            }
        }
        return [
            "all" => $result,
            "gu" => $resultGu
        ];
    }
    
    /*--------------------------------------------------*/
    
    private function getDelta(
            $ar
    ){
        if (!$ar){
            return [];
        }
        $buf = [];
        foreach($ar as $key => $value){
            $buf[] = [
                "key" => $key,
                "value" => (int)$value
            ];
        }
        usort($buf,function($a,$b){
            return (int)$b["value"] - (int)$a["value"];
        });
        $result = [];
        $len = count($buf);
        for($i = 0; $i < $len - 1; $i++){
            $cur = $buf[$i];
            $next = $buf[$i+1];
            $result[$cur["key"]] = $cur["value"] - $next["value"];
        }
        $result[$buf[count($buf) - 1]["key"]] = 0;
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getConnectReport(
            $params
    ){
        $timeStampList = [];
        foreach($params["period"] as $period){
            $timeStampList[] = $period["start"];
            $timeStampList[] = $period["end"];
        }
        $data = $this->getReportData($timeStampList)["all"];
        $periodSum = [];
        foreach($data as $period => $cityList){
            $data[$period]["all"] = [];
            foreach($cityList as $city => $managerList){
                $data[$period][$city]["all"] = 0;
                $data[$period][$city]["sum"] = 0;
                foreach($managerList as $manager => $statusList){
                    $data[$period][$city]["sum"] += array_sum($statusList);
                    $data[$period][$city][$manager] = (int)$statusList["Подключен"];
                    $data[$period][$city]["all"] += $data[$period][$city][$manager];
                    if (!isset($data[$period]["all"][$manager])){
                        $data[$period]["all"][$manager] = 0;
                    }
                    $data[$period]["all"][$manager] += $data[$period][$city][$manager];
//                    if (!in_array($manager,$params["manager"])){
//                       unset($data[$period][$city][$manager]);
//                    }
                }
                if (!isset($data[$period]["all"]["all"])){
                    $data[$period]["all"]["all"] = 0;
                }
                $data[$period]["all"]["all"] += $data[$period][$city]["all"];
                if (!isset($data[$period]["all"]["sum"])){
                    $data[$period]["all"]["sum"] = 0;
                }
                $data[$period]["all"]["sum"] += $data[$period][$city]["sum"];
            }
        }
        $result = [];
        $delta = [];
        $periodDelta = [];
        
        foreach($params["period"] as $period => $value){
            $result[$period] = [];
            $cityDelta = [];
            $periodSum[$period] = 0;
            foreach($data[$value["start"]] as $city => $managerList){
                $result[$period][$city] = [];
                $managerDelta = [];
                foreach($managerList as $manager => $unused){
                    $result[$period][$city][$manager] = $data[$value["end"]][$city][$manager] - $data[$value["start"]][$city][$manager];
                    if (in_array($manager, $params["manager"])){
                        $managerDelta[$manager] = $result[$period][$city][$manager];
                    }
                }
                $delta[$period][$city] = $this->getDelta($managerDelta);
                if (in_array($city, $params["city"])){
                    $cityDelta[$city] = $result[$period][$city]["all"];
                }
            }
            $cityDelta = $this->getDelta($cityDelta);
            foreach($cityDelta as $city => $val){
                $delta[$period][$city]["all"] = $val;
            }
            $periodDelta[$period] = $result[$period]["all"]["all"];
        }
        $periodDelta = $this->getDelta($periodDelta);
        foreach($periodDelta as $period => $val){
            $delta[$period]["all"]["all"] = $val;
        }
        return [
            "result" => $result,
            "delta" => $delta,
            "periodSum" => $periodSum
        ];
    }
    
    /*--------------------------------------------------*/
    
    public function getClientReport(
            $params
    ){
        $timeStampList = [];
        $periodKeyList = [];
        foreach($params["period"] as $key => $period){
            $timeStampList[] = $period["start"];
            $timeStampList[] = $period["end"];
            $periodKeyList[$period["start"]] = [
                "period" => $key,
                "value" => "start"
            ];
            $periodKeyList[$period["end"]] = [
                "period" => $key,
                "value" => "end"
            ];
        }
        $buf = $this->getReportData($timeStampList);
        $data = $buf["all"];
        $dataGu = $buf["gu"];
        $result = [];
        $coldStatusIgnoreList = [
            "Отключен",
            "Переоформлен",
            "Подключен",
            "Отказ НЦ",
            "Ожидает подключение",
            "Не соответствует ТС",
            "Нет тех. возможности",
            "Приостановлен"
        ];
        $all = [];
        $cold = [];
        $coldGu = [];
        foreach($data as $timeStamp => $cityList){
            $period = $periodKeyList[$timeStamp];
            foreach($cityList as $city => $managerList){
                if ($params["city"] && !in_array($city, $params["city"])){
                    continue;
                }
                foreach($managerList as $manager => $statusList){
                    if ($params["manager"] && !in_array($manager, $params["manager"])){
                        continue;
                    }
                    foreach($statusList as $status => $value){
                        if (!isset($result[$status][$period["period"]][$period["value"]])){
                            $result[$status][$period["period"]][$period["value"]] = 0;
                        }
                        $result[$status][$period["period"]][$period["value"]] += (int)$value;
                        if (!isset($all[$period["period"]][$period["value"]])){
                            $all[$period["period"]][$period["value"]] = 0;
                        }
                        $all[$period["period"]][$period["value"]] += (int)$value;
                        if (!in_array($status, $coldStatusIgnoreList)){
                            if (!isset($cold[$period["period"]][$period["value"]])){
                                $cold[$period["period"]][$period["value"]] = 0;
                            }
                            $cold[$period["period"]][$period["value"]] += (int)$value;
                            if (!isset($coldGu[$period["period"]][$period["value"]])){
                                $coldGu[$period["period"]][$period["value"]] = 0;
                            }
                            $coldGu[$period["period"]][$period["value"]] += (int)$dataGu[$timeStamp][$city][$manager][$status];
                        }
                    }
                }
            }
        }
        $result["Общее количество"] = $all;
        $result["Холодных в рвзрвботку"] = $cold;
        $result["Холодных ГУ"] = $coldGu;
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getAmountReport(
            $params
    ){
        $timeStampList = [];
        $periodKeyList = [];
        foreach($params["period"] as $key => $period){
            $timeStampList[] = $period["start"];
            $timeStampList[] = $period["end"];
            $periodKeyList[$period["start"]] = [
                "period" => $key,
                "value" => "start"
            ];
            $periodKeyList[$period["end"]] = [
                "period" => $key,
                "value" => "end"
            ];
        }
        $clientStatusList = [
            "Подключен"
        ];
        $result = [
            "amount" => [],
            "additional" => []
        ];
        $turns = [
            "income" => [],
            "outcome" => []
        ];
        $client = new \Client\Controller();
        $clientList = $client->getClientIdList();
        $cityList = \Settings\Main::cityList();
        $buf = \Settings\Main::profileList();
        $managerList = array_merge($buf["manager"],$buf["leader"]);
        $result = [];
        foreach(["amount","additional"] as $type){
            foreach($params["period"] as $period => $unused){
                foreach([
                    "start",
                    "income",
                    "outcome",
                    "end"
                ] as $k){
                    $result[$type][$period][$k] = 0;
                }
            }
        }   
        foreach($clientList as $clientId){
            $clientInfo = $client->clientInfo($clientId);
            $manager = $clientInfo["manager"];
            $city = $clientInfo["city"];
            if ($params["city"] && !in_array($city, $params["city"])){
                continue;
            }
            if ($params["manager"] && !in_array($manager, $params["manager"])){
                continue;
            }
            $clientResult = [];
            foreach($params["period"] as $period => $unused){
                foreach(["start","end"] as $type){
                    foreach([
                        "amount",
                        "additional",
                        "income",
                        "outcome"
                    ] as $k){
                        $clientResult[$period][$type][$k] = 0;
                    }
                }
            }
            foreach($timeStampList as $timeStamp){
                $period = $periodKeyList[$timeStamp];
                $status = $client->getClientStatus($clientId, $timeStamp);
                if (in_array($status, $clientStatusList)){
                    $tarif = $client->getTarif($clientId, $timeStamp);
                    $fullAmount = $tarif["amount"];
                    $amount = $clientInfo["amount"];
                    $additional = (int)((int)$fullAmount - (int)$amount);
                    $clientResult[$period["period"]][$period["value"]]["amount"] = $amount;
                    $clientResult[$period["period"]][$period["value"]]["additional"] = $additional;
                    $clientResult[$period["period"]][$period["value"]]["income"] = $tarif["income"];
                    $clientResult[$period["period"]][$period["value"]]["outcome"] = $tarif["outcome"];
                }
            }
            foreach($result["amount"] as $period => $unused){
                $buf = $clientResult[$period];
                $delta = (int)$buf["end"]["amount"] - (int)$buf["start"]["amount"];
                if ($delta > 0){
                    $result["amount"][$period]["income"] += $delta;
                }
                else{
                    $result["amount"][$period]["outcome"] -= $delta;
                }
                $result["amount"][$period]["start"] += (int)$buf["start"]["amount"];
                $result["amount"][$period]["end"] += (int)$buf["end"]["amount"];
            }
            foreach($result["additional"] as $period => $unused){
                $buf = $clientResult[$period];
                $result["additional"][$period]["start"] += (int)$buf["start"]["additional"];
                $result["additional"][$period]["end"] += (int)$buf["end"]["additional"];
                $result["additional"][$period]["income"] += (int)((int)$buf["end"]["income"] - (int)$buf["start"]["income"]);
                $result["additional"][$period]["outcome"] += (int)((int)$buf["end"]["outcome"] - (int)$buf["start"]["outcome"]);
            }
        }
        foreach($result as $type => $periodList){
            foreach($periodList as $period => $unused){
                $result[$type][$period]["delta"] = (int)$result[$type][$period]["end"] - (int)$result[$type][$period]["start"];
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getConnectSumReport(
            $params
    ){
        $postTypeList = [
            "Дополнительная точка",
            "Спецификация"
        ];
        $account = new \Account\Controller();
        $client = new \Client\Controller();
        $dnumList = $account->getAllDnums();
        $result = [];
        foreach($params["period"] as $period => $value){
            $result[$period] = 0;
        }
        foreach($dnumList as $dnum){
            $docList = $account->getAllPosList($dnum);
            foreach($docList as $doc){
                $info = $client->clientInfo($doc["clientId"]);
                $manager = $info["manager"];
                $city = $info["city"];
                if ($params["city"] && !in_array($city, $params["city"])){
                    continue;
                }
                if ($params["manager"] && !in_array($manager, $params["manager"])){
                    continue;
                }
                $activateDate = "";
                $connectSum = "";
                if (in_array($doc["posType"], $postTypeList)){
                    $activateDate = $info["activateDate"];
                    $connectSum = $info["connectSum"];
                }
                else if(isset($doc["param_connectSum"])){
                    $connectSum = $doc["param_connectSum"];
                    if (isset($doc["param_activateDate"])){
                        $activateDate = $doc["param_activateDate"];
                    }
                    else{
                        $activateDate = $doc["param_contractDate"];
                    }
                }
                if ($connectSum && $activateDate){
                    $date = (int)date("Ymd",$activateDate);
                    foreach($params["period"] as $period => $value){
                        $start = (int)date("Ymd",$value["start"]);
                        $end = (int)date("Ymd",$value["end"]);
                        if (($date >= $start)&&($date <= $end)){
                            $result[$period] += (int)$connectSum;
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getGuReport(
            $params
    ){
        $timeStampList = [];
        $periodKeyList = [];
        foreach($params["period"] as $key => $period){
            $timeStampList[] = $period["start"];
            $timeStampList[] = $period["end"];
            $periodKeyList[$period["start"]] = [
                "period" => $key,
                "value" => "start"
            ];
            $periodKeyList[$period["end"]] = [
                "period" => $key,
                "value" => "end"
            ];
        }
        $clientStatusList = [
            "Подключен"
        ];
        $result = [];
        $client = new \Client\Controller();
        $clientList = $client->getClientIdList();
        $cityList = \Settings\Main::cityList();
        $buf = \Settings\Main::profileList();
        $managerList = array_merge($buf["manager"],$buf["leader"]);
        $result = [];
        $cityList = array_merge($params["city"],["all"]);
        foreach($cityList as $city){
            foreach($params["period"] as $period => $unused){
                foreach(["start","end"] as $type){
                    foreach([
                        "count",
                        "sum"
                    ] as $k){
                        $result[$city][$period][$type][$k] = 0;
                    }
                }
            }
        }
        foreach($clientList as $clientId){
            $clientInfo = $client->clientInfo($clientId);
            $manager = $clientInfo["manager"];
            $city = $clientInfo["city"];
            if ($clientInfo["clientType"] != "ГУ"){
                continue;
            }
            if ($params["city"] && !in_array($city, $params["city"])){
                continue;
            }
            if ($params["manager"] && !in_array($manager, $params["manager"])){
                continue;
            }
            
            foreach($timeStampList as $timeStamp){
                $period = $periodKeyList[$timeStamp]["period"];
                $type = $periodKeyList[$timeStamp]["value"];
                $status = $client->getClientStatus($clientId, $timeStamp);
                if (in_array($status, $clientStatusList)){
                    $tarif = $client->getTarif($clientId, $timeStamp);
                    $fullAmount = $tarif["amount"];
                    $result["all"][$period][$type]["count"]++;
                    $result["all"][$period][$type]["sum"] += (int)$fullAmount;
                    if ($params["city"]){
                        $result[$city][$period][$type]["count"]++;
                        $result[$city][$period][$type]["sum"] += (int)$fullAmount;
                    }
                }
            }
            
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getDistrictReport(
            $params
    ){
        $timeStampList = [];
        $periodKeyList = [];
        foreach($params["period"] as $key => $period){
            $timeStampList[] = $period["start"];
            $timeStampList[] = $period["end"];
            $periodKeyList[$period["start"]] = [
                "period" => $key,
                "value" => "start"
            ];
            $periodKeyList[$period["end"]] = [
                "period" => $key,
                "value" => "end"
            ];
        }
        $clientStatusList = [
            "Подключен"
        ];
        $result = [];
        $client = new \Client\Controller();
        $clientList = $client->getClientIdList();
        $cityList = \Settings\Main::cityList();
        $buf = \Settings\Main::profileList();
        $managerList = array_merge($buf["manager"],$buf["leader"]);
        $result = [];
        $districtList = \Settings\Main::district();
        unset($districtList["Не назначен"]);
        $districtList = array_keys($districtList);
        foreach($districtList as $district){
            foreach($params["period"] as $period => $unused){
                foreach(["start","end"] as $type){
                    foreach([
                        "count",
                        "sum"
                    ] as $k){
                        $result[$district][$period][$type][$k] = 0;
                    }
                }
            }
        }
        foreach($clientList as $clientId){
            $clientInfo = $client->clientInfo($clientId);
            $manager = $clientInfo["manager"];
            $city = $clientInfo["city"];
            $district = $clientInfo["district"];
            if ($district == "Не назначен"){
                continue;
            }
            if ($params["city"] && !in_array($city, $params["city"])){
                continue;
            }
            if ($params["manager"] && !in_array($manager, $params["manager"])){
                continue;
            }
            
            foreach($timeStampList as $timeStamp){
                $period = $periodKeyList[$timeStamp]["period"];
                $type = $periodKeyList[$timeStamp]["value"];
                $status = $client->getClientStatus($clientId, $timeStamp);
                if (in_array($status, $clientStatusList)){
                    $tarif = $client->getTarif($clientId, $timeStamp);
                    $fullAmount = $tarif["amount"];
                    $result[$district][$period][$type]["count"]++;
                    $result[$district][$period][$type]["sum"] += (int)$fullAmount;
                }
            }
            
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
}







