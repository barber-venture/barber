<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Filesystem\File;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class IndexesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('SystemMails');
        $this->loadModel('Projects');
        $this->loadModel('ProjectTypes');
        $this->loadModel('Contractors');
        $this->loadModel('States');
        $this->loadComponent('Common');
        $this->loadComponent('Cookie');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }
    
    public function index(){
        
    }
   
}
