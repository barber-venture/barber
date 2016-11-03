<?php

use Cake\Core\Configure; ?>
<div class="static-sidebar-wrapper sidebar-default">
    <div class="static-sidebar">
        <div class="sidebar">
            <div class="widget">
                <div class="widget-body">
                    <div class="userinfo">
                        <div class="avatar">
                            <img src="<?php echo $this->Common->getUserImage($this->request->session()->read('Auth.User.id'), 128, 128, 1); ?>" class="img-responsive img-circle" />
                        </div>
                        <div class="info">
                            <span class="username"><?php echo  $this->request->session()->read('Auth.User.name'); ?></span>
<!--                            <span class="useremail"><?php echo Configure::read('Site.title'); ?></span>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget stay-on-collapse" id="widget-sidebar">
                <?php
                echo $this->element('Admin/admin_menu');
 
                ?>
            </div>
        </div>
    </div>
</div>