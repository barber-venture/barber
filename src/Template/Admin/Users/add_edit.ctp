<?php
$this->assign('title', (($type) ? 'Edit' : 'Add').' User');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Users', ['controller' => 'Users', 'action' => 'view-user']);
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
                echo $this->Form->create($user, ['url' => ['controller' => 'Users', 'action' => 'addEdit'],'type' => 'file', 'class' => 'form-horizontal  addEditUser', 'novalidate']);
               $this->Form->unlockField('user_detail.lat');
               $this->Form->unlockField('user_detail.lng');
                ?>  
                <div class="panel-heading">
                    <h2><?php echo (($type) ? 'Edit' : 'Add')?> User</h2>
                </div>
                <div class="panel-body">


                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Full Name', 'maxlength' => 30]); ?>
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
                                <label for="form-name" class="col-sm-4 control-label">Nick Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.nike_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Nick Name']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Date Of Birth<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.dob', ['label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'MM/DD/YYYY']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                           <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-phone" class="col-sm-4 control-label">Phone<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.phone', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Phone']); ?>
                                </div>
                            </div>   
                        </div>
                           
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Gender<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('user_detail.gender', ['label' => false, 'options' => Configure::read('SiteSetting.Gender'), 'class' => 'form-control','empty'=>'Select Gender']); ?>
                                </div>
                            </div>
                        </div>

                    </div>
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
                                    <label for="focusedinput" class="col-sm-4 control-label">Address<span class="mandatory"> *</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                        echo $this->Form->input('user_detail.address', array(
                                            'type' => 'text',
                                            'label' => false,
                                            'div' => false,
                                            'class' => 'form-control',
                                            'placeholder' => 'Site Address'
                                        ));
                                        ?>
                
                                    </div>
                                </div>                 
                            </div>
                            
                        <!-- <div class="col-md-6">-->
                        <!--    <div class="form-group">-->
                        <!--        <label for="form-mobile" class="col-sm-4 control-label">Country<span class="mandatory"> *</span></label>-->
                        <!--        <div class="col-sm-8 tabular-border">-->
                        <!--            <?php //echo $this->Form->input('user_detail.country_id', ['id' => 'country_id', 'label' => false, 'options' => $Countries, 'empty' => 'Select Country', 'class' => 'form-control']); ?>-->
                        <!--        </div>-->
                        <!--    </div> -->
                        <!--</div>-->
                        
                    </div>
                    <!---->
                    <!--<div class="row">-->
                    <!--   -->
                    <!--    -->
                    <!--    <div class="col-md-6">-->
                    <!--        <div class="form-group">-->
                    <!--            <label for="form-mobile" class="col-sm-4 control-label">State<span class="mandatory"> *</span></label>-->
                    <!--            <div class="col-sm-8 tabular-border">-->
                    <!--                <?php //echo $this->Form->input('user_detail.state_id', ['options' => $States, 'id' => 'state_id', 'label' => false, 'empty' => 'Select State', 'class' => 'form-control']); ?>-->
                    <!--            </div>-->
                    <!--        </div> -->
                    <!--    </div>-->
                    <!--    -->
                    <!--    -->
                    <!--    <div class="col-md-6">-->
                    <!--        <div class="form-group">-->
                    <!--            <label for="form-mobile" class="col-sm-4 control-label">City<span class="mandatory"> *</span></label>-->
                    <!--            <div class="col-sm-8 tabular-border">-->
                    <!--                <?php //echo $this->Form->input('user_detail.city_id', ['options' => $Cities,'empty' => 'Select City',   'label' => false, 'class' => 'form-control', 'placeholder' => 'Mobile', 'id' => 'city_id']); ?>-->
                    <!---->
                    <!--            </div>-->
                    <!--        </div> -->
                    <!--    </div>                        -->
                    <!--</div>-->
                    <!---->
                    <!--<div class="row">-->
                    <!--    <div class="col-md-6">-->
                    <!--        <div class="form-group">-->
                    <!--            <label for="form-name" class="col-sm-4 control-label">Address Line 1<span class="mandatory"> *</span></label>-->
                    <!--            <div class="col-sm-8 tabular-border">-->
                    <!--                <?php //echo $this->Form->input('user_detail.address1', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Address Line 1']); ?>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="col-md-6">-->
                    <!--        <div class="form-group">-->
                    <!--            <label for="form-name" class="col-sm-4 control-label">Address Line 2<span class="mandatory"> *</span></label>-->
                    <!--            <div class="col-sm-8 tabular-border">-->
                    <!--                <?php //echo $this->Form->input('user_detail.address2', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Address Line 2']); ?>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                     <?php
                        $AdminFilePath = Configure::read('Site.ProfileImage');
                        $logoName = 'default.png';
                        if ($user['user_detail']['profile_image'] != '') {                
                            $site_logoPath = $AdminFilePath . $user['user_detail']['profile_image'];                
                            if (file_exists($site_logoPath)) {
                                $logoName = $user['user_detail']['profile_image'];
                            }
                        }
                        ?>
                    <div class="row">                                                
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Profile Image</label>
                                <div class="col-sm-8">
                                    <div class="fileinput fileinput-new" style="width: 100%;" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;">
                                            <img src="<?php echo SITE_FULL_URL .'uploads/users/'.$logoName ?>" />
                                        </div>
                                        <div>                              
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Select Image</span>
                                                <span class="fileinput-exists">Select Image</span>
                                                <?php
                                                
                                                echo $this->Form->file('user_detail.profile_image_new', array(
                                                    'label' => false,
                                                    'div' => false,                                                 
                                                ));
                                                ?>
                                            </span>
                                            <?php
                                            echo $this->Form->input('user_detail.profile_image', array(
                                                'type' => 'hidden',
                                                'label' => false,
                                                'div' => false
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div> 
                            </div>                 
                        </div>           
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-mobile" class="col-sm-4 control-label">About Me</label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->textarea('user_detail.about_me', ['label' => false, 'class' => 'form-control', 'placeholder' => 'About Me', 'maxlength' => '250']); ?>
                                </div>
                            </div> 

                        </div>
                        
                    </div>
                
                     <div class="row">                       
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-mobile" class="col-sm-4 control-label">User Tags</label>
                                <div class="col-sm-8 tabular-border">
                                    
                                    <!--<input type="hidden" id="e12" style="width:100% !important" value="brown, red, green"/>-->
                                                    
                                    <?php echo $this->Form->input('user_tags._ids', ['options' => $UserTags, 'id' => 'user_tags', 'label' => false,/* 'empty' => 'Select Tag',*/ 'class' => 'form-control', 'multiple' => true]); ?>
                                </div>
                            </div> 
                        </div>
                        
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
                     </div>
                     <br/><br/>
                     
                    
                        <div class="row" style="display:none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Site Map</label>
                                    <div class="col-sm-10">
                                        <div id="map_canvas" style="height: 300px;width:100%;margin:0px;padding:0px;"></div>
                                    </div> 
                                </div>                 
                            </div>                
                        </div>
                        <?php
                        echo $this->Form->input('user_detail.lat', array(
                            'type' => 'hidden'
                        ));
                        echo $this->Form->input('user_detail.lng', array(
                            'type' => 'hidden'
                        ));
                        ?>
                
                <!--<div class="panel-footer">-->
                    <div class="row"><br>
                        <div class="col-sm-8 col-sm-offset-2">                                    
                            <?php echo $this->Form->button((($type) ? 'Update' : 'Save'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            <a class="btn-default btn" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'viewUser']); ?>">Cancel</a>
                        </div>
                    </div>
                <!--</div>-->
                </div>
                <?php echo $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php
    $key = Configure::read('Site.googlemap_key');
    $this->Html->script(['adminSetting', 'http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key='.$key], ['block' => true]);
    $this->Html->scriptStart(['block' => true]); ?>
        function initialize() {
            var input = document.getElementById('user-detail-address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();                
                document.getElementById('user-detail-lat').value = place.geometry.location.lat();
                document.getElementById('user-detail-lng').value = place.geometry.location.lng();  
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
<?php $this->Html->scriptEnd(); ?>

<?php echo $this->Common->loadJsClass('User'); ?>