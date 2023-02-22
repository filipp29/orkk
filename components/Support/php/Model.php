<?php


namespace Support;



class Model {
    
    private function getSupportPath(
            $dnum
    ){
        return "/users/{$dnum}/support/";
    }
    
    /*--------------------------------------------------*/
    
    public function getSupportList(
            $dnum
    ){
        $path = $this->getSupportPath($dnum);
        $br = array_keys(objLoadBranch($path, true, false));
        return $br;
    }
    
    /*--------------------------------------------------*/
    
    public function getSupport(
            $dnum,
            $supportName
    ){
        $path = $this->getSupportPath($dnum);
        $obj = objLoad($path.$supportName);
        unset($obj["#e"]);
        return $obj;
    }
    
    
    /*--------------------------------------------------*/
    
    
}
