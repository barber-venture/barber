<?php
$this->assign('title', 'Edit Profile');

use Cake\Core\Configure;
  $this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']); 
$this->Html->addCrumb('Profile', ['controller' => 'Users', 'action' => 'profile']);
$this->Html->addCrumb('Edit', ['controller' => 'Users', 'action' => 'edit_profile']);
?>
<div data-widget-group="group1">
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-profile custom-panel-profile">
                <div class="panel-body">

                    <img src="<?php echo  $this->Common->getUserImage($this->request->session()->read('Auth.User.id'), 128, 128, 1); ?>" class="img-circle" />

                    <div class="name"><?php echo  $user['name']; ?></div>
                    <div class="info"><?php echo  Configure::read('Site.title'); ?></div>
                </div>
            </div><!-- panel -->

        </div><!-- col-sm-3 -->
        <div class="col-sm-9">

            <div class="">
                <div class="tab-pane">
                    <div class="panel">
                        <?php
                        echo $this->Form->create($user, ['type' => 'file', 'class' => 'form-horizontal tabular-form edit-profile-form', 'novalidate']);
                        echo $this->Form->hidden('old_image', ['value' => $user['image']]);
                        ?>  
                        <div class="panel-heading">
                            <h2>Edit</h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">                                   


                                    <div class="form-group">
                                        <label for="form-name" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-8 tabular-border">
                                            <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Full Name']); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="form-name" class="col-sm-2 control-label">Nick Name</label>
                                        <div class="col-sm-8 tabular-border">
                                            <?php echo $this->Form->input('user_detail.nike_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Nike Name']); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="form-image" class="col-sm-2 control-label">Image</label>
                                        <div class="col-sm-8 tabular-border">                                          
                                            <div class="fileinput fileinput-new" style="width: 100%;" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;">
                                                    <!--<img src="<?php echo SITE_FULL_URL . 'uploads/users/' . $imageName ?>" />-->
                                                    <img src="<?php echo  $this->Common->getUserAlbumImage($user['id'], 128, 128, 1); ?>" class="img-circle" />
                                                </div>
                                                <div> 
                                                    <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">Select Image</span>
                                                        <span class="fileinput-exists">Select Image</span>
                                                        <?php
                                                        echo $this->Form->file('user_detail.profile_imagenew', array(
                                                            'label' => false,
                                                            'div' => false,
                                                        ));
                                                        
                                                        echo $this->Form->input('user_detail.profile_image', array(
                                                            'type' => 'hidden'
                                                        ));
                                                        ?>
                                                    </span> 
                                                   

                                                </div>
                                            </div>
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label for="form-phone" class="col-sm-2 control-label">Phone</label>
                                        <div class="col-sm-8 tabular-border">
                                            <?php echo $this->Form->input('user_detail.phone', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Phone']); ?>
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label for="form-mobile" class="col-sm-2 control-label">Mobile</label>
                                        <div class="col-sm-8 tabular-border">
                                            <?php echo $this->Form->input('user_detail.mobile', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Mobile']); ?>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">                                    
                                    <?php echo  $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                                    <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Users', 'action' => 'profile']); ?>">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <?php echo  $this->Form->end(); ?>
                    </div>
                </div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('User'); ?>