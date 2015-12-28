<?php

class StaticsController extends AppController
{
	public $name = 'Statics';
	public $helpers = array('Html', 'Session');
    public $layout = 'ezord';

	public $uses = array();
    
    public function beforeFilter () {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

	public function tour ($key)
    {
		$this->pageTitle = '';
        $this->render('tour/'.$key);
	}
    
    public function help ($key) {
        $this->pageTitle = '';
        $this->render('help/'.$key);
    }
}
