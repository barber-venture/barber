<?php
use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Online Dating: Sweeedy</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">
        <?php
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        if (isset($SiteSettingsTbl['favicon']) && $SiteSettingsTbl['favicon'] != '') {
            ?>
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE_FULL_URL; ?>uploads/sites/<?php echo $SiteSettingsTbl['favicon']; ?>">
        <?php } else { ?>
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE_FULL_URL; ?>favicon.ico">
            <?php
        }
        echo $this->Html->css(array(
            'front/bootstrap.min',
            'front/animation',
            'front/hover',
			'front/font-awesome.min',
            'front/font',
            'front/style',
            'front/easy-responsive-tabs',
            'front/jquery-ui',
            'front/bootstrap.vertical-tabs',
            'front/style-new',
            './../plugins/pines-notify/pnotify',
            './../plugins/bootstrap-datepicker/css/datepicker',
            './../plugins/form-select2/select2',
            'dev',
            'front/component',
            //'_style',
        ));
        echo $this->fetch('css');

        echo $this->Html->script(array(
            'front/cssua.min',
                //'respond.min.min',
        ));
        ?>

        <script> var SITE_URL = "<?php echo SITE_FULL_URL; ?>";</script>
	<!-- video chat message files-->
	
	<script type="text/javascript">
            var SITE_URL = '<?php echo SITE_URL; ?>';
            var path = '<?php echo SITE_URL; ?>';
            var SITE_PORT_IP = '<?php echo SITE_PORT_URL_IP; ?>';// URL IP with PORT 
            var SITE_PORT_HOST = '<?php echo SITE_PORT_IP; ?>'; // Only URL IP
            var SITE_PORT = '<?php echo SITE_PORT; ?>'; // Only PORT
			var USER_FRIST = '<?php echo $this->request->session()->read('Auth.User.id'); ?>'; // Logged in user id
			var USER_SECOND = '<?php echo $this->request->session()->read('TO_USER_ID'); ?>'; // SECOND USER FOR CHAT
	</script>
    </head>

    <body>       
        <?php
        if ($this->request->session()->read('Auth.User.id') != "") {
            echo $this->element('dashboard_header');
        } else {
            echo $this->element('header');
        }

        echo $this->fetch('content');


        echo $this->element('footer');

        echo $this->Html->script(array(           
            'front/jquery-1.11.3.min',    
            'front/custom',
            'bootstrap.min',
            'front/parallax',
            'front/easyResponsiveTabs',
            'front/jquery-ui',
            'front/modernizr-custom',
            'front/masonry.pkgd.min',
            'front/imagesloaded.pkgd.min',
            'front/classie',
            'front/colorfinder-1.1',
            'front/gridScrollFx',
            'respond.min',
            'jquery.validate.min',
            'additional-methods',
            'jquery.blockui.min',
            './../plugins/bootstrap-datepicker/bootstrap-datepicker',
            './../plugins/form-select2/select2.min.js',
            './../plugins/pines-notify/pnotify.min',
            'front/imagepreview',
            'front/ion.rangeSlider',
            'front/slimScroll/jquery.slimscroll',
            'users',
            'chat/socket.io-1.2.0.js',
            'chat/chat_msg'
        ));
        ?>
	<!--added for video chat -->
        <script src="<?php echo SITE_URL; ?>js/video_chat/public/node_modules/handlebars/dist/handlebars.min.js"></script>
		<script src="<?php echo SITE_URL; ?>js/video_chat/public/node_modules/peerjs/dist/peer.min.js"></script>
	<!--<script src="<?php echo SITE_URL; ?>js/video_chat/public/js/script.js"></script>-->
        <!--end of video chat attache files -->
	<script>
            $(document).ready(function () {
                $(".navigation").click(function () {
                    $(".nav-grid").toggleClass("is-active");
                    $(".navigation-part").toggleClass("is-active2");
                    $(".overlay").toggleClass("active");
                });
               
                $('#horizontalTab').easyResponsiveTabs({
                    type: 'default', //Types: default, vertical, accordion           
                    width: 'auto', //auto or any width like 600px
                    fit: true   // 100% fit in a container
                });

                $('.profile-edit-right .rows ul li a').click(function (e) {
                    e.preventDefault();
                    $(this).parent().find('.detail-info').slideToggle(function () {
                        $(this).parent().toggleClass('active');

                    });
                });
            });
        </script>        

        <?php
        //echo $this->element('popups/login');
        ?>
        <?php echo $this->Common->loadJsClass('User'); ?>
        <?php echo $this->fetch('script') ?> 

    </body> 

    <script>
        $(document).ready(function() {
            
            $('#boxscroll').slimscroll({
                wheelStep: 2,
                color: '#ed1a3b',
                width:'250px'
            });
            
            $('.scrollingList').slimscroll({
                wheelStep: 2,
                height: '450px',
                color: '#ed1a3b',
            });
            $(".user-info-icon li a.setting").click(function (e) {
                $(".setting-link").slideToggle();
                $(".notification-list").hide();
                e.stopPropagation();
            });
             
            $(".user-info-icon li a.bell").click(function(e){
                $(".setting-link").hide();
                $(".notification-list").slideToggle();
                e.stopPropagation();
            });                       
	
            window.socket = io('192.169.235.18:3131');
            window.userID = '<?php echo $this->request->session()->read("Auth.User.id");  ?>';
            socket.emit('userId', userID);
    
        });
        
    </script> 

</html>