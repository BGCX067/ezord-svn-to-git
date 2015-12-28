<?php

class MenuItemsController extends ManagerAppController {
    
    public $uses = array ('Place', 'MenuItemGroup', 'MenuItem', 'ItemImage', 'MenuItemPrice');
    public $components = array (
        'Upload', 
        'Image'
    );
    public $helpers = array (
        'Upload'
    );
    public $layout = 'ezord_place';
    
    public function beforeFilter () {
        parent::beforeFilter();
        // set pagers
        $level2 = array ('add', 'view');
        $level3 = array ('images', 'edit', 'price');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Foods & Drinks',
                    'link' => '/manager/menu_items/index'
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $iid = $this->request->params['pass'][0];
            $this->MenuItem->contain();
            $item = $this->MenuItem->findById($iid);
            $papers = array (
                array(
                    'name' => 'Foods & Drinks',
                    'link' => '/manager/menu_items/index'
                 ),
                array(
                    'name' => $item['MenuItem']['name'],
                    'link' => '/manager/menu_items/view/'.$iid
                )
            );
        } 
        $this->set('papers', $papers);
    }

    public function index () {
        $uid = $this->getCUserId();
        $this->MenuItemGroup->contain(array ('MenuItem' => array ('ItemImage')));
        $groups = $this->MenuItemGroup->find('all', array (
            'conditions' => array (
                'MenuItemGroup.user_id' => $uid
            )
        ));
        $this->set('groups', $groups);
        $this->pageTitle = 'Foods & Drinks';
    }
    
    public function add ($gid = null) {
        if ($this->request->is('post')) {
            $this->MenuItem->set($this->request->data);
            if ($this->MenuItem->validates()) {
                $item = $this->request->data;
                // override data
                $this->MenuItem->save($item);
                $iid = $this->MenuItem->getLastInsertID();
                $this->Session->setFlash('Your item has been created', 'success');
                $this->redirect('/manager/menu_items/view/'.$pid.'/'.$iid);
            }
            $this->Session->setFlash('Your creating item has been failed', 'error');
            $this->redirect('add/'.$pid);
        }
        $this->set('action', 'add');
        $this->set('gid', $gid);
        $this->pageTitle = 'Item Add';
        $this->render('edit');
    }
    
    public function edit ($iid = null) {
        if ($this->request->data) {
            $this->MenuItem->set($this->request->data);
            if ($this->MenuItem->validates()) {
                $item = $this->request->data;
                // override data
                $this->MenuItem->save($item);
                $this->Session->setFlash('Your item has been saved', 'success');
                $this->redirect('/manager/menu_items/view/'.$iid);
            }
            $this->Session->setFlash('Your creating item has been failed', 'error');
            $this->redirect('/manager/menu_items/index/');
        }
        $this->MenuItem->contain();
        $this->data = $this->MenuItem->findById($iid);
        if (empty($this->data)) {
            $this->redirect('/manager/menu_items/index/');
        }
        $this->pageTitle = 'Edit';
        $this->set('iid', $iid);
        $this->set('action', 'edit');
        $this->render('edit');
    }
    
    public function view ($iid = null) {
        $this->MenuItem->contain(array('MenuItemGroup', 'ItemImage', 'MenuItemPrice'));
        $item = $this->MenuItem->findById($iid);
        if (empty($item)) {
            $this->redirect('/manager/menu_items/index/');
        }
        $this->set('iid', $iid);
        $this->set('action', 'view');
        $this->pageTitle = $item['MenuItem']['name'];
        $this->set('item', $item);
    }

    public function images ($iid = null) {
        $iid = !is_null($iid) ? $iid : $this->request->data['MenuItem']['menu_item_id'];
        $cUser = $this->getCUser();
        $cManager = $this->getCManager();
        if ($this->request->data) {
            $files = $this->request->data['Upload'];
            $this->Upload->mkUploadDir($cManager['id'], $iid);
            $path = $this->Upload->getUploadDir($cManager['id'], $iid);
            foreach ($files as $f) {
                // Scale size
                $failed_files = array ();
                if ($f['file']['error']==0) {
                    $file = $this->Upload->fileUpload($f['file'], $path);
                    $origin_path = $path.DS.$file['name'];
                    $medium_path = $path.DS.'medium'.DS.$file['name'];
                    $this->Image->resize('resizeCrop', $origin_path, $medium_path, 300, 300);
                    $small_path = $path.DS.'small'.DS.$file['name'];
                    $this->Image->resize('resizeCrop', $origin_path, $small_path, 100, 100);
                    // save to database
                    $image['ItemImage'] = array (
                        'menu_item_id' => $iid,
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'size' => $file['size']
                    ); 
                    $this->ItemImage->create();
                    $this->ItemImage->save($image);
                    if (!is_array($file))
                        $failed_files[] = $f['file']['name'];
                }
            }
            if (empty($failed_files)) {
                $this->Session->setFlash('Your images have been uploaded', 'success');
                $this->redirect('/manager/menu_items/images/'.$iid);
            }
            else {
                $this->Session->setFlash('Your images have been failed', 'error');
                $this->redirect('/manager/menu_items/images/'.$iid);
            }
        }
        $this->MenuItem->contain('ItemImage');
        $item = $this->MenuItem->findById($iid);
        if (empty($item)) {
            $this->redirect('/manager/menu_items/index/');
        }
        $image_path = $this->Upload->getUploadUrlPath('small', $cManager['id'], $iid);
        $this->pageTitle = 'Upload';
        $this->set('iid', $iid);
        $this->set('action', 'view');
        $this->set('item', $item);
        $this->set('image_path', $image_path);
        $this->set('remain', 5-count($item['ItemImage']));
    }
    
    public function delete ($iid = null) {
        if ($this->request->is('post')) {
            $mnid = $this->getCManagerId();
            $iid = $this->request->data['iid'];
            $this->MenuItem->contain('ItemImage');
            $item = $this->MenuItem->find('first', array (
                'conditions' => array (
                    'MenuItem.id' => $iid
                )
            ));
            if (!empty($item['ItemImage'])) {
                foreach ($item['ItemImage'] as $image) {
                    // Delete item images
                    $path = $this->Upload->getUploadDir($mnid, $item['MenuItem']['id']).DS.$image['name'];
                    if (file_exists($path)) { unlink ($path); }
                    $path_small = $this->Upload->getUploadDir($mnid, $item['MenuItem']['id']).DS.'small'.DS.$image['name'];
                    if (file_exists($path_small)) { unlink ($path_small); }
                    $path_medium = $this->Upload->getUploadDir($mnid, $item['MenuItem']['id']).DS.'medium'.DS.$image['name'];
                    if (file_exists($path_medium)) { unlink ($path_medium); }
                    $this->ItemImage->delete($image['id'], false);
                }
            }
            $this->MenuItem->delete($item['MenuItem']['id'], false);
            $this->Upload->rmItemDir($mnid, $item['MenuItem']['id']);
            $this->Session->setFlash('Item has been deleted', 'success');
            print 'true';
            exit();
        }
        $this->Session->setFlash('Item delete has been failed', 'error');
        print 'false'; 
        exit();
    }
 
}