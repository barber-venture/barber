<header id="topnav" class="navbar navbar-default navbar-fixed-top" role="banner">

    <div class="logo-area">
        <span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg">
            <a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
                <span class="icon-bg">
                    <i class="ti ti-menu"></i>
                </span>
            </a>
        </span>

        <a href="<?php echo SITE_FULL_URL.'/admin'; ?>">
            <img style="margin-top:4px;" src="<?php echo  $this->Common->getSiteLogo('1',140,50,1); ?>" />
        </a>

    </div><!-- logo-area -->

    <ul class="nav navbar-nav toolbar pull-right">

        <!--        <li class="toolbar-icon-bg visible-xs-block" id="trigger-toolbar-search">
                    <a href="#"><span class="icon-bg"><i class="ti ti-search"></i></span></a>
                </li> -->
        <li class="toolbar-icon-bg hidden-xs">
            <a href="<?php echo SITE_URL; ?>"><span class="icon-bg"><i class="ti ti-home"></i></span></i></a>
        </li>

        <li class="dropdown toolbar-icon-bg">
            <a href="#" class="dropdown-toggle username" data-toggle="dropdown">

               <!-- <img src="<?php echo $this->Common->getUserImage($this->request->session()->read('Auth.User.image'), 128, 128, 1); ?>" class="img-circle" /> -->
               <img src="<?php echo $this->Common->getUserImage($this->request->session()->read('Auth.User.id'), 128, 128, 1); ?>" class="img-responsive img-circle" />
            </a>
            <ul class="dropdown-menu userinfo arrow">
                <li><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Site_settings', 'action' => 'index']); ?>"><i class="ti ti-settings"></i><span>Site Setting</span></a></li>
                <li><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'profile']); ?>"><i class="ti ti-user"></i><span>Profile</span></a></li>
                  <li><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'change_password']); ?>"><i class="ti ti-key"></i><span>Change Password</span></a></li>
                <li><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'logout']); ?>"><i class="ti ti-shift-right"></i><span>Sign Out</span></a></li>
            </ul>
        </li>

    </ul>

</header>