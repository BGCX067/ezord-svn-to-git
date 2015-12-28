<?php

class User extends AppModel 
{
    public $name = 'User';
    public $tablePrefix = 'fix';
    
    public $hasOne = array (
        'UserProfile' => array (
            'className' => 'UserProfile',
            'foreignKey' => 'user_id'
        )
    );
    
    public $hasAndBelongsToMany = array(
        'Place' => array(
            'className'              => 'Place',
            'joinTable'              => 'places_users',
            'foreignKey'             => 'user_id',
            'associationForeignKey'  => 'place_id',
            'unique'                 => true
        )
    );
    
    var $validate = array (
        'first_name' => array(
            'rule' => array('minLength', 1),
            'allowEmpty' => false
        ),
        'last_name' => array(
            'rule' => array('minLength', 1),
            'allowEmpty' => false
        ),
        'email' => array(
            'rule' => 'email',
            'allowEmpty' => false
        ),
        'password' => array (
            'rule' => array('minLength', 6),
            'allowEmpty' => false,
        ),
        'phone' => array(
            'rule' => array('minLength', 3),
            'allowEmpty' => false
        )
    );

    public function beforeSave() {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
}
