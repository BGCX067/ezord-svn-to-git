<?php

class PagesController extends AppController
{
	public $name = 'Pages';
	public $helpers = array('Html', 'Session');
    public $layout = 'ezord';

	public $uses = array();
    
    public function beforeFilter () {
        $this->Auth->allow('*');
    }
    
	public function index() {
		$this->pageTitle = null;
	}
    
    public function price () {
        $this->pageTitle = 'Pricing';
    }
    
    public function tour () {
        $this->redirect('/tour/first-looks');
    }
    
    public function help () {
        $this->redirect('/help/main-page');
    }
}
