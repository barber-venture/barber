<?php

use Cake\Core\Configure; ?><!-- Edit Profile Design --> 
<!-- Modal -->
<div class="modal fade edit-profile-design basic-detail-popup animated2 fadeInDown2" id="myModa41" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div class="profile-edit-right basic-detail new-basic-info">
                    <div class="rows">
                        <h2>Complete your profile </h2>
                        <section>
                            <ul>
                                <li class="icon5"><a href="#"><i></i>Basic Profile Setup <span> </span></a>
                                    <div class="detail-info Interested2" style="display:block;">
                                        <input type="hidden" id="user_detail_id" value="<?php echo $user['user_detail']['id']; ?>">
                                        <form id="form_new_user">
                                            <label for="">Gender</label>
                                            <div class="form-group custom-check-radio">
                                                <div class="gender-filed">
                                                    <?php
                                                    $options = Configure::read('SiteSetting.Gender');
                                                    foreach ($options as $k => $Gender) {
                                                        ?>
                                                        <label class="control control--radio">
    <?php echo $Gender; ?>
                                                            <input type="radio" name="gender" value="<?php echo $k; ?>" <?php if ($user['user_detail']['gender'] == $k) echo 'checked'; ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
<?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Birthday </label>
												<div class="row">
                                                <div class="col-sm-4">
                                                    <select name="month" class="form-control" id="month"></select>
                                                </div>
                                                <div class="col-sm-4">    
                                                    <select name="day" class="form-control" id="day"></select>
                                                </div>
                                                <div class="col-sm-4">    
                                                    <select name="year" class="form-control" id="year"></select>
                                                </div>
                                                </div>    
                                               
                                            <!--<input readonly type="text" id="user-detail-dob" name="dob" value="<?php echo $user['user_detail']['dob']; ?>" class="form-control">-->
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nick Name</label>
                                                <input type="text" id="dob" name="nike_name" value="<?php echo $user['user_detail']['nike_name']; ?>" class="form-control">
                                            </div>

                                            <div class="form-group"> 
                                                <label for="">Mobile</label>
                                                <input readonly type="text" id="mobile" name="mobile" value="<?php echo $user['user_detail']['mobile']; ?>" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">About Me</label>
                                                <textarea class="form-control" name="about_me" id="new_about_me" rows="5" cols="30"><?php echo trim($user['user_detail']['about_me']); ?></textarea>
                                            </div>

                                            <button type="button" class="btn btn-default hvr-rectangle-in" onclick="updateProfile_new('form_new_user');">Save</button>
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
$this->Html->scriptStart(['block' => true]);
?>  
function updateProfile_new(form_id){

if(form_id == undefined) form_id='';
var msg = '';         
if(form_id == 'form_new_user'){
if(($('input[name="gender"]:checked').length) == 0){
    msg = "Please select gender.";
}
else if( $('#month').val() == ''){
    msg = "Please select month.";
}
else if( $('#day').val() == ''){
    msg = "Please select day.";
}
else if( $('#year').val() == ''){
    msg = "Please select year.";
}

//else if( ($('input[name="dob"]').val()).trim() == ''){
//msg = "Please select date of birth.";

//}
/* else if( ($('input[name="dob"]').val()).trim() != ''){
var subDate, userDate, today;
subDate = ($('input[name="dob"]').val()).trim();
var parts = subDate.split('/');           
userDate = new Date((parts[2]), parts[0]-1, parts[1]);
today = new Date();            
var age = Math.floor((today-userDate) / (365.25 * 24 * 60 * 60 * 1000));
console.log(age);
if(age < 18){
msg = "Age should be 18 or more.";
}                   
}*/
else if( ($('input[name="nike_name"]').val()).trim() == ''){
msg = "Please enter nick name.";
}else if( ($('input[name="mobile"]').val()).trim() == ''){
msg = "Please enter mobile number.";
}else if(!$.isNumeric($('input[name="mobile"]').val())){
msg = "Please enter valid mobile number.";
}else if( ($('#new_about_me').val()).trim() == ''){
msg = "Please enter about me.";
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


$( document ).ready(function() {
var year = new Date().getFullYear();

// load years
$("#year").append('<option value="">Year</option>');
for (var i=17; i<= 120 ; i++) $("#year").append('<option value=' + ( year - i ) + '>' +  ( year - i ) + '</option>');

// load months
$("#month").append('<option value="">Month</option>');
for (var i=1; i<=12; i++) $("#month").append('<option value=' + i + '>' + i + '</option>');

// load days
$("#day").append('<option value="">Day</option>');
for (var i=1; i<=31; i++) $("#day").append('<option value=' + i + '>' + i + '</option>');
});

$(function() {
$('#year').change(function() {
var now = new Date();
if ($('#year').val()==now.getFullYear()) {
$('#month option').each(function() {
if ($(this).val()>(now.getMonth()+1)) $(this).remove();
});
} else {
for (var i=1; i<13; i++)
if ($("#month option[value='" + i + "']").val()==undefined)
$("#month").append('<option value=' + i + '>' + i + '</option>');
}

checkMonth();
});

$('#month').change(checkMonth);
});

function checkMonth() {
var now = new Date();
if ($('#year').val()==now.getFullYear() && $('#month').val()==(now.getMonth()+1)) {
$('#day option').each(function() {
if ($(this).val()>now.getDate()) $(this).remove();
});
} else {
var days = 31;
var month = $('#month').val();
if (month==2) {
if (($('#year').val() % 4) == 0) days = 29; // leap year
else days = 28;
} else if (month==2 || month==4 || month==6 || month==9 || month==11) {
days = 30;
}
for (var i=1; i<32; i++)
if (i>days)
$("#day option[value='" + i + "']").remove();
else if ($("#day option[value='" + i + "']").val()==undefined)
$("#day").append('<option value=' + i + '>' + i + '</option>');
}
}
<?php $this->Html->scriptEnd(); ?>