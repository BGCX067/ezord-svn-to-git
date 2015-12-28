<?php

class Reserve extends AppModel 
{
    public $name = 'Reserve';
    
    public $belongsTo = array(
        'Table' => array (
            'className'              => 'Table',
            'foreignKey'             => 'table_id',
        )
    );
}
