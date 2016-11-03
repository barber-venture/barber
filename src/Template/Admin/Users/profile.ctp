<?php 
$this->assign('title', 'Profile');
use Cake\Core\Configure;
 $this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Profile',['controller' => 'Users', 'action' => 'profile']); ?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
        
        <div class="col-sm-3">
            <div class="panel panel-profile custom-panel-profile">
                <div class="panel-body">
                    <img src="<?php echo $this->Common->getUserImage($this->request->session()->read('Auth.User.id'),128,128,1); ?>" class="img-circle" />
                    <div class="name"><?php echo $user['name']; ?></div>
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
                                                <th>Name</th>
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