<?php
error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set("upload_max_filesize","8M");
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT']."/_modules/orkkNew/TreeExplorer/php/TreeExplorer.php";
require_once $_SERVER['DOCUMENT_ROOT']."/_modules/orkkNew/php/classes/View2.php";



/*--------------------------------------------------*/


function utf(
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



function win(
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




/*--------------------------------------------------*/

function getTree(){
    global $model;
    global $path;
    global $view;
    $dirList = $model->getDirList();
    $fileList = $model->getFileList();
    $elementList = [];
    
    foreach($dirList as $key => $value){
        $elementList[] = [
            "name" => $value,
            "id" => $key,
            "imgPath" => "/_img/icoFolder32.png",
            "rowOnclick" => "openFolder(`{$key}`)",
            "type" => "folder"
        ];
    }
    foreach($fileList as $key => $value){
        $elementList[] = [
            "name" => $value,
            "id" => $key,
            "imgPath" => "/_img/icoBill32g.png",
            "rowOnclick" => "submitFile(`{$path}/{$key}`,`{$value}`)",
            "type" => "file"
        ];
    }

    $buttonList = [
        [
            "name" => "Назад",
            "onclick" => "back()"
        ],
        [
            "name" => "Создать папку",
            "onclick" => "createFolderForm()"
        ],
        [
            "name" => "Создать файл",
            "onclick" => "createFileForm()"
        ],
        [
            "name" => "Скачать",
            "onclick" => "openFile()"
        ]
    ];
    $adminRoleList = [
        "leader"
    ];
    $role = $_COOKIE["orkkrole"];
    if (in_array($role, $adminRoleList)){
        $buttonList[] = [
            "name" => "Удалить",
            "onclick" => "deleteTreeElement()"
        ];
    }
    /*--------------------------------------------------*/
    $menu = $view->show("inc.mainMenu", compact("buttonList"), true);
    $content = $view->show("inc.list", compact("elementList"), true);
    //echo $model->getPathName()."<br>";
    $data = [
        "path" => [
            "params" => toStr([
                "data_path" => $path,
                "class" => "explorerPath",
                "id" => "explorerPath"
            ]),
            "text" => $model->getPathName()
        ],
        "mainMenu" => [
            "text" => $menu
        ],
        "contentBox" => [
            "text" => $content
        ]
    ];
    $view->show("main", $data);
}

/*--------------------------------------------------*/

function createFolder(){
    global $model;
    global $path;
    global $view;
    global $name;
    
    $model->makeDir($name);
    getTree();
    
}

/*--------------------------------------------------*/

function createFile(){
    global $model;
    global $name;
    global $file;
    if (!$file["tmp_name"]){
        echo "Ошибка загрузки файла<br>";
        echo $file["error"]."<br>";
        echo ini_get("upload_max_filesize");
        exit();
    }
    $data = file_get_contents($file["tmp_name"]);
    $mime = $file["type"];
    $size = $file["size"];
    $name = win($name);
    
    $model->createFile($name, [
        "name" => $name,
        "data" => base64_encode($data),
        "size" => $size,
        "mime" => $mime
    ]);
    
    getTree();
}

/*--------------------------------------------------*/

function delete(){
    global $model;
    global $id;
    global $type;
    if ($type == "file"){
        $model->deleteFile($id);
    }
    if ($type == "folder"){
        $model->deleteDir($id);
    }
    getTree();
}

/*--------------------------------------------------*/

function getCreateFolderForm(){
    global $path;
    global $view;
    $data = [
        "path" => $path
    ];
    $view->show("createFolderForm",$data);
}

/*--------------------------------------------------*/

function getCreateFileForm(){
    global $path;
    global $view;
    $data = [
        "path" => $path
    ];
    $view->show("createFileForm",$data);
}

/*--------------------------------------------------*/

function getFile(){
    global $id;
    global $model;
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


$path = isset($_REQUEST["path"]) ? $_REQUEST["path"] : "";
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";
$model = new \TreeExplorer("clients",$path);
$view = new \View2("/_modules/orkkNew/TreeExplorer");
switch ($action):
    case "get":
        getTree();
        break;
    case "createFolder":
        $name = isset($_GET["name"]) ? $_GET["name"] : "";
        createFolder();
        break;
    case "createFile":
        
        $file = $_FILES["file"];
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        createFile();
        break;
    case "delete":
        $type = isset($_GET["type"]) ? $_GET["type"] : "";
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        delete();
        break;
    case "getCreateFolderForm":
        getCreateFolderForm();
        break;
    case "getCreateFileForm":
        getCreateFileForm();
        break;
    case "getFile":
        $id = $_POST["id"];
        getFile();
        break;
endswitch;









