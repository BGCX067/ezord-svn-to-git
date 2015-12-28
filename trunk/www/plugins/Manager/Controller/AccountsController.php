<?php

class AccountsController extends ManagerAppController {
    
    public $layout = 'ezord_manager';
    public $uses = array ('Place', 'User');
    public $components = array('Upload');
    
    public function beforeFilter () {
        parent::beforeFilter();
    }
     
    public function dashboard () {
        $this->pageTitle = 'My places'; 
        $cUser = $this->getCUser();
        $places = $this->Place->find('all', array (
            'joins' => array (
                array (
                    'table' => $this->Place->tablePrefix.'places_users',
                    'alias' => 'PlacesUser',
                    'type' => 'inner',
                    'conditions' => array(
                        'Place.id = PlacesUser.place_id'
                    )
                )
            ),
            'conditions' => array (
                'PlacesUser.user_id' => $cUser['id'],
                'Place.active' => 1
            )
        ));
        $this->set('place_num', count($places));
        $this->set('places', $places);
    }
    
    public function me () {
        $this->pageTitle = 'Me';
        $this->layout = 'ezord_place';
        $uid = $this->getCUserId();
        $this->User->contain('UserProfile');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $uid
            )
        ));
        $this->set('uid', $uid);
        $this->set('user', $user);
        $image_path = $this->Upload->getUploadUrlPath('', $uid).'/avatar/small';
        $this->set('image_path', $image_path);
    }

    public function detail() {
        $this->pageTitle = 'Account';
        $this->layout = 'ezord_place';
        $cUser = $this->getCUser();
        $this->set('user', $cUser);
    }
}