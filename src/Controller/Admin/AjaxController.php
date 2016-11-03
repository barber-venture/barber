<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\Utility\Xml;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Financial\Financial;
use Cake\Routing\Router;
use Cake\Filesystem\File;
use Cake\Utility\Hash;
use Cake\I18n\Date;

/**
 * Ajax Controller
 * 
 */
class AjaxController extends AdminAppController {

    public function initialize() {


        parent::initialize();
        
        $this->loadComponent('Common');
        $this->loadModel('SystemMails');       
        $this->loadModel('Countries');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Users');
    
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;
    }
             
    function checkEmailAvailibility($email = null) {
        $emailAvailable = $this->Users->find('all', ['conditions' => ['Users.email' => $_GET['email']]])->first();
        if ($emailAvailable != "") {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }

        
    function getStateFromCountry($countryId) {
       
        if ($countryId != '') {
//            $states = $this->States->find('list', ['conditions' => ['States.country_id' => $countryId]])->toArray();
              $states= $this->States->find('list', ['conditions' => ['States.country_id' => $countryId],'order' => ['name' => 'asc'], 'keyField' => 'id', 'valueField' => 'name'])->toArray();
           
            $option = '<option value="">Select State</option>';
            foreach ($states as $key => $sk) {
                $option.='<option value="' . $key . '">' . $sk . '</option>';
            }
            echo $option;
        }
        exit();
    }

    function getCitiesFromState($stateId) {
        if ($stateId != '') {
            $states = $this->Cities->find('list', ['conditions' => ['Cities.state_id' => $stateId]])->toArray();
            //pr($states); die;
            $option = '<option value="">Select City</option>';
            foreach ($states as $key => $sk) {
                $option.='<option value="' . $key . '">' . $sk . '</option>';
            }
            echo $option;
        }
    }
    
    function deleteUser($id = NULL){
        if($id){
            $this->Users->id = $id;
            $res = $this->Users->updateAll(['deleted' => '1'], ['id' => $id]);
            echo $res;
        } 
    }
    
    function updateUserStatus($id){
        if($id){
            $user = $this->Users->get($id);
            $status = !$user['status'];
            $this->Users->id = $id;
            $res = $this->Users->updateAll(['status' => $status], ['id' => $id]);
            echo $res;
        } 
    }
}
