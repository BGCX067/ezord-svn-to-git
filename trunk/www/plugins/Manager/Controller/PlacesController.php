<?php

class PlacesController extends ManagerAppController {
    
    public $layout = 'ezord_place';
    
    public $uses = array ('Place', 'Table', 'PlacesUser', 'MenuItemGroup', 'MenuItem', 'ItemImage');
    public $components = array (
        'Upload', 
        'Manager.Paypal'
    );
    
    public function beforeFilter () {
        parent::beforeFilter();
        if($this->action != 'add' && $this->action != 'getPlacesByUserId') {
            $pid = $this->request->params['pass'][0];
            $this->place = $place = $this->checkPlaceValid($pid);
            $this->set('pid', $pid);
        }
        $cUser = $this->getCUser();
        $this->set('cUser', $cUser);
        
        // set pagers
        $level2 = array ('edit', 'delete', 'archive', 'dearchive');
        $level3 = array ();
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'].' - settings',
                    'link' => '/manager/places/dashboard/'.$pid
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'].' - settings',
                    'link' => '/manager/places/dashboard/'.$pid
                ),
                array(
                    'name' => 'Place settings',
                    'link' => '/manager/places/settings/'.$pid
                )
            );
        }
        $this->set('papers', $papers);
    }

    public function getPlacesByUserId ($uid) {
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
                'PlacesUser.user_id' => $uid,
                'Place.active' => 1
            )
        ));
        return $places;
    }

    public function dashboard ($pid) {
        $this->pageTitle = $this->place['Place']['name'].' - settings';
        $this->set('pid', $pid);
        
        // count tables
        $table_num = $this->Table->find('count', array(
            'conditions' => array ('Table.place_id' => $pid)
        ));
        $this->set('table_num', $table_num);
        $this->set('place', $this->place);
    }

    public function add () {
        $cUser = $this->getCUser();
        if ($this->request->is('post')) {
            $this->Place->set($this->request->data);
            if ($this->Place->validates()) {
                $place = $this->request->data;
                // override data
                $place['Place']['alias'] = str_replace(' ', '_', $place['Place']['name']);
                $this->Place->save($place);
                $pid = $this->Place->getLastInsertID();
                $places_user = array (
                    'user_id' => $cUser['id'],
                    'place_id' => $pid
                );
                $this->PlacesUser->save($places_user);
                $this->Session->setFlash('Your place has been created', 'success');
                $this->redirect('/manager/accounts/dashboard/');
            }
            $this->Session->setFlash('Your creating place has been failed', 'error');
            $this->redirect('add');
        }
        $this->pageTitle = 'Add place';
        $this->set('action', 'add');
        $this->render('edit');
    }

    public function edit ($pid = null) {
        if ($this->request->data) {
            $this->Place->set($this->request->data);
            if ($this->Place->validates()) {
                $place = $this->request->data;
                $this->Place->save($place);
                $this->Session->setFlash('Your place has been saved', 'success');
                $this->redirect('/manager/places/dashboard/'.$pid);
            }
        }
        $this->data = $this->place;
        $this->pageTitle = 'Place edit';
        $this->set('action', 'edit');
    }

    public function dearchive ($pid = null) {
        if ($this->request->is('post')
                && !empty($this->request->data['Place']['id'])) {
            $pid = $this->request->data['Place']['id'];
            $this->Place->id = $pid;
            $this->Place->saveField('active', 1);
            $this->redirect('/manager/places/dashboard/'.$pid);
        }
        $this->pageTitle = 'De-archive place';
        $this->set('action', 'dearchive');
        $this->render('status');
    }
    
    public function archive ($pid = null) {
        if ($this->request->is('post') 
                && !empty($this->request->data['Place']['id'])) {
            $pid = $this->request->data['Place']['id'];
            $this->Place->id = $pid;
            $this->Place->saveField('active', 0);
            $this->redirect('/manager/accounts/dashboard/');
        }
        $this->pageTitle = 'Archive';
        $this->set('action', 'archive');
        $this->render('status');
    }

}