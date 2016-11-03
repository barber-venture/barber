<?php
$this->assign('title', 'My Profile');
use Cake\Core\Configure;
echo $this->element('popups/album-popup'); 
?>
<script src="<?php echo SITE_FULL_URL; ?>js/masonry.pkgd.js"></script>
<script src="<?php echo SITE_FULL_URL; ?>js/imagesloaded.pkgd.js"></script>

<!-- start profile banner -->
<section id="profile-banner" class="profile-banner" >
    <img src="<?php echo $this->Common->getUserCoverImage($user['user_detail']['profile_image']); ?>" />
    <div class="banner-container">
        <div class="text">Your interests:
            <span><?php
                foreach ($user['user_tags'] as $tags) {
                    echo '#' . $tags->name . ' ';
                }
                ?>
            </span>
        </div>
    </div>
</section>
<!-- end profile banner --> 

<!-- start profile banner -->
<div id="profile-info" class="profile-info-section">
    <div class="container">
        <div class="row">
            <section class="col-md-4 profile-img-section selected">
                <div class="rows animated3 fadeInDown">
                    <div class="profile-photo-block">
                        <div class="image">
                            <span>
                                <img src="<?php echo $this->Common->getUserAlbumImage($user['user_detail']['profile_image'], 200, 218, 1); ?>" />
                            </span>
                            <?php /* if ($type == 'me') { ?>
                                <a href="javascript:void();" class="edit-btn" data-toggle="modal" data-target="#myModal3"><?php echo $this->Html->image('edit-w-icon.png'); ?> </a>
                            <?php } */ ?>
                        </div>
                        <div class="upload-img thumb_parent" id="thumb_parent">
                            <ul>

                                <?php
                                $albumlimit = ($user['user_type'] == 1) ? 1 : 4;
                                $albumcount = 1;
                                if (!empty($user['albums'])) {

                                    foreach ($user['albums'] as $lkey => $lvalue) {
                                        if($albumcount <= $albumlimit){
                                            $albumcount++;
                                            $imgFlag = 0;
                                            if (count($lvalue['album_images']) > 0) {
                                                foreach ($lvalue['album_images'] as $iKey => $ivalue) {
                                                    if ($imgFlag == 0) {
                                                        $filePath = Configure::read('Site.ProfileImage') . $ivalue['image_name'];
                                                        if (file_exists($filePath)) {
                                                            $imgFlag++;
                                                            ?> 
                                                            <li class="thumb <?php if($type == 'other') echo 'exploreThumb'; ?> " id="thumb_li_<?php echo $lvalue['id']; ?>" user="<?php echo $user['id']; ?>">
                                                                <a href="javascript:void(0)" data-album="<?php echo $lvalue['id']; ?>" data-id="<?php echo $ivalue['id']; ?>"  class="<?php
                                                                if ($type == 'me') {
                                                                    echo "uploadAlbum";
                                                                }
                                                                ?> ">
                                                                    <span>                                  
                                                                        <img title="<?php echo $ivalue['image_name']; ?>" src="<?php echo $this->Common->getUserAlbumImage($ivalue['image_name'], 94, 80, 1); ?>" />                                    
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <li class="thumb <?php if($type == 'other') echo 'exploreThumb'; ?>" id="thumb_li_<?php echo $lvalue['id']; ?>"  user="<?php echo $user['id']; ?>">
                                                    <a href="javascript:void(0)" data-album="<?php echo $lvalue['id']; ?>" data-id="new"  class="<?php
                                                    if ($type == 'me') {
                                                        echo "uploadAlbum";
                                                    }
                                                    ?> ">
                                                        <span>                                  
                                                            <span>Upload Photo</span>                              
                                                        </span>
                                                    </a>
                                                </li>   
                                                <?php
                                            }
                                        }
                                    }
                                }
                                // album type value like free for 1 other for 5
                            if ($type == 'me') {
                                $totalAlbum = ($user['user_type'] == 1) ? 1 : 4;
                                //$totalAlbum = 5;
                                if (count($user['albums']) < $totalAlbum) {
                                    $remeningAlbum = $totalAlbum - count($user['albums']);
                                    
                                    for ($i =1; $i <= $remeningAlbum; $i++) {
                                        ?>
                                        <li>
                                            <a href="javascript:void(0)" data-album="new" data-id="new"  class="<?php
                                            if ($type == 'me') {
                                                echo "uploadAlbum";
                                            }
                                            ?> ">
                                                <span>                                  
                                                    <span>Upload Album</span>                              
                                                </span>
                                            </a>
                                        </li> 
                                        <?php
                                    }
                                }
                            }
                                ?>
                            </ul>
                        </div>
                    </div>

                </div>
            </section>
            <section class="col-md-4 profile-detail-section selected">

                <div class="rows animated3 fadeInUp">
                    <div class="info">
                        <div class="heading">    
                            <h2><?php echo $user['user_detail']['nike_name'] . ', ' . date_diff(date_create($user['user_detail']['dob']), date_create('today'))->y, "\n";
                                ?></h2>                    

                            <?php if ($type == 'me') { ?>
                                <a  href="javascript:void();" class="hvr-ripple-out" data-toggle="modal" data-target="#myModal2">Update Profile</a>
                            <?php } ?>
                        </div>

                        <div class="links">
                            <?php echo $this->Flash->render(); ?>
                            <a>
                                <?php
                                if ($user['user_detail']['address'] != '') {
                                    echo $this->Html->image('loaction-icon.png');
                                    ?>
                                    <span>From <?php echo $user['user_detail']['address']; ?></span>
                                <?php } ?>            
                            </a>
                            <?php if ($type == 'me') { ?>
                                <a  href="javascript:void();" data-toggle="modal" data-target="<?php echo ($user['is_verify'] == 1) ? '' : '#myModal'; ?>">
                                    <?php echo $this->Html->image(($user['is_verify'] == 1) ? 'check-icon.png' : 'not_verified.png'); ?>        
                                    <span><?php echo ($user['is_verify'] == 1) ? 'Verified' : 'Verify now'; ?></span>
                                </a>
                            <?php } ?>

                        </div>
                        <div class="detail-tabs-block">
                            <div id="horizontalTab">
                                <ul class="resp-tabs-list">
                                    <li>Profile Details</li>
                                    <li>Details</li>
                                </ul>
                                <div class="resp-tabs-container">
                                    <div class="profile-details">
                                        <p class="animated3 fadeInUp"><?php echo $user['user_detail']['about_me']; ?></p>
                                        <div class="name-block">
                                            <?php if (!empty($user['user_tags'])) { ?>
                                                <h3>Interests</h3>
                                                <h4>
                                                    <?php
                                                    foreach ($user['user_tags'] as $tags) {
                                                        echo '#' . $tags->name . ' ';
                                                    }
                                                    ?>
                                                </h4>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="details">
                                        <div class="animated3 fadeInUp">
                                            <div class="profile-detail">
                                                <?php $options = Configure::read('Question'); ?>
                                                <ul>
                                                    <?php if ($user['user_detail']['address'] != '') { ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-hometown.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Hometown</h2>
                                                                <h3><?php echo $user['user_detail']['address'] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['interested_in_gender'] != '') {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('interested_icon.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Looking for</h2>
                                                                <h3><?php
                                                                    $act = explode(',', $user['user_detail']['interested_in_gender']);
                                                                    foreach ($options['interested_in_gender'] as $ke => $interested_in_gender) {
                                                                        if (in_array($ke, $act))
                                                                            echo $interested_in_gender;
                                                                        if (in_array($ke + 1, $act) && count($act) == 2)
                                                                            echo ' and ';
                                                                    }
                                                                    if (($user['user_detail']['interested_in_age_from'] > 0) && ($user['user_detail']['interested_in_age_to'] > 0)) {
                                                                        echo ' (' . $user['user_detail']['interested_in_age_from'] . '-' . $user['user_detail']['interested_in_age_to'] . ')';
                                                                    }
                                                                    ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['interested_in_activity'] != '') {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-filters.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Intrested In</h2>
                                                                <h3><?php
                                                                    $act = explode(',', $user['user_detail']['interested_in_activity']);
                                                                    $interested_in_activity_arr = array();
                                                                    foreach ($options['interested_in_activity'] as $ke => $interested_in_activity) {
                                                                        if (in_array($ke, $act))
                                                                            array_push($interested_in_activity_arr, $interested_in_activity);
                                                                    }
                                                                    echo $look = implode(', ', $interested_in_activity_arr);
                                                                    ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['relationship_status'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-relationship.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Relationship Status</h2>
                                                                <h3><?php echo $options['relationship_status'][$user['user_detail']['relationship_status']] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if (($user['user_detail']['height'] > 0) || ($user['user_detail']['body_type'] > 0)) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-appearance.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Appearance</h2>
                                                                <h3><?php
                                                                    if ($user['user_detail']['height'] > 0)
                                                                        echo $user['user_detail']['height'] . ' cm, ';
                                                                    echo $options['body_type'][$user['user_detail']['body_type']];
                                                                    ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['children'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-children.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Children</h2>
                                                                <h3><?php echo $options['children'][$user['user_detail']['children']] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['religion'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-religion.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Religion</h2>
                                                                <h3><?php echo $options['religion'][$user['user_detail']['religion']] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['living_situation'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-living-situation.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Living situation</h2>
                                                                <h3><?php echo $options['living_situation'][$user['user_detail']['living_situation']] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['education'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-education.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Education</h2>
                                                                <h3><?php echo $options['education'][$user['user_detail']['education']] ?></h3>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    if ($user['user_detail']['profession'] > 0) {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('icon-profession.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Profession</h2>
                                                                <h3><?php echo $options['profession'][$user['user_detail']['profession']] ?></h3>
                                                            </div>
                                                        </li>                                                    
                                                        <?php
                                                    }
                                                    $languageVal = explode(',', $user['user_detail']['language']);

                                                    if ($user['user_detail']['language'] != '') {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('language_icon.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Languages</h2>
                                                                <h3><?php
                                                                    $lang_arr = array();
                                                                    foreach ($language as $k => $lang) {
                                                                        if (in_array($k, $languageVal))
                                                                            array_push($lang_arr, $lang);
                                                                    }
                                                                    echo $lang_arr_data = implode(', ', $lang_arr);
                                                                    ?></h3>
                                                            </div>
                                                        </li>
                                                    <?php }
                                                    
                                                    if ($user['user_detail']['mobile'] != '') {
                                                        ?>
                                                        <li>
                                                            <div class="img"><?php echo $this->Html->image('mobile.png'); ?></div>
                                                            <div class="info">
                                                                <h2>Mobile Number</h2>
                                                                <h3><?php echo '+'.$user['user_detail']['mobile_country_code'].' '.$user['user_detail']['mobile']; ?></h3>  
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </section>
            <?php if ($type == 'me') { ?>
                <section class="col-md-4 profile-message-section selected">
                    <div class="rows animated3 fadeInUp">
                        <div class="heading">
                            <h2>How to get connected</h2>
                        </div>
                        <div class="messgae-list custom-check-radio">
                            <ul>
                                <li>
                                    <label class="control control--checkbox">
                                        <input type="checkbox" <?php if ($user['user_detail']['profile_image'] != '') { ?> checked="checked" disabled="disabled" <?php } ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <h2>Upload Profile Photo</h2>
                                    <h3>Get found more easily</h3>
                                </li>
                                <li>
                                    <label class="control control--checkbox">
                                        <input disbled type="checkbox" <?php if ($user['is_verify'] == 1) { ?> checked="checked" disabled="disabled" <?php } ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <?php if ($user['is_verify'] != 1) { ?> 
                                        <a href="javascript:void();" data-toggle="modal" data-target="#myModal"><h2>Verify your Profile</h2></a>
                                    <?php }else{ ?>
                                         <h2>Verify your Profile</h2>
                                    <?php } ?>
                                    <h3>Prove that you're who you say you are</h3>
                                </li>
                                
                                <li>
                                    <label class="control control--checkbox">
                                        <input disbled type="checkbox" <?php if ($user['user_type'] == 4) { ?> checked="checked" disabled="disabled" <?php } ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <?php if ($user['user_type'] == 4) { ?>
                                        <h2>Upgrade your account to a Premier</h2>
                                    <?php }else{ ?>
                                        <a href="<?php echo SITE_FULL_URL; ?>plans"><h2>Upgrade your account to a Premier</h2></a>
                                    <?php } ?>
                                    <h3>Get all benefits of premium account</h3>
                                </li>
                                <!--
                                <li id="<?php if ($user['is_confirm_email'] != 1) { ?>contfirmEmail<?php } ?>" style="cursor: pointer;">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" <?php if ($user['is_confirm_email'] == 1) { ?> checked="checked" disabled="disabled" <?php } ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <h2>Confirm e-mail address</h2>
                                    <h3>Get found more easily</h3>
                                </li>-->
                                
                            </ul>
                        </div>
                    </div>

                </section>

            <?php } ?>
        </div>
    </div>
</div>


<div class="modal fade edit-profile-design popup animated2" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-body" id="divcontent" >
                 
            </div>
        </div>
    </div>
</div>
<!-- end profile banner -->
<?php
//echo $this->element('popups/edit_profile_picture');
echo $this->element('popups/profile_verification');
echo $this->element('popups/update_profile');
echo $this->element('popups/new_user_popup');
echo $this->element('popups/freelancer_new_user_popup');
?>


<?php
if ($user['is_verify_mobile'] && $user['is_new'] && $user['role_id'] == 2 && $type == 'me') {
    echo $this->Common->loadJsClass('newuser');
}
?>
<?php
echo $this->Common->loadJsClass('profileAlbum');
echo $this->Common->loadJsClass('Explore');

?>

