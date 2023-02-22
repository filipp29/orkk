<?php

namespace Phonebook;


$globalPath = \Settings\Main::globalPath();
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT']. $globalPath. '/php/classes/MainController.php');


/*--------------------------------------------------*/

class Controller extends \MainController {
    
    private $model;
    
    /*--------------------------------------------------*/
    
    public function __construct(){
        parent::__construct("Phonebook");
        $this->model = new \Phonebook\Model();
    }
    
    /*--------------------------------------------------*/
    
    public function getContactTable(){
        $contactIdList = $this->model->getContactList();
        $contactList = [];
        foreach($contactIdList as $contactId){
            $buf = $this->model->getContact($contactId);
            $contactList[$buf["city"]][] = $buf;
        }
        return $this->view->show("main",[
            "contactList" => $contactList
        ],true);
    }
    
    /*--------------------------------------------------*/
    
    public function saveContact(
            $contact
    ){
        $this->model->save($contact);
    }
    
    /*--------------------------------------------------*/
    
    public function deleteContact(
            $contactId
    ){
        $this->model->delete($contactId);
    }
    
    /*--------------------------------------------------*/
    
    
}