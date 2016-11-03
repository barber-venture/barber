<?php
$this->assign('title', 'Purchase Plans');  
?>
 
<div id="profile-info" class="plans-section payment-steps">
  <div class="container animated3 fadeInTop">
  <div class="row">
      <div class="col-lg-12 col-sm-12">
  		<div class="stepwizard">
            <div class="stepwizard-row setup-panel">
              <div class="stepwizard-step step-one">
                <a href="#step-1" type="button" class="btn btn-circle btn-default" disabled="disabled">1</a>
                <p>Payment Method</p>
              </div>
              <div class="stepwizard-step step-two">
                <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                <p>Pay Now</p>
              </div>
              <div class="stepwizard-step step-three step-active">
                <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
                <p>Confirmation</p>
              </div>
            </div>
          </div>
  	  </div>
  </div>
    <div class="row">
      <div class="col-lg-3 col-sm-3 w215">
      	<div class="leftplansDetails">
        	<div class="selectedPlan">
            <ul>
            	<li class="plan-active">Selected Plan</li>
                <li><span><img src="<?php echo SITE_URL; ?>img/star.png" alt=""></span><?php echo $Plan->duration; ?>  Month Plan</li>
            </ul>
        </div>
      </div>
      </div>
      <div class="col-lg-9 col-sm-9 w72-percent">
      		<div class="paymentConfirm">
            <h3>Congratulations !</h3>
            <p>You have subscribe for Selected Plan</p>
			
		 </div>
        </div>
      </div>
    </div>
  </div>