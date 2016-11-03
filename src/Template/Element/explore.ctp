<?php

if(!empty($data)){ ?>
	<div class="exploreSection bgwhite animated3 fadeInUp">
		<div class="exploreSectionLeft col-lg-7 col-sm-7 col-xs-12">
			<div class="exploreSectionthumbs">
			<div class="exploreMainPic exploreThumb" user="<?php echo $data['id'];?>" style="cursor:pointer">
				<img src="<?php echo $this->Common->getUserAlbumImage($data['user_detail']['profile_image'], 350,485,1); ?>" />
				<?php //echo $user['user_detail']['profile_image']; //echo $this->Html->image('exporerpic.jpg'); ?>
			</div>
				<ul id="explore_thumbnails">
					<?php
					foreach($data['album_images'] as $imgs){
						?>							
						<li>
							<a class="exploreThumb" user="<?php echo $data['id'];?>">
								<img src="<?php echo $this->Common->getUserAlbumImage($imgs->image_name, 100,84,1); ?>" />
							</a>
						</li>
					<?php
					} ?>
				</ul>
			</div>
		</div>
		<div class="exploreSectionRight col-lg-5 col-sm-5 col-xs-12">
			<h4 class="exploreThumb" user="<?php echo $data['id'];?>" style="cursor:pointer"><?php echo substr($data['user_detail']['nike_name'], 0,20) . ', ' . date_diff(date_create($data['user_detail']['dob']), date_create('today'))->y, "\n";
?></h4>
			<div class="userLocaion"><?php echo $this->Html->image('loaction-icon.png'); ?><span>From <?php echo $data['user_detail']['address']; ?></span></div>
			<div class="profileVerifyed">
                          
				<?php echo $this->Html->image(($data['is_verify'] == 1) ? 'check-icon.png' : 'not_verified.png'); ?>        
				<span>Profile <?php echo ($data['is_verify'] == 1) ? 'Verified' : 'not verified yet'; ?></span>
			</div>
			<p style="min-height:194px;"><?php echo $data['user_detail']['about_me'];?></p>
			<div class="likeDislikeSection" user="<?php echo $data['id']?>">
				<a href="javascript:void(0);" class="profiledislike likedislike dislikee" action_status="2"></a>
				<a href="javascript:void(0);" class="profilecancel likedislike maybe" action_status="3"></a>
				<a href="javascript:void(0);" class="profilelike likedislike likee" action_status="1"></a>
			</div>
		</div>
	</div>
	<div class="row">
	<section class="col-lg-9 col-sm-12 col-xs-12">
	<div class="exploreSectionBottom">
		<ul> 
			<li>
				<div class="labelNo">
					<label>No</label>
					<a class="btn btn-default hvr-sweep-to-bottom"><i class="fa fa-arrow-left"></i> Previous</a>
				</div>
			</li>
			<li><span>Or</span></li>
			<li>
				<div class="labelYes">
					<a class="btn btn-default hvr-sweep-to-bottom">Next <i class="fa fa-arrow-right"></i></a>
					<label>Yes</label>
				</div>
			</li>
		</ul>
	</div>
</section>

	</div>
<?php }else{
	echo '<div style="text-align:center; padding:50px;margin:0 0 20px" class="exploreSection bgwhite animated3 fadeInUp">Nobody around you.</div>';
} ?>