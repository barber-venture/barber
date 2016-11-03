<?php 
$this->assign('title', 'Profile');
use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Users',['controller' => 'Users', 'action' => 'view_freelancer']);
$this->Html->addCrumb('Detail','');

?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">        
       
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Freelancer Detail</h2>
                        </div>
                        <div class="panel-body">
                            <div class="about-area">
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
                                            								           
                                            <tr>
                                                <th width="20%">Name</th>
                                                <td><?php echo $user['name']; ?></td>
                                            </tr>
											<tr>
                                                <th width="20%">Email</th>
                                                <td><?php echo $user['email']; ?></td>
                                            </tr>
											<tr>
												<th>Camera Used</th>
												<td><?php echo $user['user_detail']['camera']; ?></td>
											</tr> 
                                                                                       
                                        </tbody>
                                    </table>
                                </div>
								
							</div>
                        </div>
                    </div>
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Contact Information</h2>
                        </div>
                        <div class="panel-body">
                            <div class="about-area">
								
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
											<tr>
                                                <th>Mobile</th>
                                                <td><?php echo $user['user_detail']['mobile']; ?></td>
                                            </tr>											
											
											<tr>
                                                <th>Address</th>
                                                <td><?php echo $user['user_detail']['address']; ?></td>
                                            </tr>											
<!--											-->
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
<!--                                            </tr> -->
										</tbody>
                                    </table>
                                </div>	
                            
							</div>
                        </div>
                    </div>
                
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Album</h2>
                        </div>
                        <div class="panel-body">
							<?php foreach($user['albums'] as $userAlbum){
								//pr($userAlbum);
								?>
                            <div class="about-area">
								<h4><?php echo $userAlbum['name']; ?></h4>
                                <div class="row">
										<?php
										foreach($userAlbum['album_images'] as $UserDetail){												
										?>	
											<div class="col-md-3">
												<div class="panel panel-default">
													<div class="panel-heading">
														<h2><?php echo $UserDetail['image_title']; ?></h2>
														
														<div class="panel-ctrls">
															<?php echo $this->Html->link(__('<i class="ti ti-close"></i>'), ['action' => 'delete', $UserDetail->id], ['escape' => false,  'class' => 'button-icon', 'title' => 'Delete', 'onclick' => "return confirm('Are you sure you want to delete \'".$UserDetail['image_title']."\' ?')"]) ?>	
														</div>
													</div>
													<div class="panel-body" style="text-align: center;">
														<img src="<?php echo  $this->Common->getUserAlbumImage($UserDetail['image_name'], 128, 128, 1); ?>" class="img-circle" />
													</div>
												</div>
											</div>
										<?php
										}									
										?>
									</div>							
							</div>
							<?php } ?>
                        </div>
                    </div>
					
				</div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>