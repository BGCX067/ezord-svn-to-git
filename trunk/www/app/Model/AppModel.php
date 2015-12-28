<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    public $actsAs = array('Containable');
    
    public function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->contain();
        if ($this->tablePrefix == 'fix') {
            $this->tablePrefix = '';
        } else {
            $this->tablePrefix = $this->getAppPrefix();
        }
    }
    
    public function getAppPrefix(){
        App::uses('CakeSession', 'Model/Datasource');
        $user = CakeSession::read('Auth');
        if (!empty($user['User']['app_prefix'])) {
            $prefix = $user['User']['app_prefix'].'_';
        } else {
            $prefix = '';
        }
        return $prefix;
    }
    
    public function beforeFind(){
        $this->contain();
    }
}
