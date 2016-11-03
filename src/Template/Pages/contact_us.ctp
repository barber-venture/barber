<?php
use Cake\Core\Configure;
?>
<!-- start profile banner -->
<div id="sitecontent" class="staticpage">
  <div class="container">
	<div class="col-sm-12">
		<?php echo  $this->Flash->render(); ?>
	</div>
    <div class="row">
      <section class="col-lg-8 col-lg-push-2">
        <div class="contentIn animated3 fadeInDown">
        	<h1>Contact Us</h1>
        	<h5>We would love to hear from you.</h5>
          	<div class="contactPageForm">
          		 <?php 
                echo $this->Form->create($Support, ['type' => 'file', 'id' => 'contact_us', 'class' => 'contact_us', 'novalidate']);
                ?>  
              <div class="form-group">
              <!--<div class="selectstyle">-->
					<?php
					$options = Configure::read('AccessVia');
					echo $this->Form->input('access_via', ['type' => 'select', 'options' => $options,'empty' => 'Select Access Type',  'label' => false, 'class' => 'form-control']); ?>
              <!--</div>  -->
              </div>
              <div class="form-group">
              <!--<div class="selectstyle">-->
                <?php
				$category = Configure::read('SupportCategory');
				$readonly = ($defcategory != '') ? 'readonly' : '';
				
				echo $this->Form->input('support_category', ['type' => 'select', 'options' => $category,'empty' => 'Select Support Category',  'label' => false, 'class' => 'form-control', 'default' => $defcategory, $readonly]);
				 ?>
              <!--</div>  -->
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('email', ['value' => $email, 'label' => false, 'class' => 'form-control', 'placeholder' => 'Your email address']); ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('subject', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Subject', 'value' => $subject]); ?>
              </div>
              <div class="form-group">
                <?php
				echo $this->Form->input('message',['type'=>'textarea', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Message']);
				echo $this->Form->input('user_id', ['type' => 'hidden', 'value' => $user_id]);
			   ?> 
              </div>             
              <div class="form-group">
				
					<!--<div class="btn btn-file">
						  Attach file			 
						  <?php
						  //echo $this->Form->input('attachment',['type' => 'file', 'label' => false, 'class' => 'form-control-file', 'placeholder' => 'Attachment']);
						 ?> 
					</div>-->
					  
				  <div class="input-group">
					  <label class="input-group-btn">
						  <span class="btn btn-danger">
							  Browse&hellip; <input type="file" style="display: none;" name="attachment">
						  </span>
					  </label>
					  <input type="text" class="form-control" readonly id="attach" name="attach">
				  </div>
				  
			</div>
			  
			  
			  <input type="submit" class="btn btn-danger w94" value="Submit">
              </div>
              
            <?php echo  $this->Form->end(); ?>
          	</div>
        </div>
      </section>
    </div>
  </div>
</div>
<!-- end profile banner -->
<?php  echo $this->Common->loadJsClass('User'); ?>

<?php $this->Html->scriptStart(['block' => true]); ?>
    
	$(function() {	
	  // We can attach the `fileselect` event to all file inputs on the page
	  $(document).on('change', ':file', function() {
		var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	  });	
	  // We can watch for our custom `fileselect` event like this
	  $(document).ready( function() {
		    $(':file').on('fileselect', function(event, numFiles, label) {	
				var input = $(this).parents('.input-group').find(':text'),
				log = numFiles > 1 ? numFiles + ' files selected' : label;	
				if( input.length ) {
					input.val(log);
				} else {
					if( log ) console.log(log);
				}	
		   });
	  });
	  
	});
	
 <?php $this->Html->scriptEnd(); ?>