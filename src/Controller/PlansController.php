<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Filesystem\File;
use Cake\I18n\Time;

require ROOT . DS . 'vendor' . DS . 'paypal' . DS . 'PaypalPro.php';
require ROOT . DS . 'vendor' . DS . 'expresscheckout' . DS . 'paypalfunctions.php';
require ROOT . DS . 'vendor' . DS . 'slydepay' . DS . 'classes/Integrator.class.php';

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class PlansController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('SystemMails');
        $this->loadComponent('Common');
        $this->loadModel('UserCardDetails');
        $this->loadModel('Payments');
        $this->loadModel('Plans');
        $this->loadModel('UserPlanAssociations');
        $this->loadModel('SlydepayOrder');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if ($this->request->is('ajax')) {
            $this->eventManager()->off($this->Csrf);
        }
    }

    public function index() {
        $addArray = array();
        $Plans = $this->Plans->find('all', [
                    'conditions' => ['Plans.deleted !=' => 1],
                    'order' => ['id' => 'ASC'],
                    'limit' => 4,
                    'fields' => array('id', 'duration', 'plan_price', 'weekly_plan_price')
                ])->toArray();


        $currentPlanID = $this->UserPlanAssociations->find('all', [
                    'conditions' => ['UserPlanAssociations.user_id' => $this->Auth->user('id')], 
                    'order' => ['UserPlanAssociations.id' => 'DESC'],
                    'fields' => array('UserPlanAssociations.plan_id', 'UserPlanAssociations.expiry_date', 'UserPlanAssociations.created')
                ])->first();
        if (!empty($currentPlanID)) {
            $addArray['id'] = $currentPlanID->plan_id;
            $CreatedDate = strtotime($currentPlanID->created);
            $CreatedDateEx = date("Y-m-d", strtotime("+1 month", $CreatedDate));
            $addArray['onemonth']=$CreatedDateEx;
            $TodayDate = date('Y-m-d');
            if ($TodayDate < $CreatedDateEx) {
                $addArray['status'] = 0;
            } else {
                $addArray['status'] = 1;
            }
        }
        $this->set(compact('Plans', 'addArray'));
    }

    function purchase($id = null) {        
		
		
        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
				
        $id = str_replace(":","+",$id);       
        $id = $this->Common->decrypt($id);


        $this->Plans->id = $id;
        if (!$this->Plans->exists(['id' => $id])) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }

        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();
        $this->set(compact('Plan'));
    }

    function paypalpro() {
		if(isset($this->request->data['planid'])){
			$this->request->data['planid'] = str_replace(":","+",$this->request->data['planid']);
			$this->request->data['planid'] = $this->Common->decrypt($this->request->data['planid']);
		}
        $payableAmount = 10;
        $paypal = new \PaypalPro();
        $paypalParams = array(
            'paymentAction' => 'Sale',
            'amount' => $this->request->data['amount'], // $payableAmount,
            'currencyCode' => 'EUR', // 'USD',
            'creditCardType' => $this->request->data['card_type'], //'VISA',
            'creditCardNumber' => $this->request->data['card_number'], // '4111111111111111', 
            'expMonth' => $this->request->data['expiry_month'], //'11',  
            'expYear' => $this->request->data['expiry_year'], //'2018',  
            'cvv' => $this->request->data['cvv'], // '874',  
            'firstName' => $this->request->data['name_on_card'], //'manish',  
            'lastName' => $this->request->data['name_on_card'], //'saini',
            'city' => 'Rotterdam', // $city,
            'zip' => '3086AW', // $zipcode,	
            'state' => 'SH',
            'address' => 'Oldegaarde 970B, 3086 AW Rotterdam, Netherlands ',
            'countryCode' => 'NL',
        );
        // print_r($paypalParams); die;
        $response = $paypal->paypalCall($paypalParams);

        $paymentStatus = strtoupper($response["ACK"]);

        if ($paymentStatus == "SUCCESS") {

            $data['status'] = 1;
            $data['transaction_id'] = $response['TRANSACTIONID'];
            $transactionID = $response['TRANSACTIONID'];
            $Payments = $this->Payments->newEntity();
            $Payment['amount'] = $this->request->data['amount'];
            $Payment['plan_id'] = $this->request->data['planid'];
            $Payment['user_id'] = $this->Auth->user('id');
            $Payment['method'] = 3;
            $Payment['transaction_id'] = $response['TRANSACTIONID'];
            $Payment['response'] = json_encode($response);
            $Payment['status'] = 1;
            $Payment = $this->Payments->patchEntity($Payments, $Payment);
            $this->Payments->save($Payments);

            $id = $this->request->data['planid'];
            $plan = $this->UserPlanAssociations->newEntity();
            $plans['user_id'] = $this->Auth->user('id');
            $plans['plan_id'] = $this->request->data['planid'];
            $plans['status'] = 1;
            $date = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $this->request->data['duration'] . 'months'));
            $plans['expiry_date'] = Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
            $plans['transaction_id'] = $response['TRANSACTIONID'];
            $plans['response'] = json_encode($response);

            $plans['amount'] = $this->request->data['amount'];
            $plan = $this->UserPlanAssociations->patchEntity($plan, $plans);
            $this->UserPlanAssociations->save($plan);
            $user = $this->Users->findById($this->Auth->user('id'))->first();
            $users['user_type'] = $id;
			$users['plan_expiry_date'] =  Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
            $this->Users->patchEntity($user, $users);
            $this->Users->save($user);
            $this->request->session()->write('Auth.User.user_type', $id);
        } else {
            $data['status'] = 0;
        }

        echo json_encode($data);
        die;
    }

    public function success($id = null) {		
        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
		$id = str_replace(":","+", $id);
		$id = $this->Common->decrypt($id);
		if (!$this->Plans->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid plan.'));
            return $this->redirect(['action' => 'index']);
        }
		
        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();
        $this->set(compact('Plan'));
    }
    
    public function iDEALSuccess($id =null, $amount=null)
    {
        //$this->autoRender = false ;
        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();
        
        $Payments = $this->Payments->newEntity();
        $Payment['amount'] = $amount;
        $Payment['plan_id'] = $id;
        $Payment['user_id'] = $this->Auth->user('id');
        $Payment['method'] = 4;
        $Payment['response'] = '';
        $Payment['status'] = 1;
        $Payment = $this->Payments->patchEntity($Payments, $Payment);
        $this->Payments->save($Payments);

        $plan = $this->UserPlanAssociations->newEntity();
        $plans['user_id'] = $this->Auth->user('id');
        $plans['plan_id'] = $id;
        $plans['status'] = 1;
        $date = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $Plan->duration . 'months'));
        $plans['expiry_date'] = Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
        $plans['response'] = '';

        $plans['amount'] = $amount;
        $plan = $this->UserPlanAssociations->patchEntity($plan, $plans);
        $this->UserPlanAssociations->save($plan);
        $user = $this->Users->findById($this->Auth->user('id'))->first();
        $users['user_type'] = $id;
		$users['plan_expiry_date'] =  Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
        $this->Users->patchEntity($user, $users);
        $this->Users->save($user);
        $this->request->session()->write('Auth.User.user_type', $id);
        return $this->redirect(['action' => 'success', $this->Common->encrypt($id) ]);
    }

    public function expresscheckout($id = null) {

        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();

        $paymentAmount = $Plan->plan_price;
        $currencyCodeType = "EUR";
        $paymentType = "Sale";
        $returnURL = SITE_FULL_URL . "plans/review/" . $id;
        $cancelURL = SITE_FULL_URL . "plans/purchase/" . $id;
        $resArray = CallShortcutExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);
        $ack = strtoupper($resArray["ACK"]);

        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {

            RedirectToPayPal($resArray["TOKEN"]);
        } else {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

            echo "SetExpressCheckout API call failed. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;
        }
        die;
    }

    public function review($id = nulll) {

        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();


        $token = "";
        if (isset($this->request->query['token'])) {
            $token = $this->request->query['token'];
        }
        if ($token != "") {
            $resArray = GetShippingDetails($token);

            $ack = strtoupper($resArray["ACK"]);
            if ($ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") {

                $Payments = $this->Payments->newEntity();
                $Payment['amount'] = $resArray['AMT'];
                $Payment['plan_id'] = $id;
                $Payment['user_id'] = $this->Auth->user('id');
                $Payment['method'] = 2;
                //$Payment['transaction_id'] = $response['TRANSACTIONID'];
                $Payment['response'] = json_encode($resArray);
                $Payment['status'] = 1;
                $Payment = $this->Payments->patchEntity($Payments, $Payment);
                $this->Payments->save($Payments);

                $plan = $this->UserPlanAssociations->newEntity();
                $plans['user_id'] = $this->Auth->user('id');
                $plans['plan_id'] = $id;
                $plans['status'] = 1;
                $date = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $Plan->duration . 'months'));
                $plans['expiry_date'] = Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
                // $plans['transaction_id'] = $response['TRANSACTIONID'];
                $plans['response'] = json_encode($resArray);

                $plans['amount'] = $resArray['AMT'];
                $plan = $this->UserPlanAssociations->patchEntity($plan, $plans);
                $this->UserPlanAssociations->save($plan);
                $user = $this->Users->findById($this->Auth->user('id'))->first();
                $users['user_type'] = $id;
				$users['plan_expiry_date'] =  Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
                $this->Users->patchEntity($user, $users);
                $this->Users->save($user);
                $this->request->session()->write('Auth.User.user_type', $id);
                return $this->redirect(['action' => 'success', $this->Common->encrypt($id) ]);
            } else {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

                echo "GetExpressCheckoutDetails API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
                die;
            }
        }
    }

    public function slydepay($id = null) {

        if ($id == null) {
            $this->Flash->error(__('Please select plan.'));
            return $this->redirect(['action' => 'index']);
        }
        $Plan = $this->Plans->find('all', [
                    'conditions' => ['Plans.id' => $id]])->first();

		
        $slydepayIntegrator = new \SlydepayConnector(
                Configure::read('slydepay.namespace'), Configure::read('slydepay.wsdl'), Configure::read('slydepay.version'), Configure::read('slydepay.merchantEmail'), Configure::read('slydepay.merchantKey'), Configure::read('slydepay.serviceType'), Configure::read('slydepay.integrationmode')
        );
		
        $order_id = $this->GUID();
        $comment1 = "";
        $comment2 = "";
        $order_items = array();
        $chromecast_unit_price = $Plan->plan_price;
        $chromecast_quantity = 1;
        $chromecast_subtotal = $chromecast_unit_price * $chromecast_quantity;
        $order_items[0] = $slydepayIntegrator->buildOrderItem("001", "chromecast", $chromecast_unit_price, $chromecast_quantity, $chromecast_subtotal);
        $sub_total = $chromecast_subtotal;
        $shipping_cost = 00;
        $tax_amount = 0;
        $total = $sub_total + $shipping_cost + $tax_amount;

        $response = $slydepayIntegrator->ProcessPaymentOrder($order_id, $sub_total, $shipping_cost, $tax_amount, $total, $comment1, $comment2, $order_items);
				
        $SlydepayOrder = $this->SlydepayOrder->newEntity();
        $SlydepayOrders['user_id'] = $this->Auth->user('id');
        $SlydepayOrders['plan_id'] = $id;
        $SlydepayOrders['order_status'] = 0;
        $SlydepayOrders['amount'] = $Plan->plan_price;
        $SlydepayOrders['duration'] = $Plan->duration;
        $SlydepayOrders['payment_token'] = $response->ProcessPaymentOrderResult;
        $SlydepayOrder = $this->SlydepayOrder->patchEntity($SlydepayOrder, $SlydepayOrders);
        if ($this->SlydepayOrder->save($SlydepayOrder)) {
            $redirect = Configure::read('slydepay.redirecturl') . $response->ProcessPaymentOrderResult;
            return $this->redirect($redirect);
        }
	
        $this->Flash->error(__('Please try again.'));
        return $this->redirect(['action' => 'index']);
    }

    function GUID() {

        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function slydepaySuccess() {


        $slydepayIntegrator = new \SlydepayConnector(
                Configure::read('slydepay.namespace'), Configure::read('slydepay.wsdl'), Configure::read('slydepay.version'), Configure::read('slydepay.merchantEmail'), Configure::read('slydepay.merchantKey'), Configure::read('slydepay.serviceType'), Configure::read('slydepay.integrationmode')
        );

        if (null == $this->request->query['status'] || null == $this->request->query['cust_ref'] || null == $this->request->query['pay_token']) {

            $this->Flash->error(__('Not good, details are missing or someone is messing with you.'));
            return $this->redirect(['action' => 'index']);
        }
        if (null == $this->request->query['transac_id'] || strlen($this->request->query['transac_id']) == 0) {
            // $db->updateOrder($orderId, "", "FAILED");
            $this->Flash->error(__('Empty or Null Transaction Id.'));
            return $this->redirect(['action' => 'index']);
        }
//        if (!checkValidity($paymentToken, $orderId)) {
//            die("There is no transaction corresponding to the received payment token. Please contact slydepay support");
//        }

        if ($this->request->query['status'] == 0) {
            $this->SlydepayOrder->updateAll(['order_status' => 1], ['payment_token' => $this->request->query['pay_token']]);
            $SlydepayOrder = $this->SlydepayOrder->findByPaymentToken($this->request->query['pay_token'])->first();
            if (!empty($SlydepayOrder)) {

                $Payments = $this->Payments->newEntity();
                $Payment['amount'] = $SlydepayOrder->amount;
                $Payment['plan_id'] = $SlydepayOrder->plan_id;
                $Payment['user_id'] = $this->Auth->user('id');
                $Payment['method'] = 1;
                $Payment['response'] = json_encode($this->request->query);
                $Payment['status'] = 1;
                $Payment = $this->Payments->patchEntity($Payments, $Payment);
                $this->Payments->save($Payments);

                $plan = $this->UserPlanAssociations->newEntity();
                $plans['user_id'] = $this->Auth->user('id');
                $plans['plan_id'] = $SlydepayOrder->plan_id;
                $plans['status'] = 1;
                $date = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $SlydepayOrder->duration . 'months'));
                $plans['expiry_date'] = Time::parseDate($date, Configure::read('Site.CakeDateFormat'));

                $plans['response'] = json_encode($this->request->query);

                $plans['amount'] = $SlydepayOrder->amount;
                ;
                $plan = $this->UserPlanAssociations->patchEntity($plan, $plans);
                $this->UserPlanAssociations->save($plan);

                $user = $this->Users->findById($this->Auth->user('id'))->first();
                $users['user_type'] = $SlydepayOrder->plan_id;
				$users['plan_expiry_date'] =  Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
                $this->Users->patchEntity($user, $users);
                $this->Users->save($user);
                $this->request->session()->write('Auth.User.user_type', $SlydepayOrder->plan_id);
                return $this->redirect(['action' => 'success', $this->Common->encrypt( $SlydepayOrder->plan_id) ]);
            } else {
                $this->Flash->error(__('There is an error in payment transaction.please contact with admin.'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->Flash->error(__('There is an error in payment transaction.please contact with admin.'));
        return $this->redirect(['action' => 'index']);
        die;
    }

}
