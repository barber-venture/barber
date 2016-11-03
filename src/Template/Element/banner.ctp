<!-- start profile banner -->
<section id="profile-banner" class="profile-banner">
    <img src="<?php echo $this->Common->getUserCoverImage(); ?>" />
    <div class="banner-container">
        <div class="text">Your interests:
			<span><?php
				  foreach($user['user_tags'] as $tags){
					  echo '#' . $tags->name . ' ';
				  }
				  ?>
			</span>
		</div>
    </div>
</section>