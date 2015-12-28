<?php 

class ReservesController extends OrderAppController {
    
    public $layout = 'ezord_order';
    public $uses = array ('Place', 'Reserve', 'Table');
    
    public function beforeFilter () {
        parent::beforeFilter();
        $pid = $this->request->params['pass'][0];
        $place = $this->checkPlaceValid ($pid);
        $cUser = $this->getCUser();
        $this->cPlace = $place;
        $this->set('pid', $pid);
        
        // set pagers
        $level2 = array ('index');
        $level3 = array ('add', 'edit', 'assign');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'],
                    'link' => '/order/tables/index/'.$pid
                )
            );
        } else if (in_array ($this->action, $level3)) {
            $papers = array (
                array(
                    'name' => $place['Place']['name'],
                    'link' => '/order/tables/index/'.$pid
                ),
                array(
                    'name' => 'Reserve',
                    'link' => '/order/reserves/index/'.$pid
                )
            );
        }
        $this->set('papers', $papers);
    }
    
    public function index ($pid = null) {
        $this->pageTitle = 'Reserve';
        $this->Reserve->contain('Table');
        $reserves = $this->Reserve->find('all', array (
            'conditions' => array (
                'Reserve.place_id' => $pid
            )
        ));
        $this->set('reserves', $reserves);
    }
    
    public function add ($pid = null) {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $this->Reserve->save($data);
            $this->Session->setFlash('Reserve has been saved', 'success');
            $this->redirect('/order/reserves/index/'.$pid);
        }
        $this->pageTitle = 'Add';
        $this->set('action', 'add');
        $this->render('edit');
    }
    
    public function edit ($pid = null, $rid = null) {
        if ($this->request->data) {
            $data = $this->request->data;
            $this->Reserve->save($data);
            $this->Session->setFlash('Reserve has been saved', 'success');
            $this->redirect('/order/reserves/index/'.$pid);
        }
        $this->Reserve->contain();
        $this->data = $this->Reserve->findById($rid);
        $this->pageTitle = 'Edit';
        $this->set('action', 'edit');
        $this->render('edit');
    }
    
    public function delete ($pid = null, $rid = null) {
        if ($this->request->is('post')) {
            $rid = $this->request->data['rid'];
            $this->Reserve->delete($rid);
            $this->Session->setFlash('Reserve has been deleted', 'success');
            print 'true';
            exit();
        }
        $this->Session->setFlash('Reserve deleting has been fail', 'error');
        print 'false';
        exit();
    }
    
    public function assign ($pid = null, $rid = null) {
        $this->Reserve->contain();
        $reserve = $this->Reserve->findById($rid);
        if (empty($reserve)) {
            $this->redirect('/order/reserves/index/'.$pid);
        }
        if ($this->request->is('post')) {
            $tid = $this->request->data['Reserve']['table_id'];
            if (!empty($tid)) {
                $this->Reserve->id = $rid;
                $this->Reserve->saveField('table_id', $tid);
                $this->Session->setFlash('Reserve has been assigned', 'success');
            }
            $this->redirect('/order/reserves/index/'.$pid);
        }
        $this->Table->contain(array ('Order', 'Reserve'));
        $tables = $this->Table->find('all', array(
            'conditions' => array (
                'Table.place_id' => $pid
            ),
            'order' => 'Table.order ASC'
        ));
        // show available table only
        if (!empty($tables)) {
            foreach ($tables as $t => $table) {
                if (!empty($table['Order']) || !empty($table['Reserve'])) {
                    unset($tables[$t]);
                }
            }
        }
        $this->pageTitle = 'Assign table';
        $this->set('tables', $tables);
        $this->set('tid', $reserve['Reserve']['table_id']);
        $this->set('rid', $rid);
    }
    
    public function un_assign ($pid = null, $rid = null) {
        $this->Reserve->contain();
        $reserve = $this->Reserve->findById($rid);
        if (empty($reserve)) {
            $this->redirect('/order/reserves/index/'.$pid);
        }
        $this->Reserve->id = $rid;
        $this->Reserve->saveField('table_id', '');
        $this->Session->setFlash('Reserve has been un-assigned', 'success');
        $this->redirect('/order/reserves/index/'.$pid);
    }
}    