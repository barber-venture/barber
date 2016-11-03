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
$.validator.addMethod("australianDate",
        function (value, element) {
            var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/;
            return date_regex.test(value);
        },
        "Please enter a date in the format!"
        );
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
    }
    var setChoosen = function () {
        var config = {
            '.chosen-select': {},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
    }
    return {
        init: function () {
            loadCommonFunction();
            setChoosen();
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
                'title': {
                    required: true,
                },
                'name': {
                    required: true,
                },
                'image': {
                    extension: "jpg|jpeg|png"
                },
                'phone': {
                    required: true,
                    phoneUS: true,
                    //  pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'mobile': {
                    required: true,
                    phoneUS: true,
                    //  pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'city': {
                    required: true,
                },
                'country_id': {
                    required: true,
                },
                'password': {
                    minlength: 6
                },
                'con_password': {
                    equalTo: "#password"
                }
            }, messages: {
                'title': {
                    required: 'Please select title',
                },
                'name': {
                    required: 'Please enter full name.',
                },
                'image': {
                    extension: 'Please select jpg, jpeg, png image.'
                },
                'phone': {
                    required: 'Please enter phone number.',
                    pattern: 'Please enter valid phone number.',
                    maxlength: 'Phone number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid phone number',
                },
                'mobile': {
                    required: 'Please enter mobile number.',
                    pattern: 'Please enter valid mobile number.',
                    maxlength: 'Mobile number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid mobile number',
                },
                'city': {
                    required: 'Please enter city.',
                },
                'country_id': {
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
    return {
        init: function () {
            validateLoginForm();
            resetFormValidate();
            editProfileFormValidate();
        }
    }
}();
/**
 * 
 * Project Class
 */
var Project = function () {
    var addressEligibilityForm = '.address-eligibility-form';
    var paymentCalculatorForm = '.payment-calculator-form';
    var ineligibleForm = '.ineligible-info-form';
    var ulititySavingForm = '.ulitity-saving-form';
    var sendCreditApplicationForm = '.send-credit-app-form';
    var verifyProjectApplicationForm = '.verify-project-application';

    var validateAddressEligibilityForm = function () {
        $(addressEligibilityForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'zipcode': {
                    required: true,
                    zipcodeUS: true
                },
                'address': {
                    required: true,
                },
                'unit': {
                    pattern: /^[a-z0-9\-\s]+$/i
                }
            }, messages: {
                'zipcode': {
                    required: 'Please enter zipcode',
                },
                'address': {
                    required: 'Please enter address.',
                },
                'unit': {
                    pattern: 'Invalid.'
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.input").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler:function (form){ 
                  if($('div.stepy-navigator.proceed-btn').is(':visible')){
                    form.submit();  
                  }else{
                    $('a.eligibility-link').click();  
                  }
            }
        });
    }
    var addressEligibility = function () {
        $('body').on('click', '.eligibility-link', function () {
            if ($(addressEligibilityForm).valid()) {
                $.ajax({
                    url: SITE_URL + 'ajax/check-address-eligibility',
                    type: 'POST',
                    data: $(addressEligibilityForm).serializeArray(),
                    beforeSend: function (xhr) {
                        $('.msg-ajax-second').html('');
                        $('.msg-ajax-first').html('');
                        $('.proceed-btn').hide();
                        $('.ineligible-body').fadeOut();
                        var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Checking address eligibility...</div></div>';
                        $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                    },
                    success: function (data) {
                        $.unblockUI();
                        var obj = $.parseJSON(data);
                        if (obj.error == 0) {
                            var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center">&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'
                            $('.msg-ajax-first').html(html);
                            setTimeout(gettingExpectedAmount, 2000)
                        } else {
                            var html = '<div class="alert alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'
                            $('.proceed-btn').hide();
                            $('.msg-ajax-first').html(html);
                            if (obj.address == 2) {
                                $('.ineligible-body').fadeIn();
                            } else {
                                $('.ineligible-body').fadeOut();
                            }
                        }
                    }
                })
            }
        })
    }
    var gettingExpectedAmount = function () {
        $.ajax({
            url: SITE_URL + 'ajax/getting-expected-amount',
            type: 'POST',
            data: $(addressEligibilityForm).serializeArray(),
            beforeSend: function (xhr) {
                var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Getting expected project amount...</div></div>';
                $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
            },
            success: function (data) {
                $.unblockUI();
                var obj = $.parseJSON(data);
                if (obj.error == 0) {
                    var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center">&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'
                    $('.msg-ajax-second').html(html);
                    $('.proceed-btn').fadeIn();
                } else {
                    var html = '<div class="alert alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'
                    $('.msg-ajax-second').html(html);
                }
            }
        })
    }
    var ineligibleFormValidate = function () {
        $(ineligibleForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'contact_name': {
                    required: true
                },
                'contact_email': {
                    required: true,
                    email: true
                },
                'contact_phone': {
                    required: true,
                    phoneUS: true,
                    // pattern: /^[\d\s]+$/,
                    maxlength: 15,
                }
            }, messages: {
                'contact_name': {
                    required: 'Please enter contact name.',
                },
                'contact_email': {
                    required: 'Please enter email.',
                    email: 'Please enter a valid email.',
                },
                'contact_phone': {
                    required: 'Please enter phone number.',
                    pattern: 'Please enter valid phone number.',
                    maxlength: 'Phone number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid phone number',
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
        $('#datepicker-pastdisabled').datepicker({
            todayHighlight: true,
            startDate: "-0d",
            format: datePickerFormat,
            autoclose: true,
            forceParse: false,
        });
        $("#datepicker-pastdisabled input").mask("99/99/9999");
    }
    var validatePaymentCalculatorForm = function () {
        $(paymentCalculatorForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'project_amount_est': {
                    required: true,
                    number: true,
                    pattern: /^[1-9]\d*(\.\d+)?$/,
                    remote: {url: SITE_URL + 'ajax/check-valid-amount/' + $('#id').val(), type: 'GET'}
                },
                'completion_date': {
                    required: true,
                    australianDate: true,
                    minDate:true
                },
                'amount_eligible_for_credit': {
                    required: false,
                    number: true,
                    pattern: /^[1-9]\d*(\.\d+)?$/,
                    lessThanEqual: '#project-amount-est'
                },
                'tax_rate': {
                    required: true,
                    number: true,
                    range: [0, 50]
                }
            }, messages: {
                'project_amount_est': {
                    required: 'Please enter cost of project.',
                    number: 'Please enter valid cost.',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                    remote: 'Entered amount is greater than the expected amount.'
                },
                'completion_date': {
                    required: 'Please enter completion date.',
                    minDate:'Please enter future date.'
                },
                'amount_eligible_for_credit': {
                    required: 'Please enter amount.',
                    number: 'Please enter valid amount.',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                    lessThanEqual: 'Must be less than or equal to cost of project.'
                },
                'tax_rate': {
                    required: 'Required.',
                    number: 'Use number.',
                    range: 'Use 0-50'
                }
            },
            errorPlacement: function (error, element) {
                if ($(element).parent('#datepicker-pastdisabled').is('div')) {
                    element.parents('div.col-sm-12').append(error);
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-md-3,div.pull-left,div.col-md-1").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler:function (form){ 
                  if($('div.stepy-navigator.proceed-btn').is(':visible')){
                    form.submit();  
                  }else{
                    $('a.payment-calculate-link').click();  
                  }
            }
        });
    }
    var ulititySavingFormValidate = function () {
        $(ulititySavingForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'savings': {
                    required: true,
                    number: true,
                    max: 1000
                },
                'inflation': {
                    required: true,
                    number: true,
                    max: 7,
                    min: 0,
                }
            }, messages: {
                'savings': {
                    required: 'Required.',
                    number: 'Enter number.',
                    max: 'Enter <= 1000'
                },
                'inflation': {
                    required: 'Required.',
                    number: 'Enter number.',
                    max: 'Enter <= 7',
                    min: 'Not valid.',
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("td").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var savingsCalculate = function () {
        $('body').on('click', '.savings-calculate-btn', function () {
            if ($(ulititySavingForm,$('.modal:visible')).valid()) {
                $.ajax({
                    url: SITE_URL + 'ajax/savings-calculate',
                    type: 'POST',
                    data: $(ulititySavingForm,$('.modal:visible')).serializeArray(),
                    beforeSend: function (xhr) {
                        var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Savings calculating...</div></div>';
                        $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});

                    },
                    success: function (data) {
                        $.unblockUI();
                        $('#saving-body',$('.modal:visible')).html(data);
                    }
                })
            }
        })
    }
    var getPaymentCalculateData = function (type) {
        $.ajax({
            url: SITE_URL + 'ajax/getting-calculate-payment',
            type: 'POST',
            data: $(paymentCalculatorForm).serializeArray(),
            beforeSend: function (xhr) {
                if (type == 'new') {
                    var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Calculating payment...</div></div>';
                    $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                }

            },
            success: function (data) {
                $.unblockUI();
                $('#payment-modal .modal-body').html(data);
                $('#payment-modal-monthly .modal-body').html($('#payment-modal .monthly-payment-data').html());
                $('#payment-modal .monthly-payment-data').remove();
                var html1 = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>Annual payment would be ' + $('.pmt').first().text() + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                var html1_month = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>Monthly payment would be ' + $('.month-pmt').val() + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                $('.first-msg').html(html1);
                $('.month-first-msg').html(html1_month);
                $('.first-msg').parents('.row').fadeIn();
                var html2 = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong><strong>1<sup>st</sup> year payment after tax deduction would be ' + $('.tax-deduction-payment').first().text() + '</strong></strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                var html2_month = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong><strong>1<sup>st</sup> year monthly payment after tax deduction would be ' + $('.monthly-tax-deduction-payment').first().text() + '</strong></strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                $('.second-msg').html(html2);
                $('.month-second-msg').html(html2_month);
                $('.second-msg').parents('.row').fadeIn();
                if ($('.tax-deduction-credit-payment').first().is('td')) {
                    var html3 = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong><strong>1<sup>st</sup> year payment after tax credit and tax deduction would be ' + $('.tax-deduction-credit-payment').first().text() + '</strong></strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                    var html3_month = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong><strong>1<sup>st</sup> year monthly payment after tax credit and tax deduction would be ' + $('.monthly-tax-deduction-credit-payment').first().text() + '</strong></strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                    $('.third-msg').html(html3);
                    $('.month-third-msg').html(html3_month);
                    $('.third-msg').parents('.row').fadeIn();
                } else {
                    $('.third-msg').parents('.row').fadeOut();
                    $('.third-msg').html('');
                    $('.month-third-msg').html('');
                }
                $('.stepy-navigator').fadeIn();
                ulititySavingFormValidate();

            }
        })
    }
    var paymentCalculate = function () {
        $('body').on('click', '.payment-calculate-link', function () {
            if ($(paymentCalculatorForm).valid()) {
                getPaymentCalculateData('new');
            }
        })
    }
    var loadPaymentCalculate = function () {
        if ($('input[name="step"]').is('input') && $('input[name="step"]').val() == 2) {
            getPaymentCalculateData('old');
        }
    }
    var selectCredit = function () {
        $('body').on('change', '#tax-credit', function () {
            if ($(this).val() == 'Yes') {
                $('.amount-eligible-body').show();
                $('input', '.amount-eligible-body').val('').removeAttr('disabled')
            } else {
                $('.amount-eligible-body').hide();
                $('input', '.amount-eligible-body').val('').attr('disabled', 'disabled')
            }
        })
    }
    var validateSendCreditAppForm = function () {
        $(sendCreditApplicationForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'owner_first_name': {
                    required: true
                },
                'owner_last_name': {
                    required: true
                },
                'email_address': {
                    required: true,
                    email: true
                }
            }, messages: {
                'owner_first_name': {
                    required: 'Please enter first name.',
                },
                'owner_last_name': {
                    required: 'Please enter last name.',
                },
                'email_address': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.'
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-6,div.col-sm-3").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var sendCreditApplication = function () {
        $('body').on('click', '.send-credit-app-link', function () {
            $this = $(this);
            if ($(sendCreditApplicationForm).valid()) {
                $.ajax({
                    url: SITE_URL + 'ajax/send-credit-application',
                    type: 'POST',
                    data: $(sendCreditApplicationForm).serializeArray(),
                    beforeSend: function (xhr) {
                        if ($this.data('type') == 'resend') {
                            var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Resending credit application...</div></div>';
                        } else {
                            var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Sending credit application...</div></div>';
                        }
                        $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                    },
                    success: function (data) {
                        $.unblockUI();
                        var obj = $.parseJSON(data);
                        if (obj.error == 0) {
                            if ($this.data('type') == 'resend') {
                                var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>Credit application resent.</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                            } else {
                                var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                                $('input', sendCreditApplicationForm).attr('disabled', 'disabled');
                                $this.parents('.stepy-navigator').remove();
                            }
                        } else {
                            var html = '<div class="alert alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                        }
                        $('.first-msg').html(html);
                        $('.first-msg').parents('.row').fadeIn();

                    }
                })
            }
        })
    }
    var getModelByManufacturer = function () {
        $('body').on('change', '#manufacturer', function () {
            $this = $(this);
            if ($(this).val() != '') {                
                $.ajax({
                    url: SITE_URL + 'ajax/get-manufacturer-model/' + $(this).val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $('#model', $this.parents('td').next('td')).html(data);
                        $('#model', $this.parents('td').next('td')).trigger("chosen:updated");
                    }
                })
            } else {
                var option = '<option value="">Select Model</option>';
                $('#model', $this.parents('td').next('td')).html(option);
                $('#model', $this.parents('td').next('td')).trigger("chosen:updated");
            }
        })
        $('body').on('change', '.term-list select[name="loan_term"]', function () {
            $('div.js-error').html('');
           // $('.term-list').hide();
        })
    }
    var getSkuByModel = function () {
        $('body').on('change', '#model', function () {
            $this = $(this);
            if ($(this).val() != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/get-model-sku/' + $(this).val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        $this.parents('tr').unblock();
                        $('#sku', $this.parents('td').next('td')).html(data);
                        $('#sku', $this.parents('td').next('td')).trigger("chosen:updated");
                    }
                })
            } else {
                var option = '<option value="">Select SKU</option>';
                $('#sku', $this.parents('td').next('td')).html(option);
                $('#sku', $this.parents('td').next('td')).trigger("chosen:updated");
            }
        })
    }
    var getTotalCost = function () {
        $('body').on('change keyup keypress', 'input.cost-eq', function () {
            calculateContractCost();
        })
    }
    var calculateContractCost = function () {
        var total = 0;
        $('input.cost-eq').each(function () {
            if (!isNaN(Number(this.value)))
                total += Number(this.value);
            else
                this.value = '';
        });
        $('input.total-cost-input').val(total.toFixed(2));
        $('.total-cost-eq').text('$' + total.toFixed(2));
    }
    var filterCost = function () {
        $('body').on('change keyup keypress', 'input.qty-eq', function () {
            var $this = $(this);
            $this.val($this.val().replace(/[^\d]/g, ''));
        });
    }
    var validateContractForm = function () {
        var parentFlag = 0;
        var emptyFlag = 0;
        $('tr.data-tr').each(function (el, tr) {
            var flag = 0;
            $('input[name!="term"],select[name!="term_of_assessment"]', $(this)).each(function () {
                if ($(this).val() != '') {
                    flag++;
                    emptyFlag = 1;
                }
            })
            if (flag > 0 && flag < 5) {
                $(tr).addClass('danger');
                parentFlag++;
            } else {
                $(tr).removeClass('danger');
            }
        })
        if (parentFlag > 0) {
            $('html, body').animate({
                scrollTop: $('.js-error').offset().top - 60
            }, 500);
            $('div.js-error').html('');
            return  false;
        }
        if (emptyFlag == 0) {
            var error = '<div class="alert alert-dismissable alert-danger"><i class="ti ti-close"></i> Eqipment information required.<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>';
            $('div.js-error').html(error);
            $('html, body').animate({
                scrollTop: $('.js-error').offset().top - 60
            }, 500);
            return  false;
        }
        var totalCost = $('input[name="total_cost"]').val();
        var approveCost = $('.app-amt').val();
        if (parseFloat(totalCost) > parseFloat(approveCost)) {
            var error = '<div class="alert alert-dismissable alert-danger"><i class="ti ti-close"></i> Total amount is greater than the approved amount.<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>';
            $('div.js-error').html(error);
            $('html, body').animate({
                scrollTop: $('.js-error').offset().top - 60
            }, 500);
            return false;
        }        
        var currentTerm = $('select[name="loan_term"]').val(); 
        var termErr=0;
        var costArr = $("input.cost-eq").map(function(){if($(this).val()!=''){ return $(this).val()}}).get();
        var maxCost=Math.max.apply(Math, costArr);       
        $('input.cost-eq').each(function(){
            if($(this).val()!='' && parseFloat($(this).val()) == parseFloat(maxCost)){
             var selectedTerm = $('input[name="term"]', $(this).parents('tr')).val();             
                var projectTypeName = $(this).parents('tr').find('th').text();
                if (parseInt(selectedTerm) < parseInt(currentTerm)) {
                    var error = '<div class="alert alert-dismissable alert-danger"><i class="ti ti-close"></i> Maximum term for ' + projectTypeName + ' is ' + selectedTerm + ' years. Please choose another term.<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>';
                    $('div.js-error').html(error);
                    $('.term-list').show();                    
                    $('html, body').animate({
                        scrollTop: $('.js-error').offset().top - 60
                    }, 500);
                    termErr++;
                    return false;
                }
            }
        })
        
        if(termErr==0){
          $('div.js-error').html('');        
          return true;  
        }else{
          return false;  
        }
        
    }
    var sendContract = function () {
        var contractForm = '.contract-form';
        $('body').on('click', '.send-contract-link', function () {
            $this = $(this);
            if (validateContractForm()) {
                $.ajax({
                    url: SITE_URL + 'ajax/send-contract',
                    type: 'POST',
                    data: $(contractForm).serializeArray(),
                    beforeSend: function (xhr) {
                        var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Preparing contract...</div></div>';
                        var secondMsg = 'Sending contract for e-signature...';
                        $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                        setTimeout(function () {
                            $('.loader-msg').text(secondMsg)
                        }, 2000);
                    },
                    success: function (data) {
                        $.unblockUI();
                        var obj = $.parseJSON(data);
                        if (obj.error == 0) {
                            var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                            $('input,select', contractForm).attr('disabled', 'disabled');
                            $this.parents('.stepy-navigator').remove();
                            $('.chosen-select').trigger("chosen:updated");
                        } else {
                            var html = '<div class="alert alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                        }
                        $('.first-msg').html(html);
                        $('.first-msg').parents('.row').fadeIn();

                    }
                })
            }
        })
    }
    var validateEmailToForm= function(form){
        var emailForm = $(form).validate({
            errorClass: "help-block text-left",
            validClass: 'valid',
            rules: {
                'email': {
                    required: true,
                    email: true
                }
            }, messages: {
                'email': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.'
                }
            },
            errorPlacement: function (error, element) {
                element.parents('form').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("form").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
        return emailForm;
    }
    var sendPayementRequest = function () {
        var emailForm='';
        $('body').on('click', '.modal:visible button.email-to', function () {
            $('.btn-other',$('.modal:visible')).fadeOut(function () {
                $('.find-email-block',$('.modal:visible')).fadeIn();
                emailForm = validateEmailToForm('.modal:visible .email-form');
            })
        })
        $('body').on('click', '.modal:visible button.email-to-cancel', function () {
            $('.find-email-block',$('.modal:visible')).fadeOut(function () {
                $('.btn-other',$('.modal:visible')).fadeIn();
                emailForm.resetForm();
                $('.email-form',$('.modal:visible')).trigger('reset');
                $('.email-form',$('.modal:visible')).removeClass('has-error');
            })
        })
        $('body').on('click', '.modal:visible button.email-me', function () {
            var email = '';
            sendPayementPdf(email);
        })
        $('body').on('click', '.modal:visible button.email-to-send', function () {
            if ($('.email-form',$('.modal:visible')).valid()) {
                var email = $('input[name="email"]',$('.modal:visible')).val();
                sendPayementPdf(email);
            }
            $('.modal:visible').data('bs.modal').handleUpdate();
        })
    }
    var sendPayementPdf = function (email) {
        $this = $(this);
        var type='';
        if($('input[name="type"]',$('.modal:visible')).is('input') && $('input[name="type"]',$('.modal:visible')).val()=='monthly'){
          type='monthly';  
        }
        $.ajax({
            url: SITE_URL + 'ajax/send-payment-detail',
            type: 'POST',
            data: {'_csrfToken': $('input[name="_csrfToken"]').val(), 'id': $('input[name="id"]').val(), 'email': email,type:type},
            beforeSend: function (xhr) {
                var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Sending payment detail...</div></div>';
                $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
            },
            success: function (data) {
                $('.email-to-cancel').click();
                $.unblockUI();
                var obj = $.parseJSON(data);
                if (obj.error == 0) {
                    var html = '<div class="alert alert-ajax alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';

                } else {
                    var html = '<div class="alert alert-ajax alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                }
                $('.modal-footer').prepend('<div class="col-md-12">' + html + '</div>');
                updateModelHeight();
                $(".alert-ajax").fadeTo(5000, 500).slideUp(500);
            }
        })

    }
    
    var verifyProjectApplicationFormValidate = function () {
        $(verifyProjectApplicationForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'all_property_owners': {
                    required: true,
                },
                'all_debt_secured': {
                    required: true,
                },
                'property_taxes': {
                    required: true,
                },
                'subject_property': {
                    required: true,
                },
                'bankruptcies': {
                    required: true,
                },
                'outstanding_mortgage': {
                    required: true,
                    pattern: /^[0-9]\d*(\.\d+)?$/,
                },
                'loan_value': {
                    required: false,
                    pattern: /^[0-9]\d*(\.\d+)?$/,
                },
                'annual_tax_payment': {
                    required: false,
                    pattern: /^[0-9]\d*(\.\d+)?$/,
                },
                'avm': {
                    required: true,
                },
                'apn': {
                    required: true,
                },
                'legal_description': {
                    required: true,
                },
                'municipality': {
                    required: true,
                },
                'county': {
                    required: true,
                }

            }, messages: {
                'all_property_owners': {
                    required: 'Please choose an option',
                },
                'all_debt_secured': {
                    required: 'Please choose an option',
                },
                'property_taxes': {
                    required: 'Please choose an option',
                },
                'subject_property': {
                    required: 'Please choose an option',
                },
                'bankruptcies': {
                    required: 'Please choose an option',
                },
                'outstanding_mortgage': {
                    required: 'Please enter outstanding mortgage.',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                },
                'loan_value': {
                    required: 'Please enter loan value.',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                },
                'annual_tax_payment': {
                    required: 'Please enter annual tax payment.',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                },
                'avm': {
                    required: 'Please enter AVM.',
                },
                'apn': {
                    required: 'Please enter APN.',
                },
                'legal_description': {
                    required: 'Please enter legal description.',
                },
                'municipality': {
                    required: 'Please enter municipality.',
                },
                'county': {
                    required: 'Please enter county.',
                }
            },
            errorPlacement: function (error, element) {
                if ($(element).is(':radio')) {
                    element.parents('div.col-sm-3').append(error);
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-3,div.col-sm-4").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
            submitHandler:function (form){ 
                 bootbox.confirm({
                        message: 'Are you sure? After this step this application will be locked up and no further changes can be made.',
                        callback: function (result) {
                            if (result) {
                               form.submit();
                            }
                        }
                    }); 
            }
        });
        $(verifyProjectApplicationForm + ' .iCheck-helper').click(function () {
            $('.help-block', $(this).parents(".has-error")).remove();
            $(this).parents(".has-error").removeClass('has-error');
        })
    }
    var verifyProjectLoanValueCalculate = function () {
        $('body').on('change keyup keypress', '', function () {
            var mortgage = $('#outstanding-mortgage').val();
            var zillow_estimate = $('input[name="zillow_estimate"]').val();
            var loanValue = (mortgage / zillow_estimate) * 100;
            if (loanValue >= 0) {
                $('#loan-value').val(loanValue.toFixed(2));
            } else {
                $('#loan-value').val('');
            }
        })
    }
    var uploadProjectFiles = function(){
        $('body').on('click','.upload-p-file',function(){           
            $('.projects_files').ajaxSubmit({
                url: SITE_URL+'ajax/post-project-file/'+$(this).data('type')+'/'+$(this).data('folder'),
                beforeSubmit: function(){
                    var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">File uploading...</div></div>';
                    $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                },
                success: function(data){                     
                     var json=$.parseJSON(data);
                     if(json.error==0){
                        window.location.reload(); 
                     }else{
                        $.unblockUI(); 
                        var html = '<div class="alert alert-ajax alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + json.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';  
                        $('.js-error').html(html);
                     }
                }
            });
        }
        )
    }
    var changeOrderEvent = function (){
        $('body').on('click','.change-order-btn',function(){   
            bootbox.dialog({
                message: "Is there any equipment change or dollar amount change for this project?",
                buttons: {                    
                    danger: {
                        label: "  No, send Certificate of Completion  ",
                        className: "btn-info",
                        callback: function () {
                            $.ajax({
                                url: SITE_URL + 'ajax/send-completion-certificate-only',
                                type: 'POST',
                                data: $('.projects_files').serializeArray(),
                                beforeSend: function (xhr) {
                                    var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Preparing certificate...</div></div>';
                                    var secondMsg = 'Sending completion certificate for e-signature...';
                                    $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                                    setTimeout(function () {
                                        $('.loader-msg').text(secondMsg)
                                    }, 2000);
                                },
                                success: function (data) {
                                    $.unblockUI();
                                    var obj = $.parseJSON(data);
                                    if (obj.error == 0) {
                                        window.location.href = window.location.href;
                                    } else {
                                        var html = '<div class="alert alert-dismissable alert-danger"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                                        $('div.js-error').html(html);
                                        $('html, body').animate({
                                            scrollTop: $('.js-error').offset().top - 60
                                        }, 500);
                                    }

                                }
                            })
                        }
                    },success: {
                        label: " Yes ",
                        className: "btn-primary",
                        callback: function () {
                            window.location.href = SITE_URL + 'projects/change-contract/' + $('#id').val();
                        }
                    }
                }
            });
            
        })
       
    }
    var sendCompletionCertificate = function(){
        var contractForm = '.contract-form';
        $('body').on('click', '.send-certificate-completion-link', function () {
            $this = $(this);
            if (validateContractForm()) {
                $.ajax({
                    url: SITE_URL + 'ajax/send-completion-certificate',
                    type: 'POST',
                    data: $(contractForm).serializeArray(),
                    beforeSend: function (xhr) {
                        var progress = '<div class="progress-loader"><div class="progress-icon"></div><div class="loader-msg">Preparing certificate...</div></div>';
                        var secondMsg = 'Sending completion certificate for e-signature...';
                        $.blockUI({message: progress, overlayCSS: {'z-index': 11111}, css: {'z-index': 111111}});
                        setTimeout(function () {
                            $('.loader-msg').text(secondMsg)
                        }, 2000);
                    },
                    success: function (data) {
                        $.unblockUI();
                        var obj = $.parseJSON(data);
                        if (obj.error == 0) {
                            var html = '<div class="alert alert-dismissable alert-success alert-info-custom text-center"><i class="ti ti-check strong"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                            $('input,select', contractForm).attr('disabled', 'disabled');
                            $this.parents('.stepy-navigator').remove();
                            $('.chosen-select').trigger("chosen:updated");
                        } else {
                            var html = '<div class="alert alert-dismissable alert-danger alert-info-custom text-center"><i class="ti ti-close"></i>&nbsp; <strong>' + obj.msg + '</strong><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
                        }
                        $('.first-msg').html(html);
                        $('.first-msg').parents('.row').fadeIn();

                    }
                })
            }
        })
    }
    var updateModelHeightOnTabCHange= function(){
        $('body').on('shown.bs.tab','a[data-toggle="tab"]', function (e) {           
           updateModelHeight();
        });
    }
    var updateModelHeight= function(){   
              $('.modal:visible').data('bs.modal').handleUpdate();
    }
    return{
        init: function () {
            validateAddressEligibilityForm();
            validatePaymentCalculatorForm();
            validateSendCreditAppForm();
            addressEligibility();
            paymentCalculate();
            ineligibleFormValidate();
            setDatePicker();
            selectCredit();
            loadPaymentCalculate();
            savingsCalculate();
            sendCreditApplication();
            getModelByManufacturer();
            getSkuByModel();
            getTotalCost();
            sendContract();
            filterCost();
            sendPayementRequest();            
            verifyProjectApplicationFormValidate();
            verifyProjectLoanValueCalculate();
            uploadProjectFiles();
            changeOrderEvent();
            sendCompletionCertificate();
            updateModelHeightOnTabCHange();
        }
    }
}();
/**
 * 
 * Customer Class
 */
var Customer = function () {
    var postApplicationForm = '.post-application-form';
    var processCreditApplication = function () {
        $('input[name="consent"]').on('ifChecked', function (event) {
            $('.process-credit-app-link').removeClass('disabled');
        });
        $('input[name="consent"]').on('ifUnchecked', function (event) {
            $('.process-credit-app-link').addClass('disabled');
        });
        $('.process-credit-app-link').click(function () {
            window.location.href = $(this).data('href');
        })
    }
    var validateCreditAppForm = function () {
        $(postApplicationForm).validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'address': {
                    required: true
                },
                'city': {
                    required: true
                },
                'state': {
                    required: true
                },
                'zipcode': {
                    required: true,
                    zipcodeUS: true
                },
                'property_type': {
                    required: true
                },
                'property_ownership': {
                    required: true
                },
                'mortgage': {
                    number: true,
                    pattern: /^[1-9]\d*(\.\d+)?$/,
                },
                'trust_name': {
                    required: true
                },
                'corporation_llc_name': {
                    required: true
                },
                'other_name': {
                    required: true
                },
                'fo_first_name': {
                    required: true
                },
                'fo_last_name': {
                    required: true
                },
                'fo_ssn': {
                    required: true,
                    SSN: true
                },
                'fo_dob': {
                    required: true,
                    australianDate: true
                },
                'fo_email': {
                    required: true,
                    email: true
                },
                'fo_address': {
                    required: true
                },
                'fo_unit': {
                    pattern: /^[a-z0-9\-\s]+$/i
                },
                'fo_city': {
                    required: true
                },
                'fo_state': {
                    required: true
                },
                'fo_zipcode': {
                    required: true,
                    zipcodeUS: true
                },
                'so_first_name': {
                    required: true
                },
                'so_last_name': {
                    required: true
                },
                'so_ssn': {
                    required: true,
                    SSN: true
                },
                'so_dob': {
                    required: true,
                    australianDate: true
                },
                'so_email': {
                    required: true,
                    email: true
                },
                'so_address': {
                    required: true
                },
                'so_unit': {
                    pattern: /^[a-z0-9\-\s]+$/i
                },
                'so_city': {
                    required: true
                },
                'so_state': {
                    required: true
                },
                'so_zipcode': {
                    required: true,
                    zipcodeUS: true
                },
                'firstcond': {
                    required: true
                },
                'secondcond': {
                    required: true
                },
                'thirdcond': {
                    required: true
                },
                'email_address': {
                    required: true,
                    email: true
                }
            }, messages: {
                'address': {
                    required: 'Please enter street address.',
                },
                'city': {
                    required: 'Please enter city.',
                },
                'state': {
                    required: 'Please enter state.',
                },
                'zipcode': {
                    required: 'Please enter zip.',
                },
                'property_type': {
                    required: 'Please select property type.',
                },
                'property_ownership': {
                    required: 'Please select property ownership.',
                },
                'mortgage': {
                    number: 'Enter only numerical characters (example: "20000" for $20,000).',
                    pattern: 'Enter only numerical characters (example: "20000" for $20,000).',
                },
                'trust_name': {
                    required: 'Please enter trust name.'
                },
                'corporation_llc_name': {
                    required: 'Please enter corporation or LLC name.'
                },
                'other_name': {
                    required: 'Please enter other name.'
                },
                'fo_first_name': {
                    required: 'Please enter first name of first owner.',
                },
                'fo_last_name': {
                    required: 'Please enter last name of first owner.',
                },
                'fo_ssn': {
                    required: 'Please enter SSN of first owner.',
                },
                'fo_dob': {
                    required: 'Please enter Date of Birth of first owner.',
                },
                'fo_email': {
                    required: 'Please enter email of first owner.',
                },
                'fo_address': {
                    required: 'Please enter street address of first owner.',
                },
                'fo_unit': {
                    pattern: 'Please enter valid unit.'
                },
                'fo_city': {
                    required: 'Please enter city of first owner.',
                },
                'fo_state': {
                    required: 'Please enter state of first owner.',
                },
                'fo_zipcode': {
                    required: 'Please enter zip of first owner.',
                },
                'so_first_name': {
                    required: 'Please enter first name of second owner.',
                },
                'so_last_name': {
                    required: 'Please enter last name of second owner.',
                },
                'so_suffix': {
                    required: 'Please enter suffx of second owner.',
                },
                'so_ssn': {
                    required: 'Please enter SSN of second owner.',
                },
                'so_dob': {
                    required: 'Please enter Date of Birth of second owner.',
                },
                'so_email': {
                    required: 'Please enter email address of second owner.',
                },
                'so_address': {
                    required: 'Please enter street address of second owner.',
                },
                'so_unit': {
                    pattern: 'Please enter valid unit.'
                },
                'so_city': {
                    required: 'Please enter city of second owner.',
                },
                'so_state': {
                    required: 'Please enter state of second owner.',
                },
                'so_zipcode': {
                    required: 'Please enter zip code of second owner.',
                },
                'firstcond': {
                    required: 'Please enter zip code of second owner.',
                },
                'secondcond': {
                    required: 'Please enter zip code of second owner.',
                },
                'thirdcond': {
                    required: 'Please enter zip code of second owner.',
                },
                'email_address': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.'
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            }
        });
    }
    var changeOwnershipEvent = function () {
        $('body').on('change', '#property-ownership', function () {
            if ($(this).val() == 'Individual') {
                $('.ownership-name').fadeOut();
                $('input', '.ownership-name').prop('disabled', true);
                $('.has-error', '.ownership-name').removeClass('has-error');
                $('.help-block', '.ownership-name').remove();
                $('.second-property-body').fadeOut(function () {
                    $('input', $(this)).prop('disabled', true);
                });
            } else if ($(this).val() == 'Trust') {
                $('input', '.ownership-name').attr('name', 'trust_name').prop('disabled', false);
                $('label', '.ownership-name').text('Trust Name:');
                $('.has-error', '.ownership-name').removeClass('has-error');
                $('.help-block', '.ownership-name').remove();
                $('.ownership-name').fadeIn();
                $('.second-property-body').fadeOut(function () {
                    $('input', $(this)).prop('disabled', true);
                });
            } else if ($(this).val() == 'Corporation or LLC') {
                $('input', '.ownership-name').attr('name', 'corporation_llc_name').prop('disabled', false);
                $('label', '.ownership-name').text('Corporation or LLC Name:');
                $('.has-error', '.ownership-name').removeClass('has-error');
                $('.help-block', '.ownership-name').remove();
                $('.ownership-name').fadeIn();
                $('.second-property-body').fadeOut(function () {
                    $('input', $(this)).prop('disabled', true);
                });
            } else if ($(this).val() == 'Other') {
                $('input', '.ownership-name').attr('name', 'other_name').prop('disabled', false);
                $('label', '.ownership-name').text('Other Name:');
                $('.has-error', '.ownership-name').removeClass('has-error');
                $('.help-block', '.ownership-name').remove();
                $('.ownership-name').fadeIn();
                $('.second-property-body').fadeOut(function () {
                    $('input', $(this)).prop('disabled', true);
                });
            } else {
                $('.ownership-name').fadeOut();
                $('input', '.ownership-name').prop('disabled', true);
                $('.has-error', '.ownership-name').removeClass('has-error');
                $('.help-block', '.ownership-name').remove();
                $('input', '.second-owner-address-body').prop('disabled', false);
                $('.second-owner-address-body').show();
                $('input', '.second-property-body').prop('disabled', false);
                $('.second-property-body').fadeIn();
            }
        })

    }
    var firstOwnerSameAsEvent = function () {
        $('input[name="fo_same_as"]').on('ifChecked', function (event) {
            $('.project-address-body input').each(function (key, obj) {
                $('#fo-' + $(obj).attr('id')).val($(obj).val());
                $('#fo-' + $(obj).attr('id')).prop('readonly', true);
            })
        });
        $('input[name="fo_same_as"]').on('ifUnchecked', function (event) {
            $('.project-address-body input').each(function (key, obj) {
                $('#fo-' + $(obj).attr('id')).val('');
                $('#fo-' + $(obj).attr('id')).prop('readonly', false);
            })
        });
    }
    var secondOwnerSameAsEvent = function () {
        $('input[name="so_same_as"]').on('ifChecked', function (event) {
            $('.project-address-body input').each(function (key, obj) {
                $('#so-' + $(obj).attr('id')).val($(obj).val());
                $('#so-' + $(obj).attr('id')).prop('readonly', true);
            })
        });
        $('input[name="so_same_as"]').on('ifUnchecked', function (event) {
            $('.project-address-body input').each(function (key, obj) {
                $('#so-' + $(obj).attr('id')).val('');
                $('#so-' + $(obj).attr('id')).prop('readonly', false);
            })
        });
    }
    var setDatePicker = function () {
        $('#fo-dob,#so-dob').datepicker({
            todayHighlight: true,
            endDate: new Date(),
            //startView: "decade",
            format: datePickerFormat,
            autoclose: true,
            forceParse: false,
        });
        $("#fo-dob,#so-dob").mask("99/99/9999");
    }
    var setMask = function () {
        $("#fo-ssn,#so-ssn").mask("999-99-9999");
    }
    var checkTermsConditions = function () {
        $('input[name="firstcond"],input[name="secondcond"],input[name="thirdcond"]').on('ifChecked', function (event) {
            if ($('input[type="checkbox"]:checked', '.conditions-box').length == 2) {
                $('.post-app-btn').prop('disabled', false);
            }
        });
        $('input[name="firstcond"],input[name="secondcond"],input[name="thirdcond"]').on('ifUnchecked', function (event) {
            if ($('input[type="checkbox"]:checked', '.conditions-box').length != 2) {
                $('.post-app-btn').prop('disabled', true);
            }
        });
    }
    return{
        init: function () {
            processCreditApplication();
            validateCreditAppForm();
            changeOwnershipEvent();
            firstOwnerSameAsEvent();
            secondOwnerSameAsEvent();
            setDatePicker();
            checkTermsConditions();
            checkTermsConditions();
            setMask();
        }
    }
}();
var Contractor = function () {
    var validateContractorForm = function () {
        $('.contractor-form').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'email': {
                    required: true,
                    email: true,
                    remote: {url: SITE_URL + 'ajax/checkEmailAvailibility/' + $('#email').val(), type: 'GET'}
                },
                'title': {
                    required: true
                },
                'first_name': {
                    required: true
                },
                'last_name': {
                    required: true
                },
                'mobile': {
                    required: true,
                    phoneUS: true,
                    // pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'phone': {
                    required: true,
                    phoneUS: true,
                    //  pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'docusign_username': {
                    required: true,
                    email: true
                },
                 'docusign_password': {
                    required: true
                },
                 'docusign_integrator_key': {
                    required: true
                },
                'contractor_detail[company_name]': {
                    required: true
                },
                'contractor_detail[website]': {
                    required: true,
                    pattern: /^http:\/\/|(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/,
                },
                'contractor_detail[company_phone]': {
                    required: true,
                    phoneUS: true,
                },
                'contractor_detail[license_number]': {
                    required: true
                },
                'contractor_detail[license_state]': {
                    required: true
                },
                'contractor_detail[license_expiration]': {
                    required: true
                },
                'contractor_detail[company_country]': {
                    required: true
                },
                'contractor_detail[state_id]': {
                    required: true
                },
                'contractor_detail[company_city]': {
                    required: true
                },
                'contractor_detail[company_pincode]': {
                    required: true
                },
                'contractor_detail[company_address]': {
                    required: true
                },
//                'contractor_detail[company_address2]': {
//                    required: true
//                },
                'contractor_detail[project_type]': {
                    required: true
                },
                'contractor_detail[administrator_name]': {
                    required: true
                },
                'contractor_detail[license_attachment]': {
                    extension: "pdf|docx|doc|pptx|ppt|xls|csv|xlsx|jpg|png|txt|odt"
                },
                'contractor_detail[liability_policy_attachment]': {
                    extension: "pdf|docx|doc|pptx|ppt|xls|csv|xlsx|jpg|png|txt|odt"
                },
                'contractor_detail[workmen_insurance_attachment]': {
                    extension: "pdf|docx|doc|pptx|ppt|xls|csv|xlsx|jpg|png|txt|odt"
                }
            }, messages: {
                'email': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.',
                    remote: 'Email already exist.'
                },
                'title': {
                    required: 'Please enter title.',
                },
                'first_name': {
                    required: 'Please enter first name.',
                },
                'last_name': {
                    required: 'Please enter last name.',
                },
                'mobile': {
                    required: 'Please enter mobile number.',
                    pattern: 'Please enter valid mobile number.',
                    maxlength: 'Mobile number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid work mobile',
                },
                'phone': {
                    required: 'Please enter work number.',
                    pattern: 'Please enter valid work number.',
                    maxlength: 'Work number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid work number',
                },
                'docusign_username': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.'
                },
                 'docusign_password': {
                    required: 'Please enter password.'
                },
                 'docusign_integrator_key': {
                    required: 'Please enter integrator key.'
                },
                'contractor_detail[company_name]': {
                    required: 'Please enter company name.',
                },
                'contractor_detail[website]': {
                    required: 'Please enter website URL.',
                    pattern: 'Please enter valid website url!'
                },
                'contractor_detail[company_phone]': {
                    required: 'Please enter company phone number.',
                    phoneUS: 'Please enter valid company phone number.',
                },
                'contractor_detail[license_number]': {
                    required: 'Please enter license number.',
                },
                'contractor_detail[license_state]': {
                    required: 'Please enter license state.',
                },
                'contractor_detail[license_expiration]': {
                    required: 'Please enter license expiration date.',
                },
                'contractor_detail[company_country]': {
                    required: 'Please select company country.',
                },
                'contractor_detail[state_id]': {
                    required: 'Please select state.',
                },
                'contractor_detail[company_city]': {
                    required: 'Please enter company city.',
                },
                'contractor_detail[company_pincode]': {
                    required: 'Please enter company zipcode.',
                },
                'contractor_detail[company_address]': {
                    required: 'Please enter address line 1.',
                },
//                'contractor_detail[company_address2]': {
//                    required: 'Please enter address line 2.',
//                },
                'contractor_detail[project_type]': {
                    required: 'Please select project type.',
                },
                'contractor_detail[administrator_name]': {
                    required: 'Please enter administrator/executive contact name.',
                },
                'contractor_detail[license_attachment]': {
                    extension: 'Please select pdf,docx,doc,pptx,ppt,xls,csv,xlsx,jpg,png,txt,odt file.'
                },
                'contractor_detail[liability_policy_attachment]': {
                    extension: 'Please select pdf,docx,doc,pptx,ppt,xls,csv,xlsx,jpg,png,txt,odt file.'
                },
                'contractor_detail[workmen_insurance_attachment]': {
                    extension: 'Please select pdf,docx,doc,pptx,ppt,xls,csv,xlsx,jpg,png,txt,odt file.'
                }
            },
            errorPlacement: function (error, element) {
                element.parents('div.col-sm-8,div.col-sm-10').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8,div.col-sm-10").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var setDatePicker = function () {
        $('#contractor-detail-license-expiration').datepicker({
            todayHighlight: true,
            format: datePickerFormat,
            autoclose: true,
            forceParse: false,
        });
        $("#contractor-detail-license-expiration").mask("99/99/9999");
    }
    return{
        init: function () {
            validateContractorForm();
            setDatePicker();
        }
    }
}();
/**
 * 
 * SalesPerson Class
 */
var SalesPerson = function () {
    var validateSalespersonForm = function () {
        $(".add-salesperson-form").validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'title': {
                    required: true
                },
                'first_name': {
                    required: true
                },
                'last_name': {
                    required: true
                },
                'mobile': {
                    required: true,
                    phoneUS: true,
                    // pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'phone': {
                    required: true,
                    phoneUS: true,
                    // pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {url: SITE_URL + 'ajax/checkEmailAvailibility/' + $('#email').val(), type: 'GET'}
                },
                'parent_id': {
                    required: true
                },
                'probationary': {
                    required: true
                },
                'address1': {
                    required: true
                },
                'state_id': {
                    required: true
                },
                'city': {
                    required: true
                },
            }, messages: {
                'title': {
                    required: 'Please enter title.',
                },
                'first_name': {
                    required: 'Please enter first name.',
                },
                'last_name': {
                    required: 'Please enter last name.',
                },
                'mobile': {
                    required: 'Please enter mobile number.',
                    pattern: 'Please enter valid mobile number.',
                    maxlength: 'Mobile number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid work mobile',
                },
                'phone': {
                    required: 'Please enter work number.',
                    pattern: 'Please enter valid work number.',
                    maxlength: 'Work number is no more than 15 digits.',
                    phoneUS: 'Please specify a valid work number',
                },
                'email': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.',
                    remote: 'Email already exist.'
                },
                'parent_id': {
                    required: 'Please select contractor.',
                },
                'probationary': {
                    required: 'Please select probationary.',
                },
                'address1': {
                    required: 'Please enter address line 1'
                },
                'address2': {
                    required: 'Please enter address line 2'
                },
                'state_id': {
                    required: 'Please select state.',
                },
                'city': {
                    required: 'Please enter city.',
                }

            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-sm-8").addClass('has-error').removeClass(validClass);

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error').addClass(validClass);
            },
        });
    }
    var getSalesPersonAddress = function () {
        $('body').on('change', '#parent_id', function () {
            $this = $(this);
            if ($(this).val() != '') {
                $.ajax({
                    url: SITE_URL + 'ajax/getContractorAddress/' + $(this).val(),
                    type: 'GET',
                    beforeSend: function (xhr) {
                        $this.parents('tr').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin: 19px 0 0;">'});
                    },
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        if (obj.error == 0) {
                            $("#address1").val(obj.company_address1);
                            $("#address2").val(obj.company_address2);
                            $("#city").val(obj.city);
                            if (obj.state != 0) {
                                $("#state-id").val(obj.state);
                            } else {
                                $("#state-id").val('');
                            }
                        }

                    }
                })
            }
        });
    }

    return{
        init: function () {
            validateSalespersonForm();
            getSalesPersonAddress();
        }
    }
}();
$(document).ready(function () {
    Common.init();
}); 