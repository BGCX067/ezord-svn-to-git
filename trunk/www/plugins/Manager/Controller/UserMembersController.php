<?php

class UserMembersController extends ManagerAppController {
    
    public $layout = 'ezord_team';
    public $uses = array ('User', 'UserMember', 'UserProfile', 'Place');
    public $components = array (
        'Upload', 
        'Image'
    );
    public $helpers = array (
        'Upload'
    );
    public function beforeFilter () {
        parent::beforeFilter();
        
        $cUser = $this->getCUser();
        $this->set('cUser', $cUser);
        if (!empty($this->params['pass'][0])) {
            $mid = $this->params['pass'][0];
        }
        // set pagers
        $level2 = array ('add', 'view');
        $level3 = array ('avatar', 'edit', 'reset_password', 'assign');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Members',
                    'link' => '/manager/user_members/'
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $papers = array (
                array(
                    'name' => 'Members',
                    'link' => '/manager/user_members/'
                 )
            );
            if (!empty($mid)) {
                $papers[] = array(
                    'name' => 'Profile',
                    'link' => '/manager/user_members/view/'.$mid
                );
            }
        }
        $this->set('papers', $papers);
    }
    
    public function dashboard () {
        $this->pageTitle = 'Dashboard';
        $cUser = $this->getCUser();
        $cPlace = $this->getCPlace(); 
        $this->set('pid', $cPlace['id']);
    }
    
    public function index () {
        $cUser = $this->getCUser();
        $this->pageTitle = 'Members';
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $members = $this->UserMember->find('all', array (
            'conditions' => array (
                'UserMember.user_id' => $cUser['id']
            )
        ));
        $this->set('members', $members);
    }
    
    public function view ($mid = null) {
        $this->UserMember->contain(array ('Place', 'Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member)) {
            $this->redirect('/manager/user_members/');
        }
        $this->pageTitle = 'Profile';
        $image_path = $this->Upload->getUploadUrlPath('', $mid).'/avatar/small';
        $this->set('member', $member);
        $this->set('image_path', $image_path);
    }
    
    public function add () {
    	if ($this->request->is('post')) {
            $cUser = $this->getCUser();
            $member = $this->request->data; 
            $member['User']['password'] = 'pending';
            $member['User']['active'] = 0;
            $member['User']['user_type'] = USER_TYPE_MEMBER;
            $member['User']['app_prefix'] = $cUser['app_prefix'];
            $this->User->save($member, false, array(
                'first_name', 'last_name', 'email', 'phone', 'active', 'user_type', 'password', 'app_prefix'
            ));
            $mid = $this->User->getLastInsertID();
            $user_member['UserMember'] = array (
                'user_id' => $cUser['id'],
                'user_member_id' => $mid
            );
            $this->UserMember->save($user_member);
            $this->Session->setFlash('Member has been saved', 'success');
            $this->redirect('/manager/user_members/assign/'.$mid);
    	}
        $this->pageTitle = 'Add';
        $this->set('action', 'add');
        $this->render('edit');
    }
    
    public function edit ($mid = null) {
        if ($this->request->data) {
            $cUser = $this->getCUser();
            $user = $this->request->data; 
            $this->User->save($user, false, array(
                'id', 'first_name', 'last_name', 'email', 'phone', 'active', 'user_type', 'password'
            ));
            $this->Session->setFlash('Member has been saved', 'success');
            $this->redirect('/manager/user_members/view/'.$mid);
    	}
        $this->pageTitle = 'Edit';
        $this->UserMember->contain('Member');
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        $data['User'] = $member['Member'];
        $this->data = $data;
        $this->set('action', 'edit');
        $this->render('edit');
    }
    
    public function delete ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->Session->setFlash('Member delete has been failed', 'error');
            $this->redirect('/manager/user_members/');
        }
        
        $this->User->delete($member['Member']['id'], false);
        $this->UserProfile->deleteAll(array(
            'user_id' => $mid
        ));
        $this->UserMember->deleteAll(array(
            'user_member_id' => $member['UserMember']['user_member_id']
        ));
        
        $avatar = $member['Member']['UserProfile']['avatar'];
        if (!empty($avatar)) {
            $image_path = $this->Upload->getUploadDir($mid).DS.'avatar';
            if (file_exists($image_path.DS.$avatar)) {
                unlink($image_path.DS.$avatar);
            }
            if (file_exists($image_path.DS.'small'.DS.$avatar)) {
                unlink($image_path.DS.'small'.DS.$avatar);
            }
            if (file_exists($image_path.DS.'medium'.DS.$avatar)) {
                unlink($image_path.DS.'medium'.DS.$avatar);
            }
        }
        $this->Upload->rmAvatarDir($mid);
        $user_path = $this->Upload->getUploadDir($mid);
        if (is_dir($user_path)) {
            rmdir($user_path);
        }
        $this->Session->setFlash('Member has been deleted', 'success');
        $this->redirect('/manager/user_members/');
    }
    
    public function avatar ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        if ($this->request->is('post')) {
            $path = $this->Upload->mkUploadDir($member['Member']['id']);
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
                if (empty($member['Member']['UserProfile'])) {
                    $profile['UserProfile'] = array ('user_id' => $member['Member']['id']);
                    $this->UserProfile->create();
                    $this->UserProfile->save($profile);
                    $prid = $this->UserProfile->getLastInsertID();
                } else {
                    $prid = $member['Member']['UserProfile']['id'];
                }
                $this->UserProfile->id = $prid;
                $this->UserProfile->saveField('avatar', $file['name']);
                $this->Session->setFlash('Avatar has been uploaded', 'success');
            } else {
                $this->Session->setFlash('Avatar upload has been failed', 'error');
            }
            $this->redirect('/manager/user_members/view/'.$mid);
        }
        $image_path = $this->Upload->getUploadUrlPath('', $mid).'/avatar/small';
        $this->set('member', $member);
        $this->set('mid', $mid);
        $this->set('image_path', $image_path);
        $this->pageTitle = 'Upload avatar';
    }
    
    public function delete_avatar ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        if (!empty($member['Member']['UserProfile']['avatar'])) {
            $avatar = $member['Member']['UserProfile']['avatar'];
            $this->UserProfile->id = $member['Member']['UserProfile'];
            $this->UserProfile->saveField('avatar', '');
            $image_path = $this->Upload->getUploadDir($mid).DS.'avatar';
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
        $this->redirect('/manager/user_members/avatar/'.$mid);
    }
    
    public function active ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        $this->User->id = $mid;
        $this->User->saveField('active', 1);
        $this->Session->setFlash('Member has been actived', 'success');
        $this->redirect('/manager/user_members/view/'.$mid);
    }
    
    public function deactive ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        $this->User->id = $mid;
        $this->User->saveField('active', 0);
        $this->Session->setFlash('Member has been de-actived', 'success');
        $this->redirect('/manager/user_members/view/'.$mid);
    }
    
    public function reset_password ($mid = null) {
        $this->UserMember->contain(array ('Member' => array ('UserProfile')));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        )); 
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        if ($this->request->data) {
            $password = $this->request->data['User']['password'];
            $this->User->id = $mid;
            $this->User->saveField('password', $password);
            $this->Session->setFlash('Password has been set', 'success');
            $this->redirect('/manager/user_members/view/'.$mid);
        }
        $this->set('mid', $mid);
        $this->pageTitle = 'Reset password';
    }
    
    public function assign ($mid = null) {
        $cUser = $this->getCUser();
        $this->UserMember->contain(array ('Member', 'Place'));
        $member = $this->UserMember->find('first', array (
            'conditions' => array (
                'UserMember.user_member_id' => $mid
            )
        ));
        if (empty($member['Member']['id'])) {
            $this->redirect('/manager/user_members/');
        }
        if ($this->request->is('post')) {
            $this->UserMember->id = $member['UserMember']['id'];
            $this->UserMember->saveField('place_id', $this->request->data['UserMember']['place_id']);
            $this->UserMember->saveField('permit_manager', $this->request->data['UserMember']['permit_manager'] ? 1 : 0);
            $this->UserMember->saveField('permit_order', $this->request->data['UserMember']['permit_order'] ? 1 : 0);
            $this->UserMember->saveField('permit_report', $this->request->data['UserMember']['permit_report'] ? 1 : 0);
            $this->redirect('/manager/user_members/view/'.$mid);
        }
        $places = $this->Place->find('list', array (
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
            ),
            'fields' => array ('id', 'name')
        ));
        if (!empty($member['Place'])) {
            $this->set('pid', $member['Place']['id']);
        } else {
            $this->set('pid', null);
        }
        $this->pageTitle = 'Permissions';
        $this->set('places', $places);
        $this->set('mid', $mid);
        $this->data = $member;
    }
}