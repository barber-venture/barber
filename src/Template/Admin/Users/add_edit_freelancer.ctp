<?php
$this->assign('title', (($type) ? 'Edit' : 'Add').' Freelancer');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Users', ['controller' => 'Users', 'action' => 'view-freelancer']);
$this->Html->addCrumb((($type) ? 'Edit' : 'Add'), '');
?>
<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div>

        <div class="tab-pane">

            <div class="panel">
                <?php
                echo $this->Form->create($user, ['url' => ['controller' => 'Users', 'action' => 'addEditFreelancer'],'type' => 'file', 'class' => 'form-horizontal  addEditUser', 'novalidate']);
                //$this->Form->unlockField('user_detail.lat');
                //$this->Form->unlockField('user_detail.lng');
               //$this->Form->unlockField('is_verify');               
                ?>  
                <div class="panel-heading">
                    <h2><?php echo (($type) ? 'Edit' : 'Add')?> Freelancer</h2>
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Full Name']);
                                    echo $this->Form->hidden('role_id', ['value' => '3']); 
                                    ?>
                                </div>
                            </div>
                        </div>                        
                    
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Email<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    if($type){
                                        echo  '<label for="form-name" class="col-sm-4 control-label">'.$user['email'].'</label>';
                                    }else{
                                       echo $this->Form->input('email', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Email Address']);  
                                    } ?>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                   
                   
                    <?php if(!$type){?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Password<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('password', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Password', 'value' => '']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Confirm Password<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('con_password', ['label' => false, 'type' => 'password',  'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="row">                                           
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-mobile" class="col-sm-4 control-label">Mobile<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.mobile', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Mobile']); ?>
                                </div>
                            </div> 
                        </div>                       
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Camera Used</label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.camera', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Camera Used']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                                          
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Address<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.address', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Address']); ?>
                                    <?php
                                    //echo $this->Form->input('user_detail.lat', array('type' => 'hidden'));
                                    //echo $this->Form->input('user_detail.lng', array('type' => 'hidden'));
                                    ?>
                                </div>
                            </div>
                        </div>                       
                        
                    </div>
                    
                    
                   <div class="row"> 
                       
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline icheck">
                                        <?php
                                        echo $this->Form->checkbox('status', array('value'=>'1'), array());
                                        //echo $this->Form->radio('status', Configure::read('SiteSetting.OptionYesNo'), array());
                                        ?> 
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Verify</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline icheck">
                                        <?php
                                        echo $this->Form->checkbox('is_verify', array('value'=>'1'), array());
                                        //echo $this->Form->radio('status', Configure::read('SiteSetting.OptionYesNo'), array());
                                        ?> 
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">                                    
                            <?php echo  $this->Form->button((($type) ? 'Update' : 'Save') , ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Users', 'action' => 'view-freelancer']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('User'); ?>
<?php
    $key = Configure::read('Site.googlemap_key');
    $this->Html->script(['adminSetting', 'http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key='.$key], ['block' => true]);
     
     $this->Html->scriptStart(['block' => true]);
     ?>     
     function initialize() {         
          var input = document.getElementById('user-detail-address');
          var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
               var place = autocomplete.getPlace();
               //document.getElementById('user-detail-lat').value = place.geometry.location.lat();
               //document.getElementById('user-detail-lng').value = place.geometry.location.lng();  
          });
     }
     google.maps.event.addDomListener(window, 'load', initialize);
     
     <?php $this->Html->scriptEnd(); ?>