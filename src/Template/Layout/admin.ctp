<?php

use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html lang="en" class="coming-soon">
    <head>
        <meta charset="utf-8">
        <title><?php echo Configure::read('Site.title'); ?> | <?php echo  h($this->fetch('title')) ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        
        <?php
        $SiteSettingsTbl = Configure::read('SiteSettingsTbl');
        if(isset($SiteSettingsTbl['favicon']) && $SiteSettingsTbl['favicon'] != ''){ ?>
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE_FULL_URL; ?>/uploads/sites/<?php echo $SiteSettingsTbl['favicon']; ?>">
        <?php } ?>
        <meta name="author" content="KaijuThemes">
        <?php echo $this->fetch('meta'); ?>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600' rel='stylesheet' type='text/css'>
        <link href=" https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"/>
        <?php
        echo $this->Html->css(array(
            '/plugins/iCheck/skins/minimal/blue',
            '/fonts/font-awesome/css/font-awesome.min',
            '/fonts/themify-icons/themify-icons.css',
            'styles',
            //'styles-new',
            '/plugins/codeprettifier/prettify',
            '/plugins/iCheck/skins/minimal/blue',
            '/plugins/fullcalendar/fullcalendar',
            '/plugins/jvectormap/jquery-jvectormap-2.0.2',
            '/plugins/switchery/switchery',
            '/plugins/form-daterangepicker/daterangepicker-bs3',
            '/plugins/form-select2/select2',
            'dev',
            '/plugins/summernote/dist/summernote'
        ));
        ?>        

        <?php echo $this->fetch('css'); ?>
        <script> var SITE_URL = "<?php echo SITE_FULL_URL; ?>";</script>
    </head>
    <body class="animated-content">
        <?php echo $this->element('Admin/header'); ?>
        <div id="wrapper">
            <div id="layout-static">
                <?php echo $this->element('Admin/sidebar'); ?>
                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">                            
                            <?php 
                            echo $this->Html->getCrumbList(
                                    [
                                'firstClass' => false,
                                'lastClass' => 'active',
                                'class' => 'breadcrumb'
                                    ], ''
                            ); 
                            ?>
                            <div class="container-fluid">

                                <?php echo  $this->fetch('content') ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load site level scripts --> 
        <?php
        echo $this->Html->script(array(
            'jquery-1.10.2.min',
            'jqueryui-1.10.3.min',
            'bootstrap.min',
            'enquire.min',
            'jquery.validate.min',
            'additional-methods',
            'jquery.blockui.min',
            '/plugins/velocityjs/velocity.min',
            '/plugins/velocityjs/velocity.ui.min',
            '/plugins/wijets/wijets',
            '/plugins/codeprettifier/prettify',
            '/plugins/bootstrap-switch/bootstrap-switch',
            '/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop',
            '/plugins/iCheck/icheck.min',
            '/plugins/nanoScroller/js/jquery.nanoscroller.min',
            '/plugins/form-daterangepicker/moment.min',
            '/plugins/bootstrap-datepicker/bootstrap-datepicker',
            '/plugins/form-jasnyupload/fileinput.min',            
            'application',
            'admin',
            '/plugins/form-select2/select2.min.js',
            '/plugins/summernote/dist/summernote',
             'jquery.form.min'
        ));
        ?>
         <?php echo  $this->fetch('script');        
        
        ?>

    </body>
</html>  