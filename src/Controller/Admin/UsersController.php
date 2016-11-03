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
class UsersController extends AdminAppController {

    public function initialize() {
        parent::initialize();

        $this->loadModel('Countries');
        $this->loadComponent('Common');
        $this->loadModel('SystemMails');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('UserDetails');
        $this->loadComponent('Cookie');
          
        $this->loadModel('Albums');
        $this->loadModel('AlbumImages');        
        $this->loadComponent('Upload');
        //$this->loadModel('user_tag_associations');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['forgotPassword', 'logout', 'resetPassword', 'login']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    function login() {

        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if (isset($this->request->data['remember_me']) && $this->request->data['remember_me'] == 1) {
                    $this->Cookie->delete('Auth.User');
                    $cookie = array();
                    $cookie['email'] = $this->request->data['email'];
                    $cookie['password'] = $this->request->data['password'];
                    $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                    unset($this->request->data['remember_me']);
                } else {
                    $this->Cookie->delete('Auth');
                }
                $this->Auth->setUser($user);
                if ($this->Auth->user('role_id') == 1) {
                    return $this->redirect(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'dashboard']);
                } else {
                    
                    if ($this->Auth->user('change_password_count') == 0) {
                        $this->Flash->success(__('Please update your password.'));
                        return $this->redirect(['prefix' => false, 'controller' => 'Users', 'action' => 'editProfile']);
                    } else {
                        return $this->redirect(['prefix' => false, 'controller' => 'Users', 'action' => 'dashboard']);
                    }
                }
            } else {
                $this->Flash->error(__('Email or password is incorrect'));
            }
        }
        $cookie = $this->Cookie->read('Auth');
        if (!empty($cookie)) {
            $this->request->data['email'] = $cookie['User']['email'];
            $this->request->data['password'] = $cookie['User']['password'];
            $this->request->data['remember_me'] = 1;
        }
    }

    public function logout() {
        $this->Auth->logout();
        return $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    public function dashboard() {
        $this->loadModel('ActiveChats');
        $userCount = $this->Users->find('all', ['conditions' => ['deleted' => 0, 'role_id' => 2]])->count();
        $freelancerCount = $this->Users->find('all', ['conditions' => ['deleted' => 0, 'role_id' => 3]])->count();
          $this->set(compact('userCount'));
                      
    }

    function profile() {
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['UserDetails']
        ]);
        $this->set('user', $user);
    }

    function editProfile() {

        $AdminFilePath = Configure::read('Site.ProfileImage');
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['UserDetails']
        ]);
        //pr($user); die;
        if ($this->request->is(['patch', 'post', 'put'])) {

           
            if (!empty($this->request->data['user_detail']['profile_imagenew']['name'])) {
                $profile_image_file = $this->request->data['user_detail']['profile_imagenew']; //put the data into a var for easy use 
                $file_name = $profile_image_file['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $profile_image_newname = 'profile_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $profile_image_oldname = $this->request->data['user_detail']['profile_image'];
                $this->request->data['user_detail']['profile_image'] = $profile_image_newname;
            }
            unset($this->request->data['user_detail']['profile_imagenew']);
            $user = $this->Users->patchEntity($user, $this->request->data);
            
            if ($this->Users->save($user)) {

                $file = new File($AdminFilePath . 'index.html', true, 0700);
                if (isset($profile_image_newname) && $profile_image_newname != '') {
                    $Profile_path = $AdminFilePath . $profile_image_newname;
                    $data = move_uploaded_file($profile_image_file['tmp_name'], $Profile_path);
                    if ($profile_image_oldname != "") {
                        if (file_exists($AdminFilePath . $profile_image_oldname)) {
                            unlink($AdminFilePath . $profile_image_oldname);
                        }
                    }
                }
//                $this->request->session()->write('Auth.User.name', $this->request->data['name']);
                $this->Flash->success(__('Profile has been successfully updated.'));
                return $this->redirect(['action' => 'profile']);
            } else {
                $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }

        $this->set(compact('user'));
    }

    public function changePassword() {


        $user = $this->Users->get($this->Auth->user('id'));
        unset($user['password']);
        if ($this->request->is('put')) {
            $activationKey = md5(uniqid());
            $this->request->data['activation_key'] = $activationKey;
            $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your password has been changed successfully.'));
                return $this->redirect(array('controller' => 'users', 'action' => 'logout'));
            } else {
                $this->Flash->error(__('An error occurred. Please try again.'));
            }
        }
        $this->set(compact('user'));
    }

    function forgotPassword() {
        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Users->findByEmail($this->request->data['email'])->first();
            if (!empty($user)) {
                $activationKey = md5(uniqid());
                $this->request->data['activation_key'] = $activationKey;
                $this->Users->patchEntity($user, $this->request->data);
                $this->Users->save($user);
                $url1 = Router::url(['controller' => 'Users', 'action' => 'reset_password', $activationKey], true);
                $url = "<a href='$url1'>Click Here</a> and $url1";
                $mail = $this->SystemMails->findByEmailType('ForgotPassword')->first();

                $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
                $mail['message'] = str_replace('[link]', $url, $mail['message']);
                $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                $mail['to'] = $user['email'];

                $this->sendEmail($mail);
                $this->Flash->success(__('You will receive an email with instructions about how to reset your password in a few minutes.'));
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                $this->Flash->error(__('No user was found with the submitted email.'));
            }
        }
    }

    public function addUser($id = null) {
        $this->loadModel('Countries');
        $AdminFilePath = Configure::read('Site.ProfileImage');
        if ($id != NULL) {
            $user = $this->Users->get($this->Auth->user('id'), [
                'contain' => ['UserDetails']
            ]);
        } else {
            $user = $this->Users->newEntity();
        }


        if ($this->request->is(['patch', 'post', 'put'])) {

            if (!empty($this->request->data['user_detail']['profile_image']['name'])) {
                $profile_image_file = $this->request->data['user_detail']['profile_image']; //put the data into a var for easy use 
                $file_name = $profile_image_file['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $profile_image_newname = 'profile_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $profile_image_oldname = $this->request->data['user_detail']['profile_image'];
                $this->request->data['user_detail']['profile_image'] = $profile_image_newname;
            }
            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {

                $this->Flash->success(__('Profile has been successfully updated.'));
                //return $this->redirect(['action' => 'profile']);
            } else {
                $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }
        $Countries = $this->Countries->find('list', ['order' => ['name' => 'asc'], 'keyField' => 'country_id', 'valueField' => 'name'])->toArray();
        $States = $this->States->find('list');
        $Cities = $this->Cities->find('list');
        $this->set(compact('user', 'Countries', 'States', 'Cities'));
    }

    function viewUser() {
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        $conditions[] = ['role_id' => 2, 'deleted' => 0];

        if (isset($this->request->query['keyword']) && !empty($this->request->query['keyword'])) {
            //pr($this->request->query); die;
            $keyword = trim($this->request->query['keyword']);
            $conditions['OR'] = ['Users.name LIKE' => '%' . $keyword . '%'];
        }
        if (isset($this->request->query['user_filter']) && !empty($this->request->query['user_filter'])) {
            $user_id = trim($this->request->query['user_filter']);
            $conditions[] = ['Users.id' => $user_id];
        }


        if (isset($this->request->query['status']) && (($this->request->query['status'] == 0) || $this->request->query['status'] == 1)) {

            //if (isset($this->request->query['deleted']) && !empty($this->request->query['deleted'])) {
            //    $deleted = trim($this->request->query['deleted']);
            //    $conditions[0]['deleted'] = $deleted;
            //}
            $status = trim($this->request->query['status']);
            $conditions[] = ['Users.status' => $status];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => ['UserDetails'],
            'order' => ['id' => 'desc'],
            'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10
        );
        $users = $this->paginate('Users');
        $users_list = $this->Users->get_list();
        $this->set(compact('users', 'users_list'));
    }

    function viewFreelancer() {
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        $conditions[] = ['role_id' => 3, 'deleted' => 0];

        if (isset($this->request->query['keyword']) && !empty($this->request->query['keyword'])) {
            $keyword = trim($this->request->query['keyword']);
            $conditions['OR'] = ['name LIKE' => '%' . $keyword . '%'];
        }

        if (isset($this->request->query['status']) && (($this->request->query['status'] == 0) || $this->request->query['status'] == 1)) {

            $status = trim($this->request->query['status']);
            $conditions[] = ['status' => $status];
        }

        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => ['UserDetails'],
            'order' => ['id' => 'desc'],
            'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10
        );
        $users = $this->paginate('Users');
        $this->set(compact('users'));
    }

    public function addEdit($id = NULL) {
                       
        $type = 0;
        if ($id) {
            $user = $this->Users->get($id, ['contain' => ['UserDetails', 'UserTags']]);
            $type = 1;
            if(isset($user->user_detail->dob) && !empty($user->user_detail->dob))
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        } else {
            $user = $this->Users->newEntity();
        }

        $UserTags = $this->UserTags->get_list();

        $AdminFilePath = Configure::read('Site.ProfileImage');

        if ($this->request->is(['patch', 'post', 'put'])) {            
            
            if (!empty($this->request->data['user_detail']['profile_image_new']['name'])) {
                $site_logos_file = $this->request->data['user_detail']['profile_image_new']; //put the data into a var for easy use 
                $file_name = $site_logos_file['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $site_logos = 'profile_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $oldsite_logos = $this->request->data['user_detail']['profile_image'];
                $this->request->data['user_detail']['profile_image'] = $site_logos;
                unset($this->request->data['user_detail']['profile_imagenew']);
                
            }
            else {
                unset($this->request->data['user_detail']['profile_image']);
                unset($this->request->data['user_detail']['profile_imagenew']);
            }
                
            $user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserDetails', 'UserTags']]);
            //pr($user); die;
            if ($result = $this->Users->save($user)) {
                
                $id = $result->id;
                if (!empty($this->request->data['user_detail']['profile_image_new']['name'])) {
                    $this->uploadImage($id, $site_logos, $name);
                }
                //$adminFileDestination = $AdminFilePath;
                if (isset($site_logos) && $site_logos != '') {
                    $Admin_logo_path = $AdminFilePath . $site_logos;
                    $data = move_uploaded_file($site_logos_file['tmp_name'], $Admin_logo_path);
                    if ($oldsite_logos != "") {
                        $query = $this->AlbumImages->find('all', ['conditions' => ['user_id' => $id]]);
                        $AlbumImages = $query->count();
                        $img_limit = Configure::read('Site.image_upload_limit_for_normal_user');
                        if($AlbumImages > $img_limit){
                            $this->AlbumImages->deleteAll(['user_id' => $id, 'image_name' => $oldsite_logos]);
                            if (file_exists($AdminFilePath . $oldsite_logos)) {
                                unlink($AdminFilePath . $oldsite_logos);
                            }
                        }            
                        
                    }
                }

                $this->Flash->success(__('User has been successfully ' . (($type) ? 'updated.' : 'added.')));
                return $this->redirect(['action' => 'view_user']);
            } else {
                $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }
        $this->set(compact('user',  'UserTags', 'type'));
    }
    
    function uploadImage($id, $new_imag_name, $name){
        if($this->Auth->User('user_type') == 1){
            #Getting album data
            $album_id = 0;
            $Albums_query = $this->Albums->find('all', ['conditions' => ['user_id' => $id]]);
            $album = $Albums_query->count();
            if($album == 0){
                $newAlbum = $this->Albums->newEntity();
                $newAlbum->user_id = $id;
                $newAlbum->name = 'Default';
                
                $albumsave = $this->Albums->save($newAlbum);
                $album_id  = $albumsave->id;                        
            }else{                          
                $Albums = $this->Albums->find('all', ['conditions' => ['user_id' => $id]])->first();
                $album_id  = $Albums->id;
            }
            
            $newAlbumImages = $this->AlbumImages->newEntity();
            $newAlbumImages->user_id = $id;
            $newAlbumImages->album_id = $album_id;
            $newAlbumImages->image_name = $new_imag_name;
            $newAlbumImages->image_title = $name;
            if($this->AlbumImages->save($newAlbumImages)){
                echo 1;
            }  
            
        }
    }

    function blockUsers() {
        $this->loadModel('ActiveChats');
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        $this->paginate = array(
            'conditions' => ['is_deleted' => '1'],
            'contain' => [
                'FromUser' => ['fields' => ['name']],
                'ToUser' => ['fields' => ['name']]
            ],
            'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10,
            'order' => ['id' => 'desc'],
        );
        $users = $this->paginate('ActiveChats');
        
        $this->set(compact('users'));
    }

    function deleteblockusers($id = NULL) {
        if ($id) {
            $this->loadModel('ActiveChats');
            $entity = $this->ActiveChats->get($id);
            if ($this->ActiveChats->delete($entity)) {
                $this->Flash->success(__('User unblocked successfully.'));
            } else {
                $this->Flash->error(__('User could not be unblocked.'));
            }
        } else {
            $this->Flash->error(__('Record not found.'));
        }
        return $this->redirect(['action' => 'blockUsers']);
    }

    public function subscription() {
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        $this->paginate = array(
            'contain' => ['Users', 'Plans'],
            'conditions' => ['Users.deleted' => 0],
            'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10,
            'order' => ['UserPlanAssociations.id' => 'desc'],
        );
        $UserPlanAssociations = $this->paginate('UserPlanAssociations');
        $this->set(compact('UserPlanAssociations'));
    }

    function detail($id) {
        if ($id) {
            $user = $this->Users->get($id, [
                'contain' => ['UserDetails' /*=> ['Countries', 'States', 'Cities']*/]
            ]);
            $this->set('user', $user);
        }
    }

    public function addSubscription($id = null) {
        $today = date(Configure::read('Site.CakeDateFormatForView'));

        $PlanDuration = Configure::read('PlanDuration');

        if ($id != NULL) {
            $UserPlanAssociations = $this->UserPlanAssociations->get($id, [
                'contain' => []
            ]);
        } else {
            $UserPlanAssociations = $this->UserPlanAssociations->newEntity();
        }
        if ($this->request->is('put') || $this->request->is('post')) {
            //$planduretion = $this->Plans->findById($this->request->data['plan_id'])->first();
            $date = date(Configure::read('Site.CakeDateFormat'), strtotime($today . ' + ' . $PlanDuration[$this->request->data['plan_id']]));           
            $new_date = Time::parse($date);   
            $this->request->data['expiry_date'] = $new_date;            
            $UserPlanAssociations = $this->UserPlanAssociations->patchEntity($UserPlanAssociations, $this->request->data);
            
            $this->Users->updateAll(['user_type' => $this->request->data['plan_id'], 'plan_expiry_date' => $new_date], ['id' => $UserPlanAssociations->user_id]);
            if ($this->UserPlanAssociations->save($UserPlanAssociations)) {
                if ($id != NULL) {
                    $this->Flash->success(__('The subscription has been updated.'));
                } else {
                    $this->Flash->success(__('The subscription has been saved.'));
                }
                return $this->redirect(['controller' => 'Users', 'action' => 'subscription']);
            } else {
                $this->Flash->error(__('The subscription could not be updated.'));
            }
        }

        $userList = $this->Users->find('list', [
            'order' => ['Users.name' => 'asc'],
            'keyField' => 'userId',
            'valueField' => 'concatenated',
            'conditions' => ['Users.role_id' => 2, 'Users.deleted' => 0]
                ]
        );

        $userList->select([
            'userId' => 'Users.id',
            'concatenated' => $userList->func()->concat([
                'Users.name' => 'literal',
                '(',
                'Users.email' => 'literal',
                ')',
            ])
        ]);

        $PlanList = $this->Plans->find('list', ['conditions' => ['Plans.deleted' => 0], 'keyField' => 'id', 'valueField' => 'plan_name'])->toArray();

        $this->set(compact('UserPlanAssociations', 'userList', 'PlanList'));
        $this->set('_serialize', ['plan']);
    }

    public function changeStatusScuscription($id = null, $starus = 0) {
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;
        if ($this->UserPlanAssociations->updateAll(array('status' => ($starus == 1) ? '0' : 1), array('id' => $id))) {
            $this->Flash->success(__('The subscription status has been updated.'));
        } else {
            $this->Flash->error(__('The subscription status could not be updated. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'subscription']);
    }

    public function addEditFreelancer($id = NULL) {
        $type = 0;
        if ($id) {
            $user = $this->Users->get($id, ['contain' => ['UserDetails']]);
            $type = 1;
        } else {
            $user = $this->Users->newEntity();
        }
        $UserTags = $this->UserTags->get_list();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data['role_id'] = 3;
            $user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserDetails']]);
            //pr($user); die;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User has been successfully ' . (($type) ? 'updated.' : 'added.')));
                return $this->redirect(['action' => 'view_freelancer']);
            } else {
                $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }
        $this->set(compact('user', 'Countries', 'States', 'Cities', 'UserTags', 'type'));
    }

    public function freelancerDetail($id) {
        if ($id) {
            $user = $this->Users->get($id, [
                'contain' => ['Albums' => ['AlbumImages'], 'UserDetails' => ['Countries', 'States', 'Cities']]
            ]);
            $this->set('user', $user);
        }
    }

    function download($file = null) {
        $this->autoRender = false;
        $projectsFilePath = Configure::read('Site.attachment');
        $filePath = $projectsFilePath . $file;
        $file = "supports";
        if (file_exists($filePath)) {
            $this->response->header(array('Content-type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document;charset=utf-8'));
            $this->response->file($filePath, array(
                'download' => true,
                'name' => $file,
            ));
        } else {
            $this->Flash->error(__('File not found. Please, try again.'));
        }
    }

    function resetPassword($key) {
        $this->viewBuilder()->layout('login');
        $user = $this->Users->findByActivationKey($key)->first();
        unset($user['password']);
        if (empty($user)) {
            $this->Flash->error(__('Your forgot password link has been expired. Please try again!'));
            $this->redirect(['controller' => 'users', 'action' => 'forgot_password']);
        }
        if ($this->request->is('put')) {
            $activationKey = md5(uniqid());
            $this->request->data['activation_key'] = $activationKey;
            $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your password has been reset successfully.'));
                return $this->redirect(array('prefix' => 'admin', 'controller' => 'users', 'action' => 'login'));
            } else {
                $this->Flash->error(__('An error occurred. Please try again.'));
            }
        }
        $this->set(compact('user'));
    }
    
    

}
