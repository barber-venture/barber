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

namespace App\Controller\Admin;

use Cake\Event\Event;
use Cake\Controller\Controller;
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
class AdminAppController extends Controller {

    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Csrf');
        $this->loadComponent('Security');


        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                    'scope' => ['role_id'=> 1,'status' => 1, 'deleted' => 0]
                ]
            ],
            'loginRedirect' => '/',
            'logoutRedirect' => '/',
            'loginAction' => ['controller' => 'Users', 'action' => 'login']
        ]);

        $this->loadModel('SiteSettings');
        $SiteSettings = $this->SiteSettings->get('1');
        Configure::write('SiteSettingsTbl', $SiteSettings);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('admin');
        if ($this->Auth->user('role_id') != '' && ( $this->Auth->user('role_id') != 1)) {
            $this->Flash->error(__('You are not authorized to access this page.'));
            return $this->redirect(['prefix' => false, 'controller' => 'Users', 'action' => 'dashboard']);
        }
    }

    function getDate($date) {
        $new_date = explode('/', $date);
        return $date = $new_date[2] . '-' . $new_date[0] . '-' . $new_date[1];
        return Time::parseDate($date, Configure::read('Site.CakeDateFormat'));
    }

    public function sendEmail($mail) {
        // $mail['to']='testkiplinfo@gmail.com';
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
