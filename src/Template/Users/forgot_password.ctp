<?php $this->assign('title', 'Forgot Password'); ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Forgot Password Form</h2>
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

            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>" class="btn btn-default pull-left">Login</a>
                    
                    <?php echo $this->Form->submit('Forgot',['class'=>'btn btn-primary pull-right']); ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>


    </div>
</div>
<?php echo $this->Common->loadJsClass('User');?>