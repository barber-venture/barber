<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\I18n\Time;

//use Cake\Database\Type

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class MasterDatasController extends AdminAppController {

    public function initialize() {

        parent::initialize();
        $this->loadModel('UserTags');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
    }

    public function tagList() {
        $this->paginate = [

            'order' => ['id desc'],
            'limit' => (isset($this->request->query['limit'])) ? $this->request->query : 10,
        ];
        $UserTags = $this->paginate($this->UserTags);
        $this->set(compact('UserTags'));
    }

    public function addTag($id = null) {
        if ($id != NULL) {
            $UserTags = $this->UserTags->get($id, [
                'contain' => []
            ]);
            //pr($user);
        } else {
            $UserTags = $this->UserTags->newEntity();
        }


        if ($this->request->is('put') || $this->request->is('post')) {


            $UserTags = $this->UserTags->patchEntity($UserTags, $this->request->data);
            if ($this->UserTags->save($UserTags)) {

                if ($id != NULL) {
                    $this->Flash->success(__('The tag has been updated.'));
                } else {
                    $this->Flash->success(__('The tag has been saved.'));
                }


                return $this->redirect(['controller' => 'MasterDatas', 'action' => 'tagList']);
            } else {

                $this->Flash->error(__('The  tag could not be updated.'));
            }
        }

        $this->set(compact('UserTags'));
        $this->set('_serialize', ['UserTags']);
    }

    public function delectTag($id = null) {

        
         
        if ($id == NULL) {
            return $this->redirect(['action' => 'tagList']);
        }
        if (!$this->UserTags->exists(['id' => $id])) {
            return $this->redirect(['action' => 'tagList']);
        }
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;
        $data = $this->UserTags->get($id);
        if ($this->UserTags->delete($data)) {
            $this->Flash->success(__('The tag has been deleted.'));
        } else {
            $this->Flash->error(__('The tag could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'tagList']);
    }
    
     

}
