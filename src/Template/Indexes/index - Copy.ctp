
<!-- start banner -->
<section id="slider" class="clearfix selected" data-role="datanav">
    <div id="backgroundGallery">
        <div class="ps_slide initial-img" style="background:url(img/slide01.jpg) no-repeat; background-size:cover;"> </div>
        <div class="ps_slide initial-img" style="background:url(img/slide02.jpg) no-repeat; background-size:cover;"> </div>
        <div class="ps_slide initial-img" style="background:url(img/slide03.jpg) no-repeat; background-size:cover;"> </div>
        <div class="ps_slide initial-img" style="background:url(img/slide04.jpg) no-repeat; background-size:cover;"> </div>
    </div>
    <div id="bannerContent">
        <div class="bannerContentin">
          
			
            <?php if ($this->request->session()->read('Auth.User.id') == "") { ?>
			
                <div class="rightSection animated3 fadeInRight">
				<div class="formContainer">
				  <h2>Join Now it’s Free</h2>
						
                        <div class="alert alert-dismissable alert-success" style="display: none" >
                            <i class="ti ti-check"></i>&nbsp;  
                            <span id="SuccessMessages"></span>
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        </div>
                        <div class="alert alert-dismissable alert-danger"  style="display: none">
                            <i class="ti ti-close"></i>&nbsp;
                            <span id="ErrorMessages"></span>
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        </div>
                        <?php echo $this->Flash->render(); ?>
                        <?php echo $this->Form->create('User', ['class' => 'signup-form']) ?>   
                        <div class="form-group">

                        <?php echo $this->Form->input('name', ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Name']); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('email', ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Email Address']); ?>


                        </div>
                        <div class="form-group">

                            <?php echo $this->Form->input('password', ['type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Password']); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('con_password', ['type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>

                        </div>

                        <div class="form-group custom-check-radio">
                            <div class="user-filed">  
                                <label class="control control--checkbox">Become a Freelancer                                
                                    <?php echo $this->Form->checkbox('freelancer', ['value' => 3]); ?>
                                    <div class="control__indicator"></div>
                                </label>


                                <a href="javascript:void(0)">
                                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                    <div class="tooltip-block">
                                        Freelancer accounts are free.
                                    </div>
                                </a>
                            </div>
                        </div>						

                        <div class="form-group">
                            <?= $this->Form->submit('Get Started Now', ['class' => 'btn btn-lg btn-danger']); ?>
                            <img id="loadergreens" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="left: 185px;   position: fixed;    top: 125px; display: none">
                        </div>
                        <div class="form-group">
                            <div class="Orlogin">Or</div>

                        </div>
                        <div class="form-group">

                            <a  href="<?php echo $this->Common->getLoginFacebookUrl(); ?>" class="btn btn-lg btn-facebook">Join With Facebook</a>

                        </div>
                        <?= $this->Form->end(); ?>

                    </div>
                </div>
            <?php } ?>
        </div>
		<div class="mainHeading">
      <h1>Where there is <em><img src="img/heart-icon.png" alt=""></em><span>there is life</span></h1>
    </div>
    </div>
</section>
<!-- end banner -->
<!-- start howitworks -->
<section id="howitworks" class="clearfix" data-role="datanav">
  <div class="leftbuble animated3 fadeInDown"></div>
  <div class="rightbuble animated3 fadeInDown"></div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="headingDiv">
          <h2>How it Works</h2>
          <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do </h3>
        </div>
        <div class="midContent">
          <div class="row">
            <div class="col-xs-12 col-sm-3 col-lg-3 text-center">
              <div class="howworks animated3 fadeInUpBig">
                <div class="howworksInner orangeicon"> <a href="#" class="freeregister"> <i class="icons-spirite registericon"></i> Register <br>
                  for free</a> </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3 text-center">
              <div class="howworks animated3 fadeInUpBig">
                <div class="howworksInner goldicon"> <a href="#" class=""> <i class="icons-spirite uploadpicture"></i> Upload your picture &amp;find compatible partners </a> </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3 text-center">
              <div class="howworks animated3 fadeInUpBig">
                <div class="howworksInner greenicon"> <a href="#" class=""> <i class="icons-spirite startchat"></i> Start conversation <br>
                  to Know <br>
                  each other </a> </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3 text-center">
              <div class="howworks animated3 fadeInUpBig">
                <div class="howworksInner redicon"> <a href="#" class=""> <i class="icons-spirite findlove"></i> Find <br>
                  Love </a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end howitworks --> 
<!-- start Video section -->
<section id="videoSection" class="clearfix" data-role="datanav">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="videoplayer animated3 fadeInLeft">
          <h2>Play</h2>
          <h3>the explainer video</h3>
          <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis dolores et quas molestias excepturi sint occaec
            ti cupiditate non provident. </p>
          <div class="videoplayBtn"> <a href="#" class="videoplay" data-toggle="modal" data-target="#videoPopup">Play <br>
            Video</a> </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end Video section --> 
<!-- start Security section -->
<section id="secureSection" class="clearfix" data-role="datanav">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="securebox animated3 fadeInLeft">
          <div class="secureboxItem">
            <div class="secureboxTextTop"><span>Discreet</span> Encounters</div>
            <img src="img/secure-seal.png" alt="">
            <div class="secureboxTextBottom">
              <div class="leftText"> <span>100%</span>Secure</div>
              <div class="rightText"> <span>SsL Secured</span>Site Badge</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end Security section --> 
<!-- start About section -->
<section id="aboutSection" class="clearfix" data-role="datanav">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="aboutBox animated3 fadeInDown">
          <h2>About us</h2>
          <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or 
            randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything
            embarrassing hidden  or non-characteristic words etc.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end About section -->

<!-- Video Popup design -->
<div class="modal fade animated2 fadeInDown2" id="videoPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
      		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Video</h4>
    	</div>
      	<div class="modal-body">
        <p>Video Come here....</p>
      </div>
    </div>
  </div>
</div>
<!-- End Video Popup design -->