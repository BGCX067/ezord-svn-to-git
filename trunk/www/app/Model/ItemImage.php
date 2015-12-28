<?php

class ItemImage extends AppModel {
    
    public $name = 'ItemImage';
    public $belongsTo = array(
        'MenuItem' => array(
            'className'    => 'MenuItem',
            'foreignKey'   => 'menu_item_id'
        )
    );
}
