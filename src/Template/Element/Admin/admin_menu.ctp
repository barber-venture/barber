<nav role="navigation" class="widget-body">
    <ul class="acc-menu">
        <li class="nav-separator"><span>Explore</span></li>

        <li class="<?php echo ($this->request->params['action'] == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'dashboard']); ?>"><i class="ti ti-home"></i>
                <span>Dashboard</span>
            </a> 
        </li>
        <!-----------------------Master Datas-------------------------->
        <li class="hasChild <?php echo (($this->request->params['controller'] == 'MasterDatas' ) && (in_array($this->request->params['action'], array('index', 'addEdit')))) ? 'active open' : ''; ?> ">
            <a href="javascript:void(0)"><i class="ti ti-server"></i><span>Master Data</span> </a>
            <ul class="acc-menu" <?php echo (($this->request->params['controller'] == 'MasterDatas' ) && (in_array($this->request->params['action'], array('addTag', 'tagList',)))) ? 'style="display:block;"' : ''; ?>>
                <li class="<?php echo (($this->request->params['controller'] == 'MasterDatas' ) && (in_array($this->request->params['action'], array('addTag', 'tagList')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'MasterDatas', 'action' => 'tagList']); ?>">User Tags</a></li> 
            </ul>
        </li>
        <!-----------------------channel partner------------------------->  
        <li class="hasChild <?php echo (($this->request->params['controller'] == 'Users' ) && (in_array($this->request->params['action'], array('channelPartner', 'channelPartnerList')))) ? 'active open' : ''; ?> "><a href="javascript:void(0)"><i class="ti ti-manager"></i><span>Users</span></a>
            <ul class="acc-menu" <?php echo (($this->request->params['controller'] == 'Users' ) && (in_array($this->request->params['action'], array('addEdit', 'viewUser', 'detail')))) ? 'style="display:block;"' : ''; ?>>
                <!--<li class="<?php echo (($this->request->params['controller'] == 'Users' ) && (in_array($this->request->params['action'], array('channelPartner')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'addEdit']); ?>">Add User</a></li> -->
                <li class="<?php echo (($this->request->params['controller'] == 'Users' ) && (in_array($this->request->params['action'], array('viewUser')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'viewUser']); ?>">Normal User</a></li>
				<li class="<?php echo (($this->request->params['controller'] == 'Users' ) && (in_array($this->request->params['action'], array('viewFreelancer')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'viewFreelancer']); ?>">Freelancer User</a></li>  
            </ul>
        </li>

        <li class="hasChild <?php echo (($this->request->params['controller'] == 'Pages' ) && (in_array($this->request->params['action'], array('add', 'edit', 'index')))) ? 'active open' : ''; ?> "><a href="javascript:void(0)"><i class="ti ti-file"></i><span>Pages</span></a>
            <ul class="acc-menu" <?php echo (($this->request->params['controller'] == 'Pages' ) && (in_array($this->request->params['action'], array('channelPartner', 'channelPartnerList')))) ? 'style="display:block;"' : ''; ?>>
                <li class="<?php echo (($this->request->params['controller'] == 'Pages' ) && (in_array($this->request->params['action'], array('add')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Pages', 'action' => 'add']); ?>">Add Page</a></li> 
                <li class="<?php echo (($this->request->params['controller'] == 'Pages' ) && (in_array($this->request->params['action'], array('index')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Pages', 'action' => 'index']); ?>">All Page</a></li>					
            </ul>
        </li>

        <li class="hasChild <?php echo (($this->request->params['controller'] == 'Faqs' ) && (in_array($this->request->params['action'], array('add', 'edit', 'index')))) ? 'active open' : ''; ?> "><a href="javascript:void(0)"><i class="ti ti-help-alt"></i><span>FAQs</span></a>
            <ul class="acc-menu" <?php echo (($this->request->params['controller'] == 'Faqs' ) && (in_array($this->request->params['action'], array('channelPartner', 'channelPartnerList')))) ? 'style="display:block;"' : ''; ?>>
                <li class="<?php echo (($this->request->params['controller'] == 'Faqs' ) && (in_array($this->request->params['action'], array('add')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Faqs', 'action' => 'add']); ?>">Add FAQ</a></li> 
                <li class="<?php echo (($this->request->params['controller'] == 'Faqs' ) && (in_array($this->request->params['action'], array('index')))) ? 'active' : ''; ?>"><a href="<?php echo $this->Url->build(['prefix' => 'admin', 'controller' => 'Faqs', 'action' => 'index']); ?>">All FAQ</a></li>					
            </ul>
        </li>
	   
    </ul>
</nav>