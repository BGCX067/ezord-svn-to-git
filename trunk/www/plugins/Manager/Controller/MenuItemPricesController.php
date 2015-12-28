<?php

class MenuItemPricesController extends ManagerAppController {
    
    public $uses = array ('Place', 'MenuItemGroup', 'MenuItem', 'ItemImage', 'MenuItemPrice');
    public $components = array (
        'Upload', 
        'Image'
    );
    public $helpers = array (
        'Upload'
    );
    public $layout = 'ezord_place';

    public function beforeFilter () {
        parent::beforeFilter();
        // set pagers
        $level2 = array ();
        $level3 = array ('price');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Foods & Drinks',
                    'link' => '/manager/menu_items/index/'
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $iid = $this->request->params['pass'][0];
            $this->item = $this->MenuItem->findById($iid);
            if (empty($this->item)) {
                $this->redirect('/manager/menu_items/index/');
            }
            $papers = array (
                array(
                    'name' => 'Foods & Drinks',
                    'link' => '/manager/menu_items/index/'
                ),
                array(
                    'name' => $this->item['MenuItem']['name'],
                    'link' => '/manager/menu_items/view/'.$iid
                )
            );
        } 
        $this->set('papers', $papers);
    }

    public function price ($iid = null) {
        if ($this->request->is('post')) {
            $price_data = $this->request->data;
            $price_data['MenuItemPrice']['menu_item_id'] = $iid;
            $this->MenuItemPrice->save($price_data);
            $this->Session->setFlash('Price has been saved', 'success');
            $this->redirect('/manager/menu_item_prices/price/'.$iid);
        }
        $this->pageTitle = 'Custom price';
        $prices = $this->MenuItemPrice->find('all', array(
            'conditions' => array (
                'menu_item_id' => $iid
            )
        ));
        $this->set('prices', $prices);
        $this->set('item', $this->item);
        $this->set('iid', $iid);
    }

    public function delete ($prid = null) {
        if ($this->request->is('post')) {
            $prid = $this->request->data['prid'];
            $this->MenuItemPrice->delete($prid);
            $this->Session->setFlash('Item price has been deleted', 'success');
            print 'true';
            exit();
        }
        print 'false';
        exit();
    }
}