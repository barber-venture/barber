<?php use Cake\Core\Configure; ?>
<ul id="boxscroll">  
	<?php
	if(!empty($notification)){
	
	foreach($notification as $noti){ ?>
	<li>
		<?php
			$ref_id = ($noti['notification_type'] == 2) ? $noti['referer_user_id'] : $noti['user']['id'];
			$link = '#';
			$days = Configure::read('Site.membership_exp_days');
			if(($this->request->session()->read('Auth.User.user_type') == 1) || ($noti['notification_type'] == 3)){
				$link = 'javascript:void(0);';
			}else{
				$link = $this->Url->build(['controller' => 'users', 'action' => 'profile', $this->common->encrypt($ref_id)]);
			}
		?>
		<a href="<?php echo $link; ?>" style="float: left; width: 80%; overflow:hidden;" class="profile_visit" data-id="<?php echo $noti['id']; ?>" >
			<div class="profilepics">
				<img src="<?php echo $this->Common->userProfileImage($noti['user']['id'],200,218,1); ?>" />
			</div>
			<span class="listingText">
			<?php 	if(strlen($noti['user']['user_detail']['nike_name']) > 22)
						echo substr($noti['user']['user_detail']['nike_name'], 0, 20).'..';
					else
						echo $noti['user']['user_detail']['nike_name']; 
					?>
			</span>			
			<span class="profileVisited"><?php echo $noti['message']; ?></span>
		</a>
		
		<a href="javascript:void(0);" class="delete_noti" data-id="<?php echo $noti['id']; ?>" title="Delete Notification"><span style="">x</span></a>
		
	</li>  
	<?php }
	}else{ ?>
		<div style="margin:100px 0 0 28px; color:grey; text-transform:none;">                            
			<span class="profileVisited"><?php echo $this->Html->image('sad.png');?>&nbsp; No Notifications Found</span>
			
		</div>  
	<?php }  ?> 
</ul>