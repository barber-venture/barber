<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Filesystem\File;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class FaqsController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadModel('FaqCategories');
       
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        //$this->Auth->allow(['forgotPassword', 'logout', 'resetPassword', 'login']);
    }
  
    public function add($id = null) {
        $type = 'Add';
        if ($id != NULL) {
            $faq = $this->Faqs->get($id, [
            ]);
            $type = 'Edit';
        } else {
            $faq = $this->Faqs->newEntity();
        }
        
        //$faq_category_id = $this->FaqCategories->find('list');
        //pr($faq_category_id); die;
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $faq = $this->Faqs->patchEntity($faq, $this->request->data);
            if ($this->Faqs->save($faq)) {              
                $this->Flash->success(__('Faq has been successfully '.$type.'ed.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Faq could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('faq', 'type'));
    }
    
    function index(){
        $SiteSettingsTbl=  Configure::read('SiteSettingsTbl');
        $this->paginate = array(
            //'conditions' => ['status' => 1],
            //'contain' => ['FaqCategories'],
            'order' => ['id' => 'desc'],
            'limit' => isset($SiteSettingsTbl->per_page_limit)?$SiteSettingsTbl->per_page_limit:10
            );        
        $faqs = $this->paginate('Faqs');
        $this->set(compact('faqs'));
    }
    
    function delete($id = NULL){
        if($id){
            $entity = $this->Faqs->get($id);
            if($this->Faqs->delete($entity)){
                $this->Flash->success(__('Faq deleted successfully.'));                
            }else{
                $this->Flash->error(__('Faq could not be deleted.'));   
            }
        }else{
            $this->Flash->error(__('Faq not found.'));   
        }        
        return $this->redirect(['action' => 'index']);
    }

}
