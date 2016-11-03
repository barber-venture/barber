<?php $this->assign('title', 'Reset Password'); ?>
<style>
    .modal1{
        bottom: 0;
        left: 0;
        outline: 0 none;
        overflow: hidden;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 1050;
    }
</style>
<div class="modal1 fade in login-popup animated2 fadeInDown2"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content"> 

            <div class="modal-body">

                <div class="formContainer">

                    <h2>Reset Password Form</h2>

                    <?= $this->Form->create($user,['class' => 'change-password']) ?>   
                    <?php echo $this->Flash->render(); ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('password', ['label' => false, 'class' => 'form-control', 'placeholder' => 'New Password']); ?>


                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('con_password', ['label' => false, 'type' => 'password', 'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>

                    </div>
 
                    <div class="form-group">
                        <?= $this->Form->submit('Change Password', ['class' => 'btn btn-lg btn-danger']); ?>
                        <img id="loadergreen" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="left: 185px;   position: fixed;    top: 125px; display: none">
                    </div>           

                    <?= $this->Form->end(); ?>


                </div>
            </div>

        </div>
    </div>
</div>
<?php echo $this->Common->loadJsClass('User'); ?>