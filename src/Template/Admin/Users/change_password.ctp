<?php
$this->assign('title', 'Change Password');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Change Password');
?>
<div data-widget-group="group1">
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div> 
        <div class="col-sm-12"> 
            <div class="tab-pane">
                <div class="panel">
                    <?php
                    echo $this->Form->create($user, ['type' => 'file', 'class' => 'form-horizontal tabular-form change-password', 'novalidate']);
                    ?>  
                    <div class="panel-heading">
                        <h2>Change Password</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row col-md-6"> 
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">New Password</label>
                                <div class="col-xs-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ti ti-key"></i>
                                        </span>
                                        <?php echo $this->Form->input('password', ['label' => false, 'class' => 'form-control', 'placeholder' => 'New Password']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Confirm Password</label>
                                <div class="col-xs-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ti ti-key"></i>
                                        </span>
                                        <?php echo $this->Form->input('con_password', ['label' => false, 'type' => 'password', 'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">                                    
                                <?php echo  $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                                <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <?php echo  $this->Form->end(); ?>
                </div> 
            </div> 
        </div><!-- .tab-content -->
    </div><!-- col-sm-8 -->
</div> 
<?php echo  $this->Common->loadJsClass('User'); ?>