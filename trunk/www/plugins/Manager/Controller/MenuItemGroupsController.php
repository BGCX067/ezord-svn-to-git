<?php

class MenuItemGroupsController extends ManagerAppController {
    
    public $uses = array ('Place', 'MenuItemGroup', 'MenuItem', 'ItemImage');
    public $components = array (
        'Upload'
    );
    public $layout = 'ezord_place';
    
    public function getOptionMenuItemGroups () {
        $mnid = $this->getCManagerId();
        return $this->MenuItemGroup->find('list', array (
            'fields' => array ('id', 'name'),
            'conditions' => array (
                'MenuItemGroup.user_id' => $mnid
            )
        ));
    }
    
    public function beforeFilter () {
        parent::beforeFilter();
        // set pagers
        $level2 = array ('add', 'edit');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Foods & Drinks',
                    'link' => '/manager/menu_items/index/'
                 ),
            );
        }
        $this->set('papers', $papers);
    }
    
    public function add () {
        if ($this->request->is('post')) {
            $this->MenuItemGroup->set($this->request->data);
            if ($this->MenuItemGroup->validates()) {
                $group = $this->request->data;
                $group['MenuItemGroup']['user_id'] = $this->getCManagerId();
                // override data
                $this->MenuItemGroup->save($group);
                $this->Session->setFlash('Your item group has been created', 'success');
                $this->redirect('/manager/menu_items/index/');
            }
            $this->Session->setFlash('Your creating item group has been failed', 'error');
            $this->redirect('add');
        }
        $this->set('action', 'add');
        $this->pageTitle = 'Add';
        $this->render('edit');
    }
    
    public function edit ($gid = null) {
        if (!empty($this->request->data)) {
            $this->MenuItemGroup->set($this->request->data);
            if ($this->MenuItemGroup->validates()) {
                $group = $this->request->data;
                // override data
                $this->MenuItemGroup->save($group);
                $this->Session->setFlash('Your item group has been saved', 'success');
                $this->redirect('/manager/menu_items/index/');
            }
            $this->Session->setFlash('Your creating item group has been failed', 'error');
            $this->redirect('/manager/menu_items/index/');
        }
        $this->data = $this->MenuItemGroup->find('first', array (
            'conditions' => array (
                'MenuItemGroup.id' => $gid
            )
        ));
        if (empty($this->data))
            $this->redirect('/manager/menu_items/index/');
        $this->set('action', 'edit');
        $this->pageTitle = 'Edit';
        $this->render('edit');
    }
    
    public function delete ($gid = null) {
        $mnid = $this->getCManagerId();
        if ($this->request->is('post')) {
            $gid = $this->request->data['gid'];
            // Delete menu item images
            $this->MenuItemGroup->contain(array ('MenuItem' => array ('ItemImage')));
            $group = $this->MenuItemGroup->find('first', array (
                'conditions' => array (
                    'MenuItemGroup.id' => $gid,
                    'MenuItemGroup.user_id' => $this->getCManagerId()
                )
            ));
            if (!empty($group)) {
                foreach ($group['MenuItem'] as $item) {
                    if (!empty($item['ItemImage'])) {
                        foreach ($item['ItemImage'] as $image) {
                            // Delete item images
                            $path = $this->Upload->getUploadDir($mnid, $item['id']).DS.$image['name'];
                            if (file_exists($path)) { unlink ($path); }
                            $path_small = $this->Upload->getUploadDir($mnid, $item['id']).DS.'small'.DS.$image['name'];
                            if (file_exists($path_small)) { unlink ($path_small); }
                            $path_medium = $this->Upload->getUploadDir($mnid, $item['id']).DS.'medium'.DS.$image['name'];
                            if (file_exists($path_medium)) { unlink ($path_medium); }
                            $this->ItemImage->delete($image['id'], false);
                        }
                    }
                    $this->MenuItem->delete($item['id'], false);
                    $this->Upload->rmItemDir($mnid, $item['id']);
                }
                // Delete group
                $this->MenuItemGroup->delete($group['MenuItemGroup']['id'], false);
            }
            $this->Session->setFlash('Group has been deleted', 'success');
            print 'true';
            exit();
        }
        print 'false';
        exit();
    }
}