<?php

class MenuItemGroup extends AppModel {
    
    public $name = 'MenuItemGroup';
    public $hasMany = array(
        'MenuItem' => array(
            'className'     => 'MenuItem',
            'foreignKey'    => 'menu_item_group_id',
            'order'         => 'MenuItem.created DESC',
            'dependent'     => true
        )
    );
}
