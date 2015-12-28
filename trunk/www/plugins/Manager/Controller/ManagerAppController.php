<?php

class ManagerAppController extends AppController {
    
    public $helpers = array ('Time');
    
    public function beforeFilter () {
        parent::beforeFilter();
    }
    
    public function checkPlaceValid($pid) {
        $place = $this->Place->find('first', array(
            'conditions' => array (
                'Place.id' => $pid
            )
        ));
        if (empty($place)) {
            $this->redirect('/manager/accounts/dashboard');
        }
        return $place;
    }
    
}