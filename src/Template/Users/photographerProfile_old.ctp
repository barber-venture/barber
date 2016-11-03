<?php
$this->assign('title', 'My Profile');
use Cake\Core\Configure;
?>
<!-- start profile banner -->
<section id="profile-banner" class="profile-banner" >
    <img src="<?php echo $this->Common->getUserCoverImage($user['user_detail']['profile_image']); ?>" />
    <div class="banner-container">
        <!--<div class="text">Your interests:  <span>#sweeedy</span></div>-->
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
    <img src="<?php echo $this->Common->getUserAlbumImage($user['user_detail']['profile_image'],200,218,1); ?>" />
    </span>
    <?php if($type == 'me'){ ?>
    <a href="#" class="edit-btn" data-toggle="modal" data-target="#myModal3"><?php echo $this->Html->image('edit-w-icon.png'); ?> </a>
    <?php } ?>
</div>
<div class="upload-img thumb_parent" id="thumb_parent">
    <ul>
        <?php
        $i = -1;
        foreach($user['album_images'] as $i=>$imgs){
        ?>
          <li id="thumb_li_<?php echo $imgs['id']; ?>">
            <a href="#" <?php if($type == 'me') echo 'data-toggle="modal" data-target="#myModal3"';?>>
            <span>                                  
                <img title="<?php echo $imgs['image_name']; ?>" src="<?php echo  $this->Common->getUserAlbumImage($imgs['image_name'],94,80,1); ?>" />                                    
            </span>
          </a>
          </li>
        <?php
        }
        $i++;
        for($k=$i; $k < (Configure::read('Site.image_upload_limit_for_normal_user')); $k++){
            ?>
           <li><a href="#" <?php if($type == 'me') echo 'data-toggle="modal" data-target="#myModal3"';?> ><span>Upload photo</span></a></li>
           <?php
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
                
                <h2><?php echo $user['name'];?></h2>
                <?php if($type == 'me'){ ?>
                <a  href="#" class="hvr-ripple-out" data-toggle="modal" data-target="#<?php if($user['role_id'] == 2) echo 'myModal2'; else echo 'myModa414';?>">Update Profile</a>
                <?php } ?>
              </div>
            
            <div class="links">
                <?php echo $this->Flash->render(); ?>
                <a>
                <?php
                if($user['user_detail']['address'] != ''){
                    echo $this->Html->image('loaction-icon.png'); ?>
                    <span>From <?php echo $user['user_detail']['address']; ?></span>
                <?php } ?>
            
                </a>
               
				<a class="camera"><?php echo $this->Html->image('camera-icon.png');?>
					<span><?php echo $user['user_detail']['camera']; ?></span>		
				</a>
               
                   
            </div>
            <div class="detail-tabs-block">
                <div id="horizontalTab">
                    <ul class="resp-tabs-list">                      
                        <li>Details</li>
                    </ul>
                    <div class="resp-tabs-container">
                     
                        <div class="details">
                            <div class="animated3 fadeInUp">
                                <div class="profile-detail">
                                    <?php $options = Configure::read('Question'); ?>
                                    <ul>                                       
                                        <li>
                                            <div class="img"><?php echo $this->Html->image('mobile.png'); ?></div>
                                            <div class="info">
                                              <h2>Mobile</h2>
                                              <h3><?php echo $user['user_detail']['mobile'];?></h3>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="img"><?php echo $this->Html->image('email.png'); ?></div>
                                            <div class="info">
                                              <h2>Email Address</h2>
                                              <h3><?php echo $user['email'];?></h3>
                                            </div>
                                        </li>
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
</div>
</div>
</div>
<!-- end profile banner -->
<?php
echo $this->element('popups/edit_profile_picture');
echo $this->element('popups/profile_verification');
echo $this->element('popups/update_profile');
echo $this->element('popups/new_user_popup');
echo $this->element('popups/freelancer_new_user_popup'); 

?>
<?php
if(!$user['user_detail']['id'] && ($user['role_id'] == 3)){
    echo $this->Common->loadJsClass('freelancernewuser');
}
?>