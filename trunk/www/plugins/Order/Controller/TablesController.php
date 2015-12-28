<?php

class TablesController extends OrderAppController {
    
    public $uses = array ('Place', 'Order', 'Table', 'TablesOrder', 'MergedTable', 'Reserve', 'MenuItem');
    public $layout = 'ezord_order';
    public $cPlace = null;
    
    public function beforeFilter () {
        parent::beforeFilter();
        $pid = $this->request->params['pass'][0];
        $place = $this->checkPlaceValid($pid);
        
        if (!empty($this->request->params['pass'][1])) {
            $tid = $this->request->params['pass'][1];
            $table = $this->checkTableValid($pid, $tid);
        }
        
        $cUser = $this->getCUser();
        $this->cPlace = $place;
        
        $this->set('pid', $pid);
        $this->set('cUser', $cUser);
        
        // set pagers
        $level2 = array ('view');
        $level3 = array ('merge', 'move');
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
                 )
            );
            if (!empty($table)) {
                $papers[] = array(
                    'name' => $table['Table']['name'],
                    'link' => '/order/tables/view/'.$pid.'/'.$tid
                );
            }
            
        }
        $this->set('papers', $papers);
    }
    
    public function getMergedTables ($tid) {
        $tables = $this->MergedTable->find('all', array (
            'conditions' => array (
                'MergedTable.table_id' => $tid
            )
        ));
        return $tables;
    }
    
    public function getInPlaceMergedTableIds ($pid, $tid, $inct = false) {
        $tables = $this->MergedTable->find('all', array (
            'conditions' => array (
                'MergedTable.place_id' => $pid
            )
        ));
        $ids = array ();
        if (!empty($tables)) {
            foreach ($tables as $t => $table) {
                if (!$inct) {
                    $ids[] = $table['MergedTable']['merged_id'];
                } else if ($inct && $table['MergedTable']['table_id']!=$tid) {
                    $ids[] = $table['MergedTable']['table_id'];
                    $ids[] = $table['MergedTable']['merged_id'];
                } 
            }
        }
        return $ids;
    }
    
    public function countMergedTablesByPlace ($pid) {
        return $this->MergedTable->find('count', array(
            'conditions' => array (
                'MergedTable.place_id' => $pid
            )            
        ));
            
    }                
    
    public function isMerged ($tid) {
        $count = $this->MergedTable->find('count', array (
            'conditions' => array (
                'MergedTable.merged_id' => $tid
            )
        ));
        return ($count>0) ? true : false;
    }
    
    public function index ($pid = null) {
        $this->pageTitle = $this->cPlace['Place']['name'];
        $this->Table->contain(array ('Reserve', 'Order' => array ('Bill', 'MenuItem')));
        $tables = $this->Table->find('all', array(
            'conditions' => array (
                'Table.place_id' => $pid,
                'Table.active' => 1
            ),
            'order' => 'Table.order ASC'
        ));
        // count tables
        $table_num = count($tables);
        $this->set('table_num', $table_num);
        // count taken tables
        $taken_num = 0;
        if (!empty($tables)) {
            foreach ($tables as $t => $table) {
                if (!empty($table['Order'])) {
                    $taken_num++;
                }
                $merged_tables = $this->getMergedTables($table['Table']['id']);
                $tables[$t]['Table']['merged_tables_count'] = count($merged_tables);
                $isMerged = $this->isMerged($table['Table']['id']);
                if ($isMerged) {
                    unset($tables[$t]);
                }
            }
            $merged_table_num = $this->countMergedTablesByPlace($pid);
            $taken_num+=$merged_table_num;   
        }
        $this->set('taken_num', $taken_num);
        // available tables
        $this->set('available_num', $table_num-$taken_num);
        // set list of tables
        $this->set('tables', $tables);
        // count number of reserves
        $reserve_num = $this->Reserve->find('count', array (
            'conditions' => array (
                'place_id' => $pid
            )
        ));
        $this->set('reserve_num', $reserve_num);
    }
    
    public function view ($pid = null, $tid = null) {
        $this->Table->contain(array('Order' => array('MenuItem')));
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        if (empty($table)) {
            $this->redirect('/order/tables/index/'.$pid);
        }
        $items = array ();
        if (!empty($table['Order'])) {
            foreach ($table['Order'] as $o => $order) {
                if (!empty($order['MenuItem'])) {
                    foreach ($order['MenuItem'] as $i => $item) {
                        $table['Order'][$o]['MenuItem'][$i]['price'] = $this->MenuItem->getPrice($item['id']);
                    }
                }
            }
        }
        //$this->set('items', $items);
        $this->set('table', $table);
        $this->set('tid', $tid);
        $this->set('orders', $table['Order']);
        // find merged tables
        $display = $table['Table']['name'];
        $merged_tables = $this->getMergedTables($tid);
        if (!empty($merged_tables)) {
            foreach ($merged_tables as $m => $mtable) {
                $this->Table->contain();
                $ta = $this->Table->findById($mtable['MergedTable']['merged_id']);
                $merged_tables[$m]['Table'] = $ta['Table'];
                if ($m<count($merged_tables)-1)
                    $display.=', '.strtolower($ta['Table']['name']);
                else
                    $display.=' and '.strtolower($ta['Table']['name']);
            }
        }
        $this->pageTitle = $display;
    }
    
    public function merge ($pid = null, $tid = null) {
        $place = $this->cPlace;
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        if (empty($table)) {
            $this->redirect('/order/tables/index/'.$pid);
        }
        $this->pageTitle = 'Merge tables';
        $this->set('tid', $tid);
        if ($this->request->is('post')) {
            if (!empty($this->request->data['Table'])) {
                $this->MergedTable->deleteAll(array('table_id' => $tid));
                foreach ($this->request->data['Table'] as $mid => $val) {
                    if ($val == 0) continue;
                    $mergedtable['MergedTable'] = array (
                        'place_id' => $pid,
                        'table_id' => $tid,
                        'merged_id' => $mid
                    );
                    $this->MergedTable->create();
                    $this->MergedTable->save($mergedtable);
                }
            }
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        }
        // find merged tables
        $display = $table['Table']['name'];
        $merged_tables = $this->getMergedTables($tid);
        $merged_ids = array (); 
        if (!empty($merged_tables)) {
            foreach ($merged_tables as $m => $mtable) {
                $this->Table->contain();
                $ta = $this->Table->findById($mtable['MergedTable']['merged_id']);
                $merged_tables[$m]['Table'] = $ta['Table'];
                if ($m<count($merged_tables)-1)
                    $display.=', '.strtolower($ta['Table']['name']);
                else
                    $display.=' and '.strtolower($ta['Table']['name']);
                $merged_ids[]=$ta['Table']['id'];
            }
        }
        $this->set('merged_tables', $merged_tables);
        // find merged tables in place
        $place_merged_table_ids = $this->getInPlaceMergedTableIds($pid, $tid, true);
        // reset papers
        $papers = array (
            array(
                'name' => $place['Place']['name'],
                'link' => '/order/tables/index/'.$pid
            ),
            array(
                'name' => $display,
                'link' => '/order/tables/view/'.$pid.'/'.$tid
            )
        );
        $this->set('papers', $papers);
        // find empty tables
        $this->Table->contain(array ('Order', 'Reserve'));
        $tables = $this->Table->find('all', array(
            'conditions' => array (
                'Table.place_id' => $pid
            ),
            'order' => 'Table.order ASC'
        ));
        if (!empty($tables)) {
            foreach ($tables as $t => $tab) {
                if (in_array($tab['Table']['id'], $merged_ids)) {
                    $tables[$t]['Table']['checked'] = true;
                }
                if ((!empty($tab['Order']) && !in_array($tab['Table']['id'], $merged_ids))
                        || !empty($tab['Reserve'])
                        || $tab['Table']['id']==$tid 
                        || (in_array($tab['Table']['id'], $place_merged_table_ids))) {
                    unset($tables[$t]);
                }
            }
        }
        $this->set('tables', $tables);
    }
    
    public function move ($pid = null, $tid = null, $oid = null) {
        $place = $this->cPlace;
        $table = $this->Table->find('first', array(
            'conditions' => array (
                'Table.id' => $tid
            )
        ));
        if (empty($table)) {
            $this->redirect('/order/tables/index/'.$pid);
        }
        $this->set('tid', $tid);
        $this->set('oid', $oid);
        if ($this->request->is('post')) {
            $tables_order = $this->TablesOrder->find('first', array(
                'conditions' => array (
                    'TablesOrder.order_id' => $oid,
                    'TablesOrder.table_id' => $tid
                )
            ));
            if (!empty($tables_order)) {
                // debug ($this->request->data); exit;
                $this->TablesOrder->id = $tables_order['TablesOrder']['id'];
                $this->TablesOrder->saveField('table_id', $this->request->data['Table']);
                $this->Session->setFlash('Table has been moved', 'success');
            } else {
                $this->Session->setFlash('Table moving has been failed', 'error');
            }
            
            $this->redirect('/order/tables/view/'.$pid.'/'.$tid);
        } 
        // find merged tables
        $display = $table['Table']['name'];
        $merged_tables = $this->getMergedTables($tid);
        $merged_ids = array (); 
        if (!empty($merged_tables)) {
            foreach ($merged_tables as $m => $mtable) {
                $this->Table->contain();
                $ta = $this->Table->findById($mtable['MergedTable']['merged_id']);
                $merged_tables[$m]['Table'] = $ta['Table'];
                if ($m<count($merged_tables)-1)
                    $display.=', '.strtolower($ta['Table']['name']);
                else
                    $display.=' and '.strtolower($ta['Table']['name']);
                $merged_ids[]=$ta['Table']['id'];
            }
        }
        $this->set('merged_tables', $merged_tables);
        // find merged tables in place
        $place_merged_table_ids = $this->getInPlaceMergedTableIds($pid, $tid, false);
        // reset papers
        $papers = array (
            array(
                'name' => $place['Place']['name'],
                'link' => '/order/tables/index/'.$pid
            ),
            array(
                'name' => $display,
                'link' => '/order/tables/view/'.$pid.'/'.$tid
            )
        );
        $this->set('papers', $papers);
        $this->Table->contain(array ('Order', 'Reserve'));
        $tables = $this->Table->find('all', array(
            'conditions' => array (
                'Table.place_id' => $pid
            ),
            'order' => 'Table.order ASC'
        ));
        if (!empty($tables)) {
            foreach ($tables as $t => $tab) {
                if ($tab['Table']['id']==$tid 
                        || in_array($tab['Table']['id'], $merged_ids)
                        || in_array($tab['Table']['id'], $place_merged_table_ids)) {
                    unset($tables[$t]);
                }
            }
        }
        $this->set('tables', $tables);
        $this->pageTitle = 'Move order to ...';
    }

}