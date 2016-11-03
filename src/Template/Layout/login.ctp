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
            '/plugins/iCheck/skins/minimal/blue',
            '/fonts/font-awesome/css/font-awesome.min',
            '/fonts/themify-icons/themify-icons.css',
            'styles'
        ));
        ?>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
        <!--[if lt IE 9]>
            <link type="text/css" href="assets/css/ie8.css" rel="stylesheet">
            <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The following CSS are included as plugins and can be removed if unused-->
         <?php echo $this->fetch('css'); ?>
         <script> 
             var SITE_URL="<?php echo SITE_FULL_URL; ?>";
             var datePickerFormat="<?php echo Configure::read('Site.DatePickerFormat'); ?>";
         </script>
    </head>
    <body class="focused-form animated-content">
        <div class="container" id="login-form">
            <a href="<?php echo SITE_FULL_URL; ?>" class="login-logo">
                <?php if($this->Common->getSiteLogo('1',200,55,1)!=""){ ?>
        <img src="<?php echo  $this->Common->getSiteLogo('1',200,55,1); ?>" />
            <?php }else{
                echo "WEDOGH";
            } ?>
               </a>
            <?php echo  $this->fetch('content') ?>
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
             'jquery.blockui.min',
             'chosen.jquery.min',
            '/plugins/velocityjs/velocity.min',
            '/plugins/velocityjs/velocity.ui.min',
            '/plugins/wijets/wijets',
            '/plugins/codeprettifier/prettify',
            '/plugins/bootstrap-switch/bootstrap-switch',
            '/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop',
            '/plugins/iCheck/icheck.min',
            '/plugins/nanoScroller/js/jquery.nanoscroller.min',
            'application',
            'site'
        )); ?> 
        <?php echo  $this->fetch('script') ?>
    </body>
</html>