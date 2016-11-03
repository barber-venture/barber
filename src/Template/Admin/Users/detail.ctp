<?php 
$this->assign('title', 'Profile');
use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Users',['controller' => 'Users', 'action' => 'view_user']);
$this->Html->addCrumb('Detail','');

?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
        
        <div class="col-sm-3">
            <div class="panel panel-profile custom-panel-profile">
                <div class="panel-body">
                    <img src="<?php echo $this->Common->getUserImage($user['id'],128,128,1); ?>" class="img-circle" />
                    <div class="name"><?php echo $user['name']; ?></div>
                    <div class="info"><?php echo Configure::read('Site.title'); ?></div>
                </div>
            </div><!-- panel -->

        </div><!-- col-sm-3 -->
        <div class="col-sm-9">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Detail</h2>
                        </div>
                        <div class="panel-body">

                            <div class="about-area">
                                <h4>User Personal Information</h4>
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
                                            								           
                                            <tr>
                                                <th width="20%">Name</th>
                                                <td><?php echo $user['name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nick Name</th>
                                                <td><?php echo $user['user_detail']['nike_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td><?php echo $user['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td><?php echo $user['user_detail']['phone']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile</th>
                                                <td><?php echo $user['user_detail']['mobile']; ?></td>
                                            </tr>
											<tr>
                                                <th>Gender</th>
                                                <td><?php												
												$gender = Configure::read('SiteSetting.Gender');
												echo $gender[$user['user_detail']['gender']];
												?></td>
                                            </tr>
											<tr>
                                                <th>Date of birth</th>
                                                <td><?php echo date(Configure::read('Site.CakeDateFormatForView'), strtotime($user['user_detail']['dob'])); ?></td>
                                            </tr>
											<tr>
                                                <th>Address</th>
                                                <td><?php echo $user['user_detail']['address']; ?></td>
                                            </tr>
<!--											<tr>-->
<!--                                                <th>Address 2</th>-->
<!--                                                <td><?php echo $user['user_detail']['address2']; ?></td>-->
<!--                                            </tr>-->
											
<!--											<tr>-->
<!--                                                <th>City</th>-->
<!--                                               <td><?php echo $user['user_detail']['city']['name']; ?></td>-->
<!--                                            </tr>-->
<!--											<tr>-->
<!--                                                <th>State</th>-->
<!--                                                <td><?php echo $user['user_detail']['state']['name']; ?></td>-->
<!--                                            </tr>-->
<!--											<tr>-->
<!--                                                <th>Country</th>-->
<!--                                                <td><?php echo $user['user_detail']['country']['name']; ?></td>-->
<!--                                            </tr>-->
                                            <tr>
                                                <th>About Me</th>
                                                <td><?php echo $user['user_detail']['about_me']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>