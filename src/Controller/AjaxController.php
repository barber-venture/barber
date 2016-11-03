<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\Utility\Xml;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Financial\Financial;
use Cake\Routing\Router;
use Cake\Filesystem\File;
use Cake\Utility\Hash;
use Cake\I18n\Date;

/**
 * Ajax Controller
 * 
 */
class AjaxController extends AppController {

    public function initialize() {


        parent::initialize();

        $this->loadComponent('Common');
        $this->loadModel('SystemMails');
        $this->loadModel('Countries');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Users');
        $this->loadModel('UserDetails');
        $this->loadModel('Plans');
        $this->loadModel('Supports');
        $this->loadModel('SupportReplays');
        $this->loadModel('Albums');
        $this->loadModel('AlbumImages');
        $this->loadModel('LikeDislikes');
    }

    public function beforeFilter(\Cake\Event\Event $event) {

        parent::beforeFilter($event);
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;
        $this->Auth->allow(['checkEmailAvailibility', 'postSendMail', 'contactUsCheckEmailAvailibility']);
        $this->eventManager()->off($this->Csrf);
    }

    public function getUserDetail($id = null) {
        $Users = $this->Users->get($id, [
            'contain' => ['UserDetails']
        ]);

        $this->set(compact('Users'));
        $this->render('/Admin/Ajax/userdetail');
    }

    public function getPlanDetail($id = null) {
        $PlanDuration = Configure::read('PlanDuration');
        $Plans = $this->Plans->get($id);
        $this->set(compact('Plans', 'PlanDuration'));
        $this->render('/Admin/Ajax/plandetail');
    }

    function checkEmailAvailibility($email = null) {
        $emailAvailable = $this->Users->find('all', ['conditions' => ['Users.email' => $_GET['email']]])->first();
        if ($emailAvailable != "") {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }

    function contactUsCheckEmailAvailibility($email = null) {
        $emailAvailable = $this->Users->find('all', ['conditions' => ['Users.email' => $_GET['email']]])->first();
        if ($emailAvailable != "") {
            echo 'true';
        } else {
            echo 'false';
        }
        exit;
    }

    function getStateFromCountry($countryId) {
        if ($countryId != '') {
            $states = $this->States->find('list', ['conditions' => ['States.country_id' => $countryId]])->toArray();
            $option = "";
            foreach ($states as $key => $sk) {
                $option.='<option value="' . $key . '">' . $sk . '</option>';
            }
            echo $option;
        }
    }

    function getCitiesFromState($stateId) {
        if ($stateId != '') {
            $states = $this->Cities->find('list', ['conditions' => ['Cities.state_id' => $stateId]])->toArray();
            $option = "";
            foreach ($states as $key => $sk) {
                $option.='<option value="' . $key . '">' . $sk . '</option>';
            }
            echo $option;
        }
    }

    public function getSendMail($id = null) {
        $support = $this->Supports->get($id, [
            'contain' => []
        ]);
        $SupportReplays = $this->SupportReplays->newEntity();

        $this->set('_serialize', ['support']);
        $this->set(compact('support', 'SupportReplays'));
        $this->render('/Admin/Ajax/getsendmail');
    }

    public function postSendMail($id = null) {
        $attachmentFilePath = Configure::read('Site.attachment');
        $support = $this->Supports->get($this->request->data['support_id'], [
            'contain' => []
        ]);

        $SupportReplays = $this->SupportReplays->newEntity();
        $attachments = array();

        if (!empty($this->request->data['attachment']['name'])) {
            $attachment = $this->request->data['attachment']; //put the data into a var for easy use 
            $file_name = $attachment['name'];
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $name = pathinfo($file_name, PATHINFO_FILENAME);

            $attachment_newname = 'attachment_' . time() . "_" . rand(000000, 999999) . '.' . $ext;
            $Profile_path = $attachmentFilePath . $attachment_newname;
            move_uploaded_file($attachment['tmp_name'], $Profile_path);

            $attachments = [
                pathinfo($Profile_path, PATHINFO_BASENAME) => [
                    'file' => $Profile_path,
                    'mimetype' => $this->request->data['attachment']['type'],
                ]
            ];
        }

        $SupportMail = $this->SystemMails->findByEmailType('Support')->first()->toArray();
        $SupportMail['message'] = $this->request->data['message'];
        $SupportMail['subject'] = $this->request->data['subject'];
        $SupportMail['to'] = $support->email;
        if ($this->sendEmailWithAttachments($SupportMail, $attachments)) {
            if (isset($attachment_newname)) {
                $this->request->data['attachment'] = $attachment_newname;
            }
            $SupportReplays = $this->SupportReplays->patchEntity($SupportReplays, $this->request->data);
            $this->SupportReplays->save($SupportReplays);
            $this->Flash->success(__('Your message successfully sent.'));
            echo "succes";
            exit();
        }
        echo "error";
        exit();
    }

    public function updateProfile($id = NULL) {
        
      
         
        if ($this->request->session()->read('Auth.User.is_new') == 1) {
            $res = $this->Users->updateAll(
                    ['is_new' => 0], ['id' => $this->Auth->user('id')]
            );
           
            $this->request->session()->write('Auth.User.is_new', '0');
        }

        if ($id) {
            $UserDetails = $this->UserDetails->get($id);
        } else {
            $UserDetails = $this->UserDetails->newEntity();
        }

        if ($this->request->is('ajax')) {

            if (isset($this->request->data['user_tags']) && !empty($this->request->data['user_tags'])) {

                $user = $this->Users->find('all', ['contain' => ['UserTags'], 'conditions' => ['Users.id' => $this->Auth->user('id')]])->first();
                $user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserTags']]);
                if ($this->Users->save($user))
                    echo 1;
                else
                    echo 0;
                die;
            }
            //First time user details
            $UserDetails->user_id = $this->Auth->user('id');
            if (isset($this->request->data['gender']) && $this->request->data['gender'] > 0)
                $UserDetails->gender = $this->request->data['gender'];

//            if (isset($this->request->data['dob']) && ($this->request->data['dob'] != ''))
            if(isset($this->request->data['year']) && isset($this->request->data['month']) && isset($this->request->data['day']))
            $UserDetails->dob = date('Y-m-d', strtotime( $this->request->data['year'].'-'.$this->request->data['month'].'-' . $this->request->data['day']));
             
         

            if (isset($this->request->data['nike_name']) && ($this->request->data['nike_name'] != ''))
                $UserDetails->nike_name = $this->request->data['nike_name'];

            if (isset($this->request->data['mobile']) && ($this->request->data['mobile'] != ''))
                $UserDetails->mobile = $this->request->data['mobile'];

            //Edit profile data save  
            if (isset($this->request->data['about_me']) && ($this->request->data['about_me'] != ''))
                $UserDetails->about_me = $this->request->data['about_me'];

            if (isset($this->request->data['address']) && ($this->request->data['address'] != ''))
                $UserDetails->address = $this->request->data['address'];

            if (isset($this->request->data['camera']) && ($this->request->data['camera'] != ''))
                $UserDetails->camera = $this->request->data['camera'];

            if (!empty($this->request->data['interested_in_gender'])) {
                $interested_in_gender = implode(',', $this->request->data['interested_in_gender']);
                $UserDetails->interested_in_gender = $interested_in_gender;
            }

            if (isset($this->request->data['interested_in_age_from']) && ($this->request->data['interested_in_age_from'] != '') && ($this->request->data['interested_in_age_from'] > 0))
                $UserDetails->interested_in_age_from = $this->request->data['interested_in_age_from'];

            if (isset($this->request->data['interested_in_age_to']) && ($this->request->data['interested_in_age_to'] != '') && ($this->request->data['interested_in_age_to'] > 0))
                $UserDetails->interested_in_age_to = $this->request->data['interested_in_age_to'];

            if (!empty($this->request->data['interested_in_activity'])) {
                $interested_in_activity = implode(',', $this->request->data['interested_in_activity']);
                $UserDetails->interested_in_activity = $interested_in_activity;
            }

            if (isset($this->request->data['relationship_status']) && $this->request->data['relationship_status'] > 0)
                $UserDetails->relationship_status = $this->request->data['relationship_status'];

            if (isset($this->request->data['height']) && ($this->request->data['height'] != '') && ($this->request->data['height'] > 0))
                $UserDetails->height = $this->request->data['height'];

            if (isset($this->request->data['body_type']) && $this->request->data['body_type'] > 0)
                $UserDetails->body_type = $this->request->data['body_type'];

            if (isset($this->request->data['children']) && $this->request->data['children'] > 0)
                $UserDetails->children = $this->request->data['children'];

            if (isset($this->request->data['smoker']) && $this->request->data['smoker'] > 0)
                $UserDetails->smoker = $this->request->data['smoker'];

            if (isset($this->request->data['religion']) && $this->request->data['religion'] > 0)
                $UserDetails->religion = $this->request->data['religion'];

            if (isset($this->request->data['living_situation']) && $this->request->data['living_situation'] > 0)
                $UserDetails->living_situation = $this->request->data['living_situation'];

            if (isset($this->request->data['education']) && $this->request->data['education'] > 0)
                $UserDetails->education = $this->request->data['education'];

            if (isset($this->request->data['profession']) && $this->request->data['profession'] > 0)
                $UserDetails->profession = $this->request->data['profession'];

            if (!empty($this->request->data['language'])) {
                $language = implode(',', $this->request->data['language']);
                $UserDetails->language = $language;
            }
            //pr($UserDetails); die;

            $res = $this->UserDetails->save($UserDetails);
            if ($res)
                echo 1;
        }else {
            echo 0;
        }
        die;
    }

    public function contfirmEmail() {

        $user = $this->Users->get($this->Auth->user('id'));

        if (date("Y-m-d", strtotime($user['send_mail_date'])) != date('Y-m-d')) {

            $user['send_mail_count'] = 0;
        } else {
            $user['send_mail_count'] = $user['send_mail_count'] + 1;
        }

        if ($user['send_mail_count'] <= 5) {
            $activationKey = md5(uniqid());
            $user['activation_key'] = $activationKey;
            $user['send_mail_date'] = Time::parseDate(date('Y-m-d'), Configure::read('Site.CakeDateFormat'));
            $this->sendConfirmEmail($user, $activationKey);
            $this->Users->save($user);
            echo "succes";
        } else {
            echo "error";
        }
    }

    public function uploadimages() {
        $res = array();
        if ($this->request->is('Ajax')) {
            
            //pr($this->request->data); die;
            
            if (isset($this->request->data['uploadImage']['name']) && $this->request->data['uploadImage']['name'] != '') {
                #Getting album data
                
                $totaluserimagesquery = $this->AlbumImages->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')]]);
                $totaluserimages = $totaluserimagesquery->count();
                $album_id = $this->request->data['albumID'];
                
                $query = $this->AlbumImages->find('all', ['conditions' => ['album_id' => $album_id, 'user_id' => $this->Auth->User('id')]]);
                $AlbumImages = $query->count();
                $img_limit = Configure::read('Site.image_upload_limit_for_normal_user');
                
                if ($AlbumImages < $img_limit) {
                   
                    $user_image_file = $this->request->data['uploadImage']; //put the data into a var for easy use
                    $file_name = $user_image_file['name'];
                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $name = pathinfo($file_name, PATHINFO_FILENAME);
                    $new_imag_name = 'user_image_' . time() . "_" . rand(000000, 999999) . '.' . $ext;

                    $newAlbumImages = $this->AlbumImages->newEntity();
                    $newAlbumImages->user_id = $this->Auth->User('id');
                    $newAlbumImages->album_id = $album_id;
                    $newAlbumImages->image_name = $new_imag_name;
                    $newAlbumImages->image_title = $name;
                    if ($this->AlbumImages->save($newAlbumImages)) {
                        $userFilePath = Configure::read('Site.ProfileImage');
                        $user_image_path = $userFilePath . $new_imag_name;
                        $data = move_uploaded_file($user_image_file['tmp_name'], $user_image_path);
                        if ($data) {
                            $AlbumImages++;
                            $delete_up_icon = ($AlbumImages == $img_limit) ? 1 : 0;
                            $set_profile = ($totaluserimages == 0) ? 1 : 0;
                            $set_thumb = ($AlbumImages == 1) ? 1 : 0;
                            $res = json_encode(array('status' => '1', 'msg' => 'Image successfully uploaded.', 'delete_up_icon' => $delete_up_icon, 'img_id' => $newAlbumImages->id, 'img_name' => $new_imag_name, 'set_profile' => $set_profile, 'set_thumb' => $set_thumb));

                            if ($totaluserimages == 0)
                                $this->UserDetails->updateAll(['profile_image' => $new_imag_name], ['user_id' => $this->Auth->User('id')]);
                            
                        }
                        // die('aass');
                    }
                }else {
                    $res = json_encode(array('status' => '0', 'msg' => 'You are not allowed to upload more than ' . $img_limit . ' pictures.'));
                }
                //}
            } else {
                $res = json_encode(array('status' => '0', 'msg' => 'Unable to save image.'));
            }
            die($res);
        }
    }

    function removeimage() {

        $id = $this->request->data['id'];
        $res = array();
        if ($this->request->is('Ajax')) {

            if ($id) {
                //if($this->Auth->User('user_type') == 1){
                $userFilePath = Configure::read('Site.ProfileImage');
                $AlbumImages = $this->AlbumImages->findById($id)->first();
                $image_name = $AlbumImages->image_name;
                $query = $this->AlbumImages->query();

                if ($query->delete()->where(['id' => $id])->execute()) {
                    if (file_exists($userFilePath . $image_name)) {
                        $result = @unlink($userFilePath . $image_name);

                        //for making profile image
                        $UserDetails = $this->UserDetails->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')]])->first();
                        if ($UserDetails->profile_image == $image_name)
                            $img_id = $this->set_another_profile();
                        else
                            $img_id = '';
                        //For showing delete icon
                        $query = $this->AlbumImages->find('all', ['conditions' => ['album_id' => $this->request->data['album_id'], 'user_id' => $this->Auth->User('id')]]);
                        $AlbumImages = $query->count();
                        
                        $query1 = $this->AlbumImages->find('all', ['conditions' => ['user_id' => $this->Auth->User('id')]]);
                        $UserImages = $query1->count();
                        $delete_profile_img = ($UserImages  <= 0 ) ? 1 : 0;
                        
                        $img_limit = Configure::read('Site.image_upload_limit_for_normal_user');
                        $delete_up_icon = ($AlbumImages < $img_limit) ? 1 : 0;
                        $delete_thumb = ($AlbumImages  <= 0 ) ? 1 : 0;
                        if ($result)
                            $res = json_encode(array('status' => '1', 'delete_up_icon' => $delete_up_icon, 'img_id' => $img_id, 'delete_thumb' => $delete_thumb, 'delete_profile_img' => $delete_profile_img));
                        else
                            $res = json_encode(array('status' => '0'));
                    }
                }else {
                    $res = json_encode(array('status' => '0'));
                }
                //}
            }
        }
        die($res);
    }

    function set_another_profile() {
        $AlbumImages = $this->AlbumImages->find('all', ['order' => 'id DESC', 'conditions' => ['user_id' => $this->Auth->User('id')]])->first();
        $image_name = (empty($AlbumImages)) ? '' : $AlbumImages->image_name;
        $data = $this->UserDetails->updateAll(['profile_image' => $image_name], ['user_id' => $this->Auth->User('id')]);
        if ($image_name) {
            return $image_name;
        }
        return '';
    }

    function makeprofilepic() {
        $id = $this->request->data['id'];
        $res = array();
        if ($this->request->is('Ajax')) {
            if ($id) {
                $AlbumImages = $this->AlbumImages->findById($id)->first();
                $image_name = $AlbumImages->image_name;
                if ($image_name) {
                    $data = $this->UserDetails->updateAll(['profile_image' => $image_name], ['user_id' => $this->Auth->User('id')]);
                    if ($data)
                        $res = json_encode(array('status' => '1'));
                    else
                        $res = json_encode(array('status' => '0'));
                }
            }
        }
        die($res);
    }

    function checkcurrentpassword($email = null) {

        $user = $this->Users->find('all', ['contain' => 'UserDetails', 'conditions' => ['Users.id' => $this->Auth->User('id')]])->first();

        if ($this->Users->setPassword($_GET['current_password'], $user->password))
            echo 'true';
        else
            echo 'false';
        exit;
    }

    function checkUserEmailAvailibility($email = null) {
        $emailAvailable = $this->Users->find('all', ['conditions' => ['id !=' => $this->Auth->User('id'), 'Users.email' => $_GET['email']]])->first();
        if ($emailAvailable != "") {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }

    function likedislike() {

        if (isset($this->request->data['to_user_id']) && ($this->request->data['to_user_id'] != NULL)) {
            $LikeDislike = $this->LikeDislikes->find('all', [
                        'conditions' => [
                            'to_user_id' => $this->request->data['to_user_id'],
                            'user_id' => $this->Auth->User('id')
                        ]
                            ]
                    )->first();
            if (!empty($LikeDislike)) {
                $res = $this->LikeDislikes->updateAll(
                        ['status' => $this->request->data['status']], [
                    'to_user_id' => $this->request->data['to_user_id'],
                    'user_id' => $this->Auth->User('id')
                        ]
                );
                if ($res)
                    echo 1;
                else
                    echo 0;
            } else {
                $LikeDislike = $this->LikeDislikes->newEntity();
                $uparr = array('LikeDislikes' => array('status' => $this->request->data['status'],
                        'user_id' => $this->Auth->User('id'),
                        'to_user_id' => $this->request->data['to_user_id']
                ));
                $LikeDislike = $this->LikeDislikes->patchEntity($LikeDislike, $uparr);
                if ($this->LikeDislikes->save($LikeDislike))
                    echo 1;
                else
                    echo 0;
            }
        }
        die;
    }

    public function useralbumpopup($albumID) {

//          $Album = $this->Albums->get($albumID, [
//            'contain' => ['AlbumImages']
//        ]);
//          
        $user = $this->Users->find('all', ['contain' => ['UserDetails'], 'conditions' => ['Users.id' => $this->Auth->user('id')]])->first();
        $Album = $this->AlbumImages->find('all', [
                    'conditions' => [
                        'album_id' => $albumID,
                        'user_id' => $this->Auth->User('id')
                    ]
                        ]
                )->toArray();

        $this->set(compact('user', 'Album', 'albumID'));
        $this->render('/Element/popups/edit_profile_picture');
    }

    public function createalbum() {
        $this->render('/Element/popups/create_album');
    }

    public function createalbumpost() {
        $response = array();
        $Albums = $this->Albums->newEntity();

        $this->request->data['user_id'] = $this->Auth->User('id');
        $this->request->data['created'] =  new Time(date('Y-m-d H:i:s'));
                 
        $Album = $this->Albums->patchEntity($Albums, $this->request->data);
                
        if ($this->Albums->save($Album)) {
            $response['status'] = 1;
            $response['id'] = $Album['id'];
        } else {
            $response['status'] = 0;
           
        }
        
       echo json_encode($response);
        die;
    }
    
    function unlatchUser(){
        $this->loadModel('ActiveChats');
        if(isset($this->request->query['id']) && !empty($this->request->query['id'])){
            $res = $this->ActiveChats->updateAll(
                  ['is_deleted' => 1], ['user_id' => $this->Auth->user('id'), 'to_user_id' => $this->request->query['id']]
            );
            if($res) echo 1; else echo 0; die;
        }
    }

}
