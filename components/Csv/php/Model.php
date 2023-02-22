<?php

namespace Csv;

class Model {
    
    private $path;
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        $this->path = \Settings\Main::dbPath()."/Csv/";
    }
    
    /*--------------------------------------------------*/
    
    private function getPath(
            $path
    ){
        return $this->path.ltrim(trim($path),"/");
    }
    
    /*--------------------------------------------------*/
    
    public function setHeaders(){
        $date = date("d-m-Y_H-i-s",time());
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="file_'.$date.'.csv"' );
    }
    
    /*--------------------------------------------------*/
    
    public function makeCsv(
            Array $ar
    ){
        
        $result = "";
        foreach($ar as $key => $value){
            $result .= implode(";", $value);
            $result .= "\n";
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
}










