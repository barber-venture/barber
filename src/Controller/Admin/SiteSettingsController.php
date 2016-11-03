<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Filesystem\File;

class SiteSettingsController extends AdminAppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('SiteSettings');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
    }

    public function index() {
        $SiteSetting = $this->SiteSettings->get('1');
     $AdminFilePath = Configure::read('Site.AdminImages'); 

        if ($this->request->is('put') || $this->request->is('post')) {
            
            if (!empty($this->request->data['site_logos']['name'])) {
                $site_logos_file = $this->request->data['site_logos']; //put the data into a var for easy use 
                $file_name = $site_logos_file['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $site_logos = 'logo_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $oldsite_logos = $this->request->data['site_logo'];
                $this->request->data['site_logo'] = $site_logos;
            }
            if (!empty($this->request->data['favicons']['name'])) {
                $site_favicons = $this->request->data['favicons']; //put the data into a var for easy use 
                $file_name = $site_favicons['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $favicons = 'favicon_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $oldfavicons = $this->request->data['favicon'];
                $this->request->data['favicon'] = $favicons;
            }

//            pr($this->request->data); die;
            $SiteSetting = $this->SiteSettings->patchEntity($SiteSetting, $this->request->data);
            if ($this->SiteSettings->save($SiteSetting)) {
                
                  $adminFileDestination = $AdminFilePath; //$user['contractor_detail']['id'];
              
                $file = new File($adminFileDestination . 'index.html', true, 0700);              
                if (isset($site_logos) && $site_logos != '') {
                    $Admin_logo_path = $adminFileDestination . $site_logos;
                    $data = move_uploaded_file($site_logos_file['tmp_name'], $Admin_logo_path);
                    if ($oldsite_logos != "") {
                        if (file_exists($adminFileDestination . $oldsite_logos)) {
                            unlink($adminFileDestination . $oldsite_logos);
                        }
                    }
                }
                
                  if (isset($favicons) && $favicons != '') {
                    $Admin_favicon_path = $adminFileDestination .  $favicons;
                    $data = move_uploaded_file($site_favicons['tmp_name'], $Admin_favicon_path);
                    if ($oldfavicons != "") {
                        if (file_exists($adminFileDestination . $oldfavicons)) {
                            unlink($adminFileDestination . $oldfavicons);
                        }
                    }
                }
                 
                $this->Flash->success(__('The site setting has been updated.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                 
                $this->Flash->error(__('The  site setting could not be updated.'));
            }
        }
    
        $this->set(compact('SiteSetting'));
        $this->set('_serialize', ['SiteSetting']);
    }

}
