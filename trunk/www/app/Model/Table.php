<?php

class Table extends AppModel {
    public $name = 'Table';
    public $hasMany = array (
        'Reserve' => array (
            'className'     => 'Reserve',
            'foreignKey'    => 'table_id'
        )
    );
    public $belongsTo = array (
        'Place' => array(
            'className'    => 'Place',
            'foreignKey'   => 'place_id'
        )
    );
    public $hasAndBelongsToMany = array(
        'Order' => array(
            'className'              => 'Order',
            'joinTable'              => 'tables_orders',
            'foreignKey'             => 'table_id',
            'associationForeignKey'  => 'order_id',
            'unique'                 => true
        )
    );
}
