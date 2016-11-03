<?php $this->assign('title', 'Reset Password'); ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Reset Password Form</h2>
            </div>
             <?=$this->Form->create($user,['class'=>'form-horizontal reset-password-form']) ?>
            <div class="panel-body">
            <?=$this->Flash->render(); ?>
                            
                    <div class="form-group mb-md">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-key"></i>
                                </span>
                                <?php echo $this->Form->input('password',['label'=>false,'class'=>'form-control','placeholder'=>'New Password']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-md">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-key"></i>
                                </span>
                                <?php echo $this->Form->input('con_password',['label'=>false,'type'=>'password','class'=>'form-control','placeholder'=>'Confirm Password']); ?>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>" class="btn btn-default pull-left">Login</a>
                    
                    <?=$this->Form->submit('Reset',['class'=>'btn btn-primary pull-right']); ?>
                </div>
            </div>
            <?=$this->Form->end(); ?>
        </div>


    </div>
</div>
<?=$this->Common->loadJsClass('User');?>