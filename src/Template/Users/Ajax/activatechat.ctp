
<?php //pr($activeUser); die; ?>
<li id="Chat_li_<?php echo $activeUser->id;?>" class="chatheads_user active" user_id="<?php echo $activeUser->id;?>">
	<a>
	  <div class="userSmallPic"><img src="<?php echo $this->Common->getUserAlbumImage($activeUser->profile_image,50,50,1); ?>" /></div>
	  <div class="userContent">
		  <div class="userName"><?php echo $activeUser->name . ', '. $activeUser->age;?></div>
		  <div class="userLocation">&nbsp;</div>
	  </div>
	</a>
</li>