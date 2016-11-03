<?php  
$this->assign('title', 'Settings');
use Cake\Core\Configure;
?>
<?php echo $this->element('banner'); ?>
<div id="profile-info" class="profile-info-section">
  	<div class="container">
        <?php echo $this->element('profile_pic_section'); ?>
        <div class="row">            
            <section class="col-lg-3 col-sm-4 col-xs-12">
            <div class="verticle-tabs-container">
                <!-- Nav Profile tabs -->
                <ul class="nav nav-tabs tabs-left">
                <?php if($user['role_id'] == 2){ ?>
                <li class="my-account active"><a href="#MyAccount" data-toggle="tab">My Account</a></li>
                <?php } ?>
                <li class="change-pass <?php if($user['role_id'] == 3) echo 'active'; ?>"><a href="#ChangePassword" data-toggle="tab">Change Password</a></li>
                <?php if($user['role_id'] == 2){ ?>
                <li class="privacy-policy"><a href="#PrivacySetting" data-toggle="tab">Privacy Setting</a></li>
                <?php } ?>
                <li class="remove-acc"><a href="#RemoveAccount" data-toggle="tab">Remove Account</a></li>                
              </ul>
            </div>
            </section>
            <section class="col-lg-5 col-sm-8 col-xs-12">  
                <!-- Tab panes -->
                <div class="tab-content verticle-tabs">
                    <div class="tab-pane animated3 fadeInUp <?php if($user['role_id'] == 2) echo 'active'; ?>" id="MyAccount">
                      
                    </div>
                    <div class="tab-pane animated3 fadeInUp <?php if($user['role_id'] == 3) echo 'active'; ?>" id="ChangePassword">
                        <div class="tab-title">
                            <h4>Password Settings</h4>
                        </div>
                        <div class="verticle-tabs-content">
                            <?php echo $this->Form->create($user, ['url' => ['action' => 'change_password'], 'class' => 'setting-form', 'id' => 'password_form']) ?>
                                <div class="form-group">
                                    <?php echo $this->Form->input('current_password', ['type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Current Password', 'value' => '', 'required' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->input('password', ['type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'New Password', 'value' => '', 'required' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->input('con_password', ['type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Confirm Password', 'required' => false]); ?>
                    
                                </div>
                              <div class="form-group text-right">
                                <input type="submit" value="Save" class="btn btn-danger small hvr-rectangle-in">
                              </div>
                          <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                    <div class="tab-pane animated3 fadeInUp" id="PrivacySetting">
                        <div class="tab-title">
                            <h4>Ghost Mode</h4>
                        </div>
                        <div class="verticle-tabs-content">
                            <p>
                                With ghost mode, you can browse profiles unnoticed and will not show up in Match or in the radar.
                            </p>
                            
                           <!-- <a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'index']); ?>" class="btn btn-default checkout selectPlanClass"><span class="img setting"></span>Activate ghost mode</a>-->
                            <?php // echo var_dump($user['is_ghost']); ?>
                            <div class="form-group">
                                <label class="switch">
                                  <input type="checkbox" id="ghostoptpion" <?php if(!$user['is_ghost']) echo 'checked';?>>
                                  <div class="slider round"></div>
                                </label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="tab-pane animated3 fadeInUp" id="RemoveAccount">
                        <div class="tab-title">
                            <h4>Leave your account</h4>
                        </div>
                        <div class="verticle-tabs-content">
                            <p>Do you really want to delete your <?php echo Configure::read('Site.title');?> account? </p>
                            <div class="btndiv">
                                <a href="javascript:void(0)" onclick="location.reload();"  class="btn btn-link">Cancel</a>
                                <!--<input type="submit" value="Ok" class="btn btn-danger small hvr-rectangle-in">-->
                                <a onclick="return confirm('Your account will be permanantly deleted, Do you still want to continue?')" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'delete_account']); ?>" class="btn btn-danger small hvr-rectangle-in"><span class="img setting"></span>Ok</a>
                                <!--<a href="javascript:void(0)" onclick="location.reload();" class="btn btn-danger small hvr-rectangle-in">Cancel</a>-->
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            
        </div>
    </div>
</div>



<div id="editinfo" style="display:none;">
    <div class="tab-title">
        <h4>Profile Details<a id="closeicon" class="edit-btn pull-right"><?php echo $this->Html->image('close.png'); ?></a></h4>
    </div>
    <div class="verticle-tabs-content">
        <?php echo $this->Form->create($user, ['class' => 'setting-form', 'id' => 'setting_form']) ?>   
            <div class="form-group">    
                <?php echo $this->Form->input('name', ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Name', 'maxlength' => 30, 'readonly']); ?>
            </div>
            
            <div class="form-group">
            <?php echo $this->Form->input('email', ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Email Address', 'readonly']); ?>
            </div>
            
            <div class="form-group">
            <?php echo $this->Form->input('user_detail.dob', ['type' => 'test', 'autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Date of birth', 'readonly']); ?>
            </div>
              
             <div class="form-group">
                <?php echo $this->Form->input('user_detail.address', ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control ', 'placeholder' => 'Address', 'id' => 'autocomplete']); ?>
            </div>
            <div class="form-group text-right">
                <input type="submit" value="Save" class="btn btn-danger small hvr-rectangle-in">
            </div>
        <?php echo $this->Form->end(); ?>
    </div>

</div>
<div id="MyAccountDetail" style="display:none;">
    <div class="tab-title">
        <h4>Personal Information <a id="editprofil" class="edit-btn pull-right" ><?php echo $this->Html->image('edit-icon-black.png'); ?></a></h4>
    </div>
    <div class="verticle-tabs-content myaccount">
        <div class="form-group">
        <div class="row">
            <div class="col-lg-4 col-sm-4 col-xs-6"><label>Name:</label></div>
            <div class="col-lg-8 col-sm-8 col-xs-6 text-right"><label class="textvalue"><?php echo $user['name'];?></label></div>
        </div>    
        </div>
        <div class="form-group">
        <div class="row">
            <div class="col-lg-4 col-sm-4 col-xs-6"><label>Email:</label></div>
            <div class="col-lg-8 col-sm-8 col-xs-6 text-right"><label class="textvalue"><?php echo $user['email'];?></label></div>
        </div>
        </div>
        
        <div class="form-group">
        <div class="row">
            <div class="col-lg-4 col-sm-4 col-xs-6"><label>Date of birth:</label></div>
            <div class="col-lg-8 col-sm-8 col-xs-6 text-right"><label class="textvalue"><?php echo date('d M, Y', strtotime($user['user_detail']['dob']));?></label></div>
        </div>
        </div>
        
        <div class="form-group">
        <div class="row">
            <div class="col-lg-4 col-sm-4 col-xs-6"><label>Address:</label></div>
            <div class="col-lg-8 col-sm-8 col-xs-6 text-right"><label class="textvalue"><?php echo $user['user_detail']['address'];?></label></div>
        </div>
        </div>
        
        <div class="form-group">
        <div class="row">
            <div class="col-lg-4 col-sm-4 col-xs-6"><label>Password:</label></div>
            <div class="col-lg-8 col-sm-8 col-xs-6 text-right"><label class="textvalue">*******</label></div>
        </div>
        </div>
    </div>
    
</div>
<!-- end profile banner -->
<?php
echo $this->element('popups/ghostmode');
echo $this->element('popups/profile_verification');
echo $this->element('popups/update_profile');
?>