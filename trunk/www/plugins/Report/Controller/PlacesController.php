<?php

class PlacesController extends ReportAppController {

	public $layout = 'ezord_report';
	public $uses = array('Place', 'Order');

	private function getJsonMenuItems($items) {
		return json_decode($items);
	}

    private function getIncomeStr($co) {
        $today = date('Y-m-d');
        switch ($co) {
            case 'yesterday':
                $str = "From yesterday";
                break;
            case '7-days':
                $last_7ds = date("Y-m-d", strtotime("-7 days"));
                $str = "From $last_7ds";
                break;
            case '30-days':
                $last_30ds = date("Y-m-d", strtotime("-30 days"));
                $str = "From $last_30ds";
                break;
            case '3-months':
                $last_3mths = date("Y-m-d", strtotime("-3 months"));
                $str = "From $last_3mths";
                break;
            default:
                $str = "Today";
                break;
        }
        return $str;
    }

    private function getReportConditions($pid, $co) {
        $conditions = array(
            'Order.place_id' => $pid,
            'Order.order_status' => 3
        );
        $today = date('Y-m-d');
        $tomorow = date("Y-m-d", strtotime("tomorow"));
        switch ($co) {
            case 'yesterday':
                $yesterday = date("Y-m-d", strtotime("yesterday"));
                $conditions+=array('Order.created >' => $yesterday);
                break;
            case '7-days':
                $last_7ds = date("Y-m-d", strtotime("-7 days"));
                $conditions+= array('or' => array('Order.created >=' => $last_7ds, 'Order.created <' => $tomorow));
                break;
           case '30-days':
                $last_30ds = date("Y-m-d", strtotime("-30 days"));
                $conditions+= array('or' => array('Order.created >=' => $last_30ds, 'Order.created <' => $tomorow));
                break;
            case '3-months':
                $last_3mths = date("Y-m-d", strtotime("-3 months"));
                $conditions+= array('or' => array('Order.created >=' => $last_3mths, 'Order.created <' => $tomorow));
                break;
            default:
                $conditions+=array('Order.created >' => $today);
                break;
        }
        return $conditions;
    }

	public function beforeFilter () {
		parent::beforeFilter();
        if (!empty($this->params['pass'][0])) {
            $pid = $this->params['pass'][0];
            $this->place = $this->checkPlaceValid($pid);
        }
	}

	public function dashboard ($pid = null, $co = 'today') {
        $this->pageTitle = 'Report';
        $this->set('pid', $pid);
        $this->Order->contain('Bill');
        $list_items = array();
        $report_items = array();
        $conditions = $this->getReportConditions($pid, $co);
	    $orders = $this->Order->find('all', array(
        	'conditions' => $conditions
        ));
        if (!empty($orders)) {
        	foreach ($orders as $o => $order) {
        		$items = $this->getJsonMenuItems($order['Bill']['items']);
        		if (!empty($items)) {
        			foreach ($items as $i => $item) {
        				if(!in_array($item->id, $list_items)) {
        					$list_items[] = $item->id;
        					$report_items [$item->id] = array(
        						'name' => $item->name,
        						'quantity' => $item->MenuItemsOrder->quantity,
        						'price' => $item->price
        					);
        				} else {
        					if (isset($report_items [$item->id]['quantity'])) {
        						$report_items [$item->id]['quantity'] += $item->MenuItemsOrder->quantity;
        					}
        				}
        			}
        		}
        	}
        }
        $this->set('income_str', $this->getIncomeStr($co));
        $this->set('items', $report_items);
	}
}