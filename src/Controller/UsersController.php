<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Filesystem\File;
use Abraham\TwitterOAuth\TwitterOAuth;

require ROOT . DS . 'vendor' . DS . 'Abraham' . DS . 'TwitterOAuth' . DS . 'autoload.php';

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('SystemMails');
        $this->loadModel('Projects');
        $this->loadModel('ProjectTypes');
        $this->loadModel('Contractors');
        $this->loadModel('Countries');
        $this->loadModel('UserDetails');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Languages');
        $this->loadComponent('Common');
        $this->loadModel('LikeDislikes');
        $this->loadmodel('Notifications');
        $this->loadmodel('UserTags');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if ($this->request->is('ajax')) {
            $this->eventManager()->off($this->Csrf);
        }
        $this->Auth->allow(['forgotPassword', 'logout', 'plan', 'resetPassword', 'facebookLogin', 'signUp', 'login', 'verify', 'resetNewPassword', 'social', 'social_redirect']);
    }

    /**
     * Login method for Admin
     *    
     */
    function login() {
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;
        if ($this->request->is('post')) {

            $usrdata = $this->Users->findByEmail($this->request->data['email'])->first();
            if ($usrdata->deleted) {
                echo '3';
            } else if (!$usrdata->status) {
                echo '2';
            } else {
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Users->updateAll(['is_online' => 1], ['id' => $user['id']]);
                    if (isset($this->request->data['remember_me']) && $this->request->data['remember_me'] == 1) {
                        $this->Cookie->delete('Auth.User');
                        $cookie = array();
                        $cookie['email'] = $this->request->data['email'];
                        $cookie['password'] = $this->request->data['password'];
                        $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                        // $user = $this->Cookie->read('Auth.User');
                        unset($this->request->data['remember_me']);
                    } else {
                        $this->Cookie->delete('Auth');
                    }
                    $this->Auth->setUser($user);
                    $this->request->session()->write('Auth.User.is_online', '1');
                    if ($user['role_id'] == 3)
                        echo '4';
                    else
                        echo "1";
                } else {
                    echo '0';
                }
            }
        }
        $this->render(false);
    }

    public function facebookLogin() {
        require ROOT . DS . 'vendor' . DS . 'Facebook' . DS . 'Facebook.php';
        $facebook = new \Facebook(array(
            'appId' => Configure::read('Facebook.appId'),
            'secret' => Configure::read('Facebook.secret'),
            'cookie' => true,
        ));

        $access = $facebook->getAccessToken();

        if ($this->request->query('code')) {
            
            $userSocialId = $facebook->getUser();
            
            $facebookInfo = $facebook->api('/' . $userSocialId . '?fields=id,name,email');
            
            if ($facebookInfo) {

                if (!isset($facebookInfo['email'])) {
                    $this->Flash->error(__('Unable to get your facebook data.'));
                    return $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
                }
                $local_user = $this->Users->find('all', [
                            'conditions' => [
                                'email' => $facebookInfo['email'],
                                'status' => 1
                            ]
                        ])->first();
                
                // If exists, we will log them in
                if ($local_user) {

                    $this->Auth->setUser($local_user->toArray());
                    $this->Flash->success(__('Successfully logged in'));
                    $this->redirect(array('controller' => 'users', 'action' => 'myProfile'));
                } else {
                    $user = $this->Users->newEntity();
                    
                    if (isset($facebookInfo['email']) && !empty($facebookInfo['email'])) {

                        $user['email'] = $facebookInfo['email'];
                        $user['name'] = $facebookInfo['name'];
                        $user['role_id'] = 2;
                        $user['is_verify'] = 1;
                        $user['facebook_user_id'] = $facebookInfo['id'];                        
                                                
                        if ($user = $this->Users->save($user)) {
                                                    
                            $this->Auth->setUser($user->toArray());
                            $this->redirect(array('controller' => 'users', 'action' => 'myProfile'));
                        }
                    } else {
                        $this->Flash->error(__('Unable to get your facebook data'));
                    }
                }
            } else {
                $this->Flash->error(__('Unable to get your facebook data'));
            }
        } else {
            $this->Flash->error(__('Unable to get your facebook data'));
        }
        $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
    }

    /**
     * dashboard method.
     *    
     */
    public function myProfile($type = null) {
        
        if ($this->Auth->User('role_id') != 2) {
            return $this->redirect(['action' => 'photographerMyProfile']);
        }
        $type = 'me';
        $UserTags = $this->UserTags->get_list();
        if (isset($this->request->query['key'])) {
            $user1 = $this->Users->findByActivationKey($this->request->query['key'])->first();
            if (!empty($user1)) {
                $user1['send_mail_count'] = 0;
                $user1['is_confirm_email'] = 1;
                $user1['activation_key'] = "";
                $this->Users->save($user1);
            }
        }
        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'UserTags', 'Albums', 'Albums.AlbumImages'], 'conditions' => ['Users.id' => $this->Auth->user('id')]])->first();

        if (isset($user->user_detail->dob)) {
            if ($user->user_detail->dob != '')
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        }


        $language = $this->Languages->find('list');
        $this->set(compact('user', 'type', 'language', 'UserTags'));
    }

    public function profile($id = null) {
        //echo $this->Common->encrypt(27);; die;
        $id = $this->Common->decrypt($id);
        if (($id == '') || (!$this->Users->exists(['id' => $id]))) {
            $this->Flash->error('Profile Not exist!');
            return $this->redirect(['action' => 'my_profile']);
        }        
        $type = 'other';
        $UserTags = $this->UserTags->get_list();

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'UserTags', 'Albums.AlbumImages', 'Albums'], 'conditions' => ['Users.id' => $id]])->first();
        if ($user->role_id == 3) {
            $this->Flash->error('Profile Not exist!');
            return $this->redirect(['action' => 'my_profile']);
        }
        if (isset($user->user_detail->dob)) {
            if ($user->user_detail->dob != '')
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        }
        $language = $this->Languages->find('list');
        $this->set(compact('user', 'type', 'language', 'UserTags'));
        $this->render('my_profile');
    }

    public function dashboard() {
        if ($this->Auth->user('role_id') != 1) {
            $this->Flash->error('You are not authorised to access this location.');
            $this->redirect(['action' => 'myProfile']);
        }
    }

    public function logout() {
        $this->Users->updateAll(['is_online' => 0], ['id' => $this->Auth->user('id')]);
        $this->Cookie->delete('Auth.User');
        $this->Auth->logout();
        return $this->redirect('/');
    }

    /**
     * forgot password method.
     *    
     */
    function forgotPassword() {
        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Users->findByEmail($this->request->data['email'])->first();
            if (!empty($user)) {

                $activationKey = md5(uniqid());
                $this->request->data['activation_key'] = $activationKey;
                $this->Users->patchEntity($user, $this->request->data);
                $this->Users->save($user);
                $url1 = Router::url(['controller' => 'Users', 'action' => 'resetNewPassword', $activationKey], true);
                $url = "<a href='$url1'>Click Here</a> and $url1";
                $mail = $this->SystemMails->findByEmailType('ForgotPassword')->first();
                $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                $mail['message'] = str_replace('[name]', $user['name'], $mail['message']);
                $mail['message'] = str_replace('[link]', $url, $mail['message']);
                $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                $mail['to'] = $user['email'];
                $this->sendEmail($mail);
                echo 'succes';
            } else {
                echo 'No user was found with the given email.';
            }
        }
        exit();
    }

    function resetNewPassword($key = null) {
        if ($key == null) {
            $this->Flash->error(__('Your forgot password link has been expired. Please try again!'));
            return $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
        }

        $user = $this->Users->findByActivationKey($key)->first();
        unset($user['password']);
        if (empty($user)) {
            $this->Flash->error(__('Your forgot password link has been expired. Please try again!'));
            return $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
        }
        if ($this->request->is('put')) {
            $activationKey = md5(uniqid());
            $this->request->data['activation_key'] = $activationKey;
            $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your password has been reset successfully.'));
                return $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
            } else {
                $this->Flash->error(__('An error occurred. Please try again.'));
            }
        }
        $this->set(compact('user'));
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
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                $this->Flash->error(__('An error occurred. Please try again.'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * 
     * User Profile 
     * 
     */
    function profile_old() {
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['Countries']
        ]);
        $this->set('user', $user);
    }

    /**
     * 
     * User Edit Profile 
     * 
     */
    function editProfile() {

        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['Countries']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $image = $this->request->data['image'];
            $image_name = '';
            if (isset($image['name']) && !empty($image['name'])) {
                $destination = WWW_ROOT . 'uploads' . DS . 'users' . DS;
                $image_name = $this->getFileName($this->request->data);
                $this->request->data['image'] = $image_name;
            } else {
                unset($this->request->data['image']);
            }
            if (trim($this->request->data['password']) == '') {
                unset($this->request->data['password']);
            }
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                if ($this->request->data['password'] != '') {
                    $this->Users->updateAll(['change_password_count' => 1], ['id' => $this->Auth->user('id')]);
                }
                if (isset($image['name']) && !empty($image['name'])) {
                    move_uploaded_file($image['tmp_name'], $destination . $image_name);
                    $this->request->session()->write('Auth.User.image', $this->request->data['image']);
                    if ($this->request->data['old_image'] != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['old_image'])) {
                        @unlink(WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['old_image']);
                    }
                }
                $this->request->session()->write('Auth.User.name', $this->request->data['name']);
                $this->Flash->success(__('Profile has been successfully updated.'));
                return $this->redirect(['action' => 'profile']);
            } else {
                $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }
        unset($user['password']);
        // $countries = $this->Users->Countries->find('list', ['order' => ['name' => 'asc'], 'keyField' => 'id', 'valueField' => 'name']);
        $this->set(compact('user'));
    }
    /*
    function download($type = null, $id = null, $file = null) {
        $this->autoRender = false;
        if ($type == 'company') {
            $companyFilePath = Configure::read('Site.CompanyFilePath');
            $filePath = $companyFilePath . $id . DS . $file;
        }
        $projectsFilePath = Configure::read('Site.ProjectsFilePath');
        if ($type == 'project') {
            $filePath = $projectsFilePath . $id . DS . $file;
        }
        if ($type == 'pace') {
            $filePath = $projectsFilePath . $id . DS . 'pace_files' . DS . $file;
        }
        if ($type == 'customer') {
            $filePath = $projectsFilePath . $id . DS . 'customer_signed_files' . DS . $file;
        }
        if ($type == 'contractor') {
            $filePath = $projectsFilePath . $id . DS . 'contractor_files' . DS . $file;
        }
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
    */

    public function signUp() {

        $this->autoRender = false;
        if ($this->request->is('post')) {

            if ($this->request->data['freelancer'] == 3) {
                $this->request->data['role_id'] = 3;
            }

            $activationKey = md5(uniqid());
            $this->request->data['activation_key'] = $activationKey;
            $user = $this->Users->newEntity();
            $this->request->data['status'] = 1;
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $type = 'Free User';
                if ($this->request->data['freelancer'] == 3) {
                    $type = 'Freelancer';
                }
//                $this->Auth->setUser($user);
                if ($this->request->data['freelancer'] == 3) {
                    $this->sendFreelancerRegEmail($user, $type);
					$this->sendEmailWithPassword($user, $this->request->data['password'], $type, $activationKey);
                    echo "freelancer";
                } else {
                    $this->sendEmailWithPassword($user, $this->request->data['password'], $type, $activationKey);
                    echo "succes";
                }
            } else {
                echo 'Profile could not be created. Please, try again.';
            }
        }
        exit();
    }

    public function verify($key = null) {
        $user = $this->Users->findByActivationKey($key)->first();
        if ($user) {
            //$user['is_verify'] = 1;
            $user['status'] = 1;
            $user['is_confirm_email'] = 1;
            if ($user = $this->Users->save($user)) {
                $this->Flash->success(__('Your account has been successfully verified'));
            } else {
                $this->Flash->error(__('Your account has been not verified.please again'));
            }
        } else {
            $this->Flash->error(__('Your account has been not verified.please again'));
        }



        return $this->redirect(array('controller' => 'indexes', 'action' => 'index'));
    }

    public function projectsExport() {
        $common = new \App\View\Helper\CommonHelper(new \Cake\View\View());

        if ($this->Auth->user('role_id') == 2) {
            $all_con = array('NOT' => ['Projects.status' => 0]);
        } elseif ($this->Auth->user('role_id') == 3) {
            $all_con = array('NOT' => ['Projects.status' => 0], 'Users.parent_id' => $this->Auth->user('id'));
        } else {
            $all_con = array('NOT' => ['Projects.status' => 0], 'Projects.user_id' => $this->Auth->user('id'));
        }
        $all_con = array_merge($all_con, ['Projects.deleted' => 0]);

        $all_projects = $this->Projects->find('all', ['contain' => ['Users.Contractors.ContractorDetails'], 'order' => ['Projects.id' => 'desc'], 'conditions' => $all_con])->toArray();
        $projects = [];
        foreach ($all_projects as $project) {
            $new_project['id'] = $project['id'];
            $new_project['contractor'] = $project['user']['contractor']['contractor_detail']['company_name'];
            $new_project['salesperson'] = $project['user']['name'];
            $new_project['status'] = $common->getStatusTitle($project['status']);
            $new_project['name'] = strip_tags($common->checkValue($project['owner_name']));
            $new_project['address'] = strip_tags($common->checkValue($project['address']));
            $new_project['city'] = strip_tags($common->checkValue($project['city']));
            $new_project['zipcode'] = strip_tags($common->checkValue($project['zipcode']));
            $new_project['a_amount'] = strip_tags($common->getProjectAmount($project, 'auth'));
            $new_project['p_amount'] = strip_tags($common->getProjectAmount($project));
            $projects[] = $new_project;
        }
        $_serialize = 'projects';
        if ($this->Auth->user('role_id') == 2) {
            $_header = ['ID', 'Contractor', 'Salesperson', 'Status', 'Name', 'Address', 'City', 'Zipcode', 'Authorized amt.', 'Project amt.'];
            $_extract = ['id', 'contractor', 'salesperson', 'status', 'name', 'address', 'city', 'zipcode', 'a_amount', 'p_amount'];
        } elseif ($this->Auth->user('role_id') == 3) {
            $_header = ['ID', 'Salesperson', 'Status', 'Name', 'Address', 'City', 'Zipcode', 'Authorized amt.', 'Project amt.'];
            $_extract = ['id', 'salesperson', 'status', 'name', 'address', 'city', 'zipcode', 'a_amount', 'p_amount'];
        } else {
            $_header = ['Project ID', 'Status', 'Name', 'Address', 'City', 'Zipcode', 'Authorized amt.', 'Project amt.'];
            $_extract = ['id', 'status', 'name', 'address', 'city', 'zipcode', 'a_amount', 'p_amount'];
        }
        $this->response->download('projects.csv');
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('projects', '_serialize', '_header', '_extract'));
    }

    public function twitterVerification() {

        $CONSUMER_KEY = Configure::read('Twitter.appId');
        $CONSUMER_SECRET = Configure::read('Twitter.secret');
        $OAUTH_CALLBACK = Configure::read('Twitter.callback');

        //$this->request->session()->delete('twitter_token');
        if (!$this->request->session()->read('twitter_token')) {

            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
            $twitter_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $OAUTH_CALLBACK));
            if ($twitter_token) {
                $token = $twitter_token['oauth_token'];
                $this->request->session()->write('twitter_token', $token);
                $this->request->session()->write('twitter_token_secret', $twitter_token['oauth_token_secret']);
                $url = $connection->url('oauth/authorize', array('oauth_token' => $token));

                if ($url) {
                    //echo $url; die;
                    return $this->redirect($url);
                }
            } else { //error receiving request token
                echo "Error in Receiving Request Token";
            }
        } else if (isset($this->request->query['oauth_token'])) {

            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $this->request->session()->read('twitter_token'), $this->request->session()->read('twitter_token_secret'));
            $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $this->request->query['oauth_verifier']]);
            if ($access_token) {
                $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                $params = array();
                $params['include_entities'] = 'false';
                $content = $connection->get('account/verify_credentials', $params);
                if ($content && isset($content->id) && isset($content->name)) {
                    $display_name = $content->name;
                    $twitter_id = $content->id;
                    $image_url = $content->profile_image_url;
                    $image_url = str_replace('_normal', '_400x400', $image_url);

                    $existing_data = $this->Users->find('all', ['conditions' => ['twitter_user_id' => $twitter_id]])->first();
                    if(empty($existing_data)){
                        if ($this->Users->updateAll(['is_verify' => '1', 'twitter_user_id' => $twitter_id], ['id' => $this->Auth->User('id')])) {
                            $this->Flash->success(__('Successfully verified from twitter account.'));
                        } else {
                            $this->Flash->error(__('Unable to save your twitter data'));
                            $this->request->session()->delete('twitter_token');
                        }
                    }else{
                        $this->Flash->error(__('This twitter id is already used.'));
                        $this->request->session()->delete('twitter_token');
                    }
                } else {
                    $this->Flash->error(__('Unable to get your twitter data'));
                    $this->request->session()->delete('twitter_token');
                }
            }
        } else {
            $this->Flash->error(__('Unable to get your twitter data'));
            $this->request->session()->delete('twitter_token');
        }
        return $this->redirect(['action' => 'myProfile']);
    }

    public function changePassword() {

        $user = $this->Users->find('all', ['contain' => 'UserDetails', 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();

        if ($this->Users->setPassword($this->request->data['current_password'], $user->password)) {
            if ($this->request->is(['patch', 'post', 'put'])) {
                if (trim($this->request->data['password']) == '')
                    unset($this->request->data['con_password']);

                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($user)) {
                    //$this->logout();
                    $this->Flash->success(__('Password successfully updated.'));
                } else {
                    $this->Flash->error(__('Some error occured.'));
                }
            }
        } else {
            $this->Flash->error(__('Current password is incorrect.'));
        }

        return $this->redirect(['action' => 'setting']);
    }

    public function setting() {

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'AlbumImages', 'UserTags'], 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();
        $old_email = $user->email;
        if (isset($user->user_detail->dob)) {
            if ($user->user_detail->dob != '')
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($old_email != $this->request->data['email']) {
                $activationKey = md5(uniqid());
                $this->request->data['activation_key'] = $activationKey;
            }

            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                if ($old_email != $this->request->data['email']) {
                    if ($this->sendConfirmEmail($user, $activationKey))
                        $this->Flash->success(__('Your Detail has been successfully updated.'));
                }
            } else {
                $this->Flash->error(__('Some error occured.'));
            }
        }
        $this->set('user', $user);
    }

    public function facebookVerification() {
        require ROOT . DS . 'vendor' . DS . 'Facebook' . DS . 'Facebook.php';
        $facebook = new \Facebook(array(
            'appId' => Configure::read('Facebook.appId'),
            'secret' => Configure::read('Facebook.secret'),
            'cookie' => true,
        ));

        $access = $facebook->getAccessToken();

        if ($this->request->query('code')) {
            $userSocialId = $facebook->getUser();
            $facebookInfo = $facebook->api('/' . $userSocialId . '?fields=id,email');

            if ($facebookInfo) {

                if($this->Auth->User('email') == $facebookInfo['email']) {

                    if ($this->Users->updateAll(['is_verify' => '1', 'facebook_user_id' => $facebookInfo['id']], ['id' => $this->Auth->User('id')])) {
                        $this->Flash->success(__('Successfully verified from facebook account.'));
                    } else {
                        $this->Flash->error(__('Unable to save your facebook data'));
                    }

                }else{
                    $this->Flash->error(__('Your facebook email id is different from registered email id.'));
                }                
            } else {
                $this->Flash->error(__('Unable to get your facebook data'));
            }
        } else {
            $this->Flash->error(__('Unable to get your facebook data'));
        }
        $this->redirect(array('controller' => 'users', 'action' => 'myProfile'));
    }

    function deleteAccount() {
        if ($this->Users->updateAll(['deleted' => '1'], ['id' => $this->Auth->User('id')])) {
            $this->Flash->success(__('Your account is permanently deleted. You are no longer authorized to login.'));
            $this->logout();
        } else {
            $this->Flash->error(__('Unable to delete yout account'));
        }
    }

    public function search() {

        if ($this->request->session()->read('Auth.User.role_id') != 2) {
            $this->Flash->error('You are not authorized to access this location.');
            $this->redirect(array('action' => 'myProfile'));
        }

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'AlbumImages', 'UserTags'], 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();
        $this->set('user', $user);
    }

    public function loadMoreSearch() {

//        $con['Users.is_verify'] = 1;
        //$con['Users.is_discovery'] = 1;
        $con['role_id'] = 2;
        $con['Users.deleted'] = 0;
        $con['Users.role_id'] = 2;
        $con['status'] = 1;
        $con['is_new'] = 0;

        $exclude_user = $this->LikeDislikes->find('all', [
                    'conditions' => [
                        'OR' => [
                            [
                                'user_id' => $this->Auth->User('id'),
                                'status IN' => [1, 2]
                            ],
                            [
                                'user_id' => $this->Auth->User('id'),
                                'status IN' => [3],
                                'date(created)' => date('Y-m-d')
                            ],
                        ],
                    ]
                ])->all();
        $exclude = array();
        array_push($exclude, $this->Auth->User('id'));
        foreach ($exclude_user as $aa) {
            array_push($exclude, $aa->to_user_id);
        }
        if (!empty($exclude)) {
            $con['Users.id NOT IN'] = $exclude;
        }

        if (isset($this->request->query['discover'])) {
            if ($this->request->query['discover'] == 1) {

                if (isset($this->request->query['fromValue'])) {
                    $con['YEAR(NOW())-YEAR(UserDetails.dob) >='] = $this->request->query['fromValue'];
                }

                if (isset($this->request->query['toValue'])) {
                    $con['YEAR(NOW())-YEAR(UserDetails.dob) <='] = $this->request->query['toValue'];
                }
                if (isset($this->request->query['gender'])) {
                    $con['UserDetails.gender  IN'] = $this->request->query['gender'];
                }

                if (isset($this->request->query['verified'])) {
                    $con['Users.is_verify'] = $this->request->query['verified'];
                }

                if (isset($this->request->query['keyword'])) {
                    if ($this->request->query['keyword'] != "") {
                        $con['OR'] = array(
                            //'Users.name LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.address LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.nike_name LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.about_me LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.dob LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.camera LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.mobile LIKE' => '%' . $this->request->query['keyword'] . '%',
                            'UserDetails.phone LIKE' => '%' . $this->request->query['keyword'] . '%'
                        );
                    }
                }
            }
        }

        $this->paginate = [
            'conditions' => $con,
            'contain' => ['UserDetails' => [ 'fields' => ['age' => 'YEAR(NOW())-YEAR(UserDetails.dob)']]],
            'order' => ['Users.id' => 'desc'], // 
            'fields' => ['Users.is_online', 'Users.user_type', 'Users.is_discovery', 'Users.id', 'Users.name', 'UserDetails.dob', 'UserDetails.address', 'UserDetails.dob', 'UserDetails.nike_name', 'UserDetails.profile_image'],
            'limit' => 10,
            'page' => $this->request->query['nextPage']
        ];
        $Users = $this->paginate($this->Users);

        $this->set('Users', $Users);

        $this->render('/Users/Ajax/search');
    }

    public function explore() {

        if ($this->request->session()->read('Auth.User.role_id') != 2) {
            $this->Flash->error('You are not authorized to access this location.');
            $this->redirect(array('action' => 'myProfile'));
        }
        $con = array(
            'Users.id != ' . $this->Auth->User('id'),
        );
        $con['is_new'] = 0;
        if (isset($this->request->query['discover']) && $this->request->query['discover']) {
            if (isset($this->request->query['fromValue'])) {
                $con['YEAR(NOW())-YEAR(UserDetails.dob) >='] = $this->request->query['fromValue'];
            }
            if (isset($this->request->query['toValue'])) {
                $con['YEAR(NOW())-YEAR(UserDetails.dob) <='] = $this->request->query['toValue'];
            }
            if (isset($this->request->query['gender'])) {
                $con['UserDetails.gender IN'] = $this->request->query['gender'];
            }
            if (isset($this->request->query['verified'])) {
                $con['Users.is_verify'] = $this->request->query['verified'];
            }
            if (isset($this->request->query['keyword']) && $this->request->query['keyword'] != '') {
                $con['OR'] = array(
                    'Users.name LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.address LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.nike_name LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.about_me LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.dob LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.camera LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.mobile LIKE' => '%' . $this->request->query['keyword'] . '%',
                    'UserDetails.phone LIKE' => '%' . $this->request->query['keyword'] . '%'
                );
            }
        }

        if ($this->request->is('ajax')) {
            if (isset($this->request->query['to_user_id']) && ($this->request->query['to_user_id'] != NULL)) {
                $LikeDislike = $this->LikeDislikes->find('all', [
                            'conditions' => [
                                'to_user_id' => $this->request->query['to_user_id'],
                                'user_id' => $this->Auth->User('id')
                            ]
                                ]
                        )->count();
                if ($LikeDislike > 0) {
                    $res = $this->LikeDislikes->updateAll(
                            [
                        'status' => $this->request->query['status'],
                        'created' => date('Y-m-d H:i:s')
                            ], [
                        'to_user_id' => $this->request->query['to_user_id'],
                        'user_id' => $this->Auth->User('id')
                            ]
                    );
                } else {
                    $LikeDislike = $this->LikeDislikes->newEntity();
                    $uparr = array('LikeDislikes' => array('status' => $this->request->query['status'],
                            'user_id' => $this->Auth->User('id'),
                            'to_user_id' => $this->request->query['to_user_id']
                    ));
                    $LikeDislike = $this->LikeDislikes->patchEntity($LikeDislike, $uparr);
                    $LikeDislike = $this->LikeDislikes->save($LikeDislike);
                }

                $MutualLikeDislikes = $this->LikeDislikes->find('all', [
                            'conditions' => [
                                'OR' => [
                                            [
                                                'user_id' => $this->Auth->User('id'),
                                                'to_user_id' => $this->request->query['to_user_id'],
                                                'status' => 1
                                            ],
                                            [
                                                'user_id' => $this->request->query['to_user_id'],
                                                'to_user_id' => $this->Auth->User('id'),
                                                'status' => 1
                                            ],
                                ],
                            ]]
                        )->count();
                if ($MutualLikeDislikes == 2) {
                    $this->loadModel('ActiveChats');
                    $ActiveChat = $this->ActiveChats->newEntity();
                    $uparr = array('ActiveChats' => array(
                            'user_id' => $this->Auth->User('id'),
                            'to_user_id' => $this->request->query['to_user_id'],
                    ));
                    $ActiveChat = $this->ActiveChats->patchEntity($ActiveChat, $uparr);
                    if ($this->ActiveChats->save($ActiveChat)) {
                        $ActivefriendChat = $this->ActiveChats->newEntity();
                        $uparr = array('ActiveChats' => array(
                                'to_user_id' => $this->Auth->User('id'),
                                'user_id' => $this->request->query['to_user_id'],
                        ));
                        $ActivefriendChat = $this->ActiveChats->patchEntity($ActivefriendChat, $uparr);
                        $this->ActiveChats->save($ActivefriendChat);
                    }
                }
            }
        }

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'AlbumImages', 'UserTags'], 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();

        $gender = explode(',', $user['user_detail']['interested_in_gender']);

        $exclude_user = $this->LikeDislikes->find('all', [
                    'conditions' => [
                        'OR' => [
                            [
                                'user_id' => $this->Auth->User('id'),
                                'status IN' => [1, 2]
                            ],
                            [
                                'user_id' => $this->Auth->User('id'),
                                'status IN' => [3],
                                'date(created)' => date('Y-m-d')
                            ],
                        ],
                    ]
                ])->all();
        $exclude = array(0);
        foreach ($exclude_user as $aa) {
            array_push($exclude, $aa->to_user_id);
        }

        $con['role_id'] = 2;
        $con['Users.id NOT IN'] = $exclude;
        $con['deleted'] = 0;
        $con['status'] = 1;
        $data = $this->Users->find('all', [
                    'contain' => [
                        'UserDetails',
                        'AlbumImages'
                    ],
                    'conditions' => $con,
                    'order' => ['Users.id' => 'desc']
                ])->first();

        $this->set(compact('user', 'data', 'like_users'));

        if ($this->request->is('ajax')) {
            $this->render('/Element/explore');
        }
    }

    public function getuserdetail() {
        
        $user_id = $this->request->query['user_id'];
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $user_id],
                                           'contain' => ['UserDetails', 'Albums', 'UserTags', 'Albums.AlbumImages'],
                                           ])->first();
        //if (($this->request->query['user_id']) && ($this->Auth->User('is_ghost') != 1) && $user['role_id'] == 2) { 
        //    $this->sendAllNotification($user_id, $user);        
        //}    

        $language = $this->Languages->find('list');
        $this->set(compact('user', 'language'));
        echo $this->render('/Element/view_album');
        die;
    }
    
    function sendProfileVisitNotification(){
        $user_id = $this->request->query['user_id'];
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $user_id],
                                           'contain' => ['UserDetails'],
                                           ])->first();
        if (($this->request->query['user_id']) && ($this->Auth->User('is_ghost') != 1) && $user['role_id'] == 2) { 
            $this->sendAllNotification($user_id, $user);        
        }
        die;
    }

    function sendAllNotification($user_id, $user) {
        
        if($user_id != $this->Auth->User('id')){
            #sending notification
            $con = array(
                'from_user_id' => $this->Auth->User('id'),
                'user_id' => $user_id,
                'notification_type' => '1'
            );
            $Notification = $this->Notifications->find('all', [
                        'conditions' => $con,
                        'order' => 'id DESC']
                    )->first();
            if (!empty($Notification)) {
                $dt = strtotime($Notification->created);
                $new_date = date("Y-m-d h:i:s", strtotime("+1 day", $dt));
            }       
            
            if (empty($Notification)|| (!empty($Notification) && $new_date < date("Y-m-d h:i:s"))) {
                $con['message'] = 'Profile Visited';
                $con['sender_name'] = $this->Auth->User('name');
                $con['sender_email'] = $this->Auth->User('email');
                $Notification = $this->Notifications->newEntity();
                                      
                if ($user['user_type'] == 1) {
                    $url = '';
                    $sms_link = '';
                } else {
                    $url1 = Router::url(['controller' => 'users', 'action' => 'profile', $this->Common->encrypt($this->Auth->User('id'))], true);
                    $url = "Click here to see who he/she is:<br/><br/><a href='" . $url1 . "'>Click Here</a>";
                    $sms_link = " Get profile details here - " . $this->bitly_v3_shorten($url1);
                }
                
                #sending email notification   
                $user['TEXT'] = $url;
                $mailstatus = $this->sendUserVisitEmail($user);
                if($mailstatus == true) $con['is_email_send'] = 1;
    
                #Sending SMS Notification
                $sms = Configure::read('sms.profile_visit');
                $sms = str_replace('[name]', $this->Auth->User('name'), $sms);
                $sms = str_replace('[link]', $sms_link, $sms);
                $mobile =  isset($user['user_detail']['mobile']) ? $user['user_detail']['mobile'] : '';
                $mobile_country_code = isset($user['user_detail']['mobile_country_code']) ? $user['user_detail']['mobile_country_code'] : '';  
                if(($mobile != '') && ($mobile_country_code != '')){
                    $sms_status = $this->sendMessage($mobile, $sms, $mobile_country_code);
                    if($sms_status == '1'){
                        $con['is_sms_send'] = 1;
                    }
                }
                            
                $uparr = array('Notifications' => $con);
                $Notifications = $this->Notifications->patchEntity($Notification, $uparr);
                $this->Notifications->save($Notifications);
            }
        }
    }

    function photographer() {

        if (!($this->Auth->User('role_id') == 2) || (($this->Auth->User('role_id') == 2) && (in_array($this->Auth->User('user_type'), array(1, 2, 3))))) {
            $this->Flash->error('You are not authorized to access this location.');
            $this->redirect(array('action' => 'myProfile'));
        }
        $user = $this->Users->find('all', [
                    'contain' => ['UserDetails', 'AlbumImages', 'UserTags', 'Albums.AlbumImages'],
                    'conditions' => [
                        'Users.id' => $this->Auth->User('id')
                    ]]
                )->first();
        $this->set('user', $user);
    }

    function loadMoreSearchPhotographer() {

        $con['Users.is_verify'] = 1;
        //$con['Users.is_discovery'] = 1;
        $con['Users.deleted'] = 0;
        $con['Users.role_id'] = 3;

        $this->paginate = [
            'conditions' => $con,
            'contain' => ['UserDetails' => ['fields' => ['age' => 'YEAR(NOW())-YEAR(UserDetails.dob)']]],
            'order' => ['Users.id' => 'desc'], // 
            'fields' => ['Users.user_type', 'Users.id', 'Users.name', 'UserDetails.dob', 'UserDetails.address', 'UserDetails.dob', 'UserDetails.nike_name', 'UserDetails.profile_image'],
            'limit' => 10,
            'page' => $this->request->query['nextPage']
        ];
        $Users = $this->paginate($this->Users);

        $this->set('Users', $Users);

        $this->render('/Users/Ajax/photographer');
    }

    function photographerProfile($id = NULL) {
        if (($id == '') || (!$this->Users->exists(['id' => $id]))) {
            $this->Flash->error('Profile Not exist!');
            return $this->redirect(['action' => 'my_profile']);
        }
        if (!($this->Auth->User('role_id') == 2) || (($this->Auth->User('role_id') == 2) && (in_array($this->Auth->User('user_type'), array(1, 2, 3))))) {
            $this->Flash->error('You are not authorized to access this location.');
            $this->redirect(array('action' => 'myProfile'));
        }

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'AlbumImages', 'Albums.AlbumImages'], 'conditions' => ['Users.id' => $id]])->first();
        if ($user->role_id == 2) {
            $this->Flash->error('Profile Not exist!');
            return $this->redirect(['action' => 'my_profile']);
        }
        if (isset($user->user_detail->dob)) {
            if ($user->user_detail->dob != '')
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        }
        $type = 'other';
        $this->set(compact('user', 'type'));
        //$this->render('my_profile');
    }

    function photographerMyProfile() {
        if (($this->Auth->User('role_id') != 3)) {
            $this->Flash->error('You are not authorized to access this location.');
            $this->redirect(array('action' => 'myProfile'));
        }

        $user = $this->Users->find('all', ['contain' => ['UserDetails', 'AlbumImages', 'Albums.AlbumImages'], 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();
        if (isset($user->user_detail->dob)) {
            if ($user->user_detail->dob != '')
                $user->user_detail->dob = date(Configure::read('Site.CakeDateFormatForView'), strtotime($user->user_detail->dob));
        }
        $type = 'me';
        //die('aaaaa');
        $this->set(compact('user', 'type'));
        $this->render('photographer_profile');
    }

    function activatechat() {
        //pr($this->request->query);
        $this->loadModel('ActiveChats');
        if (isset($this->request->query['to_user_id'])) {
            $ActiveChat = $this->ActiveChats->find('all', [
                        'conditions' => [
                            'to_user_id' => $this->request->query['to_user_id'],
                            'user_id' => $this->Auth->User('id')
                        ]]
                    )->count();

            if ($ActiveChat == 0) {
                $ActiveChat = $this->ActiveChats->newEntity();
                $uparr = array('ActiveChats' => array(
                        'user_id' => $this->Auth->User('id'),
                        'to_user_id' => $this->request->query['to_user_id'],
                ));
                $ActiveChat = $this->ActiveChats->patchEntity($ActiveChat, $uparr);
                if ($this->ActiveChats->save($ActiveChat)) {
                    $ActivefriendChat = $this->ActiveChats->newEntity();
                    $uparr = array('ActiveChats' => array(
                            'to_user_id' => $this->Auth->User('id'),
                            'user_id' => $this->request->query['to_user_id'],
                    ));
                    $ActivefriendChat = $this->ActiveChats->patchEntity($ActivefriendChat, $uparr);
                    $this->ActiveChats->save($ActivefriendChat);

                    //$this->set('activeUser', $activeUser );
                    //$this->render('/Users/ajax/activatechat');
                }
            }
        }
        die;
    }

    function chatcontent() {
      
        $this->viewBuilder()->layout('');
        $this->loadModel('ActiveChats');

        $ActiveChats = $this->ActiveChats->find('all', [
                    'conditions' => ['ActiveChats.user_id' => $this->Auth->User('id'), 'is_deleted' => 0, 'Users.status' => 1, 'Users.deleted' => '0'],
                    'contain' => ['Users' => ['UserDetails' => [ 'fields' => [
                                    'age' => 'YEAR(NOW())-YEAR(UserDetails.dob)',
                                    'profile_image' => 'UserDetails.profile_image',
                                    'nike_name' => 'UserDetails.nike_name',
                                ]]],
                                 ],
                    'fields' => ['Users.name', 'Users.role_id', 'Users.id', 'ActiveChats.last_message', 'ActiveChats.is_request', 'Users.is_online', 'ActiveChats.to_user_id', 'ActiveChats.unread_messages'],
                    'order' => 'ActiveChats.id DESC'   
                ])->toArray();
        
        
        $loginUserdata = $this->UserDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')], 'fields' => 'profile_image'])->first()->toArray();
        $this->set(compact('ActiveChats', 'loginUserdata'));
        $this->render('Ajax/chatcontent');
    }

    function getuserchatmessages($page = 1) {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->layout('');
            if (isset($this->request->query['to_user_id'])) {

                $page = $this->request->query['page_num'];
                $this->loadModel('ChatMessages');
                
                #marking messages read
                $resss = $this->ActiveChats->UpdateAll(['unread_messages' => '0'], ['user_id' => $this->Auth->User('id'), 'to_user_id' => $this->request->query['to_user_id']]);
                
                $this->paginate = array(
                    'conditions' => [
                        'OR' => [
                            [
                                'ChatMessages.user_id' => $this->Auth->User('id'),
                                'ChatMessages.to_user_id' => $this->request->query['to_user_id']
                            ],
                            [
                                'ChatMessages.user_id' => $this->request->query['to_user_id'],
                                'ChatMessages.to_user_id' => $this->Auth->User('id')
                            ]
                        ],
                    ],
                    'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10,
                    'order' => ['id' => 'DESC'],
                    'page' => $page,
                );

                $ChatMessages = array_reverse($this->paginate('ChatMessages')->toArray());

                $to_user_detail = $this->Users->find('all', [
                            'contain' => 'UserDetails',
                            'conditions' => ['Users.id' => $this->request->query['to_user_id']],
                            'fields' => ['profile_image' => 'UserDetails.profile_image',]
                        ])->first()->toArray();

                $loginUserdata = $this->UserDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')], 'fields' => 'profile_image'])->first()->toArray();
                
                
                
                $this->set(compact('ChatMessages', 'to_user_detail', 'loginUserdata'));
                
                $this->request->session()->write('TO_USER_ID', $this->request->query['to_user_id']); //FOR CHAT
                $this->render('Ajax/get_userchat_messages');
            }
        }
    }

    function getchatheaduserdetail() {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->layout('');
            $to_user_detail = $this->Users->find('all', [
                        'contain' => 'UserDetails',
                        'conditions' => ['Users.id' => $this->request->query['to_user_id']],
                        'fields' => [
                            'Users.id',
                            'profile_image' => 'UserDetails.profile_image',
                            'nike_name' => 'UserDetails.nike_name',
                            'address' => 'UserDetails.address',
                            'about_me' => 'UserDetails.about_me',
                            'name' => 'Users.name',
                            'role' => 'Users.role_id'
                            ]
                    ])->first()->toArray();

            //$loginUserdata = $this->UserDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')], 'fields' => 'profile_image'])->first()->toArray();
            $this->set(compact('to_user_detail'));
            $this->render('Ajax/get_chathead_user_detail');
        }
    }

    public function liveChat($user_id = NULL, $page=1) {
		
		$this->viewBuilder()->layout('chat');
        if($user_id != NULL){
            $this->request->session()->write('TO_USER_ID', $user_id); //FOR CHAT
        }else{
            
        }
        
        $this->loadModel('ChatMessages');
        $this->paginate = array(
            'conditions' => [
                'OR' => [
                    [
                        'ChatMessages.user_id' => $this->Auth->User('id'),
                        'ChatMessages.to_user_id' => $user_id
                    ],
                    [
                        'ChatMessages.user_id' => $user_id,
                        'ChatMessages.to_user_id' => $this->Auth->User('id')
                    ]
                ],
            ],
            'limit' => isset($SiteSettingsTbl->per_page_limit) ? $SiteSettingsTbl->per_page_limit : 10,
            'order' => ['id' => 'DESC'],
            'page' => $page,
        );
        
        $to_user_detail = $this->Users->find('all', [
                            'contain' => 'UserDetails',
                            'conditions' => ['Users.id' => $user_id],
                            'fields' => ['profile_image' => 'UserDetails.profile_image',]
                        ])->first();

        $loginUserdata = $this->UserDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')], 'fields' => 'profile_image'])->first()->toArray();

        $ChatMessageslist = array_reverse($this->paginate('ChatMessages')->toArray());
        $this->set(compact('ChatMessageslist', 'page', 'to_user_detail', 'loginUserdata','user_id'));
		
		
    }

    function referFriend() {

        if (($this->request->query['friend_name'] == '') || ($this->request->query['friend_email'] == '')) {
            die(0);
        } else {
            $user_id = $this->request->query['suggested_user_id'];

            $frienduser = $this->Users->find('all', ['conditions' => ['email' => $this->request->query['friend_email']], 'fields' => ['Users.id']])->first();
            if (!empty($frienduser)) {
                $con = array();

                $con['user_id'] = $frienduser->id;
                $con['referer_user_id'] = $user_id;
                $con['message'] = 'Suggested A Friend';
                $con['sender_name'] = $this->Auth->User('name');
                $con['sender_email'] = $this->Auth->User('email');
                $con['from_user_id'] = $this->Auth->User('id');
                $con['notification_type'] = '2';
                $Notification = $this->Notifications->newEntity();
                $uparr = array('Notifications' => $con);
                $Notifications = $this->Notifications->patchEntity($Notification, $uparr);
                if ($this->Notifications->save($Notifications))
                    echo '';
            }

            $user = $this->Users->find('all', ['conditions' => ['id' => $user_id], 'fields' => ['Users.id', 'Users.name', 'Users.email']])->first()->toArray();

            $url1 = Router::url(['controller' => 'users', 'action' => 'profile', $this->Common->encrypt($user_id)], true);
            $url = "<a href='" . $url1 . "'>Click Here</a>: $url1";

            $user['link'] = $url;
            //die;
            $mailstatus = $this->sendSuggestAFriendEmail($user, $this->request->query['friend_name'], $this->request->query['friend_email']);
            if ($mailstatus)
                echo 1;
            else
                echo 0;
            die;
        }
    }

    function setGhost() {
        if ($this->request->is('ajax')) {
            if ($this->request->query['status'] != '') {
                //pr($this->request->query);
                if ($this->request->query['status'] == 1) {
                    if ($this->Auth->User('user_type') != 4) {
                        echo 'purchase';
                        die;
                    }
                }
                if ($this->Users->UpdateAll(['is_ghost' => $this->request->query['status']], ['id' => $this->Auth->User('id')])) {
                    $this->request->session()->write('Auth.User.is_ghost', $this->request->query['status']);
                    echo 'done';
                } else {
                    echo 'not';
                }
            }
        }
        die;
    }

    public function sendOtp() {
        $users = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['UserDetails']
        ]);
        
        $mobiledetail = $this->UserDetails->find('all', ['conditions' => ['user_id !=' => $this->Auth->User('id'), 'mobile' => $this->request->query['mobile'], 'mobile_country_code' => $this->request->query['countrycode']]])->first();
        
        if(!empty($mobiledetail)){
            $response['status'] = 0;
            $response['message'] = 'Mobile number already registered.';
        }else{
        
            $response = array();
            $response['status'] = 0;
            $OtpCode = $this->Common->generateOtpCode('6');
    
            $user['mobile_otp'] = $OtpCode;
            $user['user_detail']['mobile'] = $this->request->query['mobile'];
            $user['user_detail']['mobile_country_code'] = $this->request->query['countrycode'];
    
            $User = $this->Users->patchEntity($users, $user);
            $this->Users->save($User);
    
            $sid = Configure::read('twilio.sid');  
            $token =Configure::read('twilio.token');   
            require ROOT . DS . 'vendor' . DS . 'twilio' . DS . 'sdk' . DS . 'Twilio' . DS . 'Rest' . DS . 'Client.php';
            $client = new \Twilio\Rest\Client($sid, $token);
            try {
                 $client->messages->create(
                        '+' .$this->request->query['countrycode']. $this->request->query['mobile'], // Text this number
                        array(
                    'from' => Configure::read('twilio.fromNumber'),
                    'body' => 'Your OTP code For sweedy is ' . $OtpCode
                        )
                );
                $response['status'] = 1;
            } catch (\Exception $e) {
                $response['status'] = 0;
    //            $response['message'] = $e->getMessage();
                $response['message'] = 'Could not send OTP.';
            }
        }
        echo json_encode($response);
        die;
    }

    public function checkOtp() {
        $response = array();
        $response['status'] = 0;
        $users = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['UserDetails']
        ]);
        if ($users['mobile_otp'] == $this->request->query['otpcode']) {
            $user['is_verify_mobile'] = 1;
           
         
            $User = $this->Users->patchEntity($users, $user);
            $this->Users->save($User);
              $this->request->session()->write('Auth.User.is_verify_mobile',1);
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
            $response['message'] = 'Code not match.please try again.';
        }

        echo json_encode($response);
        die;
    }

    function sendMessage($number, $message, $mobile_country_code = 0) {

        //echo $number .' - '. $message;
        $mobile_country_code = ($mobile_country_code == 0) ? '91' : $mobile_country_code;
         $sid = Configure::read('twilio.sid');  
        $token =Configure::read('twilio.token');   
        require ROOT . DS . 'vendor' . DS . 'twilio' . DS . 'sdk' . DS . 'Twilio' . DS . 'Rest' . DS . 'Client.php';
        $client = new \Twilio\Rest\Client($sid, $token);

        try {
            $message = $client->messages->create(
                    '+'. $mobile_country_code . $number,  
                    array(
             'from' => Configure::read('twilio.fromNumber'),  
                'body' => $message
                    )
            );            
            if(!empty($message)) return 1; else return 0; //die;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }       
    }

    public function bitly_v3_shorten($longUrl) {
        $x_login = 'kiplphp51';
        $x_apiKey = 'R_f831bade16c44f43860f0d107b630a0b';
        $x_bitly_api = 'http://api.bit.ly/v3/';
        $result = array();

        $url = $x_bitly_api . "shorten?login=" . $x_login . "&apiKey=" . $x_apiKey . "&format=json&longUrl=" . urlencode($longUrl);

        if ($x_login != '' && $x_apiKey != '') {
            $url .= "&x_login=" . $x_login . "&x_apiKey=" . $x_apiKey;
        }

        $output_str = "";
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $output_str = curl_exec($ch);
        } catch (Exception $e) {
            //echo '<hr/>'.$url.'<hr/>';
        }
        $output = json_decode($output_str);
        if (isset($output->{'data'}->{'hash'})) {
            $result['url'] = $output->{'data'}->{'url'};
            $result['hash'] = $output->{'data'}->{'hash'};
            $result['global_hash'] = $output->{'data'}->{'global_hash'};
            $result['long_url'] = $output->{'data'}->{'long_url'};
            $result['new_hash'] = $output->{'data'}->{'new_hash'};
        }
        if (isset($result['url'])) {
            $bitly_url = $result['url'];
        } else {
            $bitly_url = '';
        }
        return $bitly_url;
    }

    function testsendMessage($number, $message) {
        $sid = "AC791f575145985dc163fa3fb96b8d9d6c"; // Your Account SID from www.twilio.com/console
        $token = "e4dab70ad21a534c4ae4d8720d036f51"; // Your Auth Token from www.twilio.com/console twilio\sdk\Twilio\Rest
        require ROOT . DS . 'vendor' . DS . 'twilio' . DS . 'sdk' . DS . 'Twilio' . DS . 'Rest' . DS . 'Client.php';
        $client = new \Twilio\Rest\Client($sid, $token);

        try {
//            $ call = $client->calls->create(
//  '+917014590557', // Call this number
//  '+12052365129', // From a valid Twilio number
//  array(
//      'url' => 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient'
//  )
//);
//pr($call); die;
            $message = $client->messages->create(
                    '+917793057779', // Text this number
                    array(
                'from' => '+12052365129', // From a valid Twilio number
                'body' => 'hello manish'
                    )
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }


        die;
        // print $message->sid;
    }
    
    function startChat(){
        
        $this->loadModel('ActiveChats');
        if($this->Auth->User('id') != $this->request->query['to_user_id']){
            $dataA = $this->ActiveChats->find('all', ['conditions' => [
                                                                    'OR' => [
                                                                                [
                                                                                    'user_id' => $this->Auth->User('id'),
                                                                                    'to_user_id' => $this->request->query['to_user_id']
                                                                                ],
                                                                                [
                                                                                    'to_user_id' => $this->Auth->User('id'),
                                                                                    'user_id' => $this->request->query['to_user_id']
                                                                                ]
                                                                            ]
                                                                       
                                                                    ]
                                                      ])->count();
           
            if($dataA == 0){
                $ActiveChat = $this->ActiveChats->newEntity();
                $uparr = array('ActiveChats' => array(
                        'user_id' => $this->Auth->User('id'),
                        'to_user_id' => $this->request->query['to_user_id'],
                        'is_request' => 0
                ));
                $ActiveChat = $this->ActiveChats->patchEntity($ActiveChat, $uparr);
                if ($this->ActiveChats->save($ActiveChat)) {
                    $ActivefriendChat = $this->ActiveChats->newEntity();
                    $uparr = array('ActiveChats' => array(
                            'to_user_id' => $this->Auth->User('id'),
                            'user_id' => $this->request->query['to_user_id'],
                            'is_request' => 0
                    ));
                    $ActivefriendChat = $this->ActiveChats->patchEntity($ActivefriendChat, $uparr);
                    $this->ActiveChats->save($ActivefriendChat);
                }
                echo 1; die;
                
            }else if($dataA == 2){
                echo 1; die;
            }
        }
        echo 0; die;
        
    }

}
