<?php

class UsersController extends ManagerAppController {
    
    public $layout = 'ezord_place';
    public $uses = array ('User', 'UserProfile', 'Place');
    public $components = array ('Upload', 'Image');
    public $helpers = array (
        'Upload'
    );
    public function beforeFilter () {
        parent::beforeFilter();
        $cUser = $this->getCUser();
        $this->set('cUser', $cUser);
        // set pagers
        $level2 = array ('avatar', 'edit', 'change_password',);
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Me',
                    'link' => '/manager/accounts/me'
                )
            );
        }
        $this->set('papers', $papers);
    }
   
    public function edit () {
        $uid = $this->getCUserId();
        if ($this->request->data) {
            $user = $this->request->data; 
            $this->User->save($user, false, array(
                'id', 'first_name', 'last_name', 'email', 'phone'
            ));
            $this->Session->setFlash('Your information has been saved', 'success');
            $this->redirect('/manager/accounts/me/');
    	}
        $this->pageTitle = 'Edit';
        $this->User->contain('UserProfile');
        $this->data = $this->User->findById($uid);
        $this->set('action', 'edit');
        $this->render('edit');
    }

    public function change_password () {
        $uid = $this->getCUserId();
        $user = $this->User->findById($uid);
        if ($this->request->data) {
            $current_password = AuthComponent::password($this->request->data['User']['current-password']);
            if($user['User']['password']==$current_password) {
                $password = $this->request->data['User']['password'];
                $this->User->id = $uid;
                $this->User->saveField('password', $password);
                $this->Session->setFlash('Password has been change', 'success');
                $this->redirect('/manager/accounts/me');
            } else {
                $this->Session->setFlash('Current password is invalid', 'error');
                $this->redirect('/manager/users/change_password');
            }
        }
        $this->set('email', $user['User']['email']);
        $this->pageTitle = 'Change password';
    }
 
    public function avatar ($mid = null) {
        $uid = $this->getCUserId();
        $this->User->contain('UserProfile');
        $user = $this->User->find('first', array (
            'conditions' => array (
                'User.id' => $uid
            )
        ));
        if ($this->request->is('post')) {
            $path = $this->Upload->mkUploadDir($uid);
            $f = $this->request->data['Upload'];
            if ($f['file']['error']==0) {
                $path = $path.DS.'avatar'; 
                $file = $this->Upload->fileUpload($f['file'], $path);
                $origin_path = $path.DS.$file['name'];
                $medium_path = $path.DS.'medium'.DS.$file['name'];
                $this->Image->resize('resizeCrop', $origin_path, $medium_path, 300, 300);
                $small_path = $path.DS.'small'.DS.$file['name'];
                $this->Image->resize('resizeCrop', $origin_path, $small_path, 100, 100);
                
                // save to database
                if (empty($user['UserProfile']['id'])) {
                    $profile['UserProfile']['user_id'] = $uid;
                    $this->UserProfile->create();
                    $this->UserProfile->save($profile);
                    $prid = $this->UserProfile->getLastInsertID();
                } else {
                    $prid = $user['UserProfile']['id'];
                }
                $this->UserProfile->id = $prid;
                $this->UserProfile->saveField('avatar', $file['name']);
                $this->Session->setFlash('Avatar has been uploaded', 'success');
            } else {
                $this->Session->setFlash('Avatar upload has been failed', 'error');
            }
            $this->redirect('/manager/accounts/me'); 
        }
        $image_path = $this->Upload->getUploadUrlPath('', $uid).'/avatar/small';
        $this->set('user', $user);
        $this->set('uid', $uid);
        $this->set('image_path', $image_path);
        $this->pageTitle = 'Upload avatar';
    }
    
    public function delete_avatar ($mid = null) {
        $uid = $this->getCUserId();
        $this->User->contain('UserProfile');
        $user = $this->User->find('first', array (
            'conditions' => array (
                'User.id' => $uid
            )
        ));
        if (!empty($user['UserProfile']['avatar'])) {
            $avatar = $user['UserProfile']['avatar'];
            $this->UserProfile->id = $user['UserProfile']['id'];
            $this->UserProfile->saveField('avatar', '');
            $image_path = $this->Upload->getUploadDir($uid).DS.'avatar';
            if (file_exists($image_path.DS.$avatar)) {
                unlink($image_path.DS.$avatar);
            }
            if (file_exists($image_path.DS.'small'.DS.$avatar)) {
                unlink($image_path.DS.'small'.DS.$avatar);
            }
            if (file_exists($image_path.DS.'medium'.DS.$avatar)) {
                unlink($image_path.DS.'medium'.DS.$avatar);
            }
            $this->Session->setFlash('Avatar has been deleted', 'success');
        } else {
            $this->Session->setFlash('Avatar delete has been failed', 'error');
        }
        $this->redirect('/manager/accounts/me');
    }
}