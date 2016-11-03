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
        $.wijets.make(); 	// Make yo Widjit - see docs for more details.
        prettyPrint(); 		//Apply Code Prettifier
        $(".bootstrap-switch").bootstrapSwitch(); // Bootstrap Switches


        // Bootstrap JS
        $('.popovers').popover({container: 'body', trigger: 'hover', placement: 'top'}); //bootstrap's popover
        $('.tooltips').tooltip(); //bootstrap's tooltip

        //Tabdrop
        jQuery.expr[':'].noparents = function (a, i, m) {
            return jQuery(a).parents(m[3]).length < 1;
        }; // Only apply .tabdrop() whose parents are not (.tab-right or tab-left)
        $('.nav-tabs').filter(':noparents(.tab-right, .tab-left)').tabdrop();

        // Custom Checkboxes
        $('.icheck input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('.summernote').summernote({
            height: 150
        });
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
                    required: true
                },
                'password': {
                    required: true,
                }
            }, messages: {
                'email': {
                    required: 'Please enter an email.'
                },
                'password': {
                    required: 'Please enter password.',
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
                element.parents('div.col-xs-8').append(error);
                // error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-xs-8").addClass('has-error').removeClass(validClass);

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
                'user_detail[profile_imagenew]': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG"
                },
                'user_detail[phone]': {
                    required: true,
                    //phoneUS: true,
                    number: true,
                },
                'user_detail[mobile]': {
                    required: true,
                    //phoneUS: true,
                    number: true,
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
                'user_detail[profile_imagenew]': {
                    extension: 'Please select jpg, jpeg, png image.'
                },
                'user_detail[phone]': {
                    required: 'Please enter phone number.',
                    //maxlength: 'phone number is no more than 15 digits.',
                    number: 'Please specify a valid phone number',
                },
                'user_detail[mobile]': {
                    required: 'Please enter mobile number.',
                    //maxlength: 'Mobile number is no more than 15 digits.',
                    number: 'Please specify a valid mobile number',
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
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
//                 error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);
//                $(element).parents("div.input").addClass('has-error').removeClass(validClass);

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
                'user_detail[address]': {
                    required: true,
                },
                'user_detail[profile_image]': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG",
                },
                'user_detail[profile_image_new]': {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG",
                },
                'user_detail[phone]': {
                    required: true,
                    //phoneUS: true,
                    number: true,
                },
                'user_detail[mobile]': {
                    required: true,
                    //phoneUS: true,
                    number: true,
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
                'user_detail[address]': {
                    required: 'Please enter address.',
                },
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
                    required: 'Please Select Date of Birth.',
                },
                'user_detail[profile_image]': {
                    extension: 'Please select jpg, jpeg, png image.',
                },
                'user_detail[profile_image_new]': {
                    extension: 'Please select jpg, jpeg, png image.',
                },
                'user_detail[phone]': {
                    required: 'Please enter phone number.',
                    //maxlength: 'phone number is no more than 15 digits.',
                    number: 'Please specify a valid phone number',
                },
                'user_detail[mobile]': {
                    required: 'Please enter mobile number.',
                    //maxlength: 'Mobile number is no more than 15 digits.',
                    number: 'Please specify a valid mobile number',
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
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
//                 error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                 $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);
//                $(element).parents("div.input").addClass('has-error').removeClass(validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
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

    var deleteUser = function () {
        $('body').on('click', '.delete_user', function () {            
            $this = $(this);
            if (confirm("Are you sure you want to delete this user?")) {
                if ($this.attr('id') != '') {
                    $.ajax({
                        url: SITE_URL + 'admin/ajax/deleteUser/' + $this.attr('id'),
                        type: 'GET',
                        beforeSend: function (xhr) {
                            //$this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                        },
                        success: function (data) {
                            if (data == 1) {
                                $this.parent().parent().parent().remove();
                                alert('Successfully deleted.');
                            }
                        }
                    })
                }
            }
        });
    }

    var updateUserStatus = function () {
        $('body').on('click', '.update_user_status', function () {
            $this = $(this);
            var status = $this.attr('status');
            var txt = (status == 1) ? 'Deactivate' : 'Activate';
            if (confirm("Are you sure you want to " + txt + " this user?")) {
                if ($this.attr('id') != '') {
                    $.ajax({
                        url: SITE_URL + 'admin/ajax/updateUserStatus/' + $this.attr('id'),
                        type: 'GET',
                        beforeSend: function (xhr) {
                            //$this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                        },
                        success: function (data) {
                            if (data == 1) {
                                if (status) {
                                    $this.html('<i class="ti ti-na"></i>');
                                    $this.parent().removeClass('btn-success-alt').addClass('btn-danger-alt');
                                    $this.parent().attr('title', 'Activate User ?');
                                    $this.attr('status', '0');
                                } else {
                                    $this.html('<i class="ti ti-check"></i>');
                                    $this.parent().removeClass('btn-danger-alt').addClass('btn-success-alt');
                                    $this.parent().attr('title', 'Deactivate User ?');
                                    $this.attr('status', '1');
                                }
                            }
                        }
                    })
                }
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
            addEditUser();
            getStateFromCountry();
            getCitiesFromState();
            deleteUser();
            updateUserStatus();

        }
    }
}();
/**
 * 
 * Master Class
 */

var Plans = function () {
    var validatePlanFormAdmin = function () {
        $('.plan-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'plan_name': {
                    required: true
                },
                'duration': {
                    required: true,
                },
                'plan_price': {
                    required: true,
                    integer: true,
                }
            }, messages: {
                'plan_name': {
                    required: 'Please enter plan name.'
                },
                'duration': {
                    required: 'Please select plan duration.',
                },
                'plan_price': {
                    required: 'Please enter plan price.',
                    integer: 'Please enter positive only number.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
//                 error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }

    var validatePlanForm = function () {
        $('.plan-asso-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'user_id': {
                    required: true
                },
                'plan_id': {
                    required: true,
                }
            }, messages: {
                'user_id': {
                    required: 'Please select user.'
                },
                'plan_id': {
                    required: 'Please select plan.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }



    return {
        init: function () {
            validatePlanForm();
            validatePlanFormAdmin();
        }
    }
}();


var Notification = function () {
    var validateNotificationForm = function () {
        $('.notification-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'sender_name': {
                    required: true
                },
                'sender_email': {
                    required: true,
                    email: true,
                },
                'title': {
                    required: true,
                },
                'subject': {
                    required: true,
                },
                'message': {
                    required: true,
                }
            }, messages: {
                'sender_name': {
                    required: 'Please enter sender name.'
                },
                'sender_email': {
                    required: 'Please select sender email.',
                    email: 'Please enter valid email.'
                },
                'title': {
                    required: 'Please enter title.',
                },
                'subject': {
                    required: 'Please enter subject.',
                },
                'message': {
                    required: 'Please enter message.',
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
//                 error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }




    return {
        init: function () {
            validateNotificationForm();
        }
    }
}();
var Master = function () {

    var setDatePicker = function () {
        $('#datepicker-pastdisabled').datepicker({
            todayHighlight: true,
            startDate: "-0d",
            format: 'mm/dd/yyyy',
            autoclose: true,
        });

        $('#contractor-detail-license-expiration').datepicker({
            todayHighlight: true,
            format: 'mm/dd/yyyy',
            autoclose: true,
        });
    }

    var validateTagForm = function () {
        $('.tags-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'name': {
                    required: true
                }
            }, messages: {
                'name': {
                    required: 'Please enter name.'
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8').append(error);
//                 error.insertBefore(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }

    var getGetUserDetail = function () {
        $('body').on('click', '.UserDetail', function () {
            $this = $(this);

            if ($this.data('id') != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/getUserDetail/' + $this.data('id'),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $('.modal-body-userDetail').html(data);
                        $('#UserDetailModel').modal('show');



                    }
                })
            }
        });

        $('body').on('click', '.PlanDetail', function () {
            $this = $(this);

            if ($this.data('id') != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/getPlanDetail/' + $this.data('id'),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $('.modal-body-userDetail').html(data);
                        $('#UserDetailModel').modal('show');

                    }
                })
            }
        });

        $('body').on('click', '#replay', function () {
            $this = $(this);

            if ($this.data('id') != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/getSendMail/' + $this.data('id'),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $('.modal-body-userDetail').html(data);
                        $('#UserDetailModel').modal('show');

                        validateSupportReplayForm();



                    }
                })
            }
        });
    }

    var validateSupportReplayForm = function () {

        $('.SupportReplays').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'subject': {
                    required: true,
                },
                'message': {
                    required: true,
                },
//                'attachment': {
//                    required: true,
//                }
            }, messages: {
                'subject': {
                    required: 'Please enter subject.',
                },
                'message': {
                    required: 'Please enter message.',
                },
//                'attachment': {
//                    required: true,
//                }

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
            submitHandler: function (form) {
                var options = {                
                    beforeSubmit: function () {
                        $('.modal').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    }, // pre-submit callback 
                    success: function (result) {
                        
                      location.reload();
                    },
                    url: SITE_URL + 'ajax/postSendMail'         // override for form's 'action' attribute 

                };

                $(form).ajaxSubmit(options);

//                $.ajax({
//                    url: SITE_URL + 'ajax/postSendMail',
//                    type: 'POST',
//                    data: $('#SupportReplays').serialize(),
//                    beforeSend: function (response) {
//
//                    },
//                    success: function (result) {
//
//
//                        if (result == 'succes') {
//                            $(".alert-danger").hide();
//                            $(".alert-success").show();
//                            $("#SuccessMessage").html('You are successfully login.');
//
//                            setInterval(function () {
//                                window.location = SITE_URL + 'users/myProfile/newuser';
//                            }, 2000);
//
//                        } else {
//                            $("#ErrorMessage").html('Email or password is incorrect');
//                            $(".alert-danger").show();
//                            $("#loadergreen").hide();
//
//                        }
//
//                        return false;
//                    }
//                })
            }
        });
    }

    return{
        init: function () {
            setDatePicker();
            validateTagForm();
            getGetUserDetail();
            validateSupportReplayForm();

        }
    }
}();



/**
 * 
 * SalesPerson Class
 */
var SalesPerson = function () {

    var getStateFromCountry = function () {
        $('body').on('change', '#country-id', function () {
            $this = $(this);
            if ($(this).val() != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/getStateFromCountry/' + $(this).val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $("#state").append(data);
                    }
                })
            }
        });
    }


    var submitForLimit = function () {
        $('body').on('change', '#selector1', function () {
            $(this).closest("form").submit();

        });
    }

    return{
        init: function () {

            getStateFromCountry();
            getCitiesFromState();
            validateSalespersonForm();
            submitForLimit();
            getSalesPersonAddress();

        }
    }
}();



/**
 * 
 * SalesPerson Class
 */
var Page = function () {

    var submitForLimit = function () {
        $('body').on('change', '#selector1', function () {
            $(this).closest("form").submit();

        });
    }

    var validatePageForm = function () {
        $("#add_page").validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'page_key': {
                    required: true
                },
                'page_headline': {
                    required: true
                },
                'page_title': {
                    required: true
                },
                'keyword': {
                    required: true,
                },
                'description': {
                    required: true
                },
                'status': {
                    required: true
                },
                'content': {
                    required: true
                },
            }, messages: {
                'page_key': {
                    required: 'Please enter page key.',
                },
                'page_headline': {
                    required: 'Please enter page headline.',
                },
                'page_title': {
                    required: 'Please enter page title.',
                },
                'keyword': {
                    required: 'Please enter keyword.',
                },
                'description': {
                    required: 'Please enter description.',
                },
                'status': {
                    required: 'Please choose status.',
                },
                'content': {
                    required: 'Please enter content.',
                },
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }

    return{
        init: function () {
            validatePageForm();
        }
    }
}();

var Faq = function () {

    var submitForLimit = function () {
        $('body').on('change', '#selector1', function () {
            $(this).closest("form").submit();

        });
    }

    var validateFaqForm = function () {
        $("#add_faq").validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'question': {
                    required: true
                },
                'answer': {
                    required: true
                },
                'position': {
                    required: true,
                    number: true,
                    digits: true
                },
                'faq_category_id': {
                    required: true,
                },
                'status': {
                    required: true
                }
            }, messages: {
                'question': {
                    required: 'Please enter question.',
                },
                'answer': {
                    required: 'Please enter answer.',
                },
                'position': {
                    required: 'Please enter position.',
                },
                'faq_category_id': {
                    required: 'Please select faq category.',
                },
                'status': {
                    required: 'Please choose status.',
                },
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }

    return{
        init: function () {
            validateFaqForm();
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

