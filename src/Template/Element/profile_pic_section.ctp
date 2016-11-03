<div class="row">
      <section class="col-md-3 profile-img-section edit-profile-section selected">
        <div class="rows animated3 fadeInDown">
          <div class="profile-photo-block">
             <div class="image"><span>
                <?php // echo $this->Html->image('profile_img.jpg'); ?>
                <img src="<?php echo  $this->Common->getUserAlbumImage($user['user_detail']['profile_image'],200,218,1); ?>" />
                </span> 
            </div>
            
          </div>
	    </div>
      </section>  
      <section class="col-md-9 profile-detail-section selected">
        <div class="rows animated3 fadeInUp">
          <div class="info">
            <div class="heading">
				  <?php if($this->request->session()->read('Auth.User.role_id') == 2){ ?> 
              <h2><?php echo $user['name'] . ', ' . date_diff(date_create($user['user_detail']['dob']), date_create('today'))->y, "\n";
?></h2>
			  <?php }else{ ?>
			   <h2><?php echo $user['name'];
?></h2>
			  <?php }?>
			<div class="links">
				  <?php if ($user['user_detail']['address'] != '') { ?>
				  <a href="#">
						<?php echo $this->Html->image('loaction-icon.png'); ?>
						<span>From <?php echo $user['user_detail']['address']; ?></span>	
				  </a>
				  <?php } ?>
				  <?php if($this->request->session()->read('Auth.User.role_id') == 3){ ?> 
				  <a class="camera"><?php echo $this->Html->image('camera-icon.png');?>
					  <span><?php echo $user['user_detail']['camera']; ?></span>		
				  </a><br/><br/>
				  <?php }?>
			</div>
            
          </div>
        </div>
        <?php echo $this->Flash->render(); ?>
      </section>      
    </div>