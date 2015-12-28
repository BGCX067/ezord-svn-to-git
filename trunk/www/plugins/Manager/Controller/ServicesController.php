<?php

class ServicesController extends ManagerAppController {
    
    public $layout = 'ezord_place';
    public $uses = array ();
    public $components = array('Upload');
    
    public function beforeFilter () {
        parent::beforeFilter();
    }
     
    public function index () {
        $this->pageTitle = 'Services'; 
    }
    
}