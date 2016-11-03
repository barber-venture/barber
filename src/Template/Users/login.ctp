<?php $this->assign('title', 'Login'); ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Login Form</h2>
            </div>
             <?php echo $this->Form->create('User',['class'=>'form-horizontal login-form']) ?>   
            <div class="panel-body">
            <?php echo $this->Flash->render(); ?>
                            
                    <div class="form-group mb-md">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('email',['label'=>false,'class'=>'form-control','placeholder'=>'Email']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-md">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-key"></i>
                                </span>
                                <?php echo $this->Form->input('password',['label'=>false,'class'=>'form-control','placeholder'=>'Password']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-n">
                        <div class="col-xs-12">
                            <?php echo $this->Html->link('Forgot password?',['controller'=>'users','action'=>'forgot_password'],['class'=>'pull-left']); ?>
                            <div class="checkbox-inline icheck pull-right p-n">
                                <?php echo $this->Form->checkbox('remember_me',['value'=>1]);?>
                                <label for="">                                    
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="panel-footer">
                <div class="clearfix">
<!--                    <a href="extras-registration.html" class="btn btn-default pull-left">Register</a>-->
                    
                    <?php echo $this->Form->submit('Login',['class'=>'btn btn-primary pull-right']); ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>


    </div>
</div>
<?php echo $this->Common->loadJsClass('User');?>