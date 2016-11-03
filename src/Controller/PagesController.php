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

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index','underconstraction', 'display', 'contactUs', 'faq']);
    }
    
    public function initialize(){
        parent::initialize();
        $this->loadModel('Supports');
        $this->loadModel('Faqs');
        $this->loadComponent('Common');
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function index() {
        $this->viewBuilder()->layout(false);
    }

    public function underconstraction() {
        $this->viewBuilder()->layout(false);
    }

    public function display() {
        $page = func_get_args();   
        if(isset($page[0]) && !empty($page[0])){
            $page_detail = $this->Pages->find('all', [
                'conditions' => [
                    'page_key' => $page[0],
                    'status' => 1
                ]
             ])->first();;
        }else{
			$this->Flash->error(__('Page not found.'));
			$this->redirect('/');
		}
		$this->set('page_detail', $page_detail);		 
       
    }
    
    public function faq() {
        
        $faq = $this->Faqs->find('all', [
            'conditions' => [
                'status' => 1
            ],
            'order' => 'position'
         ])->all();
		$this->set('faq', $faq);		 
       
    }
    
    public function contactUs($user_id = NULL){
		
        $user_id =  $this->Common->decrypt($user_id);
        $subject = '';
        if (($user_id != '') && ($this->Users->exists(['id' => $user_id]))) {
            $defcategory = 11;
            
            $report_user = $this->Users->find('all', ['conditions' => ['Users.id' => $user_id], 'fields' => ['name','email', 'UserDetails.gender'], 'contain' => 'UserDetails'])->first()->toArray();
            $gender = (isset($report_user['user_detail']['gender']) && ($report_user['user_detail']['gender'] == 1)) ? 'him' : 'her';
			if(!empty($report_user))
				$subject = "Reporting ".$report_user['name']." (".$report_user['email'].") against ".$gender." miscellaneous activities.";
        }else{
            $defcategory = ''; 
        }
        
        $this->loadModel('Supports'); 
        $Support = $this->Supports->newEntity();
        $AdminFilePath = Configure::read('Site.attachment');
         
         if ($this->request->is(['patch', 'post', 'put'])) {
			$attachments = array();		            
            if (!empty($this->request->data['attachment']['name'])) {
                $attachment = $this->request->data['attachment']; //put the data into a var for easy use 
                $file_name = $attachment['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $attachment_newname = 'attachment_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $this->request->data['attachment'] = $attachment_newname;
				
				$Profile_path = $AdminFilePath . $attachment_newname;
				$attachments = [
					pathinfo($Profile_path, PATHINFO_BASENAME) => [
						'file' => $Profile_path,
						'mimetype' => $attachment['type'],
					]
				];
		
            }else{
                $this->request->data['attachment'] = '';
            }
			
            $Support = $this->Supports->patchEntity($Support, $this->request->data);
            if ($this->Supports->save($Support)) {  
               		
                if (isset($attachment_newname) && $attachment_newname != '') {
                    $Profile_path = $AdminFilePath . $attachment_newname;
                    $data = move_uploaded_file($attachment['tmp_name'], $Profile_path);                   
                }
				if($this->request->data['user_id'] > 0)
					$this->Flash->success(__('Report request successfully submitted.'));
				else
					$this->Flash->success(__('Contact form has been successfully posted.'));
                $Support = $this->Supports->newEntity();
                $this->redirect(['action' => 'contact_us']);
            } else {		 
                $this->Flash->error(__('Some error occured.'));
            }
			# Sending email	
			$this->loadModel('SystemMails');
			$SupportMail = $this->SystemMails->findByEmailType('Support')->first()->toArray();
			$SupportMail['message'] = $this->request->data['message'];
			$SupportMail['subject'] = $this->request->data['subject'];
			$SupportMail['to'] = Configure::read('Site.support_email');
			$SupportMail['sender_name'] = $this->request->data['email'];
			$this->sendEmailWithAttachments($SupportMail, $attachments);
			#end mail
        }
		$email = (!empty($this->Auth->User('id'))) ? $this->Auth->User('email') : '';
					
        $this->set(compact('Support', 'defcategory', 'subject', 'user_id', 'email'));
    }

   
}
