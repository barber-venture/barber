<ul class="chattingList">
	<li>
		<div class="userSmallPic"><img alt="" src="<?php echo $this->Common->getUserAlbumImage($to_user_detail['profile_image'] ,50,58,1); ?>"></div>
		<div class="userContent">
			<div class="userName"><?php echo ($to_user_detail['role'] == 2) ? $to_user_detail['nike_name'] : $to_user_detail['name']; ?></div>
			<div class="userLocation"><?php echo $to_user_detail['address']; ?></div>
		</div>
	</li>
</ul>
<div class="aboutBox">
	<h3>About me</h3> 
	<p><?php echo $to_user_detail['about_me']; ?> </p>
</div>
