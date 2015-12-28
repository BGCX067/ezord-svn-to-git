<?php

class OrdersController extends OrderAppController {
    
    public $uses = array ('Place', 'Table', 'MergedTable', 'Order','MenuItem', 'MenuItemGroup', 'MenuItemsOrder', 'TablesOrder', 'Bill');
    public $layout = 'ezord_order';
    public $cPlace = null;
    public $helpers = array (
        'Upload'
    );
    
    public function beforeFilter () {
        parent::beforeFilter();
        $pid = $this->request->params['pass'][0];
        $tid = $this->request->params['pass'][1];
        $place = $this->checkPlaceValid($pid);
        $this->place = $place;
        $table = $this->checkTableValid($pid, $tid); 
        $cUser = $this->getCUser();
        $this->cPlace = $place;
        
        $this->set('pid', $pid);
        $this->set('tid', $tid);
        $this->set('cTable', $table);
        $this->set('cUser', $cUser);
        
        // set pagers
        $level2 = array ('view', 'reserve');
        $level3 = array ('confirm', 'checkout', 'place');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'],
                    'link' => '/order/tables/index/'.$pid
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'],
                    'link' => '/order/tables/index/'.$pid
                 ),
                array(
                    'name' => $table['Table']['name'],
                    'link' => '/order/tables/view/'.$pid.'/'.$tid
                )
            );
        }
        $this->set('papers', $papers);
    }
    
    public function getOrderTotalPrice ($order) {
        $total = 0;
        if (!empty($order['MenuItem'])) {
            foreach ($order['MenuItem'] as $item) {
                $price = $this->MenuItem->getPrice($item['id']);
                $total += $price*$item['MenuItemsOrder']['quantity'];
            }
        }
        return $total;
    }
    
    public function isOrderPermitted () {
        return true;
    }
    
    public function countDownOrder ($pid) {
        $profile = $this->PlaceProfile->find('first', array (
            'conditions' => array (
                'PlaceProfile.place_id' => $pid
            )
        ));
        $order_remain = intval($profile['PlaceProfile']['order_remain'])-1;
        $this->PlaceProfile->id = $profile['PlaceProfile']['id'];
        $this->PlaceProfile->saveField('order_remain', $order_remain);
    }
    
    public function place ($pid = null, $tid = null, $oid = null) {
        $cUser = $this->getCUser();
        if ($this->request->is('post')) {
            $items = array ();
            if (!empty($this->request->data['Order']['quantity'])) {
                $items = $this->request->data['Order']['quantity'];
            }
            if (!empty($items) && empty($oid)) {
                // Save order
                $order['Order'] = array (
                    'place_id' => $pid,
                    'order_status' => 1
                );
                $this->Order->save($order);
                $oid = $this->Order->getLastInsertID();
                // Save relation between table & order
                $table_order['TablesOrder'] = array (
                    'table_id' => $tid,
                    'order_id' => $oid
                );
                $this->TablesOrder->save($table_order);
                // Save relation between order & items
                foreach ($items as $iid => $quantity) {
                    if (empty($iid)) continue;
                    if ($quantity<1) $quantity = 1;
                    $item_order['MenuItemsOrder'] = array (
                        'menu_item_id' => $iid,
                        'order_id' => $oid,
                        'quantity' => $quantity
                    );
                    $this->MenuItemsOrder->create();
                    $this->MenuItemsOrder->save($item_order);
                }
            } else if (!empty($items) && !empty($oid)) {
                // Delete relation between order & items
                $this->MenuItemsOrder->deleteAll(array(
                    'order_id' => $oid
                ));
                // Save relation between new order & items 
                foreach ($items as $iid => $quantity) {
                    if (empty($iid)) continue;
                    if ($quantity<1) $quantity = 1; 
                    $item_order['MenuItemsOrder'] = array (
                        'menu_item_id' => $iid,
                        'order_id' => $oid,
                        'quantity' => $quantity
                    );
                    $this->MenuItemsOrder->create();
                    $this->MenuItemsOrder->save($item_order);
                }
            }
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        }
        $this->pageTitle = 'Select items';
        if (!is_null($oid)) {
            $order = $this->Order->find('first', array (
                'contain' => array ('MenuItem'),
                'conditions' => array (
                    'Order.id' => $oid
                )
            ));
            $order_items = array ();
            if (!empty($order['MenuItem'])) {
                foreach ($order['MenuItem'] as $item) {
                    $order_items[] = $item['id'];
                }
            }
            $this->set('order_items', implode(',', $order_items));
        } else {
            $this->set('order_items', '');
        }
        $this->set('oid', $oid);
        // check order status
        $is_permit_order = $this->isOrderPermitted();
        $this->set('is_permit_order', $is_permit_order);
        // get menu
        $this->MenuItemGroup->contain(array ('MenuItem' => array ('ItemImage')));
        $groups = $this->MenuItemGroup->find('all', array (
            'conditions' => array (
                'MenuItemGroup.user_id' => $this->getCManagerId()
            )
        ));
        $this->set('groups', $groups);
        // find merged tables
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        $merged_tables = $this->MergedTable->find('all', array (
            'conditions' => array (
                'MergedTable.table_id' => $tid
            )
        ));
        $display = $table['Table']['name'];
        $merged_ids = array (); 
        if (!empty($merged_tables)) {
            foreach ($merged_tables as $m => $mtable) {
                $this->Table->contain();
                $ta = $this->Table->findById($mtable['MergedTable']['merged_id']);
                $merged_tables[$m]['Table'] = $ta['Table'];
                if ($m<count($merged_tables)-1)
                    $display.=', '.strtolower($ta['Table']['name']);
                else
                    $display.=' and '.strtolower($ta['Table']['name']);
                $merged_ids[]=$ta['Table']['id'];
            }
        }
        $this->set('merged_tables', $merged_tables);
        // reset papers
        $papers = array (
            array(
                'name' => $this->place['Place']['name'],
                'link' => '/order/tables/index/'.$pid
            ),
            array(
                'name' => $display,
                'link' => '/order/tables/view/'.$pid.'/'.$tid
            )
        );
        $this->set('papers', $papers);
    }
    
    public function edit ($pid = null, $tid = null, $oid = null) {
        if ($this->request->is('post')) {
            if (!empty($this->request->data['Order']['quantity'])) {
                foreach ($this->request->data['Order']['quantity'] as $iid => $quantity) {
                    $item_order = $this->MenuItemsOrder->find('first', array(
                        'conditions' => array (
                            'menu_item_id' => $iid,
                            'order_id' => $oid
                        )
                    ));
                    $this->MenuItemsOrder->id = $item_order['MenuItemsOrder']['id'];
                    $this->MenuItemsOrder->saveField('quantity', $quantity);
                }
                if ($this->request->data['Order']['action']=='update') {
                    $this->Session->setFlash('Order has been updated', 'success');
                    $this->redirect('/order/orders/confirm/'.$pid.'/'.$tid.'/'.$oid);
                }
            } 
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        }
        $this->pageTitle = 'Confirm';
        $this->Order->contain('MenuItem');
        $order = $this->Order->find('first', array(
            'conditions' => array (
                'Order.id' => $oid
            )
        ));
        if (empty($order))
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        $this->set('order', $order);
        $this->set('oid', $oid);
    }
    
    public function delete ($pid = null, $tid = null, $oid = null) {
        $cUser = $this->getCUser();
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->Order->contain();
            $order = $this->Order->findById($oid);
            if (empty($order)) {
                print 'false';
                exit();
            }
            $this->Order->id = $oid;
            $this->Order->saveField('order_status', 2);
            $this->Order->saveField('deleted_by', $cUser['email']);
            $this->TablesOrder->deleteAll(array('order_id' => $oid));
            $this->MenuItemsOrder->deleteAll(array('order_id' => $oid));
            
            // count remaining orders
            $this->Table->contain('Order');
            $table = $this->Table->find('first', array(
                'conditions' => array ('Table.id' => $tid)
            ));
            print 'true,'.count($table['Order']);
            exit();
        }
        print 'false';
        exit();
    }
    
    public function checkout($pid = null, $tid = null) {
        $cUser = $this->getCUser();
        $this->Table->contain(array('Order' => array ('MenuItem' => array ('MenuItemPrice'))));
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        if ($this->request->is('post')) {
            $issued_key = String::uuid();
            if (!empty($table['Order'])) {
                foreach ($table['Order'] as $order) {
                    $bill['Bill'] = array (
                        'order_id' => $order['id'],
                        'items' => json_encode($order['MenuItem']),
                        'table_id' => $tid,
                        'total_price' => $this->getOrderTotalPrice($order),
                        'bill_status' => 1,
                        'issued_key' => $issued_key
                    );
                    $this->Bill->create();
                    $this->Bill->save($bill);
                    foreach ($table['Order'] as $order) {
                        $this->Order->id = $order['id'];
                        $this->Order->saveField('order_status', 3);
                        $this->Order->saveField('confirm_by', $cUser['email']);
                    }
                    $this->MenuItemsOrder->deleteAll(array('MenuItemsOrder.order_id' => $order['id']));
                }
                $this->TablesOrder->deleteAll(array('TablesOrder.table_id' => $tid));
                $this->Session->setFlash('Checkout is successful', 'success');
                $this->redirect('/order/tables/index/'.$pid);
            }
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        }
        $items = array ();
        if (!empty($table['Order'])) {
            foreach ($table['Order'] as $o => $order) {
                if (!empty($order['MenuItem'])) {
                    foreach ($order['MenuItem'] as $i => $item) {
                        $item['price'] = $this->MenuItem->getPrice($item['id']);
                        $items[] = $item;
                    }
                }
            }
        }
        $this->pageTitle = 'Checkout';
        $this->set('items', $items);
    }
}