<?php

class UsersController extends ApiAppController {

    public $uses = array ('User', 'ApiSession');
    
    private function getUserConfig ()
    {
        $config = array (
            'version' => 20,
            'host' => 'ezord.tabsclouding.com',
            'theme-name' => 'default',
            'theme-background' => 'http://ezord.tabsclouding.com/bg.gif'
        );
        return $config;
    }
    
    private function getAPIAuth($user) {
        // App::uses('String', 'Utility');
        $auth = array (
            'session_id' => String::uuid()
        );
        return $auth;
    }
    
    public function beforeFilter () {
        $this->Auth->allow('getLogin');
    }
    
    public function getLogin () {
        if ($this->request->is('post')) {
            $email = $this->request->data['email'];
            $password = $this->request->data['password'];
            $user = $this->User->find('first', array(
                'fields' => array ('id', 'email', 'first_name', 'last_name', 'last_login', 'user_type', 'created', 'updated'),
                'conditions' => array (
                    'email' => $email,
                    'password' => AuthComponent::password($password)
                )
            ));
            if (!empty($user['User'])) {
                $data['User'] = $user['User'];
                $data['Config'] = $this->getUserConfig();
                $data['Auth'] = $this->getAPIAuth($user);
                $session['ApiSession'] = array (
                    'id' => $data['Auth']['session_id'],
                    'value' => json_encode($user['User'])
                );
                $this->ApiSession->save($session);
            } else {
                $data = array (
                    'Error' => 1,
                    'ErrorMessage' => 'Invalid username or password'
                );
            }
            $this->renderJson($data);
        } else {
            $data = array (
                'Error' => 1,
                'ErrorMessage' => 'Required parrams are missing'
            );
            $this->renderJson($data);
        }
    }
}