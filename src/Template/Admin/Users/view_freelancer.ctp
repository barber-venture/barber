<?php 
$this->assign('title', 'Freelancer User List');
use Cake\Core\Configure;
 $this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Users'); ?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
              
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Freelancer List</h2>
							<div class="pull-right">
								<a class="btn btn-success" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'addEditFreelancer']); ?>">Add</a>    
   
							</div>
                        </div>
                        <div class="panel-body">
							
							<div class="row">
								<div class="col-md-4 col-sm-6">
									<?php
									echo $this->Form->create('search', ['type' => 'GET', 'class' => 'form-horizontal', 'id' => 'album_search']);									
									//echo $this->Form->hidden('deleted', ['label' => false, 'type' => 'hidden']);
									//echo $this->Form->hidden('status', ['label' => false, 'type' => 'hidden']);
									//$this->Form->templates(['inputContainer' => '{{content}}']);
									?>
									<div class="input-group mt-sm">
										<span class="input-group-btn">
											<button type="submit" class="btn"><i class="ti ti-search"></i></button>
										</span>
										<input type="text" id="keyword" placeholder="Search..." class="form-control" name="keyword"  value="<?php echo (isset($this->request->query['keyword'])) ? $this->request->query['keyword'] : ''?>">
									</div>
									<?php echo $this->Form->end(); ?>
								</div>
								
								<?php								
								$flag = 2;
								if(isset($this->request->query['status']) && $this->request->query['status'] == 1)
									$flag = 1;
								else if(isset($this->request->query['status']) && $this->request->query['status'] == 0)
									$flag = 0;
								?>
								<div class="col-md-3 mt-sm">
									<a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'viewFreelancer', '?' => ['status' => 1]]); ?>" class="btn btn-<?php if($flag == 1) echo 'success'; else echo 'inverse'; ?>">Active</a>    
									<a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'viewFreelancer', '?' => ['status' => 0]]); ?>" class="btn btn-<?php if($flag == 0) echo 'success'; else echo 'inverse'; ?>">Inactive</a>    
									<a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'viewFreelancer']); ?>" class="btn btn-<?php if($flag == 2) echo 'success'; else echo 'inverse'; ?>">All</a>    
								</div>								
							</div>
							
                           <div class="table-responsive table-bordered mt-md">
								<table class="table-list table table-bordered table-striped m-n">
										<thead>
											<th>Name</th>
											<th>Camera Used</th>											
											<th>Mobile</th>
											<th>Varified</th>
											<th>Online</th>
											<th>Action</th>
										</thead>
                                        <tbody>
                                            <?php
										if (!$users->isEmpty()) {
											foreach($users as $user){												
											?>				 			           
                                            <tr>
                                                <td>
												
												<?php echo  $this->Html->link(__($user['name']), ['action' => 'freelancer_detail', $user->id], ['escape' => false]) ?>
												</td>
                                           
                                                <td>
													<?php echo  $this->Html->link(__($user['user_detail']['camera']), ['action' => 'freelancer_detail', $user->id], ['escape' => false]) ?>
												
												</td>
												
                                                <td>
												<?php echo  $this->Html->link(__($user['user_detail']['mobile']), ['action' => 'freelancer_detail', $user->id], ['escape' => false]) ?>
												</td>
												
												<!--<td> -->
												<!--	<?php echo $this->Common->getStatus($user['status']); ?>-->
												<!--</td>-->
												 
												<td> 
													<?php echo $this->Common->getStatusWithLabel($user['is_verify']); ?>
												</td>
												<td> 
													<?php echo $this->Common->getStatusWithLabel($user['is_online']); ?>
												</td>
																								 
												<td>													
													<?php
													if($user['status'] == 1){ ?>
														<a title="Deactivate User ?" class="btn btn-success-alt"><span style="cursor:pointer;" id="<?php echo $user['id'] ?>" status="<?php echo $user['status'];?>" class="update_user_status" ><i class="ti ti-check"></i></span></a>
													<?php	
													}else{
													?>
														<a title="Activate User ?" class="btn btn-danger-alt"><span style="cursor:pointer;" id="<?php echo $user['id'] ?>" status="<?php echo $user['status'];?>" class="update_user_status" ><i class="ti ti-na"></i></span></a>
													<?php	
													}
													?>													
													&nbsp;
													<?php echo  $this->Html->link(__('<i class="ti ti-folder"></i>'), ['controller' => 'albums', 'action' => 'index', $user->id], ['escape' => false, 'class' => 'btn btn-info-alt', 'title' => 'View Album']) ?>
													&nbsp;
													
													<?php echo  $this->Html->link(__('<i class="ti ti-pencil"></i>'), ['controller' => 'users', 'action' => 'add_edit_freelancer', $user->id], ['escape' => false, 'class' => 'btn btn-inverse-alt', 'title' => 'View Album']) ?>													
													&nbsp;
													<a class="btn btn-danger-alt"><span style="cursor:pointer;" id="<?php echo $user['id'] ?>" class="delete_user" title="Archive User" ><i class="ti ti-close"></i></span></a>
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