<?php

class UserMember extends AppModel 
{
    public $name = 'UserMember';
    public $belongsTo = array(
        'Member' => array (
            'className'              => 'User',
            'foreignKey'             => 'user_member_id',
        ),
        'User' => array(
            'className'              => 'User',
            'foreignKey'             => 'user_id'
        ),
        'Place' => array (
            'className' => 'Place',
            'foreignKey' => 'place_id'
        )
    );
}
