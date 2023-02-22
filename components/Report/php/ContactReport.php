<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Report;

/**
 * Description of ContactReport
 *
 * @author Admin
 */
class ContactReport {
    public function getEmailReport(
            $params
    ){
        $result = [];
        $client = new \Client\Controller();
        $clientList = $client->clientList();
        $index = 0;
        foreach($clientList as $clientInfo){
            $city = $clientInfo["city"];
            $status = $clientInfo["clientStatus"];
            $dnum = $clientInfo["dnum"];
            $clientType = $clientInfo["clientType"];
            if ($params["emailType"] && in_array("ЭАВР", $params["emailType"])){
                $emailEavr = $clientInfo["emailEavr"];
            }
            else if(!$params["emailType"]){
                $emailEavr = $clientInfo["emailEavr"];;
            }
            else{
                $emailEavr = "";
            }
            if ($params["emailType"] && in_array("Основная", $params["emailType"])){
                $emailMain = $clientInfo["emailMain"];
            }
            else if(!$params["emailType"]){
                $emailMain = $clientInfo["emailMain"];;
            }
            else{
                $emailMain = "";
            }
            
            $name = "{$clientInfo["clientType"]} {$clientInfo["name"]}";
            if ($params["city"] && !in_array($city, $params["city"])){
                continue;
            }
            if ($params["clientType"] && !in_array($clientType, $params["clientType"])){
                continue;
            }
            if ($params["status"] && !in_array($status,$params["status"])){
                continue;
            }
            if ($dnum && array_key_exists($dnum, $result)){
                $mainFlag = true;
                $eavrFlag = true;
                if (in_array($emailEavr, $result[$dnum]["emailList"])){
                    $eavrFlag = false;
                }
                if (in_array($emailMain, $result[$dnum]["emailList"])){
                    $mainFlag = false;
                }
                if ($mainFlag && $emailMain){
                    $result[$dnum]["emailList"][] = $emailMain;
                }
                if ($eavrFlag && $emailEavr){
                    $result[$dnum]["emailList"][] = $emailEavr;
                }
                
            }
            else if ($dnum){
                $result[$dnum] = [
                    "emailList" => [],
                    "name" => $name
                ];
                if ($emailMain){
                    $result[$dnum]["emailList"][] = $emailMain;
                }
                if ($emailEavr && !in_array($emailEavr,$result[$dnum]["emailList"])){
                    $result[$dnum]["emailList"][] = $emailEavr;
                }
            }
            else{
                $index++;
                $dnum = "noDnum_{$index}";
                $result[$dnum] = [
                    "emailList" => [],
                    "name" => $name
                ];
                if ($emailMain){
                    $result[$dnum]["emailList"][] = $emailMain;
                }
                if ($emailEavr && !in_array($emailEavr,$result[$dnum]["emailList"])){
                    $result[$dnum]["emailList"][] = $emailEavr;
                }
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getContactReport(
            $params
    ){
        $result = [];
        $client = new \Client\Controller();
        $account = new \Account\Controller();
        $clientList = $client->clientList();
        $index = 0;
        foreach($clientList as $clientInfo){
            $city = $clientInfo["city"];
            $status = $clientInfo["clientStatus"];
            $dnum = $clientInfo["dnum"];
            $clientType = $clientInfo["clientType"];
            
            $name = "{$clientInfo["clientType"]} {$clientInfo["name"]}";
            if ($params["city"] && !in_array($city, $params["city"])){
                continue;
            }
            if ($params["clientType"] && !in_array($clientType, $params["clientType"])){
                continue;
            }
            if ($params["status"] && !in_array($status,$params["status"])){
                continue;
            }
            if ($dnum && !array_key_exists($dnum, $result)){
                $contactList = $account->getContactList($dnum);
                foreach($contactList as $key => $value){
                    if (strlen($value["phone"]) == 1){
                        unset($contactList[$key]);
                    }
                }
                $result[$dnum] = [
                    "name" => $name,
                    "contactList" => $contactList
                ];
            }
            else if(!$dnum){
                $index++;
                $dnum = "noDnum_{$index}";
                $contactList = $client->getContacts($clientInfo["id"]);
                foreach($contactList as $key => $value){
                    if (strlen($value["phone"]) == 1){
                        unset($contactList[$key]);
                    }
                }
                $result[$dnum] = [
                    "name" => $name,
                    "contactList" => $contactList
                ];
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
}



