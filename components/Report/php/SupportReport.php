<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Report;

/**
 * Description of SupportReport
 *
 * @author Admin
 */
class SupportReport {
    
    
    public function getSupportReportAll(
            $params,
            $compare = false
    ){
        $account = new \Account\Controller();
        $first = $params["first"];
        $second = $params["second"];
        $firstDays = cal_days_in_month(CAL_GREGORIAN, $first["month"], $first["year"]);
        $secondDays = cal_days_in_month(CAL_GREGORIAN, $second["month"], $second["year"]);
        
        $billing = new \Billing();
        if ($compare){
            $firstStart = "{$first["year"]}-{$first["month"]}-01";
            $firstEnd = "{$first["year"]}-{$first["month"]}-{$firstDays}";
            $secondStart = "{$second["year"]}-{$second["month"]}-01";
            $secondEnd = "{$second["year"]}-{$second["month"]}-{$secondDays}";
            $reportFirst = $billing->getServiceListByPeriod($firstStart, $firstEnd);
            $reportSecond = $billing->getServiceListByPeriod($secondStart, $secondEnd);
            $report = array_merge($reportFirst,$reportSecond);
        }
        else{
            $firstDate = "{$first["year"]}-{$first["month"]}-01";
            $secondDate = "{$second["year"]}-{$second["month"]}-{$secondDays}";
            $report = $billing->getServiceListByPeriod($firstDate, $secondDate);
        }
        $result = [];
        foreach($report as $value){
            $date = $value["charge_date"];
            $amount = $value["amount"];
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 2);
            if (!isset($result[$year][$month])){
                $result[$year][$month] = [
                    "count" => 0,
                    "amount" => 0,
                    "rateCount" => 0,
                    "rateSum" => 0
                ];
            }
            $result[$year][$month]["count"]++;
            $result[$year][$month]["amount"] += (int)$amount;
        }
        $dnumList = $account->getAllDnums();
        $start = (int)"{$first["year"]}{$first["month"]}";
        $end = (int)"{$second["year"]}{$second["month"]}";
        foreach($dnumList as $dnum){
            $supportList = $account->getSupportList($dnum);
            foreach($supportList as $supportName){
                $buf = explode("-",date("Y-m",$supportName));
                $year = $buf[0];
                $month = $buf[1];
                $date = "{$year}{$month}";
                if ($compare){
                    if (($date == $start) || ($date == $end)){
                        $support = $account->getSupport($dnum, $supportName);
                        if ((int)$support["rate"] > 0){
                            $result[$year][$month]["rateCount"]++;
                            $result[$year][$month]["rateSum"] += (int)$support["rate"];
                        }
                    }
                }
                else{
                    if (($date >= $start) && ($date <= $end)){
                        $support = $account->getSupport($dnum, $supportName);
                        if ((int)$support["rate"] > 0){
                            $result[$year][$month]["rateCount"]++;
                            $result[$year][$month]["rateSum"] += (int)$support["rate"];
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
}
