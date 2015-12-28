<?php

class UsersController extends AppController {

	public $name = 'Users';
	public $uses = array('User', 'UserMember', 'UserProfile', 'UserStatus');
    public $layout = 'ezord';

    public function createSampleData($uid, $prefix) {
        // Create sample place with 12 tables
        $this->requestAction('/sample/addPlace/'.$uid.'/'.$prefix);
    }
    
    public function beforeFilter() {
        $this->Auth->allow('login', 'logout', 'register', 'thankyou', 'captcha_image');
    }
    
    public function pre_dashboard() {
        $user = $this->getCUser();
        if ($user['user_type'] == USER_TYPE_MANAGER) {
            $this->redirect('/manager/accounts/dashboard/');
        } else if ($user['user_type'] == USER_TYPE_MEMBER) {
            $auth = $this->Session->read('Auth');
            $this->UserMember->contain(array ('User', 'Place'));
            $manager = $this->UserMember->find('first', array(
                'conditions' => array ('user_member_id' => $user['id'])
            ));
            // Manager information
            $auth['Manager'] = $manager['User'];
            // Current place
            $auth['Place'] = $manager['Place'];
            $auth['Place']['permit_manager'] = $manager['UserMember']['permit_manager'];
            $auth['Place']['permit_order'] = $manager['UserMember']['permit_order'];
            $auth['Place']['permit_report'] = $manager['UserMember']['permit_report'];
            $this->Session->write('Auth', $auth);
            $this->redirect('/order/tables/index/'.$manager['Place']['id']);
        }
        else {
            $this->logout();
        }
    }
    
	public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->User->id = $this->getCUserId();
                $this->User->saveField('last_login', date('Y-m-d H:i:s'));
                $this->redirect('pre_dashboard');
            }
            $this->redirect('login');
        }
        $this->pageTitle = 'Login';
	}
    
    public function logout() {
       $this->Session->write('Auth', '');
       $this->redirect($this->Auth->logout());
    }
    
    public function register() {
        if ($this->request->is('post')) {
            $validCaptcha = $this->Session->read('captcha') == $this->request->data['User']['captcha'];
            if ($validCaptcha) {
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $user = $this->request->data;
                    $user['User']['user_type'] = USER_TYPE_MANAGER;
                    $user['User']['app_prefix'] = EZORD_APP_PREFIX;
                    $this->User->save($user);
                    $uid = $this->User->getLastInsertID();
                    $this->createSampleData($uid, EZORD_APP_PREFIX.'_');
                    $this->redirect('thankyou'); 
                }
                $this->redirect('register');
            } else {
                $this->set('invalidCaptcha', true);
            }
        } 
        $this->pageTitle = 'Register';
    }
    
    public function thankyou() {
        $this->pageTitle = 'Thank you';
    }
    
    public function captcha_image () {
        $this->disableCache();
        App::import('Vendor', 'captcha/captcha');
        $captcha = new captcha();
        $captcha->show_captcha();
    }
    
}
