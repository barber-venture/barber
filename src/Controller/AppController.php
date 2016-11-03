<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Csrf');
        $this->loadComponent('Security');
        $this->loadComponent('Cookie');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                    //'scope' => ['deleted' => 0]
                ]
            ],
            'loginRedirect' => '/',
            'logoutRedirect' => '/',
            'loginAction' => '/'
        ]);


        $this->loadModel('SiteSettings');
        $SiteSettings = $this->SiteSettings->get('1');
        Configure::write('SiteSettingsTbl', $SiteSettings);

        if (!isset($this->request->params['prefix'])) {

            if ($SiteSettings['is_online'] == 2 && $this->request->params['action'] != 'underconstraction') {

                return $this->redirect(['prefix' => false, 'controller' => 'pages', 'action' => 'underconstraction']);
            }
        }
             $user = $this->Cookie->read('Auth');
         
        if ($this->Auth->user('id')=="") {  
            if ($this->Cookie->check('Auth.User.email')) {   
                $this->loadModel('Users');
                $user = $this->Cookie->read('Auth');
                $local_user = $this->Users->find('all', [
                            'conditions' => [
                                'email' => $user['User']['email'],
                                'status' => 1
                            ]
                        ])->first()->toArray();
                $this->Auth->setUser($local_user);
            };
        }else{
            //Getting notifications
            $this->loadModel('Notifications');
            $notification = $this->Notifications->find('all', [
                                'conditions' => ['Notifications.user_id' => $this->Auth->User('id'), 'is_read' => '0', 'Users.status' => '1', 'deleted' => '0'],
                                'fields' => ['Notifications.id', 'Notifications.notification_type', 'Notifications.message', 'Notifications.user_id', 'UserDetails.nike_name', 'Users.id', 'UserDetails.profile_image', 'Notifications.referer_user_id'],
                                'contain' => ['Users' => ['UserDetails']],
                                'order' => 'Notifications.id DESC'
                            ])->toArray();
            $this->set('notification', $notification);
            //pr($notification); die;
            $this->loadModel('ActiveChats');
            $ChatMessages = $this->ActiveChats->find('all', [
                                'conditions' => ['ActiveChats.user_id' => $this->Auth->User('id'), 'is_deleted' => '0'],
                                'fields' => ['unread_messages' /*, 'sum(unread_messages) as allcount'*/]
                            ])->all()->toArray();            
            $total  = 0;
            foreach($ChatMessages as $cht){
                $total += $cht['unread_messages'];
            }
            $ChatMessages = $total;
            $this->set('ChatMessages', $ChatMessages);
            
        }
        if($this->Auth->User('id')){
            $this->loadModel('Users');
            $status = $this->Users->findById($this->Auth->User('id'))->first();        
            if(!$status->status){
                $this->Users->updateAll(['is_online' => 0], ['id' => $this->Auth->user('id')]);
                $this->Cookie->delete('Auth.User');
                $this->Flash->error(__('Your account is deactivated.'));
                $this->redirect($this->Auth->logout());
            }else if($status->deleted){
                $this->Users->updateAll(['is_online' => 0], ['id' => $this->Auth->user('id')]);
                $this->Cookie->delete('Auth.User');
                $this->Flash->error(__('Your account is deleted.'));
                $this->redirect($this->Auth->logout());
            }
        }
            
    }

    public function afterFilter(Event $event) {
        parent::afterFilter($event);
        $this->aclPermissionCheck();
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Security->config('blackHoleCallback', 'blackhole');
        if ($this->request->params['controller'] == 'Ajax') {
            $this->Security->config('unlockedActions', ['postSendMail', 'updateProfile', 'uploadimages', 'removeimage', 'makeprofilepic', 'likedislike']);
        } else {
            $this->Security->config('unlockedActions', ['contactUs']);
        }
        if (in_array($this->request->params['action'], array('docuSignTrackAndDownload', 'uploadCertificate', 'noticeToProceed', 'fniProcess'))) {
            $this->eventManager()->off($this->Csrf);
        }        
        
    }

    public function blackhole($type) {
        //$this->Flash->error(__('You are doing something wrong. For security reasons you have logout.'));
        //$this->redirect($this->Auth->logout());
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * 
     * Send Email From all system
     * 
     */
    public function sendEmail($mail) {
        
        $email = new Email();
        $email->to($mail['to']);
        $email->subject($mail['subject']);
        $email->replyTo([$mail['sender_email'] => $mail['sender_name']]);
        $email->from([$mail['sender_email'] => $mail['sender_name']]);
        $email->emailFormat('html');
        $email->template('default');
        $this->set('message', $mail['message']);
        try {
            $re = $email->send($mail['message']);
            $flag = true;
        } catch (\Exception $exc) {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 
     * Send Email With attachment From all system
     * 
     */
    public function sendEmailWithAttachments($mail, $attachments = null) {

        $email = new Email();
        $email->to($mail['to']);
        $email->subject($mail['subject']);
        $email->replyTo([$mail['sender_email'] => $mail['sender_name']]);
        $email->from([$mail['sender_email'] => $mail['sender_name']]);
        $email->emailFormat('html');
        $email->attachments($attachments);
        $email->template('default');
        $this->set('message', $mail['message']);
        try {
            $re = $email->send($mail['message']);
            $flag = true;
        } catch (\Exception $exc) {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 
     * Send Email With Password From all system
     * 
     */
    function sendEmailWithPassword($user, $password, $type, $activationKey) {
        $url1 = Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'verify', $activationKey], true);
        $url = "<a href='$url1'>$url1</a>";
        $mail = $this->SystemMails->findByEmailType('AddUser')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
        $mail['message'] = str_replace('[email]', $user['email'], $mail['message']);
        $mail['message'] = str_replace('[password]', $password, $mail['message']);
        $mail['message'] = str_replace('[login_url]', $url, $mail['message']);
        $mail['message'] = str_replace('[type]', $type, $mail['message']);
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['to'] = $user['email'];        
        return $this->sendEmail($mail);
    }

    function sendConfirmEmail($user, $auth_key) {
        $url1 = Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'myProfile', 'key' => $auth_key], true);
        $mail = $this->SystemMails->findByEmailType('ConfirmEmail')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
        $mail['message'] = str_replace('[email]', $user['email'], $mail['message']);
        $mail['message'] = str_replace('[AuthKey]', $auth_key, $mail['message']);
        $mail['message'] = str_replace('[link]', $url1, $mail['message']);
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['to'] = $user['email'];
        return $this->sendEmail($mail);
    }
    
    function sendFreelancerRegEmail($user, $type) {
        $mail = $this->SystemMails->findByEmailType('FreelancerRegistration')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
        $mail['message'] = str_replace('[email]', $user['email'], $mail['message']);
        $admin = $this->Users->findByRoleId('1')->first();
        $mail['to'] = $admin->email;   
        return $this->sendEmail($mail);
    }
    
    function sendSuggestAFriendEmail($user, $toname, $toemail) {
        $mail = $this->SystemMails->findByEmailType('SuggestAFriend')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $toname, $mail['message']);
        $mail['message'] = str_replace('[suggested_name]', $user['name'], $mail['message']);
        $mail['message'] = str_replace('[suggested_email]', $user['email'], $mail['message']);
        $mail['message'] = str_replace('[link]', $user['link'], $mail['message']);
        $mail['to'] = $toemail; 
        return $this->sendEmail($mail);
    }
    
    function sendUserVisitEmail($user) {
        $mail = $this->SystemMails->findByEmailType('ProfileVisit')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
        //$mail['message'] = str_replace('[visited_by_user_name]', $this->Auth->User('name'), $mail['message']);
        //$mail['message'] = str_replace('[visited_by_user_email]', $this->Auth->User('email'), $mail['message']);
        $mail['message'] = str_replace('[TEXT]', $user['TEXT'], $mail['message']);
        $mail['to'] = $user['email']; 
        return $this->sendEmail($mail);
    }        

    /**
     * 
     * Send mail with auth key
     * 
     */
    function sendEmailWithAuthKey($user, $auth_key, $type) {
        $url1 = Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'login'], true);
        $mail = $this->SystemMails->findByEmailType('ChannelPartnerAdd')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
        $mail['message'] = str_replace('[email]', $user['email'], $mail['message']);
        $mail['message'] = str_replace('[AuthKey]', $auth_key, $mail['message']);
        $mail['message'] = str_replace('[type]', $type, $mail['message']);
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['to'] = $user['email'];
        return $this->sendEmail($mail);
    }

    /**
     * 
     * Get file name
     * 
     */
    function getFileName($data) {
        return strtolower(str_replace(' ', '', $data['name'])) . '_' . time() . '.' . pathinfo($data['image']['name'], PATHINFO_EXTENSION);
    }

    /*     * *
     * 
     * Get date with format
     * 
     */

    function getDate($date) {
        $new_date = explode('/', $date);
        return $date = $new_date[2] . '-' . $new_date[0] . '-' . $new_date[1];
        return Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
    }

    /*
     * 
     * ACL Apply
     * 
     */

    function aclPermissionCheck() {
        $role_id = $this->Auth->user('role_id');
        if ($role_id != '') {
            $aclData = Configure::read('ACL');
            $controller = $this->request->params['controller'];
            $action = $this->request->params['action'];
            $prefix = (isset($this->request->params['prefix'])) ? $this->request->params['prefix'] : '';

            if ($prefix == '' && isset($aclData[$role_id]['Controller'][$controller]['DenyAction']) && (in_array($action, $aclData[$role_id]['Controller'][$controller]['DenyAction']) || in_array('*', $aclData[$role_id]['Controller'][$controller]['DenyAction']))) {
                $this->Flash->error(__('You are not authorized to access this page.'));
                
                if ($this->Auth->user('role_id') == 1) {

                    return $this->redirect(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'dashboard']);
                } else {
                    return $this->redirect(['prefix' => false, 'controller' => 'Users', 'action' => 'dashboard']);
                }
            }
        }
    }

}
