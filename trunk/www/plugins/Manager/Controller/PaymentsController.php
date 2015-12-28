<?php

class PaymentsController extends ManagerAppController {
    
    public $uses = array ('Payment');
    public $layout = 'ezord_place';

    public $components = array (
        'Manager.Paypal'
    );
    
    public function beforeFilter () {
        parent::beforeFilter();
        $cUser = $this->getCUser();
        $this->set('cUser', $cUser);
        // set pagers
        $level2 = array ('pay', 'pay_thankyou', 'pay_success', 'pay_failed');
        $papers = array ();
        if (in_array ($this->action, $level2)) {
            $papers = array (
                array(
                    'name' => 'Plans & Upgrades',
                    'link' => '/manager/payments/index/'
                )
            );
        } 
        $this->set('papers', $papers);
    }

    public function getPaymentPackage($plan_type) {
        switch ($plan_type) {
            case 'A':
                $package = array(
                    'name' => '4000 bills (60$)',
                    'price' => 60,
                    'quantity' => 4000
                );
                break;
            case 'B':
                $package = array(
                    'name' => '8,000 bills (100$)',
                    'price' => 100,
                    'quantity' => 8000
                );
                break;
            case 'C':
                $package = array(
                    'name' => '16,000 bills (180$)',
                    'price' => 180,
                    'quantity' => 16000
                );
                break;
            case 'D':
                $package = array(
                    'name' => 'Unlimited bills (155$ per month, pay 3 months)',
                    'price' => 155*3,
                    'quantity' => -1
                );
                break;
            default:
                $package = array ();
                break;
        }
        return $package;
    }

    public function index () {
        $this->pageTitle = 'Plans & Upgrades';
    }

    public function pay ($plan_type = null) {
        if (is_null($plan_type)) {
            $this->redirect('/manager/payments/');
        }
        $package = $this->getPaymentPackage($plan_type);
        if ($this->request->is('post')) {
            $host = 'http://'.$_SERVER['SERVER_NAME'];
            $vk = md5('paypal'.$plan_type);
            $returnUrl = $host.$this->base.'/manager/payments/pay_success/'.$plan_type.'/vk:'.$vk;
            $cancelUrl = $host.$this->base.'/manager/payments/pay_failed/'.$plan_type;
            $package = $this->getPaymentPackage($plan_type);
            if (!empty($package)) {
                $this->Paypal->setExpressCheckout($package['price'], $returnUrl, $cancelUrl);
            } else {
                $this->redirect('/manager/payments/index/');
            }
            exit;
        }
        $this->pageTitle = 'Payment';
        $this->set('package', $package);
        $this->set('plan_type', $plan_type);
    }

    public function pay_success ($plan_type) {
        $cUser = $this->getCUser();
        $vk = md5('paypal'.$plan_type);
        $rvk = $this->params['named']['vk'];
        $token = $this->params->query['token']; 
        if ($vk = $rvk) {
            // Get payment detail
            $payment_detail = $this->Paypal->getExpressCheckoutDetail($token);

            // Set payment
            $payer_id = $payment_detail['PAYERID'];
            $token = $payment_detail['TOKEN'];
            $package = $this->getPaymentPackage($plan_type);
            $payment_result = $this->Paypal->doExpressCheckout($payer_id, $token, $package['price']);
            
            $paypal_result = array (
                'checkout_detail' => $payment_detail,
                'checkout_result' => $payment_result
            );

            if ($payment_result['ACK'] == 'Success') {
                // Update  information to place
                $payment['Payment'] = array (
                    'user_id' => $cUser['id'],
                    'package' => $plan_type,
                    'amount' => $package['price'],
                    'paid_email' => $payment_detail['EMAIL'],
                    'paid_result' => json_encode($paypal_result),
                    'paid_date' => date('Y-m-d H:i:s') 
                );
                $this->Payment->create();
                $this->Payment->save($payment);
                $this->redirect('/manager/payments/pay_thankyou/'.$plan_type);
            }
            debug($paypal_result); exit;
        } 
        $this->redirect('/manager/payments/pay_failed/'.$plan_type);
        exit();
    }

    public function pay_thankyou ($plan_type) {
        $this->pageTitle = 'Thank you';
    }
    
    public function pay_failed ($plan_type) {
        $this->pageTitle = 'Payment failed';
    }    
}