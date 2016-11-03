$('body').on('click', '#forgetPassword', function () {
    $("#userLoginDiv").hide();
    $("#ForgotPasswordDiv").show();
});

$('body').on('click', '.login', function () {
    $("#loginErrorMessages").html('');
    $("#ForgotMessageDivSuccess").html('');
    document.getElementById('loginForm').reset();
    document.getElementById('ForgetPassword').reset();
    $("#ForgotPasswordDiv").hide();
    $("#userLoginDiv").show();
});

$('body').on('click', '.resp-tab-item', function () {
    $('.resp-tab-item').toggleClass('active');
});

$('body').on('click', '#loginClick', function () {
    $("#ForgotPasswordDiv").hide();
    $("#userLoginDiv").show();
});

$('#myModal2').on('hidden.bs.modal', function () {
    location.reload();
});

$('#myModalchat').on('hidden.bs.modal', function () {
    location.reload();
});

$('#modalGhost').on('hidden.bs.modal', function () {
    $('#ghostoptpion').prop('checked', true);
});

$('body').on('click', '#closedetailview, .exploreThumb', function () {
    $('body').removeClass('profile-popup-open');
    $('.album-popup').hide();
    $('.profilePopupOverlay').hide();
})

$('body').on('click', '.delete_noti, .profile_visit', function () {
    $this = $(this);
    $.ajax({
        url: SITE_URL + 'notifications/mark_read',
        type: 'GET',
        data: {'id' : $this.data('id')},
        beforeSend: function (response) {
            $("#loadergreen").show();
        },
        success: function (result) {
            $("#loadergreen").hide();
            if (result) {
                $this.parent().remove();
                $(".notification-list").show();
                var countt = $('.bell span').html();
                --countt;
                if (countt > 0){
                    $('.bell span').html(countt);
                }else{
                    $('.bell span').remove()
                }                
            }
        }
    })
});


$('body').on('click', '#freelancer_chat', function(){
    $this = $(this);
      
    $.ajax({
        url: SITE_URL + 'users/startChat',
        type: 'GET',
        data: {'to_user_id' : $this.data('to_user_id')},
        beforeSend: function (xhr) {
            $('.grid').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
        },
        success: function (result) {
            if (result == 1) {
                $('.comment').trigger('click');
                $('body').removeClass('profile-popup-open');
                $('.album-popup').hide();
                $('.profilePopupOverlay').hide();
            }else{
                alert('You do not have access to chat this user.');
            }
            $('.grid').unblock();
        }
    })
});


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

        //$.wijets.make(); 	// Make yo Widjit - see docs for more details.
        //prettyPrint(); 		//Apply Code Prettifier
        //$(".bootstrap-switch").bootstrapSwitch(); // Bootstrap Switches


        // Bootstrap JS
        //$('.popovers').popover({container: 'body', trigger: 'hover', placement: 'top'}); //bootstrap's popover
        $('.tooltips').tooltip(); //bootstrap's tooltip

        //Tabdrop
        //jQuery.expr[':'].noparents = function (a, i, m) {
        //    return jQuery(a).parents(m[3]).length < 1;
        //}; // Only apply .tabdrop() whose parents are not (.tab-right or tab-left)
        //$('.nav-tabs').filter(':noparents(.tab-right, .tab-left)').tabdrop();

        // Custom Checkboxes
        //$('.icheck input').iCheck({
        //    checkboxClass: 'icheckbox_minimal-blue',
        //    radioClass: 'iradio_minimal-blue'
        //});

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

    var validateLoginForm = function () {
        $('.login-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                }
            }, messages: {
                'email': {
                    required: 'Please enter an email.',
                    email: "Please enter a valid email."
                },
                'password': {
                    required: 'Please enter password.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                $.ajax({
                    url: SITE_URL + 'users/login',
                    type: 'POST',
                    data: $('#loginForm').serialize(),
                    beforeSend: function (response) {
                        $("#loadergreen").show();
                    },
                    success: function (result) {

                        if (result == 1) {

                            $("#loginErrorMessages").html('<div class="alert alert-dismissable alert-success" > &nbsp;  <span>You are successfully login.</span> </div>');

                            //setInterval(function () {
                            //    window.location = SITE_URL + 'users/myProfile';
                            //}, 2000);
                            window.location = SITE_URL + 'users/myProfile';

                        } else if (result == 4) {

                            $("#loginErrorMessages").html('<div class="alert alert-dismissable alert-success" > &nbsp;  <span>You are successfully login.</span> </div>');

                            //setInterval(function () {
                            //    window.location = SITE_URL + 'users/photographerMyProfile';
                            //}, 2000);
                            window.location = SITE_URL + 'users/photographerMyProfile';

                        } else if (result == 2) {

                            $("#loginErrorMessages").html('<div class="alert alert-dismissable alert-danger">&nbsp;<span>Your account is deactivated. Please contact support.</span></div>');
                            $("#loadergreen").hide();

                        } else if (result == 3) {

                            $("#loginErrorMessages").html('<div class="alert alert-dismissable alert-danger">&nbsp;<span>Your account is deleted. Please contact support.</span></div>');
                            $("#loadergreen").hide();

                        } else {

                            $("#loginErrorMessages").html('<div class="alert alert-dismissable alert-danger">&nbsp;<span>Email or password is incorrect.</span></div>');
                            $("#loadergreen").hide();

                        }

                        return false;
                    }
                })
            }
        });
    }


    var validateForgetForm = function () {
        $('.ForgetPassword').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'email': {
                    required: true,
                    email: true
                }
            }, messages: {
                'email': {
                    required: 'Please enter an email.',
                    email: "Please enter a valid email."
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                $.ajax({
                    url: SITE_URL + 'users/forgotPassword',
                    type: 'POST',
                    data: $('#ForgetPassword').serialize(),
                    beforeSend: function (response) {
                        $("#loadergreenf").show();
                    },
                    success: function (result) {
                        if (result == 'succes') {
                            $("#ForgotMessageDivSuccess").html('<div class="alert alert-dismissable alert-success alert-successs"> &nbsp;<span>You will receive an email with instructions about how to reset your password in a few minutes.</span></div>');
                            data.reset();
                        } else {
                            $("#ForgotMessageDivSuccess").html('<div class="alert alert-dismissable alert-danger"  > &nbsp;  <span>' + result + '</span> </div>');
                        }
                        $("#loadergreenf").hide();
                        return false;
                    }
                })
            }
        });
    }
    var validateSignupForm = function () {

        $('.signup-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {
                        url: SITE_URL + 'ajax/checkEmailAvailibility/' + $('#email').val(),
                        type: 'GET'
                    }
                },
                'password': {
                    required: true,
                    minlength: 6
                },
                'con_password': {
                    required: true,
                    equalTo: "#password"
                }
            }, messages: {
                'name': {
                    required: 'Please enter name.',
                },
                'email': {
                    required: 'Please enter email address.',
                    email: "Please enter a valid email.",
                    remote: "This email address already exists"
                },
                'password': {
                    required: 'Please enter new password.',
                    minlength: 'New password must be at least 6 characters long'
                },
                'con_password': {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and confirm password not match."
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                $.ajax({
                    url: SITE_URL + 'users/signUp',
                    type: 'POST',
                    data: $('.signup-form').serialize(),
                    beforeSend: function (response) {
                        $("#loadergreens").show();
                    },
                    success: function (result) {


                        if (result == 'succes' || result == 'freelancer') {
                            var msgg = 'You are successfully signup.';
                            if (result == 'succes')
                                msgg += 'please check your email to verify your account.';
                            $(".alert-danger").hide();
                            $(".alert-success").show();
                            $("#SuccessMessages").html(msgg);
                            $("#loadergreens").hide();
                            data.reset();
//                            setInterval(function () {
//                                window.location = SITE_URL + 'users/myProfile';
//                            }, 2000);

                        } else {
                            $("#ErrorMessages").html(result);
                            $(".alert-danger").show();
                            $("#loadergreens").hide();

                        }

                        return false;
                    }
                })
            }
        });
    }
    var changePaswordFormValidate = function () {
        $('.change-password').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'password': {
                    required: true,
                    minlength: 6
                },
                'con_password': {
                    required: true,
                    equalTo: "#password"
                }
            }, messages: {
                'password': {
                    required: 'Please enter new password.',
                    minlength: 'New password must be at least 6 characters long'
                },
                'con_password': {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and confirm password not match."
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var resetFormValidate = function () {
        $('.reset-password-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'password': {
                    required: true,
                    minlength: 6
                },
                'con_password': {
                    required: true,
                    equalTo: "#password"
                }
            }, messages: {
                'password': {
                    required: 'Please enter new password.',
                    minlength: 'New password must be at least 6 characters long'
                },
                'con_password': {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and confirm password not match."
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-xs-12').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-xs-12").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var editProfileFormValidate = function () {
        $('.edit-profile-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true,
                },
                'user_detail[profile_image]': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG"
                },
                'user_detail[phone]': {
                    required: true,
                    phoneUS: true,
                    maxlength: 15,
                },
                'user_detail[mobile]': {
                    required: true,
                    phoneUS: true,
                    maxlength: 15,
                },
                'user_detail[city_id]': {
                    required: true,
                },
                'user_detail[country_id]': {
                    required: true,
                },
                'password': {
                    minlength: 6
                },
                'con_password': {
                    equalTo: "#password"
                }
            }, messages: {
                'name': {
                    required: 'Please enter full name.',
                },
                'user_detail[profile_image]': {
                    extension: 'Please select jpg, jpeg, png image.'
                },
                'user_detail[phone]': {
                    required: 'Please enter phone number.',
                    maxlength: 'phone number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid phone number',
                },
                'user_detail[mobile]': {
                    required: 'Please enter mobile number.',
                    maxlength: 'Mobile number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid mobile number',
                },
                'user_detail[city_id]': {
                    required: 'Please enter city.',
                },
                'user_detail[country_id]': {
                    required: 'Please select country.',
                },
                'password': {
                    minlength: 'New password must be at least 6 characters long'
                },
                'con_password': {
                    equalTo: "New password and confirm password not match."
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.input").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var setDatePicker = function () {
        $('#user-detail-dob').datepicker({
            todayHighlight: true,
            endDate: new Date(),
            format: 'mm/dd/yyyy',
            endDate: '+0d',
                    autoclose: true,
        });
    }

    var addEditUser = function () {
        $('.addEditUser').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {
                        url: SITE_URL + 'admin/ajax/checkEmailAvailibility/' + $('#email').val(),
                        type: 'GET'
                    }
                },
                'user_detail[nike_name]': {
                    required: true,
                },
                //'user_detail[about_me]': {
                //    required: true,
                //},
                'user_detail[dob]': {
                    required: true,
                },
                'user_detail[profile_image]': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG"
                },
                'user_detail[phone]': {
                    required: true,
                    phoneUS: true,
                    maxlength: 15,
                },
                'user_detail[mobile]': {
                    required: true,
                    phoneUS: true,
                    maxlength: 15,
                },
                'user_detail[city_id]': {
                    required: true,
                },
                'user_detail[state_id]': {
                    required: true,
                },
                'user_detail[country_id]': {
                    required: true,
                },
                'password': {
                    required: true,
                    minlength: 6
                },
                'con_password': {
                    equalTo: "#password",
                    minlength: 6
                },
                '#user_tags': {
                    required: true,
                }
            }, messages: {
                'name': {
                    required: 'Please enter full name.',
                },
                'email': {
                    required: 'Please enter email.',
                    email: 'Enetr a valid email.',
                    remote: 'Email already Exist.'
                },
                'user_detail[nike_name]': {
                    required: 'Please enter nick name.',
                },
                //'user_detail[about_me]': {
                //    required: 'Please enter about me text.',
                //},
                'user_detail[dob]': {
                    required: 'Please Select Date of Birth.'
                },
                'user_detail[profile_image]': {
                    extension: 'Please select jpg, jpeg, png image.'
                },
                'user_detail[phone]': {
                    required: 'Please enter phone number.',
                    maxlength: 'phone number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid phone number',
                },
                'user_detail[mobile]': {
                    required: 'Please enter mobile number.',
                    maxlength: 'Mobile number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid mobile number',
                },
                'user_detail[city_id]': {
                    required: 'Please select city.',
                },
                'user_detail[state_id]': {
                    required: 'Please select state.',
                },
                'user_detail[country_id]': {
                    required: 'Please select country.',
                },
                'password': {
                    required: 'Please enter Password.',
                    minlength: 'Password must be at least 6 characters long'
                },
                'con_password': {
                    equalTo: "Password and confirm password not match."
                },
                '#user_tags': {
                    required: "Please select User Tags."
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.input").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }

    var sendContfirmEmail = function () {

        $('body').on('click', '#contfirmEmail', function (event) {
            event.preventDefault();
            $this = $(this);
            $this.parents('ul').block();
            $.ajax({
                url: SITE_URL + 'ajax/contfirmEmail',
                type: 'GET',
                beforeSend: function (xhr) {
                    $this.block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                },
                success: function (data) {
                    if (data == 'succes') {
                        new PNotify({
                            title: 'Success',
                            text: 'Confirm mail has been successfully sent.',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome'
                        });
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'You have reached the limit for confirmation e-mails today. But come again tomorrow!.',
                            type: 'error',
                            icon: 'fa fa-check',
                            styling: 'fontawesome'
                        });
                    }

                    $this.unblock()
                    $this.parents('ul').unblock();
                    console.log(data);

                }
            })
        });
    }

    var getStateFromCountry = function () {
        $('body').on('change', '#country_id', function () {

            $this = $(this);

            if ($(this).val() != '') {
                $.ajax({
                    url: SITE_URL + 'admin/ajax/getStateFromCountry/' + $this.val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        console.log(data);
                        $("#state_id option").remove();

                        $("#state_id").append(data);
                    }
                })
            }
        });
    }

    var getCitiesFromState = function () {
        $('body').on('change', '#state_id', function () {

            $this = $(this);

            if ($(this).val() != '') {
                $.ajax({
                    url: SITE_URL + 'admin/ajax/getCitiesFromState/' + $this.val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        console.log(data);
                        $("#city_id option").remove();

                        $("#city_id").append(data);
                    }
                })
            }
        });
    }

    var contactUs = function () {
        $('.contact_us').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'access_via': {
                    required: true,
                },
                'support_category': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true,
                    //remote: {
                    //    url: SITE_URL + 'ajax/contactUsCheckEmailAvailibility/' + $('#email').val(),
                    //    type: 'GET'
                    //}
                },
                'subject': {
                    required: true,
                },
                'attach': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG|doc|docx|pdf|xls|xlsx"
                },
                'message': {
                    required: true,
                }
            }, messages: {
                'access_via': {
                    required: 'Please select access type.',
                },
                'email': {
                    required: 'Please enter email.',
                    email: 'Enetr a valid email.',
                    //remote: 'Email address not registered.',
                },
                'support_category': {
                    required: 'Please select category.',
                },
                'subject': {
                    required: 'Please enter subject.',
                },
                'message': {
                    required: 'Please enter message.'
                },
                'attach': {
                    extension: 'Please select image, doc and pdf files.'
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var validateSettingForm = function () {

        $('#setting_form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {
                        url: SITE_URL + 'ajax/checkUserEmailAvailibility/' + $('#email').val(),
                        type: 'GET'
                    }
                },
                'user_detail[dob]': {
                    required: true,
                    checkage: 18
                },
                'user_detail[address]': {
                    required: true,
                }
            }, messages: {
                'name': {
                    required: 'Please enter name.',
                },
                'email': {
                    required: "Please enter email.",
                    email: "Please enter a valid email.",
                    remote: "This email address already exists"
                },
                'user_detail[dob]': {
                    required: 'Please select Date of birth.',
                    checkage: 'Age should be 18 or above.'
                },
                'user_detail[address]': {
                    required: 'Please enter address.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            }
        });

        $.validator.addMethod("checkage", function (value, element, params) {
            var subDate, userDate, today;
            subDate = $('#user-detail-dob').val(); // some function that returns the user's birthdate as a Date
            var parts = subDate.split('/');
            userDate = new Date((parts[2]), parts[0] - 1, parts[1]);
            today = new Date();
            today = new Date();
            var age = Math.floor((today - userDate) / (365.25 * 24 * 60 * 60 * 1000));
            return age >= 18;
        }, "You must be over 18 to enter");
    }

    var validatePasswordForm = function () {

        $('#password_form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'current_password': {
                    required: true,
                    remote: {
                        url: SITE_URL + 'ajax/checkcurrentpassword/' + $('#current-password').val(),
                        type: 'GET'
                    }
                },
                'password': {
                    required: true,
                    minlength: 6
                },
                'con_password': {
                    required: true,
                    equalTo: "#password"
                }
            }, messages: {
                'current_password': {
                    required: 'Please enter current password.',
                    remote: 'Current password is incorrect.'
                },
                'password': {
                    required: 'Please enter new password.',
                    minlength: 'New password must be at least 6 characters long'
                },
                'con_password': {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and confirm password do not match."
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            }
        });
    }

    var setting = function () {
        $(document).on('click', '#editprofil', function () {
            $('#MyAccount').html($('#editinfo').html());
            setDatePicker();
            initAutocomplete();
            validateSettingForm();
        });

        $(document).on('click', '#closeicon', function () {
            $('#MyAccount').html($('#MyAccountDetail').html());
        });

        $('#MyAccount').html($('#MyAccountDetail').html());

        $('#ghostoptpion').click(function () {
            if ($(this).is(':checked')) {
                setghoststatus(0);
            } else {
                setghoststatus(1);
            }
        });
       
        function setghoststatus(status) {
            $.ajax({
                url: SITE_URL + '/users/setGhost',
                type: 'GET',
                data: {'status': status},
                success: function (res) {
                    var statustxt = (status) ? 'Activated' : 'Deactivated';
                    if (res == 'purchase') {
                        $('#modalGhost').modal({
                            show: 'true',
                        });
                    } else if (res == 'done') {
                        $('.alert').remove();
                        $('.tab-content').prepend('<div class="alert alert-dismissable alert-success"> <i class="ti ti-close"></i>Ghost mode successfully ' + statustxt + '!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></div>');
                    } else {
                        $('.alert').hide();
                        $('.tab-content').prepend('<div class="alert alert-dismissable alert-danger"> <i class="ti ti-close"></i>Error occured!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></div>');
                    }
                }
            });
        }
    }

    var validatepurchaseForm = function () {

        $('#purchaseForm').validate({
            rules: {
                'name_on_card': {
                    required: true,
                },
                'card_type': {
                    required: true,
                },
                'card_number': {
                    required: true,
                },
                'exp_month': {
                    required: true,
                },
                'exp_year': {
                    required: true,
                },
                'cvv': {
                    required: true,
                },
            },
            messages: {
                'name_on_card': {
                    required: 'Please provide name on card.',
                },
                'card_type': {
                    required: 'Please select card type.',
                },
                'card_number': {
                    required: 'Please provide valid card number.',
                },
                'exp_month': {
                    required: 'Please select valid expire month of card.',
                },
                'exp_year': {
                    required: 'Please select valid expire year of card.',
                },
                'cvv': {
                    required: 'Please provide valid CVV.',
                },
            }
        });
    }

    return {
        init: function () {
            validateLoginForm();
            changePaswordFormValidate();
            resetFormValidate();
            editProfileFormValidate();
            setDatePicker();
            contactUs();
            getStateFromCountry();
            getCitiesFromState();
            validateSignupForm();
            validateForgetForm();
            sendContfirmEmail();
            validateSettingForm();
            validatePasswordForm();
            setting();
            validatepurchaseForm();
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
            show: 'true',
            backdrop: 'static',
            keyboard: false
        });
    }
    return {
        init: function () {
            newuserPopu();

        }
    }
}();

var profileAlbum = function () {
    var albumdetail = function () {
        $('body').on('click', '.uploadAlbum', function () {
            var imageID = $(this).data('id');
            var albumId = $(this).data('album');

            if (albumId == 'new') {

                $.ajax({
                    url: SITE_URL + 'ajax/createalbum/',
                    type: 'GET',
                    beforeSend: function (xhr) {

                    },
                    success: function (data) {
                        $("#divcontent").html(data);
                        $('#myModal3').modal('show');
                        createAlbums.init();
                    }
                })

            } else {
                albumPopupCreate(albumId, imageID);
                // $('#myModal3').modal('show');
            }
        });

        $(document).on('click', 'button.star_empty', function () {
            $this = $(this);
            $.ajax({
                type: 'post',
                url: SITE_URL + 'ajax/makeprofilepic',
                data: {'id': $this.attr('img_id')},
                dataType: "json",
                beforeSend: function (xhr) {
                    $this.parent().block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
                },
                success: function (res) {
                    if (res.status == '1') {
                        var img_name = $this.attr('img_name');
                        setImage(img_name);
                        $('.star_fill').addClass('star_empty').removeClass('star_fill');
                        $this.addClass('star_fill').removeClass('star_empty');
                        new PNotify({
                            title: 'Success',
                            text: 'Profile image successfully updated.',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Error occured.',
                            type: 'error',
                            icon: 'fa fa-close',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    }
                    $this.parent().unblock();
                }
            });
        });

        $(document).on('click', 'button.delete_image', function () {
            $this = $(this);
            $.ajax({
                type: 'post',
                url: SITE_URL + 'ajax/removeimage',
                data: {'id': $this.attr('img_id'), 'album_id' : $this.data('album_id')},
                dataType: "json",
                beforeSend: function (xhr) {
                    $this.parent().block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
                },
                success: function (res) {
                    if (res.status == '1') {
                        $this.parent().remove();
                        if (res.delete_up_icon == 1) {
                            $('.loaderpic').show();
                        }
                        if (res.img_id != '') {
                            $('.star_empty').each(function () {
                                if ($(this).attr('img_name') == res.img_id) {
                                    $(this).removeClass('star_empty').addClass('star_fill');    
                                }
                            });
                            setImage(res.img_id);
                        }
                        
                        if (res.delete_profile_img == 1) {
                            setImage('no-user.png', 'cover1.jpg');
                        }
                        if (res.delete_thumb == 1) {
                            removeThumb($this.data('album_id'));
                        }                        

                        new PNotify({
                            title: 'Success',
                            text: 'Image successfully deleted.',
                            type: 'success',
                            icon: 'fa fa-check',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Error in image delete.',
                            type: 'error',
                            icon: 'fa fa-close',
                            styling: 'fontawesome',
                            hide: true,
                            delay: 1000
                        });
                    }
                    $this.parent().unblock();
                }
            });
        });
    }
    return {
        init: function () {
            albumdetail();

        }
    }

    /////
}();

var createAlbums = function () {

    var createAlbum = function () {

        $('#createAlbumForm').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true,
                },
                'description': {
                    required: true,
                }
            }, messages: {
                'email': {
                    required: 'Please enter album name.',
                },
                'password': {
                    required: 'Please enter album description.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.form-group').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {

                $.ajax({
                    url: SITE_URL + 'ajax/createalbumpost/',
                    type: 'POST',
                    data: $('#createAlbumForm').serialize(),
                    beforeSend: function (response) {
                        // $("#loadergreen").show();
                    },
                    success: function (result) {
                        result = $.parseJSON(result);
                        if (result.status == 1) {
                            numItems = $('.thumb').length;
                            numItems++;
                            console.log('#thumb_parent li:nth-child(' + numItems + ') ');
                            $('#thumb_parent li:nth-child(' + numItems + ') ').attr('id', 'thumb_li_' + result.id);
                            $('#thumb_parent li:nth-child(' + numItems + ') ').addClass('thumb');
                            albumPopupCreate(result.id, 'new');
                        }
                        return false;
                    }
                })
            }
        });
    }
    return {
        init: function () {

            createAlbum();

        }
    }

}();
var freelancernewuser = function () {
    var freelancernewuserPopu = function () {
        $('#myModa414').modal({
            show: 'true',
            backdrop: 'static',
            keyboard: false
        });
        initAutocomplete();
    }
    return {
        init: function () {
            freelancernewuserPopu();

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

var Photographer = function () {
    var processing;
    var PhotographerFunction = function () {

        $(window).scroll(function () {

            var lastID = $('.flag').attr('lastID');
            if (processing)
                return false;

            if ($(window).scrollTop() + $(window).height() + 500 >= $(document).height()) {
                processing = true;
                $this = $(this);
                $this.parents('ul').block();
                var nextPage = $("#nextPage").val();
                var totalPage = $("#totalPage").val();

                if (totalPage >= nextPage) {
                    $.ajax({
                        url: SITE_URL + 'users/loadMoreSearch',
                        type: "GET",
                        data: {rowcount: $("#rowcount").val(), nextPage: nextPage, },
                        beforeSend: function () {

                            $("#searchLoader").show();
//                        $this.block();
                        },
                        complete: function () {
                            $this.unblock()
                            $this.parents('ul').unblock();
                            $("#searchLoader").hide();
                        },
                        success: function (data) {
                            processing = false;

                            $("#grid").append(data);
                            $this.unblock()
                            $this.parents('ul').unblock();
                            $("#searchLoader").hide();
                        },
                        error: function () {}
                    });
                }

            }
        });

        $(document).ready(function () {
            searchResult();
        })

    }
    function searchResult() {

        var totalPage = $("#totalPage").val();
        var nextPage = $("#nextPage").val();

        if (totalPage >= nextPage) {
            $.ajax({
                url: SITE_URL + 'users/loadMoreSearchPhotographer',
                type: "GET",
                data: {rowcount: $("#rowcount").val(), nextPage: nextPage},
                beforeSend: function () {
                    $("#searchLoader").show();
                },
                complete: function () {

                    $("#searchLoader").hide();
                },
                success: function (data) {
                    processing = false;
                    $("#grid").html(data);
                    $("#searchLoader").hide();
                },
                error: function () {}
            });
        }
    }

    return {
        init: function () {
            PhotographerFunction();

        }
    };
}();


var Search = function () {
    var processing;
    var SearchFunction = function () {
       
        $('.album-popup').bind('click', function () {
            //$('body').removeClass('profile-popup-open');
            //$('.album-popup').hide();
            //$('.profilePopupOverlay').hide();
        });

        $("#load_content, #refertoFriend").bind("click", function (event) {
            //event.stopPropagation();
        });

        $('body').on('click', '#suggest', function (e) {
            $('#refertoFriend').show();
        });
        $('body').on('click', '#close_suggest', function () {
            $('#refertoFriend').hide();
            $('#suggest_friend_form').show();
            $('.success').hide();
            $('.danger').hide();
            $('#suggest_friend_form')[0].reset();
            var validator1 = $("#suggest_friend_form").validate();
            validator1.resetForm();
            $('.form-group').removeClass('has-error');
        });


        $('#suggest_friend_form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'friend_email': {
                    required: true,
                    email: true
                },
                'friend_name': {
                    required: true,
                }
            }, messages: {
                'friend_email': {
                    required: 'Please enter an email.',
                    email: "Please enter a valid email."
                },
                'friend_name': {
                    required: "Please enter Friend's Name.",
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                var name = $('#friend_name').val();
                $.ajax({
                    url: SITE_URL + 'users/refer_friend',
                    type: 'GET',
                    data: $('#suggest_friend_form').serialize(),
                    beforeSend: function (xhr) {
                        $('#refertoFriend').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
                    },
                    success: function (res) {
                        $('#refertoFriend').unblock();
                        $('#suggest_friend_form')[0].reset();
                        $('#suggest_friend_form').hide();
                        if (res == 1) {
                            $('.success').show();
                        } else {
                            $('.danger').show();
                        }
                    }
                })
            }
        });

        $("#range").ionRangeSlider({
            type: "double",
            min: 18,
            max: 60,
            from: 18,
            to: 60,
            step: 1,
            postfix: ' Year',
            onFinish: function (data) {
                if ($('#discover').is(":checked")){
                    $("#fromValue").val(data.from);
                    $("#toValue").val(data.to);
                    $("#nextPage").val(1);
                    $("#totalPage").val(1);
                    searchResult();
                }
            },
            onUpdate: function (data) {
            }
        });

        $(window).scroll(function () {
            
            var loading_img_hide = $('#not_found').val(); //for record not found case
            if (loading_img_hide == '1')
            {                
                var lastID = $('.flag').attr('lastID');
    
                if (processing)
                    return false;

                if ($(window).scrollTop() + $(window).height() + 500 >= $(document).height()) {
                    processing = true;
                    $this = $(this);
                    $this.parents('ul').block();
    
                    var gender = [];
                    $('input[name="lookingFor[]"]:checked').each(function () {
                        gender.push(this.value);
                    });
                    var fromValue = $("#fromValue").val();
                    var toValue = $("#toValue").val();
                    var verifiedUser;
                    var discover;
                    var nextPage = $("#nextPage").val();
                    var totalPage = $("#totalPage").val();
                    if ($('#verifiedUser').is(":checked"))
                    {
                        verifiedUser = $("#verifiedUser").val();
                    }
    
                    if ($('#discover').is(":checked"))
                    {
                        discover = $("#discover").val();
                    }
                    var keyword = $("#keyword").val();
                    if (totalPage >= nextPage) {
                        $.ajax({
                            url: SITE_URL + 'users/loadMoreSearch',
                            type: "GET",
                            data: {rowcount: $("#rowcount").val(), nextPage: nextPage, gender: gender, fromValue: fromValue, toValue: toValue, verified: verifiedUser, discover: discover, keyword: keyword},
                            beforeSend: function () {
                                $("#searchLoader").show();
    //                        $this.block();
                            },
                            complete: function () {
                                $this.unblock()
                                //$this.parents('ul').unblock();
                                $("#searchLoader").hide();
                            },
                            success: function (data) {
                                processing = false;
                                if (data == 'not') {
                                    $('#not_found').val('0');
                                    data = '<li style="margin-left: 30% !important;display:block; ">Nobody around you.</li>';
                                    $("#grid").html(data); 
                                }else{
                                    $("#grid").append(data);
                                }
                                
                                //$this.unblock()
                                //$this.parents('ul').unblock();
                                $("#searchLoader").hide();
                            },
                            error: function () {}
                        });
                    }
    
                }
            }
        });

        $(document).ready(function () {
            searchResult();

            $('body').on('change', '.gender', function () {
                if ($('#discover').is(":checked")){
                    $("#nextPage").val(1);
                    $("#totalPage").val(1);
                    searchResult();
                }
            });
            $('body').on('change', '#verifiedUser', function () {
                    if ($('#discover').is(":checked")){
                    $("#nextPage").val(1);
                    $("#totalPage").val(1);
                    searchResult();
                }

            });
            $('body').on('change', '#discover', function () {
                $("#nextPage").val(1);
                $("#totalPage").val(1);
                searchResult();

            });

            $('body').on('keyup', '#keyword', function () {
                if ($('#discover').is(":checked")){
                    $("#nextPage").val(1);
                    $("#totalPage").val(1);
                    searchResult(function () {
                    }, 5000);
                }
            });

            $(document).on('click', '.exploreThumb', function () {
                                
                $this = $(this);
                $.ajax({
                    url: SITE_URL + 'users/getuserdetail',
                    type: "GET",
                    async: false,
                    data: {user_id: $this.attr('user')},
                    beforeSend: function () {
                        //$("#searchLoader").show();
                        $('.exploreParent').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});

                    },
                    success: function (data) {
                        $('#suggested_user_id').val($this.attr('user'));
                        $('body').addClass('profile-popup-open');
                        $('#popup').show();

                        $('#refertoFriend').hide();
                        $('#suggest_friend_form')[0].reset();
                        var validator1 = $("#suggest_friend_form").validate();
                        validator1.resetForm();
                        $('.form-group').removeClass('has-error');

                        $('#load_content').html(data);
                    
                    
                        var $grid = $('.grid-popup').imagesLoaded(function () {
                            $grid.masonry({
                                itemSelector: '.grid-item',
                                percentPosition: true,
                                columnWidth: '.grid-sizer'
                            });
                        });

                        $('#boxscroll2').slimscroll({
                            wheelStep: 2,
                            color: '#ed1a3b',
                        });
                        
                        //$("#searchLoader").hide();
                        $('.exploreParent').unblock();

                    },
                    error: function () {}
                });
                
                $.ajax({
                    url: SITE_URL + 'users/sendProfileVisitNotification',
                    type: "GET",
                    async: false,
                    data: {user_id: $this.attr('user')},
                    beforeSend: function () {
                    },
                    success: function (data) {
                        
                    }
                  });

            });

        })

    }
    function searchResult() {
        
        var gender = [];
        $('input[name="lookingFor[]"]:checked').each(function () {
            gender.push(this.value);
        });
        var fromValue = $("#fromValue").val();
        var toValue = $("#toValue").val();
        var verifiedUser;
        var discover;
        var totalPage = $("#totalPage").val();
        var nextPage = $("#nextPage").val();

        if ($('#verifiedUser').is(":checked"))
        {
            verifiedUser = $("#verifiedUser").val();
        }

        if ($('#discover').is(":checked"))
        {
            discover = $("#discover").val();
        }
        var keyword = $("#keyword").val();
               
        if (totalPage >= nextPage) {
            $.ajax({
                url: SITE_URL + 'users/loadMoreSearch',
                type: "GET",
                data: {rowcount: $("#rowcount").val(), nextPage: nextPage, gender: gender, fromValue: fromValue, toValue: toValue, verified: verifiedUser, discover: discover, keyword: keyword},
                beforeSend: function () {
                    $("#searchLoader").show();

                },
                complete: function () {
                    $("#searchLoader").hide();
                },
                success: function (data) {
                    processing = false;                   
                    if (data == 'not') {
                        data = '<li style="margin-left: 30% !important; display:block; ">Nobody around you.</li>';
                    }
                    $("#grid").html(data);
                    $("#searchLoader").hide();
                },
                error: function () {}
            });
        }
    }

    return {
        init: function () {
            SearchFunction();

        }
    };
}();


var Explore = function () {

    var ExploreFunction = function () {

        $("#range").ionRangeSlider({
            type: "double",
            min: 18,
            max: 60,
            from: 18,
            to: 60,
            step: 1,
            postfix: ' Year',
            onFinish: function (data) {
                $("#fromValue").val(data.from);
                $("#toValue").val(data.to);
                if ($('#discover').is(":checked"))
                    searchResult();
            },
        });

        $(document).ready(function () {
            searchResult();

            $('body').on('keyup', '#keyword', function () {
                searchResult();
            });

            $('body').on('change', '.gender', function () {
                if ($('#discover').is(":checked"))
                    searchResult();
            });
            $('body').on('change', '#verifiedUser', function () {
                if ($('#discover').is(":checked"))
                    searchResult();
            });
            $('body').on('change', '#discover', function () {
                //if ($('#discover').is(":checked"))
                searchResult();
            });


            $('body').on('click', '#suggest', function (e) {
                $('#refertoFriend').show();
            });
            $('body').on('click', '#close_suggest', function () {
                $('#refertoFriend').hide();
                $('#suggest_friend_form').show();
                $('.success').hide();
                $('.danger').hide();
                $('#suggest_friend_form')[0].reset();
                var validator1 = $("#suggest_friend_form").validate();
                validator1.resetForm();
                $('.form-group').removeClass('has-error');
            });

            $('#suggest_friend_form').validate({
                errorClass: "help-block",
                validClass: 'valid',
                rules: {
                    'friend_email': {
                        required: true,
                        email: true
                    },
                    'friend_name': {
                        required: true,
                    }
                }, messages: {
                    'friend_email': {
                        required: 'Please enter an email.',
                        email: "Please enter a valid email."
                    },
                    'friend_name': {
                        required: "Please enter Friend's Name.",
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);

                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
                },
                submitHandler: function (data) {
                    var name = $('#friend_name').val();
                    $.ajax({
                        url: SITE_URL + 'users/refer_friend',
                        type: 'GET',
                        data: $('#suggest_friend_form').serialize(),
                        beforeSend: function (xhr) {
                            $('#refertoFriend').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
                        },
                        success: function (res) {
                            $('#refertoFriend').unblock();
                            $('#suggest_friend_form')[0].reset();
                            $('#suggest_friend_form').hide();
                            if (res == 1) {
                                $('.success').show();
                            } else {
                                $('.danger').show();
                            }
                        }
                    })
                }
            });


            $('body').on('click', '.dislikee, .likee, .maybe', function () {
                liking_action($(this).attr('action_status'));
            });
            $(document).on('keydown', function (e) {
                switch (e.which) {
                    case 37: // left
                        liking_action(2);
                        console.log('left');
                        break;

                    case 39: // right
                        liking_action(1);
                        console.log('right');
                        break;

                        //case 13: // Enter
                        //    if ($('#discover').is(":checked"))
                        //        searchResult();
                        //    console.log('enter');
                        //break;
                    default:
                        return; // exit this handler for other keys
                }
                e.preventDefault(); // prevent the default action (scroll / move caret)
            });

        });
        $(document).on('click', '.exploreThumb', function () {
           
            $this = $(this);
            $.ajax({
                url: SITE_URL + 'users/getuserdetail',
                type: "GET",
                //async:true,
                data: {user_id: $this.attr('user')},
                beforeSend: function () {
                },
                success: function (data) {

                    $('#suggested_user_id').val($this.attr('user'));
                    $('body').addClass('profile-popup-open');
                    $('#popup').show();
                    $('#refertoFriend').hide();
                    $('#suggest_friend_form')[0].reset();
                    var validator1 = $("#suggest_friend_form").validate();
                    validator1.resetForm();
                    $('.form-group').removeClass('has-error');

                    $('#load_content').html(data);

                    var $grid = $('.grid').imagesLoaded(function () {
                        $grid.masonry({
                            itemSelector: '.grid-item',
                            percentPosition: true,
                            columnWidth: '.grid-sizer'
                        });
                    });
                    
                    $('#boxscroll2').slimscroll({
                        wheelStep: 2,
                        color: '#ed1a3b',
                    });

                },
                error: function () {}
            });
            
            $.ajax({
                url: SITE_URL + 'users/sendProfileVisitNotification',
                type: "GET",
                async: false,
                data: {user_id: $this.attr('user')},
                beforeSend: function () {
                },
                success: function (data) {
                    
                }
            });

            //$('#myModal4').modal({
            //    show: 'true',
            //});

        });


    };

    function liking_action(stat) {
        var gender = [];
        $('input[name="lookingFor[]"]:checked').each(function () {
            gender.push(this.value);
        });
        var fromValue = $("#fromValue").val();
        var toValue = $("#toValue").val();
        var keyword = $("#keyword").val();
        var verifiedUser;
        if ($('#verifiedUser').is(":checked"))
            verifiedUser = $("#verifiedUser").val();
        var discover;
        if ($('#discover').is(":checked"))
            discover = $("#discover").val();

        var to_user_id = $('.likeDislikeSection').attr('user');
        var status = stat;
        $.ajax({
            type: 'get',
            url: SITE_URL + 'users/explore',
            data: {to_user_id: to_user_id, status: status, gender: gender, fromValue: fromValue, toValue: toValue, verified: verifiedUser, discover: discover, keyword: keyword},
            beforeSend: function (xhr) {
                $('.exploreSection').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
            },
            complete: function () {
                $('.likeDislikeSection').unblock();
            },
            success: function (res) {
                $('.exploreParent').html(res);
                DetectTheThing();
                $('#explore_thumbnails').slimscroll({
                    wheelStep: 2,
                    color: '#ed1a3b',
                    height: '500px'
                });
            }
        });
    }

    function DetectTheThing()
    {
        var uagent = navigator.userAgent.toLowerCase();
        if ((uagent.search("iphone") > -1) || (uagent.search("ipad") > -1)
                || (uagent.search("android") > -1) || (uagent.search("blackberry") > -1)
                || (uagent.search("webos") > -1)) {
            $('.exploreSectionBottom').hide();
        }
    }

    function searchResult() {
        var gender = [];
        $('input[name="lookingFor[]"]:checked').each(function () {
            gender.push(this.value);
        });
        var fromValue = $("#fromValue").val();
        var toValue = $("#toValue").val();
        var keyword = $("#keyword").val();
        var verifiedUser;
        if ($('#verifiedUser').is(":checked"))
            verifiedUser = $("#verifiedUser").val();
        var discover;
        if ($('#discover').is(":checked"))
            discover = $("#discover").val();

        $.ajax({
            url: SITE_URL + 'users/explore',
            type: "GET",
            data: {gender: gender, fromValue: fromValue, toValue: toValue, verified: verifiedUser, discover: discover, keyword: keyword},
            beforeSend: function () {
                $('.exploreSection').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
            },
            complete: function () {
                $('.likeDislikeSection').unblock();
            },
            success: function (data) {
                $('.exploreParent').html(data);
                DetectTheThing();
                
                $('#explore_thumbnails').slimscroll({
                    wheelStep: 2,
                    color: '#ed1a3b',
                    height: '500px'
                });
            },
            error: function () {}
        });
    }

    return {
        init: function () {
            ExploreFunction();

        }
    };

}();
function albumPopupCreate(albumId, imageID)
{
    $.ajax({
        url: SITE_URL + 'ajax/useralbumpopup/' + albumId + '/' + imageID,
        type: 'GET',
        beforeSend: function (xhr) {
            //
        },
        success: function (data) {
            $("#divcontent").html(data);
            $('#myModal3').modal('show');
        }
    })
}



var optClass = function () {

    var sendOtpCode = function () {        
           
        $('#sendOtpCode').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'countrycode': {
                    required: true,
                    number: true,
                    maxlength : 4
                },
                'mobilenumber': {
                    required: true,
                    number: true,
                }
            }, messages: {
                'countrycode': {
                    required: 'Please enter country code.',
                    number: "Please enter valid country code.",
                    maxlength: "Country code should be 4 digits."
                },
                'mobilenumber': {
                    required: 'Please enter mobile number.',
                    number: "Please enter valid mobile number."
                }
            },
            //errorPlacement: function (error, element) {
            //    element.parents('div.form-group').append(error);
            //    error.insertBefore(element);
            //},
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                $.ajax({
                    url: SITE_URL + 'users/sendOtp',
                    type: 'GET',
                    data: {mobile: ($('#mobilenumber').val()).trim(), countrycode:($('#countrycode').val()).trim()},
                    dataType:'JSON',
                    beforeSend: function (response) {
                        $("#loadergreen").show();
                    },
                    success: function (res) {
                        if (res.status == 1) {
                            $('.alert-success').show();
                            $('.alert-danger').hide();
                        }else{
                            $('.alert-success').hide();
                             $('.alert-danger span').html(res.message);
                            $('.alert-danger').show();
                        }
                    }
                });
            }
        });
     
    }
    
    var checkOtpCode = function() {
    
        //var otpcode = ($('input[name="otpcode"]').val()).trim();
        $('#sendOtpCodeVer').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'otpcode': {
                    required: true,
                    number: true
                },           
            }, messages: {
                'otpcode': {
                    required: 'Please enter otp code.',
                    number: "Please enter valid otp code."
                },            
            },
            //errorPlacement: function (error, element) {
            //    element.parents('div.form-group').append(error);
            //    error.insertBefore(element);
            //},
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass(validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler: function (data) {
                $.ajax({
                    url: SITE_URL + 'users/checkOtp',
                    type: 'GET',
                    data: {otpcode: ($('input[name="otpcode"]').val()).trim()},
                    dataType:'JSON',
                    beforeSend: function (response) {
                        $("#loadergreen").show();
                    },
                    success: function (res) {
                        if (res.status == 1) {
                            $('.alert-success span').html('Your mobile number is successfully verified.');
                            $('.alert-success').show();                            
                            $('.alert-danger').hide();
                            location.reload();
                        }else{
                            $('.alert-success').hide();
                            $('.alert-danger span').html(res.message);
                            $('.alert-danger').show();
                        }
                    }
                });
            }
        });
    }
    
    
    var moblienumberPopup = function () {
        $('#moblienumber').modal({
            show: 'true',
            backdrop: 'static',
            keyboard: false
        });
    }
    
    return {
        init: function () {
            sendOtpCode();
            checkOtpCode();
            moblienumberPopup();
        }
    }
}();
