<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';


class MainController {
    protected $path;
    protected $view;
    
    /*--------------------------------------------------*/
    
    public function __construct(
            $name
    ){
        
        $globalPath = \Settings\Main::globalPath();
        require_once $_SERVER['DOCUMENT_ROOT']. $globalPath.  "/php/classes/View2.php";
        $this->path = "{$globalPath}/components/{$name}/";
        require_once $_SERVER['DOCUMENT_ROOT']. $this->path. "/php/Model.php";
        $this->view = new \View2($this->path);
    }
    
    /*--------------------------------------------------*/
    
    public function getView(): \View2{
        return $this->view;
    }
    
    /*--------------------------------------------------*/
    
    static public function getViewSt(
            $name
    ): \View2{
        $globalPath = \Settings\Main::globalPath();
        require_once $_SERVER['DOCUMENT_ROOT']. $globalPath.  "/php/classes/View2.php";
        $path = "{$globalPath}/components/{$name}/";
        
        return new \View2($path);
    }
    
    /*--------------------------------------------------*/
    
}
