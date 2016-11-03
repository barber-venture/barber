<?php
use Cake\Core\Configure;
echo $this->Html->css(array('front/ion.rangeSlider.css',
    'front/ion.rangeSlider.skinFlat.css'));
?>
<style>
    .pac-container{
        z-index: 50000000000;
    }
</style>
<!-- Edit Profile Design --> 
<!-- Modal -->

<div class="modal fade edit-profile-design animated2" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
        <div class="profile-edit-left">
          <div class="rows">
            <h2>Details</h2>
            <input type="hidden" id="user_detail_id" value="<?php echo $user['user_detail']['id']; ?>">
            <form id="form_about_me">
              <div class="form-group">
                <label for="exampleInputEmail">My message to the world:</label>
                <textarea class="form-control" rows="3" name="about_me" maxlength="250"> <?php echo $user['user_detail']['about_me']; ?></textarea>                
              </div>
              <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_about_me');">Save</button>
            </form>
            
            <!--<div class="cover-photo"> <a href="#">Cover photo <i> </i></a> </div>-->
            <div class="interested-block">
               <h3>What I'm interested in:</h3>               
                    <?php
                    echo $this->Form->create($user, ['url' => ['controller' => 'Users', 'action' => 'myProfile'], 'id' => 'usertagform']);
                    
                    echo $this->Form->input('user_tags._ids', ['options' => $UserTags, 'id' => 'select2', 'label' => false,/* 'empty' => 'Select Tag',*/ 'class' => 'form-control', 'multiple' => true, "onchange" => "updateProfile('usertagform');"]);
                    
                    echo $this->Form->end();
                    ?>
            </div>
               
          </div>
        </div>
          <div class="profile-edit-right">
          <div class="rows">
            <h2>Details</h2>
            <?php $options = Configure::read('Question'); ?>
            <section>
              <ul>
                <li class="icon1 active"><a href="#"><i></i>Hometown <span> </span></a>
                  <div class="detail-info hometown" style="display:block;">
                    <form id="form_address">
                      <div class="form-group">
                        <input type="text" class="form-control" id="autocomplete" placeholder="Location" name="address" value="<?php echo $user['user_detail']['address']; ?>">
                      </div>
                      <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_address');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon2"><a href="#"><i></i>Looking for <span> </span></a>
                  <div class="detail-info Interested">
                    <form id="form_interested_in_1">
                      <div class="form-group custom-check-radio">
                        <label for="exampleInputEmail1">Looking for</label>
                        <div class="gender-filed">
                         
                         <?php
                         $interested_in_gender_data = explode(',', $user['user_detail']['interested_in_gender']);
                         foreach($options['interested_in_gender'] as $k=>$interested_in_gender){ ?>
                          <label class="control control--checkbox">
                              <?php echo $interested_in_gender; ?>
                              <input name="interested_in_gender[]" <?php if(in_array($k, $interested_in_gender_data)) echo 'checked'; ?> type="checkbox" value="<?php echo $k; ?>" />
                              <div class="control__indicator"></div>
                          </label>                         
                          <?php } ?>
                          
                        </div>
                      </div>
                      <div class="form-group">
                        
                        <div class="form-group">
                              <input type="text" id="range" value="" name="range" /> 
                              <input type="hidden" id="interested_in_age_from" name="interested_in_age_from" value="">
                              <input type="hidden" id="interested_in_age_to" name="interested_in_age_to" value="">
                         </div>
                      </div>
                      <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_interested_in_1');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon3"><a href="#"><i></i>Interested in<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_interested_in_2">
                      <div class="form-group custom-check-radio">
                         <?php
                         $interested_in_activity_data = explode(',', $user['user_detail']['interested_in_activity']);
                         foreach($options['interested_in_activity'] as $k=>$interested_in_activity){ ?>
                              <label class="control control--checkbox">
                              <?php echo $interested_in_activity; ?>
                              <input type="checkbox" name="interested_in_activity[]" <?php if(in_array($k, $interested_in_activity_data)) echo 'checked'; ?> value="<?php echo $k;?>"/>
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                      </div>
                      <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_interested_in_2');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon4"><a href="#"><i></i>Relationship status<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_relationship_status">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['relationship_status'] as $k=>$relationship_status){ ?>
                              <label class="control control--radio">
                                 <?php echo $relationship_status;?>
                              <input type="radio" name="relationship_status" <?php if($user['user_detail']['relationship_status'] == $k) echo 'checked';?> value="<?php echo $k;?>"/>
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_relationship_status');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon5"><a href="#"><i></i>Appearance<span> </span></a>
                  
                  <div class="detail-info Interested">
                    <form id="form_appearance">
                      <div class="form-group">
                        <p class="age-text">
                         <!--<input type="text" id="cm" readonly>-->
                         
                         <!--<span>><?php echo Configure::read('Question.height_to'); ?> cm</span></p>-->
                        <div id="height"></div>
                        <input type="hidden" readonly name="height" id="height_val">
                        
                      </div>
                      <div class="form-group custom-check-radio">
                        <label for="exampleInputEmail1">Body Type</label>
                        <div class="gender-filed">
                          <?php foreach($options['body_type'] as $k=>$body_type){ ?>
                              <label class="control control--radio">
                              <?php echo $body_type; ?>
                              <input type="radio" name="body_type" value="<?php echo $k;?>" <?php if($user['user_detail']['body_type'] == $k) echo 'checked';?> />
                              <div class="control__indicator"></div>
                              </label>
                          <?php } ?>
                        </div>
                      </div>                      
                      <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_appearance');">Save</button>
                    </form>
                  </div>                  
                  
                </li>
                <li class="icon6"><a href="#"><i></i>Children<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_children">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['children'] as $k=>$children){ ?>
                              <label class="control control--radio">
                                 <?php echo $children;?>
                              <input type="radio" name="children" value="<?php echo $k;?>" <?php if($user['user_detail']['children'] == $k) echo 'checked';?> />
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_children');">Save</button>
                    </form>
                  </div>
                </li>
                <!--<li class="icon6"><a href="#"><i></i>Smoker<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_smoker">
                       <div class="form-group custom-check-radio">
                         <?php //foreach($options['smoker'] as $k=>$smoker){ ?>
                              <label class="control control--radio">
                                 <?php //echo $smoker;?>
                              <input type="radio" name="smoker" value="<?php //echo $k;?>" <?php //if($user['user_detail']['smoker'] == $k) echo 'checked';?> />
                              <div class="control__indicator"></div>
                              </label>
                         <?php //} ?>
                       </div>
                         <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_smoker');">Save</button>
                    </form>
                  </div>
                </li>-->
                <li class="icon7"><a href="#"><i></i>Religion<span> </span></a>
                 <div class="detail-info Interested2">
                    <form id="form_religion">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['religion'] as $k=>$religion){ ?>
                              <label class="control control--radio">
                                 <?php echo $religion;?>
                              <input type="radio" name="religion" value="<?php echo $k;?>" <?php if($user['user_detail']['religion'] == $k) echo 'checked';?> />
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_religion');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon8"><a href="#"><i></i>Living situation<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_living_situation">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['living_situation'] as $k=>$living_situation){ ?>
                              <label class="control control--radio">
                                 <?php echo $living_situation;?>
                              <input type="radio" name="living_situation" value="<?php echo $k;?>" <?php if($user['user_detail']['living_situation'] == $k) echo 'checked';?> />
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_living_situation');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon9"><a href="#"><i></i>Education<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_education">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['education'] as $k=>$education){ ?>
                              <label class="control control--radio">
                                 <?php echo $education;?>
                              <input type="radio" name="education" value="<?php echo $k;?>" <?php if($user['user_detail']['education'] == $k) echo 'checked';?>/>
                              <div class="control__indicator"></div>
                              </label>
                         <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_education');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon10"><a href="#"><i></i>Profession<span> </span></a>
                  <div class="detail-info Interested2">
                    <form id="form_profession">
                       <div class="form-group custom-check-radio">
                         <?php foreach($options['profession'] as $k=>$profession){ ?>
                              <label class="control control--radio">
                                 <?php echo $profession;?>
                              <input type="radio" name="profession" value="<?php echo $k;?>" <?php if($user['user_detail']['profession'] == $k) echo 'checked';?>/>
                              <div class="control__indicator"></div>
                              </label>
                          <?php } ?>
                       </div>
                       <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_profession');">Save</button>
                    </form>
                  </div>
                </li>
                <li class="icon11"><a href="#"><i></i>Languages<span> </span></a>
                    <div class="detail-info">                         
                         <div class="detail-info Interested2">
                         <form id="form_language">
                            <div class="form-group custom-check-radio">
                              <?php
                              $languageVal = explode(',', $user['user_detail']['language']);
                              foreach($language as $l_id=>$lang){ ?>
                                   <label class="control control--checkbox">
                                      <?php echo $lang;?>
                                   <input name="language[]" value="<?php echo $l_id;?>" type="checkbox" <?php if(in_array($l_id, $languageVal)) echo 'checked'; ?>/>
                                   <div class="control__indicator"></div>
                                   </label>
                               <?php } ?>

                            </div>
                            <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile('form_language');">Save</button>
                        </form>
                        
                         </div>
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
     $this->Html->script(['autoAddress'], ['block' => true]);     

     $this->Html->scriptStart(['block' => true]);
     ?>
     
     $(function(){
      // turn the element to select2 select style
          $('#select2').select2();
     });
     
     var min_val = (parseInt('<?php echo $user['user_detail']['interested_in_age_from']; ?>') > 0) ? parseInt('<?php echo $user['user_detail']['interested_in_age_from']; ?>') : parseInt('<?php echo Configure::read("Question.interested_in_age_from_default"); ?>') ;
     
     var max_val = (parseInt('<?php echo $user['user_detail']['interested_in_age_to']; ?>') > 0) ? parseInt('<?php echo $user['user_detail']['interested_in_age_to']; ?>') : parseInt('<?php echo Configure::read("Question.interested_in_age_to_default"); ?>') ;
     
     var min =  parseInt('<?php echo Configure::read("Question.interested_in_age_from_limit"); ?>') ;
     var max =  parseInt('<?php echo Configure::read("Question.interested_in_age_to_limit"); ?>') ;
          
      $("#range").ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            from: min_val,
            to: max_val,
            step: 1,
            postfix: ' Years',            
            onFinish: function (data) {
                $("#interested_in_age_from").val(data.from);
                $("#interested_in_age_to").val(data.to);                
            },
            
        });
      
      
      var height_min =  parseInt('<?php echo Configure::read("Question.height_from"); ?>') ;
      var height_max =  parseInt('<?php echo Configure::read("Question.height_to"); ?>') ;
      var current_height =  parseInt('<?php echo $user['user_detail']['height']; ?>');
      $("#height").ionRangeSlider({
          type: "single",
          min: height_min,
          max: height_max,
          from: current_height,
          step: 1,
          postfix: ' cm',
          onFinish: function (data) {
              $("#height_val").val( data.from );  
          },
          
      });      
      
     
     updateProfile = function (form_id){
          if(form_id == undefined) form_id='';
          var msg = '';          
          if(form_id == 'form_about_me'){
               if( ($('textarea[name="about_me"]').val()).trim() == '')
                    msg = "Please add some text.";
          }else if(form_id == 'form_address'){
               if( ($('#autocomplete').val()).trim() == '')
                    msg = "Please enter hometown.";
          }
          else if(form_id == 'form_interested_in_1'){
            
               if(($('input[name="interested_in_gender[]"]:checked').length) == 0)
                    msg = "Please choose atleast one interest.";            
          }          
          else if(form_id == 'form_interested_in_2'){               
               if(($('input[name="interested_in_activity[]"]:checked').length) == 0)
                    msg = "Please choose atleast one interest.";
          }
          else if(form_id == 'form_relationship_status'){
               if(($('input[name="relationship_status"]:checked').length) == 0)
                    msg = "Please choose atleast one relationship.";
          }
          else if(form_id == 'form_appearance'){
               if(($('input[name="height"]').val()) == 0)
                    msg = "Please select height.";
               else
               if(($('input[name="body_type"]:checked').length) == 0)
                    msg = "Please choose atleast one body type.";
          }
          else if(form_id == 'form_children'){
               if(($('input[name="children"]:checked').length) == 0)
                    msg = "Please choose status of children.";
          }
           else if(form_id == 'form_smoker'){
               if(($('input[name="smoker"]:checked').length) == 0)
                    msg = "Please choose smoking status.";
          }
           else if(form_id == 'form_religion'){
               if(($('input[name="religion"]:checked').length) == 0)
                    msg = "Please choose religion.";
          }
           else if(form_id == 'form_living_situation'){
               if(($('input[name="living_situation"]:checked').length) == 0)
                    msg = "Please choose living situation.";
          }
           else if(form_id == 'form_education'){
               if(($('input[name="education"]:checked').length) == 0)
                    msg = "Please choose education.";
          }
           else if(form_id == 'form_profession'){
               if(($('input[name="profession"]:checked').length) == 0)
                    msg = "Please choose profession.";
          }
          else if(form_id == 'form_language'){
               if(($('input[name="language[]"]:checked').length) == 0)
                    msg = "Please choose language.";
          }
          else if(form_id == 'usertagform'){
               if($('#select2').val() == '')
                    msg = "Please choose interest.";
          }
          if(msg != ''){
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    icon: 'fa fa-check',
                    styling: 'fontawesome',
                    hide: true,
                    delay: 2000
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
                         new PNotify({
                            title: 'Success',
                            text: 'Changes saved successfully',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    }else{
                         new PNotify({
                            title: 'Error',
                            text: 'Error occured.',
                            type: 'error',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    }                                   
               }
          })
     }
          
     /*
     function initialize() {         
          var input = document.getElementById('home_town');
          var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              // var place = autocomplete.getPlace();
            //    document.getElementById('user-detail-lat').value = place.geometry.location.lat();
            //    document.getElementById('user-detail-lng').value = place.geometry.location.lng();  
          });
     }
     google.maps.event.addDomListener(window, 'load', initialize);
     */
     
     <?php $this->Html->scriptEnd(); 

?>