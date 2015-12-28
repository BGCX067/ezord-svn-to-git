<?php

class TablesController extends ManagerAppController {
    
    public $layout = 'ezord_place';
    public $uses = array ('Table', 'Place');
    
    public function beforeFilter () {
        parent::beforeFilter();
        $pid = $this->request->params['pass'][0];
        $place = $this->checkPlaceValid($pid);
        $cUser = $this->getCUser();
        
        $this->set('pid', $pid);
        $this->set('cUser', $cUser);
        
        // set pagers
        $level2 = array ('index');
        $level3 = array ('add', 'edit', 'delete', 'auto_add');
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
                    'name' => 'Tables',
                    'link' => '/manager/tables/index/'.$pid
                )
            );
        }
        $this->set('papers', $papers);
    }

    public function index ($pid) {
        $cUser = $this->getCUser();
        $this->paginate = array (
            'Table' => array (
                'conditions' => array (
                    'Table.place_id' => $pid
                ),
                'limit' => 8,
                'order' => 'Table.order ASC'
            )
        );
        $tables = $this->paginate('Table');
        $this->set('tables', $tables);
        $this->pageTitle = 'Tables';
    }

    public function add ($pid = null) {
        $cUser = $this->getCUser();
        if ($this->request->is('post')) {
            $this->Table->set($this->request->data);
            if ($this->Table->validates()) {
                $table = $this->request->data;
                // override data
                $this->Table->save($table);
                $this->Session->setFlash('Your table has been created', 'success');
                $this->redirect('/manager/tables/index/'.$pid);
            }
            $this->Session->setFlash('Your creating place has been failed', 'error');
            $this->redirect('add');
        }
        $this->pageTitle = 'Add';
        $this->set('action', 'add');
        $this->render('edit');
    }
    
    public function auto_add ($pid = null) {
        $this->pageTitle = 'Add';
        if ($this->request->is('post')) {
            $number = $this->request->data['Table']['number'];
            if ($number>0 && $number<30) {
                for ($i=1; $i<=$number; $i++) {
                    $table['Table'] = array (
                        'place_id' => $pid,
                        'name' => 'Table #'.$i,
                        'active' => 1
                    );
                    $this->Table->create();
                    $this->Table->save($table);
                }
                $this->Session->setFlash('Tables have been created', 'success');
                $this->redirect('/manager/tables/index/'.$pid);
            }
            $this->Session->setFlash('Tables auto adding have been failed', 'error');
            $this->redirect('/manager/tables/auto_add/'.$pid);
        }
    }

    public function edit ($pid = null, $tid = null) {
        if ($this->request->data) {
            $this->Table->set($this->request->data);
            if ($this->Table->validates()) {
                $table = $this->request->data;
                $this->Table->save($table);
                $this->Session->setFlash('Your table has been saved', 'success');
                $this->redirect('/manager/tables/index/'.$pid);
            }
        }
        $this->Table->contain();
        $this->data = $this->Table->findById($tid);
        $this->pageTitle = 'Edit';
        $this->set('action', 'edit');
    }

    public function delete ($pid = null, $tid = null) {
        if ($this->request->data) {
            $tid = $this->request->data['tid'];
            $this->Table->delete($tid);
            $this->Session->setFlash('Table has been deleted', 'success');
            print 'true';
            exit();
        } 
        $this->Session->setFlash('Table deleting has been failed', 'error');
        print 'false';
        exit();
    }
    
    public function active ($pid = null, $tid = null) {
        $this->Table->contain();
        $table = $this->Table->findById($tid);
        if (!empty($table)) {
            $this->Table->id = $tid;
            $this->Table->saveField('active', 1);
            $this->Session->setFlash($table['Table']['name'].' has been actived', 'success');
        } else {
            $this->Session->setFlash('Table active has been failed', 'error');
        }
        $this->redirect('/manager/tables/index/'.$pid);
    }
    
    public function de_active ($pid = null, $tid = null) {
        $this->Table->contain();
        $table = $this->Table->findById($tid);
        if (!empty($table)) {
            $this->Table->id = $tid;
            $this->Table->saveField('active', 0);
            $this->Session->setFlash($table['Table']['name'].' has been de-actived', 'success');
        } else {
            $this->Session->setFlash('Table de-active has been failed', 'error');
        }
        $this->redirect('/manager/tables/index/'.$pid);
    }

}