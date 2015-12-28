<?php

class OrderAppController extends AppController {
    
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
            $this->redirect('/order/tables/index/'.$pid);
        }
        return $place;
    }
    
    public function checkTableValid($pid, $tid) {
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        if (empty($table)) {
            $this->redirect('/order/tables/index/'.$pid);
        }
        return $table;
    }
}