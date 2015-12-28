<?php

class AppController extends Controller {
    
    public $pageTitle = 'EzOrd';
    public $components = array(
        'Auth'=> array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            )
        ),
        'Session'
    );
    public $helpers = array ('Session', 'Html', 'Form');
    
    public function beforeFilter () {
        $auth = $this->Session->read('Auth');
        $user = $this->getCUser(); 
        $place = $this->getCPlace();
        $manager = $this->getCManager();
        $this->set('cUser', $user);
        $this->set('cManager', $manager);
        $this->set('cPlace', $place);
    }
    
    public function beforeRender() {
        $this->set('page_title', $this->pageTitle);
    }

    public function getCUser() {
        return $this->Auth->user();
    }
    
    public function getCUserId() {
        $user = $this->getCUser();
        if (!empty($user['id']))
            return $user['id'];
        return null;
    }
    
    public function getCPlace () {
        $auth = $this->Session->read('Auth');
        if (!empty($auth['Place'])) {
            return $auth['Place'];
        }
        return null;
    }
    
    public function getCManager () {
        $user = $this->Session->read('Auth');
        if (!empty($user['Manager'])) {
            return $user['Manager'];
        } else if (empty($user['Manager']) 
                && !empty($user['User'])) {
            return $user['User'];
        } else {
            return null;
        }
    }

    public function getCManagerId() {
        $manager = $this->getCManager();
        if (!empty($manager['id']))
            return $manager['id'];
        return null;
    }
    
}
