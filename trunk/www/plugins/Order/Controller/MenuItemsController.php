<?php

class MenuItemsController extends OrderAppController {
    
    public $uses = array ('Place', 'MenuItem');
    
    public $helpers = array (
        'Upload'
    );
    
    public function beforeFilter () {
        parent::beforeFilter();
    }
    
    public function search ($pid = null) {
        $this->Place->contain();
        $place = $this->Place->findById($pid);
        if (empty($place)) {
            print 'Invalid place!';
            exit();
        }
        $keyword = $this->request->params['named']['keyword'];
        $this->MenuItem->contain('ItemImage', 'MenuItemGroup');
        $menu_items = $this->MenuItem->find('all', array(
            'conditions' => array (
                'MenuItem.name LIKE' => '%'.$keyword.'%',
                'MenuItemGroup.place_id' => $pid
            )
        ));
        $this->layout = 'ajax';
        $this->set('pid', $pid);
        $this->set('keyword', $keyword);
        $this->set('menu_items', $menu_items);
    }
}