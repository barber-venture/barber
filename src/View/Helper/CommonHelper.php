<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\I18n\Number;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

/**
 * Common helper
 */
class CommonHelper extends Helper {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public $helpers = ['Html', 'Session', 'Boot'];

    /*
     * Load Js class on page
     * 
     */

    function getLoginFacebookUrl($varification = 0) {

        if (!class_exists('Facebook')) {
            require ROOT . DS . 'vendor' . DS . 'Facebook' . DS . 'Facebook.php';
        }
        $facebook = new \Facebook(array(
            'appId' => Configure::read('Facebook.appId'),
            'secret' => Configure::read('Facebook.secret'),
            'cookie' => true,
        ));
        $action = ($varification == 1) ? 'facebook_verification' : 'facebook_login';
        $fb_login_url = $facebook->getLoginUrl(array(
            'redirect_uri' => SITE_FULL_URL . 'users/' . $action,
            'scope' => 'user_about_me,user_birthday,email'
                )
        );
        return $fb_login_url;
    }

    function encrypt($data, $secret = 'secret') {
        //Generate a key from a hash
        $key = md5(utf8_encode($secret), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);
        //Pad for PKCS7
        $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = $blockSize - ($len % $blockSize);
        $data .= str_repeat(chr($pad), $pad);
        //Encrypt data
        $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');
        return base64_encode($encData);
    }

    function decrypt($data, $secret = 'secret') {
        //Generate a key from a hash
        $key = md5(utf8_encode($secret), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);
        $data = base64_decode($data);
        $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');
        $block = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = ord($data[$len - 1]);
        return substr($data, 0, strlen($data) - $pad);
    }

    function loadJsClass($classes) {
        if (!empty($classes)) {
            $this->Html->scriptStart(['block' => true]);
            echo "$(document).ready(function () {";
            if (is_array($classes)) {
                foreach ($classes as $class) {
                    echo $class . '.init();';
                }
            } else {
                echo $classes . '.init();';
            }
            echo "});";
            $this->Html->scriptEnd();
        }
    }

    function getUserImage($id = null, $w = 100, $h = 100, $crop = 1) {
        $img = SITE_URL . "thumbnail/thumbnail.php?file=../img/no-user.png&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
        if ($id != null) {
            $this->UserDetails = TableRegistry::get('UserDetails');
            $userDetail = $this->UserDetails->get($id, ['fields' => 'profile_image']);
            if (isset($userDetail['profile_image'])) {
                if ($userDetail['profile_image'] != "") {
                    if ($img != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $userDetail['profile_image'])) {
                        $img = SITE_URL . "thumbnail/thumbnail.php?file=../uploads/users/{$userDetail['profile_image']}&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
                    }
                }
            }
        }
        return $img;
    }

    function getSiteLogo($id = 1, $w = 220, $h = 49, $crop = 1) {
        $img = SITE_URL . "thumbnail/thumbnail.php?file=../img/logo.png&w={$w}&h={$h}&el=0&gd=2&crop={$crop}&tp=1";
        if ($id != null) {
            $this->SiteSettings = TableRegistry::get('SiteSettings');
            $SiteSettings = $this->SiteSettings->get($id, ['fields' => 'site_logo']);

            if (isset($SiteSettings['site_logo'])) {
                if ($SiteSettings['site_logo'] != "") {

                    if (file_exists(WWW_ROOT . 'uploads' . DS . 'sites' . DS . $SiteSettings['site_logo'])) {

                        $img = SITE_URL . "thumbnail/thumbnail.php?file=../uploads/sites/{$SiteSettings['site_logo']}&w={$w}&h={$h}&el=0&gd=2&crop={$crop}&tp=1";
                    }
                }
            }
        }
        return $img;
    }

    function checkValue($value) {
        if ($value != '') {
            return $value;
        } else {
            return '<label class="btn btn-xs btn-danger btn-label" style="cursor: default;">N/A</label>';
        }
    }

    function getStatusWithLabel($value) {
        if ($value == 1) {
            return '<label class="btn btn-xs btn-success btn-label" style="cursor: default;">Yes</label>';
        } else {
            return '<label class="btn btn-xs btn-danger btn-label" style="cursor: default;">No</label>';
        }
    }

    function getUserStatusWithLabel($value) {
        if ($value == 0) {
            return '<label class="btn btn-xs btn-success btn-label" style="cursor: default;">Active</label>';
        } else {
            return '<label class="btn btn-xs btn-danger btn-label" style="cursor: default;">Archived</label>';
        }
    }

    function getStatus($value) {
        if ($value == 1) {
            return '<label class="btn btn-success-alt" style="cursor: default;" >Active</label>';
        } else {
            return '<label class="btn btn-danger-alt" style="cursor: default;" >Deactive</label>';
        }
    }

    function getOnOffStatus($value) {
        if ($value == 1) {
            return '<label class="btn btn-xs btn-success btn-label" style="cursor: default;">On</label>';
        } else {
            return '<label class="btn btn-xs btn-danger btn-label" style="cursor: default;">Off</label>';
        }
    }

    function getUserAlbumImage($album_image_name = '', $w = 100, $h = 100, $crop = 1, $color = 'FFFFFF') {
        $img = SITE_URL . "thumbnail/thumbnail.php?file=../img/no-user.png&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
        if ($album_image_name != "") {
            if ($img != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $album_image_name)) {
                $img = SITE_URL . "thumbnail/thumbnail.php?file=../uploads/users/{$album_image_name}&w={$w}&h={$h}&el=0&gd=2&color={$color}&crop={$crop}&tp=1";
            }
        }
        return $img;
    }

    function getUserCoverImage($album_image_name = '', $w = 1344, $h = 284, $crop = 1, $color = 'FFFFFF') {
        $img = SITE_URL . "thumbnail/thumbnail.php?file=../img/cover/cover1.jpg&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";

        if ($album_image_name != "") {
            if ($img != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $album_image_name)) {
                $img = SITE_URL . "thumbnail/thumbnail.php?file=../uploads/users/{$album_image_name}&w={$w}&h={$h}&el=0&gd=2&color={$color}&crop={$crop}&tp=1";
            }
        }
        return $img;
    }

    function userProfileImage($id = null, $w = 100, $h = 100, $crop = 1) {

        $img = SITE_URL . "thumbnail/thumbnail.php?file=../img/no-user.png&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
        if ($id != null) {
            $UserDetails = TableRegistry::get('UserDetails');

            $userDetail = $UserDetails->find('all', [
                        'conditions' => [
                            'user_id' => $id,
                        ],
                        'fields' => 'profile_image'
                    ])->first();
      
            if (isset($userDetail['profile_image'])) {
                if ($userDetail['profile_image'] != "") {
                    if ($img != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $userDetail['profile_image'])) {
                        $img = SITE_URL . "thumbnail/thumbnail.php?file=../uploads/users/{$userDetail['profile_image']}&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
                    }
                }
            }
        }
        return $img;
    }

    
    
}
