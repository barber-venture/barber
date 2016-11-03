<!-- start header -->
<?php use Cake\Core\Configure; ?>
<header id="pageHeader" class="header">
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-lg-4">
            <div class="logo animated fadeInDown">
                <figure><a href="<?php echo SITE_FULL_URL; ?>">
                    <img src="<?php echo  $this->Common->getSiteLogo('1',145,50,1); ?>" />
                </a></figure>
            </div>
        </div>
         <div class="col-xs-12 col-sm-6 col-lg-8">
            <div class="helpDesk animated fadeInDown">
                <ul>
                  <!--<li><a href="#" class="freelancer hvr-ripple-out">Freelancer</a></li>-->
                  <li><a href="#" class="login hvr-rectangle-in" data-toggle="modal" data-target="#myModal">Login</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
  <!-- /.container --> 
</header>

<!-- end header -->