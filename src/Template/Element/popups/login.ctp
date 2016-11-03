<!-- Login Popup design -->
<!-- Modal -->
<div class="modal fade login-popup animated2 fadeInDown2" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <div class="modal-body">

                <div class="formContainer">

                    <div id="userLoginDiv" >
                        <h2>Login Here</h2>
                        <div id="loginErrorMessages"> 
                        </div> 
                        <?= $this->Form->create('User', ['class' => 'login-form', 'id' => 'loginForm']) ?>   

                        <div class="form-group"> 
                            <?php echo $this->Form->input('email', ['label' => false,'value'=>  isset($Cookie['email'])?$Cookie['email']:'', 'class' => 'form-control', 'placeholder' => 'Email Address']); ?>

                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('password', ['label' => false,'value'=>  isset($Cookie['password'])?$Cookie['password']:'',  'class' => 'form-control', 'placeholder' => 'Password']); ?>

                        </div>
                    

                        <div class="form-group custom-check-radio">
                            <div class="row">
                                <div class="col-md-6 remember-me">
                                    <div class="remember-check"> 
                                        <label class="control control--checkbox">Remember me

                                            <?php echo $this->Form->checkbox('remember_me', ['value' => 1,isset($Cookie['email'])?'checked':'', ]); ?>
                                            <div class="control__indicator"></div>
                                        </label></div> 			
                                </div>
                                <div class="col-md-6 for-pass-text">
                                    <a href="javascript:void(0);" id="forgetPassword" class="" >Forgot Password?</a>                                 
                                </div>
                            </div>
                        </div> 
<!--<div class="ajax_css_loader">
                            <div class='uil-ripple-css' style='transform:scale(0.52);'><div></div><div></div></div>
                    </div>--> 
                        <div class="form-group">
                            <?= $this->Form->submit('Login', ['class' => 'btn btn-lg btn-danger']); ?>
                            <img id="loadergreen" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" class="login-loader" style="display:none;">
                        </div>
                        <div class="form-group">
                            <div class="Orlogin">Or</div>
                        </div>
                        <div class="form-group">

                            <a href="<?php echo $this->Common->getLoginFacebookUrl(); ?>" class="btn btn-lg btn-facebook">Login With Facebook</a>
                        </div>
                        <?= $this->Form->end(); ?>
                    </div>

                    <div id="ForgotPasswordDiv" style="display: none"> 
                        <h2>Forgot Password</h2>
                        
                        <div id="ForgotMessageDivSuccess" >
                             
                        </div>
                        
                        <?= $this->Form->create('User', ['class' => 'ForgetPassword', 'id' => 'ForgetPassword']) ?>   
                        <div class="form-group">
                            <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Email Address']); ?>

                        </div> 
                      

                        <div class="form-group">
                            <?= $this->Form->submit('Submit', ['class' => 'btn btn-lg btn-danger']); ?>
                            <img id="loadergreenf" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="left: 185px;   position: fixed;    top: 125px; display: none">
                        </div>      
                          <div class="form-group custom-check-radio">
                            <div class="row">
                                <div class="col-md-8 remember-me">

                                </div>
                                <div class="col-md-4 for-pass-text">
                                    <a href="javascript:void(0);" id="loginClick" class="pull-left" >
                                          <i class="ti ti-back-left"></i>&nbsp;Back To Login</a>                                 
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">

                        </div>

                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div style="display:none; "><img src="<?php echo SITE_FULL_URL; ?>img/login_bg.jpg" /></div>
