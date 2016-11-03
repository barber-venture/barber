<?php 
$this->assign('title', 'Album Detail');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Album', ['controller' => 'Albums', 'action' => 'index']);
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
                            <h2><?php 
							echo $album['name']; ?>
							</h2>
                        </div>
                        <div class="panel-body">

                            <div class="about-area">
                                            
									<div class="row">
										<?php
									if (!$UserDetails->isEmpty()) {
										foreach($UserDetails as $UserDetail){												
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
										<?php }
									}else{
										echo '<div class="col-md-10">No image found.</div>';
									} ?>
									</div>							
                            </div>
						</div>
                    </div>
                </div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('User'); ?>