<?php

class ToolsController extends ApiAppController {

    public $uses = array ();
    public $layout = 'ezord_api';
    
    public function beforeFilter ()
    {
        $this->Auth->allow('index', 'command_test');
    }
    
    public function index ()
    {
        $this->pageTitle = 'Ezord API';
    }
    
    public function command_test ()
    {
        $this->pageTitle = "API Command Test Page";
    }
    
}