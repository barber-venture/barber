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
 
var MasterSiteSetting = function () {
    var validateSiteSettingForm = function () {
        $('.siteSetting').validate({
            errorClass: "help-block",
            validClass: 'valid',
            rules: {
                'site_name': {
                    required: true
                },
                'site_hotline_no': {
                    required: true,
                    phoneUS: true,
                    maxlength: 15,
                },
                'contact_us_email': {
                    required: true,
                    email: true
                },
                'info_email': {
                    required: true,
                    email: true
                },
                'no_reply_email': {
                    required: true,
                    email: true
                },
                'per_page_limit': {
                    required: true,
                    number: true,
                    maxlength: 3,
                },
                'site_logos': {
                    extension: "PNG|JPG|jpg|png|jpeg|JPEG"
                },
                'favicons': {
                    extension: "PNG|png|ico|ICO"
                },
                'site_address': {
                    required: true
                }
            }, messages: {
                'site_name': {
                    required: 'Please enter site name.'
                },
                'site_hotline_no': {
                    required: 'Please enter contact number.',
                    phoneUS: 'Please enter valid contact number.',
                    maxlength: 'Please enter max 10 digit',
                },
                'contact_us_email': {
                    required: 'Please enter contact us email id.',
                    email: 'Please enter valid contact us email id.',
                },
                'info_email': {
                    required: 'Please enter info email id.',
                    email: 'Please enter valid info email id.',
                },
                'no_reply_email': {
                    required: 'Please enter no-reply email id.',
                    email: 'Please enter valid no-reply email id.',
                },
                'per_page_limit': {
                    required: 'Please enter par page limit.',
                    number: 'Please enter only number.',
                    maxlength: 'Please enter only three digit.',
                },
                'site_logos': {
                    extension: 'Please select jpg ,jpeg and png file.',  
                },
                'favicons': {
                    extension:  'Please select png and ico file.'
                },
                'site_address': {
                    required: 'Please enter site address.'
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

    return{
        init: function () {
            validateSiteSettingForm();
        }
    }
}();

     