<?php 
$this->assign('title', 'My Profile');
use Cake\Core\Configure;
echo $this->element('popups/album-popup'); 

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
    <?php /* if($type == 'me'){ ?>
    <a href="#" class="edit-btn" data-toggle="modal" data-target="#myModal3"><?php echo $this->Html->image('edit-w-icon.png'); ?> </a>
    <?php }  */ ?>
</div>
 <div class="upload-img thumb_parent" id="thumb_parent">
	<ul>
		<?php
		if (!empty($user['albums'])) {

			foreach ($user['albums'] as $lkey => $lvalue) {
				
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
								<span>Upload photo</span>                              
							</span>
						</a>
					</li>   
					<?php
				}
			}
		}
		// album type value like free for 1 other for 5
		if($type == 'me'){ 
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
                
                <h2><?php echo $user['name'];?></h2>
				
                <?php if($type == 'me'){ ?>
                <a  href="#" class="hvr-ripple-out" data-toggle="modal" data-target="#myModa414">Update Profile</a>
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
//echo $this->element('popups/profile_verification');
echo $this->element('popups/update_profile');
//echo $this->element('popups/new_user_popup');
echo $this->element('popups/freelancer_new_user_popup'); 

?>
<?php
if ($user['is_new'] && $user['role_id'] == 3 && $type == 'me') {
    echo $this->Common->loadJsClass('freelancernewuser');
}
echo $this->Common->loadJsClass('profileAlbum');
echo $this->Common->loadJsClass('Explore');

?>