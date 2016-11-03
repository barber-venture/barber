<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html lang="en" class="coming-soon">
    <head>
        <meta charset="utf-8">
        <title><?php echo Configure::read('Site.title'); ?> | <?php echo  h($this->fetch('title')) ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="author" content="KaijuThemes">
        <?php echo $this->fetch('meta'); ?>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600' rel='stylesheet' type='text/css'>
        <?php
        echo $this->Html->css(array(
            '../plugins/iCheck/skins/minimal/blue',
            '../fonts/font-awesome/css/font-awesome.min',
            '../fonts/themify-icons/themify-icons.css',
            'styles',
            //'styles-new',
            '../plugins/codeprettifier/prettify',
            '../plugins/iCheck/skins/minimal/blue',
            '../plugins/fullcalendar/fullcalendar',
            '../plugins/jvectormap/jquery-jvectormap-2.0.2',
            '../plugins/switchery/switchery',
            '../plugins/form-daterangepicker/daterangepicker-bs3',
            'dev'
        ));
        ?>
        
        <!--[if lt IE 10]>
        <script type="text/javascript" src="<?php echo SITE_URL; ?>js/media.match.min.js"></script>
        <script type="text/javascript" src="<?php echo SITE_URL; ?>js/respond.min.js"></script>
        <script type="text/javascript" src="<?php echo SITE_URL; ?>js/placeholder.min.js"></script>
    <![endif]-->

        <!-- The following CSS are included as plugins and can be removed if unused-->
         <?php echo $this->fetch('css'); ?>
         <script> 
             var SITE_URL="<?php echo SITE_FULL_URL; ?>";
             var datePickerFormat="<?php echo Configure::read('Site.DatePickerFormat'); ?>";
         </script>
    </head>
    <body class="animated-content">
        <?php echo $this->element('header_customer'); ?>
        <div id="wrapper">
            <div id="layout-static">
                 
                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">                          
                            
                            <div class="container-fluid mt-lg <?php echo ($this->request->params['action']=='application')?'pre-login-content':''; ?>">

                             <?php echo  $this->fetch('content') ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Load site level scripts -->

<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->
        <?php echo $this->Html->script(array(
            'jquery-1.10.2.min',
            'jqueryui-1.10.3.min',
            'bootstrap.min',
            'enquire.min',
            'jquery.validate.min',
            'additional-methods',
            'jquery.blockui.min',
             'chosen.jquery.min',
            '../plugins/velocityjs/velocity.min',
            '../plugins/velocityjs/velocity.ui.min',
            '../plugins/wijets/wijets',
            '../plugins/codeprettifier/prettify',
            '../plugins/bootstrap-switch/bootstrap-switch',
            '../plugins/bootstrap-tabdrop/js/bootstrap-tabdrop',
            '../plugins/iCheck/icheck.min',
            '../plugins/nanoScroller/js/jquery.nanoscroller.min',
            '../plugins/form-daterangepicker/moment.min',
            '../plugins/bootstrap-datepicker/bootstrap-datepicker',
            'bootbox.min',
            'application',
            'jquery.mask.min',
            'site'
        )); ?> 
        <?php echo  $this->fetch('script') ?>
    </body>
</html>