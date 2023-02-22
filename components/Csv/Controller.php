<?php

namespace Csv;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    public function __construct(){
        parent::__construct("Csv");
        $this->model = new \Csv\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function download(
            $content
    ){
        $this->model->setHeaders();
        echo $content;
        exit;
    }
    
    /*--------------------------------------------------*/
    
    public function makeCsv(){
        $ar = [
            [
                "1",
                "2",
                "3"
            ],
            [
                "4",
                "5",
                "6"
            ]
        ];
        return $this->model->makeCsv($ar);
    }
    
    /*--------------------------------------------------*/
    
    public function getMonthSupport(
            $table,
    ){
        $accountList = $table["accountList"];
        $clientList = $table["clientList"];
        $result = [];
        foreach($accountList as $dnum => $supportList){
            $info = $clientList[$dnum];
            foreach($supportList as $key => $value){
                $dnum = trim($value["dnum"]);
                $incTime = date("d.m.Y H:s:i",$value["inc_time"]);
                $endTime = isset($value["end_time"]) ? "\n".date("d.m.Y H:s:i",$value["end_time"]) : "";
                $executed = isset($value["executed"]) ? profileGetUsername($value["executed"]) : "";
                $buf = $value["text"];
                $text = "";
                $count = 0;
                for($i = 0; $i < strlen($buf); $i++){
                    $count++;
                    $ch = $buf[$i];
                    $text .= $ch;
                    if ((ctype_punct($ch) || ctype_space($ch)) && (!in_array($ch, ["\n","\r"])) && ($count >= 50)){
                        $count = 0;
                        $text .= "\n";
                    }
                }
                $text = str_replace("\"", "'", $text);
                $buf = $value["resolution"];
                $resolution = "";
                $count = 0;
                for($i = 0; $i < strlen($buf); $i++){
                    $count++;
                    $ch = $buf[$i];
                    $resolution .= $ch;
                    if ((ctype_punct($ch) || ctype_space($ch)) && (!in_array($ch, ["\n","\r"])) && ($count >= 50)){
                        $count = 0;
                        $resolution .= "\n";
                    }
                }
                $resolution = str_replace("\"", "'", $resolution);
                if (isset($info["support"][$value["inc_time"]])){
                    $buf = $info["support"][$value["inc_time"]];
                    $rate = $buf["rate"];
                    $comment = str_replace("\"", "'", $buf["comment"]);
                    if ($buf["callDate"]){
                        $date = date("d.m.Y H:s:i",$buf["callDate"]);
                    }
                    else{
                        $date = "";
                    }
                }
                else{
                    $rate = "";
                    $comment = "";
                    $date = "";
                }
                $row = [
                    $dnum,
                    $info["name"],
                    $incTime.$endTime,
                    $executed,
                    $text,
                    $resolution,
                    $rate,
                    $comment,
                    $date
                ];
                foreach($row as $key => $value){
                    $row[$key] = "\"{$value}\"";
                }
                $result[] = implode(";", $row);
            }
        }
        return implode("\n",$result);
    }
    
    /*--------------------------------------------------*/
    
    private function formatString(
            $str
    ){
        
        $result = "";
        $count = 0;
        for($i = 0; $i < strlen($str); $i++){
            $count++;
            $ch = $str[$i];
            $result .= $ch;
            if ((ctype_punct($ch) || ctype_space($ch)) && (!in_array($ch, ["\n","\r"])) && ($count >= 50)){
                $count = 0;
                $result .= "\n";
            }
        }
        return "\"". str_replace("\"", "'", $result). "\"";;
    }
    
    /*--------------------------------------------------*/
    
    public function getCsvFromArray(
            $ar
    ){
        $result = [];
        foreach($ar as $key => $value){
            $result[] = $this->formatString($key);
            foreach($value as $row){
                foreach($row as $k => $v){
                    $row[$k] = $this->formatString($v);
                }
                $result[] = implode(";", $row);
            }
            $result[] = "\" \"";
        }
        return implode("\n", $result);
    }
    
    /*--------------------------------------------------*/
    
    
}












