/**
 * 
 * Block UI settings
 */
$.blockUI.defaults.message = '';
$.blockUI.defaults.css.border = 'none';
$.blockUI.defaults.css.padding = '0 15px 15px';
$.blockUI.defaults.css.backgroundColor = 'transparent';
$.blockUI.defaults.css.opacity = 1;
$.blockUI.defaults.css.cursor = 'default';
$.blockUI.defaults.overlayCSS.backgroundColor = '#fff';
$.blockUI.defaults.overlayCSS.cursor = 'default';
$.blockUI.defaults.overlayCSS.opacity = 0.75;
/**
 * 
 * Custome method for validation
 */
jQuery.validator.addMethod("pattern", function (value, element, param) {
    if (this.optional(element)) {
        return true;
    }
    if (typeof param === 'string') {
        param = new RegExp('^(?:' + param + ')$');
    }
    return param.test(value);
}, "Invalid format.");

jQuery.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please use letters only.");

jQuery.validator.addMethod("phoneUS", function (phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please specify a valid phone number");

$.validator.addMethod('lessThanEqual', function (value, element, param) {
    return this.optional(element) || parseInt(value) <= parseInt($(param).val());
}, "The value {0} must be less than {1}");
$.validator.addMethod('integer', function (value, element, param) {
    return (value >>> 0 === parseFloat(value) && value > 0);
}, 'Please enter a non zero integer value!');
/*
 * 
 * Common Class
 * Load common function on page
 */
var Common = function () {
    var loadCommonFunction = function () {
        $('.tooltips').tooltip(); //bootstrap's tooltip
    }
    var applyLinit = function () {
        $('body').on('change', '#limit', function () {
            $(this).closest("form").submit();

        });
    }
    return {
        init: function () {
            applyLinit();
            loadCommonFunction();
        }
    };
}();
/*
 * 
 * User
 * 
 */


var User = function () {


    var setDatePicker = function () {
        $('#user-detail-dob').datepicker({
            todayHighlight: true,
            endDate: new Date(),
            format: 'mm/dd/yyyy',
            autoclose: true,
        });
    }

    var updateProfile = function () {
       
     function updateProfile(form_id=''){
          var msg = '';
          if(form_id == 'form_about_me'){
               if( ($('textarea[name="about_me"]').val()).trim() == '')
                    msg = "Please add some text.";
          }else if(form_id == 'form_address'){
               if( ($('#home_town').val()).trim() == '')
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
          
          if(msg != ''){
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    icon: 'fa fa-check',
                    styling: 'fontawesome',
                    delay:1000
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
                    if(result){
                         new PNotify({
                            title: 'Success',
                            text: 'Changes saved successfully',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            delay:1000
                        });
                    }                                   
               }
          })
     }
    }

    return {
        init: function () {
            setDatePicker();
            updateProfile();
        }
    }
}();
/** 
 * 
 * Master Class
 */
var newuser = function () {
    var newuserPopu = function () {
        $('#myModa41').modal({
            show: 'true'
        });
    }
    return {
        init: function () {
            newuserPopu();

        }
    }
}();

$(document).ready(function () {
    Common.init();

    $('#selector1').on('change', function () {
        var limit = this.value;
        $.ajax({
            url: 'setLimt',
            type: 'POST',
            data: {limit: limit},
            success: function () {
                console.log("Data sent!");
            }
        });

        // or $(this).val()
    });
});

