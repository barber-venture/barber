<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Filesystem\File;

require ROOT . DS . 'vendor' . DS . 'PayPal-PHP' .  DS . 'bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;

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
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if ($this->request->is('ajax')) {
            $this->eventManager()->off($this->Csrf);
        }
       
    }
    
    public function index() {

        $Plans = $this->Plans->find('all', [
                    'conditions' => ['Plans.deleted !=' => 1],
                    'order' => ['id' => 'ASC'],
                    'limit' => 4,
                    'fields' => array('id', 'duration', 'plan_price')
                ])->toArray();

        $this->set(compact('Plans'));
    }

	function purchase($data = ''){
		$this->loadModel('UserCardDetails');		
		if(empty($data)){
			$this->Flash->error('Unsufficient data, please select plan again.');
			$this->redirect(array(
				'controller'=>'plans',
				'action'=>'index',
			));			
		}
		$UserCardDetails = $this->UserCardDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')]])->first();
		if(!$UserCardDetails){
			$UserCardDetails = $this->UserCardDetails->newEntity();
		}	

		$this->set(compact('data', 'UserCardDetails'));
		
		if($this->request->is('post') || $this->request->is('put') && !empty($this->request->data)){
			//pr($this->request->data); die;
			$this->UserCardDetails->patchEntity($UserCardDetails, $this->request->data);
			$this->UserCardDetails->save($UserCardDetails);
			
			$configuration = Configure::read('Paypal');								 
			$card_token = '';
			$user_id = $this->Auth->User('id');
			
			//$Paypal = new \Paypal(array(
			//	'sandboxMode' => true,
			//	'nvpUsername' => $configuration['paypal_username'],
			//	'nvpPassword' => $configuration['paypal_password'],
			//	'nvpSignature' => $configuration['paypal_signature'],
			//	'oAuthClientId' => $configuration['paypal_oAuthClientId'],
			//	'oAuthSecret' => $configuration['paypal_oAuthSecret'],
			//));
						
			$name_on_card = $this->request->data['name_on_card'];
			$card_type = $this->request->data['card_type'];
			$card_number = $this->request->data['card_number'];
			$cvv = $this->request->data['cvv'];
			$exp_month = $this->request->data['exp_month'];
			$exp_year = $this->request->data['exp_year'];
							
			$card = new CreditCard();
			$card->setType($card_type)
				->setNumber($card_number)
				->setExpireMonth($exp_month)
				->setExpireYear($exp_year)
				->setCvv2($cvv)
				->setFirstName($name_on_card)
				->setLastName($name_on_card);
			
				$fi = new FundingInstrument();
				$fi->setCreditCard($card);
				$payer = new Payer();
				$payer->setPaymentMethod("credit_card")
					->setFundingInstruments(array($fi));
					
				$item1 = new Item();
				$item1->setName('Buying Credits')
					->setDescription('descriptioin')
					->setCurrency('EUR')
					->setQuantity(1)
					->setTax(0)
					->setPrice(10);
					
				$itemList = new ItemList();
				$itemList->setItems(array($item1));
				$details = new Details();
				$details->setShipping(0)
					->setTax(0)
					->setSubtotal(10);
					
				$amount = new Amount();
				$amount->setCurrency("EUR")
					->setTotal(10)
					->setDetails($details);
					
				$transaction = new Transaction();
				$transaction->setAmount($amount)
					->setItemList($itemList)
					->setDescription("Payment description")
					->setInvoiceNumber(uniqid());
				
				$payment = new Payment();
				$payment->setIntent("sale")
					->setPayer($payer)
					->setTransactions(array($transaction));
				$request = clone $payment;
					
				//pr($request); die;
				$client_ID = 'AUDE5-WhEAhu_rPpO-Njlrbg-sOVdbAeSK6OZ6AiVBQRPfE1WrQR-Sw23Y-o20_9pp0QkvxJaKv4n0HL';
				$client_Secret =  'EHlkZngXYxLUf9mVNLy2lrcV1JQLY7qBZY-j3X2AqWU2axAZkekKfmHIml2vMu0iXJnB9vrrW9KfeGs7';
				$apiContext = getApiContext($client_ID, $client_Secret);
				//pr($apiContext); die;
				
				try {
					$payment->create($apiContext);					
				} catch (Exception $ex) {
					echo 'Error occured'; die;
				}				
		}		
		
	}	
	

}
