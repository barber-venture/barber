<?php
use Cake\Core\Configure; ?><!-- Edit Profile Design --> 
<!-- Modal -->
<div class="modal fade edit-profile-design basic-detail-popup animated2 fadeInDown2" id="myModa414" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        
        <div class="profile-edit-right basic-detail new-basic-info">
          <div class="rows">
            <h2>Update your profile </h2>
            <section>
              <ul>
                <li class="icon5"><a href="#"><i></i>Basic Profile <span> </span></a>
                    <div class="detail-info Interested2" style="display:block;">
                        <input type="hidden" id="user_detail_id" value="<?php echo $user['user_detail']['id']; ?>">
                        <form id="form_new_user_free">
                         
                           <div class="form-group">
	                           <label for="">Name</label>
                                <input disabled type="text" id="name" name="name" value="<?php echo $user['name']; ?>" class="form-control">
                           </div>
						   
			    <div class="form-group">
	                           <label for="">Email</label>
                                <input disabled type="text" id="email" name="email" value="<?php echo $user['email']; ?>" class="form-control">
                           </div>
						   
						   <div class="form-group">
	                           <label for="">Camera Used</label>
                                <input type="text" id="camera" name="camera" value="<?php echo $user['user_detail']['camera']; ?>" class="form-control">
                           </div>
                          
                           <div class="form-group"> 
                          		<label for="">Mobile</label>
                          		<input readonly type="text" id="free_mobile" name="mobile" value="<?php echo $user['user_detail']['mobile']; ?>" class="form-control">
                           </div>
                           
                           <div class="form-group">
                           		<label for="">Address</label>
                                <!--<input type="text" id="address" name="address" value="<?php echo $user['user_detail']['address']; ?>" class="form-control">-->
							<!--<textarea cols="46" id="autocomplete" name="address"><?php echo $user['user_detail']['address']; ?></textarea>-->
							
							<input type="text" class="form-control" id="address" placeholder="Location" name="address" value="<?php echo $user['user_detail']['address']; ?>">
                           </div>                           
                           <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile_freelancer('form_new_user_free');">Save</button>
                        </form>
                    </div>
                </li>       
              </ul>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
   
<?php

    $this->Html->script(["https://maps.googleapis.com/maps/api/js?key=AIzaSyDXXrSF7NlK0ZGSuRtfQv2VNgYMYCE8fyw&libraries=places"], ['block' => true]);
    	 
     $this->Html->scriptStart(['block' => true]);
     ?>  
    function updateProfile_freelancer(form_id){
		//console.log(form_id);
		//return false;
		var msg = '';         
		if(form_id == 'form_new_user_free'){
			if( ($('input[name="name"]').val()).trim() == ''){
				msg = "Please enter name.";
				
			}else if( ($('#camera').val()).trim() == ''){
				msg = "Please enter camera.";
				
			}else if( ($('#free_mobile').val()).trim() == ''){
				msg = "Please enter mobile number.";
				
			}else if(!$.isNumeric($('#free_mobile').val())){
				msg = "Please enter valid mobile number.";				
			
			}else if( ($('#address').val()).trim() == ''){
				msg = "Please enter address.";
			}
		}
          if(msg != ''){
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    icon: 'fa fa-check',
                    styling: 'fontawesome',
                    delay: 1000
                });
                return false;
          }
          
          
          var form_data =  $('#' + form_id).serialize();  
          $.ajax({
               url: SITE_URL + 'ajax/updateProfile/' + $('#user_detail_id').val(),
               type: 'POST',
               data: form_data,               
               beforeSend: function (response) {
                    $("#loadergreen").show();
               },
               success: function (result) {
                    if(result == 1){
                        location.reload();
                        //$('#myModa41').modal('hide');
                         new PNotify({
                            title: 'Success',
                            text: 'Changes saved successfully',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            delay: 1000
                        });
                        
                    }else{
                         new PNotify({
                            title: 'Error',
                            text: 'Error occured.',
                            type: 'error',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            delay: 1000
                        });
                    }                                  
               }
          })
     }
	var input = document.getElementById('address');
	var autocomplete = new google.maps.places.Autocomplete(input);
	 
<?php $this->Html->scriptEnd(); ?>