<?php

class UserProfile extends AppModel 
{
    public $name = 'UserProfile';
    
    public $belongsTo = array(
        'User' => array(
            'className'              => 'User',
            'foreignKey'             => 'user_id'
        ),
    );
}