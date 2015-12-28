<?php

class Order extends AppModel {
    
    public $name = 'Order';
    
    public $hasOne = 'Bill';
    public $hasAndBelongsToMany = array(
        'Table' => array(
            'className'              => 'Table',
            'joinTable'              => 'tables_orders',
            'foreignKey'             => 'order_id',
            'associationForeignKey'  => 'table_id',
            'unique'                 => true
        ),
        'MenuItem' => array(
            'className'              => 'MenuItem',
            'joinTable'              => 'menu_items_orders',
            'foreignKey'             => 'order_id',
            'associationForeignKey'  => 'menu_item_id',
            'unique'                 => true
        )
    );
}
