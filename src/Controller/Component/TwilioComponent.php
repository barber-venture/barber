<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
require ROOT . DS . 'vendor' . DS . 'twilio' . DS . 'Services' .DS.'lib.php';
use Twillo\Twilio;
/**
 * Twilio component
 * Auther Sonu Verma
 */
class TwilioComponent extends Component
{

    /**
     * Default configuration.
     *
     */
    private  $account_sid;
    private  $auth_token;
    private  $client;
    
    public function __construct() {
       if(Configure::read('Twillo.IsLive')==0){
           $this->account_sid = Configure::read('Twillo.TestAccountSID'); 
           $this->auth_token = Configure::read('Twillo.TestDevAuthToken'); 
       }else{
           $this->account_sid = Configure::read('Twillo.AccountSID'); 
           $this->auth_token = Configure::read('Twillo.DevAuthToken'); 
       }
       $this->client = new Twilio( $this->account_sid, $this->auth_token); 
    }
    public function sendSMS($from, $to, $message) {
        return $this->client->sendSMS($from, $to, $message);
        
    }
}
