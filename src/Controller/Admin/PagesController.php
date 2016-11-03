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
class PagesController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
        //$this->loadModel('Countries');
       
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        //$this->Auth->allow(['forgotPassword', 'logout', 'resetPassword', 'login']);
    }
  
    public function add($id = null) {
        $type = 'Add';
        if ($id != NULL) {
             $type = 'Edit';
            $page = $this->Pages->get($id, [
            ]);
        } else {
            $page = $this->Pages->newEntity();
        }        
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data['page_key'] = $this->Pages->get_slug($this->request->data['page_title']);
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {              
                $this->Flash->success(__('Page has been successfully '.$type.'ed.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Page could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('page', 'type'));
    }
    
    function index(){
        $SiteSettingsTbl=  Configure::read('SiteSettingsTbl');
        $this->paginate = array(
            //'conditions' => ['status' => 1],
            'order' => ['id' => 'desc'],
            'limit' => isset($SiteSettingsTbl->per_page_limit)?$SiteSettingsTbl->per_page_limit:10
            );        
        $pages = $this->paginate('Pages');
        $this->set(compact('pages'));
    }    
       
    function delete($id = NULL){
        if($id){
            $entity = $this->Pages->get($id);
            if($this->Pages->delete($entity)){
                $this->Flash->success(__('Page deleted successfully.'));                
            }else{
                $this->Flash->error(__('Page could not be deleted.'));   
            }
        }else{
            $this->Flash->error(__('Page not found.'));   
        }        
        return $this->redirect(['action' => 'index']);
    }

}
