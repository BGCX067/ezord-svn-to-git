<?php

class MenusController extends ManagerAppController {
    
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
        $pid = $this->request->params['pass'][0];
        $place = $this->checkPlaceValid($pid);
        $cUser = $this->getCUser();
        
        $this->set('pid', $pid);
        $this->set('cUser', $cUser);
        
        // set pagers
        $level2 = array ('index');
        $level3 = array ('add', 'view', 'copy');
        $level4 = array ('images', 'edit', 'price');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Settings',
                    'link' => '/manager/places/dashboard/'.$pid
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $papers = array (
                array(
                    'name' => 'Settings',
                    'link' => '/manager/places/dashboard/'.$pid
                 ),
                array(
                    'name' => 'Menu',
                    'link' => '/manager/menu_items/index/'.$pid
                )
            );
        } else if (in_array ($this->action, $level4)) {
            $iid = $this->request->params['pass'][1];
            $item = $this->MenuItem->findById($iid);
            if (empty($item))
                $this->redirect('/manager/menu_items/index/'.$pid);
            $papers = array (
                array(
                    'name' => $place['Place']['name'],
                    'link' => '/manager/places/dashboard/'.$pid
                 ),
                array(
                    'name' => 'Menu',
                    'link' => '/manager/menu_items/index/'.$pid
                ),
                array(
                    'name' => $item['MenuItem']['name'],
                    'link' => '/manager/menu_items/view/'.$pid.'/'.$iid
                )
            );
        }
        $this->set('papers', $papers);
    }

    public function index ($pid = null) {
        $uid = $this->getCUserId();
        $this->MenuItemGroup->contain(array ('MenuItem' => array ('ItemImage')));
        $groups = $this->MenuItemGroup->find('all', array (
            'conditions' => array (
                'MenuItemGroup.place_id' => $pid
            )
        ));
        $this->set('groups', $groups);
        $this->pageTitle = 'Menu';
        if (empty($groups)) {
            $places = $this->requestAction('/manager/places/getPlacesByUserId/'.$uid);
            $copy = empty($places) || count($places) <= 1 ? false : true; 
            $this->set('copy', $copy);
        }
    }
    
    public function add ($pid = null, $gid = null) {
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
    
    public function edit ($pid = null, $iid = null) {
        if ($this->request->data) {
            $this->MenuItem->set($this->request->data);
            if ($this->MenuItem->validates()) {
                $item = $this->request->data;
                // override data
                $this->MenuItem->save($item);
                $iid = $this->MenuItem->getLastInsertID();
                $this->Session->setFlash('Your item has been saved', 'success');
                $this->redirect('/manager/menu_items/index/'.$this->request->data['MenuItem']['place_id']);
            }
            $this->Session->setFlash('Your creating item has been failed', 'error');
            $this->redirect('add');
        }
        $this->MenuItem->contain();
        $this->data = $this->MenuItem->findById($iid);
        if (empty($this->data)) {
            $this->redirect('/manager/menu_items/index/'.$pid);
        }
        $this->pageTitle = 'Edit';
        $this->set('iid', $iid);
        $this->set('action', 'edit');
        $this->render('edit');
    }
    
    public function view ($pid = null, $iid = null) {
        $this->MenuItem->contain(array('MenuItemGroup', 'ItemImage', 'MenuItemPrice'));
        $item = $this->MenuItem->findById($iid);
        if (empty($item)) {
            $this->redirect('/manager/menu_items/index/'.$pid);
        }
        $this->set('iid', $iid);
        $this->set('action', 'view');
        $this->pageTitle = $item['MenuItem']['name'];
        $this->set('item', $item);
    }

    public function copy ($pid = null) {
        set_time_limit(300);
        $cUser = $this->getCUser();
        $this->pageTitle = 'Copy menu from ...';
        if ($this->request->is('post') && !empty($this->request->data)) {
            $pid_from = $this->request->data['MenuItem']['pid_from'];
            $this->Place->contain(array(
                'MenuItemGroup' => array(
                    'MenuItem' => array(
                        'ItemImage' => array(
                            'order' => 'created ASC'
                        ),
                        'MenuItemPrice',
                        'order' => 'created ASC'
                    ),
                    'order' => 'created ASC'
                )
            ));
            $place = $this->Place->findById($pid_from);
            if (!empty($place['MenuItemGroup'])) {
                foreach ($place['MenuItemGroup'] as $group) {
                    // copy group
                    $group_data['MenuItemGroup'] = array (
                        'place_id' => $pid,
                        'name' => $group['name'],
                        'description' => $group['description']
                    );
                    $this->MenuItemGroup->create();
                    $this->MenuItemGroup->save($group_data);
                    $gid = $this->MenuItemGroup->getLastInsertID();
                    if (!empty($group['MenuItem'])) {
                        foreach ($group['MenuItem'] as $item) {
                            // copy item
                            $item_data['MenuItem'] = array (
                                'menu_item_group_id' => $gid,
                                'name' => $item['name'],
                                'price' => $item['price'],
                                'description' => $item['description']
                            );
                            $this->MenuItem->create();
                            $this->MenuItem->save($item_data);
                            $iid = $this->MenuItem->getLastInsertID();
                            if (!empty($item['ItemImage'])) {
                                foreach ($item['ItemImage'] as $image) {
                                    // copy image
                                    $image_data = array (
                                        'menu_item_id' => $iid,
                                        'name' => $image['name'],
                                        'caption' => $image['caption'],
                                        'type' => $image['type'],
                                        'size' => $image['size']
                                    );
                                    $this->ItemImage->create();
                                    $this->ItemImage->save($image_data);
                                    // copy file
                                    $src_path = $this->Upload->getUploadDir($cUser['id'], $pid_from, $item['id']);
                                    $des_path = $this->Upload->mkUploadDir($cUser['id'], $pid, $iid);
                                    copy($src_path.DS.$image['name'], $des_path.DS.$image['name']);
                                    copy($src_path.DS.'small'.DS.$image['name'], $des_path.DS.'small'.DS.$image['name']);
                                    copy($src_path.DS.'medium'.DS.$image['name'], $des_path.DS.'medium'.DS.$image['name']);
                                }
                            }
                            if (!empty($item['MenuItemPrice'])) {
                                foreach ($item['MenuItemPrice'] as $price) {
                                    $price_data = array(
                                        'menu_item_id' => $iid,
                                        'price' => $price['price'],
                                        'time_from' => $price['time_from'],
                                        'time_to' => $price['time_to']
                                    );
                                    $this->MenuItemPrice->create();
                                    $this->MenuItemPrice->save($price_data);
                                }
                            }
                        }
                    }
                }
            } 
            $this->Session->setFlash('Menu has been copied', 'success');
            $this->redirect('/manager/menu_items/index/'.$pid);
        } else if ($this->request->is('post') && empty($this->request->data)) {
            $this->Session->setFlash('Please select a place to copy', 'error');
        }
        $places = $this->requestAction('/manager/places/getPlacesByUserId/'.$cUser['id']);
        if (!empty($places)) {
            foreach ($places as $i => $place) {
                $item_num = $this->MenuItem->countByPlaceId ($place['Place']['id']);
                if ($place['Place']['id'] == $pid || $item_num<=0) {
                    unset($places[$i]);
                }
            }
        }
        $this->set('places', $places);
        $this->set('pid', $pid);
    }
    
    public function images ($pid = null, $iid = null) {
        $pid = !is_null($pid) ? $pid : $this->request->data['MenuItem']['place_id'];
        $iid = !is_null($iid) ? $iid : $this->request->data['MenuItem']['menu_item_id'];
        $place = $this->checkPlaceValid($pid);
        $cUser = $this->getCUser();
        $cManager = $this->getCManager();
        if ($this->request->data) {
            $files = $this->request->data['Upload'];
            $path = $this->Upload->mkUploadDir($cManager['id'], $pid, $iid);
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
                $this->redirect('/manager/menu_items/images/'.$pid.'/'.$iid);
            }
            else {
                $this->Session->setFlash('Your images have been failed', 'error');
                $this->redirect('/manager/menu_items/images/'.$pid.'/'.$iid);
            }
        }
        $this->MenuItem->contain('ItemImage');
        $item = $this->MenuItem->findById($iid);
        if (empty($item)) {
            $this->redirect('/manager/menu_items/index/'.$pid);
        }
        $image_path = $this->Upload->getUploadUrlPath('small', $cManager['id'], $pid, $iid);
        $this->pageTitle = 'Upload';
        $this->set('pid', $pid);
        $this->set('iid', $iid);
        $this->set('action', 'view');
        $this->set('item', $item);
        $this->set('image_path', $image_path);
        $this->set('remain', 5-count($item['ItemImage']));
    }
    
    public function delete ($pid = null, $iid = null) {
        if ($this->request->is('post')) {
            $cUser = $this->getCUser();
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
                    $path = $this->Upload->getUploadDir($cUser['id'], $pid, $item['MenuItem']['id']).DS.$image['name'];
                    if (file_exists($path)) { unlink ($path); }
                    $path_small = $this->Upload->getUploadDir($cUser['id'], $pid, $item['MenuItem']['id']).DS.'small'.DS.$image['name'];
                    if (file_exists($path_small)) { unlink ($path_small); }
                    $path_medium = $this->Upload->getUploadDir($cUser['id'], $pid, $item['MenuItem']['id']).DS.'medium'.DS.$image['name'];
                    if (file_exists($path_medium)) { unlink ($path_medium); }
                    $this->ItemImage->delete($image['id'], false);
                }
            }
            $this->MenuItem->delete($item['MenuItem']['id'], false);
            $this->Upload->rmMenuDir($cUser['id'], $pid, $item['MenuItem']['id']);
            $this->Session->setFlash('Item has been deleted', 'success');
            print 'true';
            exit();
        }
        $this->Session->setFlash('Item delete has been failed', 'error');
        print 'false'; 
        exit();
    }

    public function price ($pid = null, $iid = null) {
        $item = $this->MenuItem->findById($iid);
        if (empty($item)) {
            $this->redirect('/manager/menu_items/index/'.$pid);
        }
        if ($this->request->is('post')) {
            $price_data = $this->request->data;
            $price_data['MenuItemPrice']['menu_item_id'] = $iid;
            $this->MenuItemPrice->save($price_data);
            $this->Session->setFlash('Price has been saved', 'success');
            $this->redirect('/manager/menu_items/price/'.$pid.'/'.$iid);
        }
        $this->pageTitle = 'Custom price';
        
        $prices = $this->MenuItemPrice->find('all', array(
            'conditions' => array (
                'menu_item_id' => $iid
            )
        ));
        $this->set('prices', $prices);
        $this->set('item', $item);
        $this->set('iid', $iid);
    }
}