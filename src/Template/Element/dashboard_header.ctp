<!-- start header -->
<?php // pr($notification);
//pr($this->request->session()->read('Auth.User')); die; ?>
<header id="pageHeader" class="header">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-lg-2">
        <div class="logo row">
          <figure><a href="<?php echo SITE_FULL_URL; ?>">
                    <img src="<?php echo  $this->Common->getSiteLogo('1',145,50,1); ?>" />
                </a></figure>
        </div>
      </div>
	<?php if($this->request->session()->read('Auth.User.role_id') != 1){  ?>
      <div class="col-xs-12 col-sm-6 col-lg-10">
        <div class="custom-navbar row">
        	<div class="user-info-icon <?php if($this->request->session()->read('Auth.User.role_id') == 3) echo 'phographer'; ?>">
            <ul>
                <?php
                if($this->request->session()->read('Auth.User.role_id') == 2){
                $noti_count = count($notification); ?>
                <li> <a href="javascript:void(0);" class="bell <?php if($noti_count > 0) echo 'bell_noti'; ?>" title="Notification">
				  <span class="badge label-danger"><?php if($noti_count > 0) echo $noti_count; ?></span>
				  <i class="fa fa-bell"></i>
				</a>
                  <div class="notification-list" >
                    <?php echo $this->element('/popups/notification_list'); ?>
                  </div>
                </li>
              <?php } ?>
              <li> <a href="javascript:void(0);" class="comment" title="Messages">
				<span class="badge label-danger" id="chat_counter"><?php				
				if($ChatMessages > 0) echo $ChatMessages; ?></span>
				<i class="fa fa-comment"></i>
			  </a></li>
              <li> <a href="javascript:void(0);" class="setting" title="Settings"><i class="fa fa-cog"></i></a>
                <div class="setting-link">
                  <ul>
                    <li>
                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'setting']); ?>" class="hvr-bounce-to-bottom"><i class="fa fa-gear"></i> Settings</a> 
                    </li>                    
                    <li>
                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'logout']); ?>" class="hvr-bounce-to-bottom"><i class="fa fa-circle-o-notch"></i> Logout</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        	<nav class="navbar navbar-default navbar-static-top">
            <div class="navigation">
              <div class="nav-grid"><a href="javascript:void(0)"><span></span></a></div>
              <div class="navbar-collapse navigation-part">
                <ul class="nav navbar-nav">
                  <?php if(($this->request->session()->read('Auth.User.role_id') == 2) && ($this->request->session()->read('Auth.User.user_type') == 4)){
?>
                  <li class="hvr-bounce-to-bottom <?php if(in_array($this->request['action'], array('photographer', 'photographerProfile'))) echo 'active'; ?>"><a href="<?php echo SITE_FULL_URL; ?>users/photographer">Photographer</a></li>
                  <?php }if($this->request->session()->read('Auth.User.role_id') == 2){ ?>
                  <li class="hvr-bounce-to-bottom <?php if($this->request['action'] == 'search') echo 'active'; ?>"><a href="<?php echo SITE_FULL_URL; ?>users/search">Adventure</a></li>
                  <?php }
                  $profile = ($this->request->session()->read('Auth.User.role_id') == 3) ? 'photographerMyProfile' : 'myProfile';
                  ?>                  
                  <li class="hvr-bounce-to-bottom <?php if(in_array($this->request['action'], array('myProfile', 'photographerMyProfile')))  echo 'active'; ?>"><a href="<?php echo SITE_FULL_URL; ?>users/<?php echo $profile;?>">My Profile</a></li>
                  
                  <?php if($this->request->session()->read('Auth.User.role_id') == 2){ ?>               
                  <li class="hvr-bounce-to-bottom <?php if($this->request['action'] == 'explore') echo 'active'; ?>"><a href="<?php echo SITE_FULL_URL; ?>users/explore">Explore</a></li>
                  <?php if($this->request->session()->read('Auth.User.user_type') != 4){ ?>
                  <li><a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'index']); ?>" class="hvr-rectangle-in become-premier-btn">Become a Premier</a></li>
                  <?php }
                  } ?>
                </ul>
              </div>
              <div class="overlay"></div>
            </div>
            
            <!--/.nav-collapse --> 
          </nav>
        </div>
      </div>
    <?php } ?>
	</div>
  </div>
  <!-- /.container --> 
</header>
<!-- end header -->
<?php
    echo $this->Element('/popups/chat');
    echo $this->Common->loadJsClass('chat');
?>