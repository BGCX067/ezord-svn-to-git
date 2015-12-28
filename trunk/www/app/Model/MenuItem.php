<?php

class MenuItem extends AppModel {
    
    public $name = 'MenuItem';
    public $belongsTo = array(
        'MenuItemGroup' => array(
            'className'    => 'MenuItemGroup',
            'foreignKey'   => 'menu_item_group_id'
        )
    );

    public $hasMany = array(
        'ItemImage' => array(
            'className'    => 'ItemImage',
            'foreignKey'   => 'menu_item_id'
        ),
        'MenuItemPrice' => array(
            'className'    => 'MenuItemPrice',
            'foreignKey'   => 'menu_item_id'
        )
    );

    public $hasAndBelongsToMany = array(
        'Order' => array(
            'className'              => 'Order',
            'joinTable'              => 'menu_items_orders',
            'foreignKey'             => 'menu_item_id',
            'associationForeignKey'  => 'order_id',
            'unique'                 => true
        )
    );
    
    public function getPrice ($iid) {
        $this->contain('MenuItemPrice');
        $item = $this->find('first', array(
            'conditions' => array('MenuItem.id' => $iid)
        ));
        if (empty($item)) {
            return null;
        }
        if (!empty($item['MenuItemPrice'])) {
            // get price by time
            foreach ($item['MenuItemPrice'] as $price) {
                if (time()>=strtotime($price['time_from']) 
                            && time()<strtotime($price['time_to']) ) {
                    return $price['price'];
                }
            }
        } 
        return $item['MenuItem']['price'];
    }

}
