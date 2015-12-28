<?php

class Place extends AppModel 
{
    public $name = 'Place';
    
    public $hasMany = array(
        'Table' => array(
            'className'     => 'Table',
            'foreignKey'    => 'place_id',
            'order'         => 'Table.created DESC',
            'dependent'     => true
        ),
        'MenuItemGroup' => array(
            'className'     => 'MenuItemGroup',
            'foreignKey'    => 'place_id',
            'order'         => 'MenuItemGroup.created DESC',
            'dependent'     => true
        )
    );
    public $hasAndBelongsToMany = array(
        'User' => array(
            'className'              => 'User',
            'joinTable'              => 'places_users',
            'foreignKey'             => 'place_id',
            'associationForeignKey'  => 'user_id',
            'unique'                 => true
        )
    );
}
