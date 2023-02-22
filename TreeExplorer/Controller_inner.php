<?php


namespace Explorer;
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/orkkNew/TreeExplorer/php/TreeExplorer.php';


class Controller_inner {
    
    private $model;
//    private $view;
    
    /*--------------------------------------------------*/
    
    public function __construct(
            $path = ""
    ){
        $this->model = new \TreeExplorer("clients",$path);
//        $this->view = new \View2("/_modules/orkkNew/TreeExplorer");
    }
    
    /*--------------------------------------------------*/


    private function utf(
        $str
    ){
        if (is_array($str)){
            $result = [];
            foreach ($str as $key => $value){
                $result[$key] = utf($value);
            }
            return $result;
        }
        else{
            return iconv("windows-1251", "utf-8", $str);
        }
    }

    /*--------------------------------------------------*/



    private function win(
            $str
    ){
        if (is_array($str)){
            $result = [];
            foreach ($str as $key => $value){
                $result[$key] = win($value);
            }

            return $result;
        }
        else{
            return iconv("utf-8", "windows-1251", $str);
        }

    }



    /*--------------------------------------------------*/
    
    public function createFolder(
            $name
    ){

        return $this->model->makeDir($name);

    }

    /*--------------------------------------------------*/

    public function createFile(
            $file,
            $name
    ){
        $data = file_get_contents($file["tmp_name"]);
        $mime = $file["type"];
        $size = $file["size"];
        $name = $this->win($name);

        return $this->model->createFile($name, [
            "name" => $name,
            "data" => base64_encode($data),
            "size" => $size,
            "mime" => $mime
        ]);
    }

    /*--------------------------------------------------*/

    public function delete(
            $id,
            $type
    ){
        if ($type == "file"){
            $this->model->deleteFile($id);
        }
        if ($type == "folder"){
            $this->model->deleteDir($id);
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getDirList(){
        return $this->model->getDirList();
    }
    
    /*--------------------------------------------------*/
    
    public function getFileList(){
        return $this->model->getFileList();
    }
    
    /*--------------------------------------------------*/
    
    public function getFile(
            $fullPath
    ){
        $buf = explode("/",$fullPath);
        $id = $buf[count($buf) - 1];
        unset($buf[count($buf) - 1]);
        $path = implode("/", $buf);
        $model = new \TreeExplorer("clients",$path);
        $obj = $model->getFile($id);
        $mime = $obj["mime"];
        $name = $obj["name"];
        $data = $obj["data"];
        $size = $obj["size"];

        // Отправка заголовков для скачиваний файла
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Type: '.$mime);
        header('Content-Length: '.$size);
        header('Connection: close');

        echo base64_decode($data);
    }
    
    /*--------------------------------------------------*/
    
}
