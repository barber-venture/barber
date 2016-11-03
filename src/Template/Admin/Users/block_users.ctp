<?php 
$this->assign('title', 'Blocked User');
use Cake\Core\Configure;
 $this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Blocked Users'); ?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
              
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Blocked User List</h2>
                        </div>
                        <div class="panel-body">

                            <div class="table-responsive table-bordered mt-md">
								<table class="table-list table table-bordered table-striped m-n">
									<thead>
										<th>Blocked By</th>
										<th>Blocked To</th>
										<th>Block Date</th>
										<th>Action</th>
									</thead>
									<tbody>
										<?php
									if (!$users->isEmpty()) {
										foreach($users as $user){												
										?>				 			           
										<tr>                                          
											<td><?php echo $user['from_user']['name']; ?></td>
									   
											 <td><?php echo $user['to_user']['name']; ?></td>											 
									   
											<td><?php echo date(Configure::read('Site.CakeDateTimeFormatForView'), strtotime($user['created']));  ?></td>
											<td>													
												<?php echo $this->Html->link(__('Unblock'), ['action' => 'delete_block_users', $user->id], ['class' => 'btn btn-danger-alt', 'escape' => false, 'title' => 'Unblock User ?',  'onclick' => "return confirm('Are you sure you want to unblock this user?')"]) ?>
											</td>
										</tr>
										<?php }
										} else {
											?>
											<tr><td colspan="10" style="text-align: center;">No Users found.</td></tr>
										<?php } ?>
									</tbody>
							
								</table>
							 </div>
						
							<?php echo $this->Element('Admin/pagination'); ?>
                        </div>
                    </div>
                </div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('User'); ?>