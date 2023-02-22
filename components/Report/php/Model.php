<?php



namespace Report;

class Model {
    
    private $path;
    public $clientReport;
        
    /*--------------------------------------------------*/
    
    public function __construct(){
        
        $this->path = \Settings\Main::dbPath()."/Report/";
    }
    
    /*--------------------------------------------------*/
    
}










