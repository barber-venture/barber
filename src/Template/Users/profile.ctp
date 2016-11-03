<?php  die('dfdf');
$this->assign('title', 'Profile');
use Cake\Core\Configure;
$this->Html->addCrumb('Profile',['controller' => 'Users', 'action' => 'profile']); ?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
        
        <div class="col-sm-3">
            <div class="panel panel-profile custom-panel-profile">
                <div class="panel-body">
                    <img src="<?php echo $this->Common->getUserImage($this->request->session()->read('Auth.User.image'),128,128,1); ?>" class="img-circle" />
                    <div class="name"><?php echo $user['user_detail']['nike_name']; ?></div>
                    <div class="info"><?php echo Configure::read('Site.title'); ?></div>

                    <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'edit_profile']);?>" class="list-group-item"><i class="ti ti-pencil"></i> Edit</a>
                </div>
            </div><!-- panel -->

        </div><!-- col-sm-3 -->
        <div class="col-sm-9">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>About</h2>
                        </div>
                        <div class="panel-body">

                            <div class="about-area">
                                <h4>Personal Information</h4>
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
                                            <tr>
                                                <th>Title</th>
                                                <td><?php echo $user['title']; ?></td>
                                            </tr>								           
                                            <tr>
                                                <th>Full Name</th>
                                                <td><?php echo $user['name']; ?></td>
                                            </tr>								           
                                            <tr>
                                                <th>Email</th>
                                                <td><?php echo $user['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td><?php echo $user['phone']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile</th>
                                                <td><?php echo $user['mobile']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>City</th>
                                                <td><?php echo $user['city']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Country</th>
                                                <td><?php echo $user['country']['name']; ?></td>
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