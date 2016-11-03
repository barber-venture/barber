<!-- start profile banner -->
<div id="sitecontent" class="staticpage">
  <div class="container">
    <div class="row">
      <section class="col-lg-8 col-lg-push-2">
        <div class="contentIn animated3 fadeInDown">
        	<h1>FAQ</h1>
        	<!--h5>We would love to hear from you.</h5-->
          	<div class="pageContent">
          		<div class="panel-group" id="accordion">
				<?php
				if(!$faq->isEmpty()){
					foreach($faq as $k => $faqs){?>	
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $k; ?>" class="open"><?php echo $faqs['question'];?></a>
						  </h4>
						</div>
						<div id="collapse<?php echo $k; ?>" class="panel-collapse collapse <?php if($k == 0) echo 'in';?>">
						  <div class="panel-body"><?php echo $faqs['answer'];?></div>
						</div>
					  </div>
					  <?php }
				}else{ ?>
					<div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							FAQs Not found.
							</h4>
						</div>
					</div>
				<?php	
				}
				  ?>
			    </div>
          	</div>
        </div>
      </section>
    </div>
  </div>
</div>
<!-- end profile banner -->