<?php 
$this->assign('title', 'Questions');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Questions','');
?>

<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
              
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Question List</h2>
                        </div>
                        <div class="panel-body">

                           <div class="table-responsive table-bordered mt-md">
								<table class="table-list table table-bordered table-striped m-n">
										<thead>
											<th>Question</th>
											<th>Status</th>
											<!--<th>Created</th>-->
											<!--<th>Modified</th>-->
											<th>Action</th>
										</thead>
                                        <tbody>
                                            <?php
									 if (!$questions->isEmpty()) {
											foreach($questions as $page){												
											?>				 			           
                                            <tr>
                                                <td>
												<?php echo  $this->Html->link(__($page['question']), ['action' => 'detail', $page->id], ['escape' => false ,'title' => 'Edit', 'class' => '']) ?>												
												</td>
												
												<td><?php echo $this->Common->getStatus($page['status']); ?></td>
                                           
<!--                                                <td><?php echo date(Configure::read('Site.CakeDateTimeFormatForView'), strtotime($page['created'])); ?></td>-->
<!--                                           												-->
<!--												<td><?php echo date(Configure::read('Site.CakeDateTimeFormatForView'), strtotime($page['updated'])); ?></td>-->
												<td>
													<?php echo  $this->Html->link(__('<i class="ti ti-pencil"></i>'), ['action' => 'add', $page->id], ['escape' => false ,'title' => 'Edit', 'class' => 'btn btn-inverse-alt']) ?>													
													&nbsp;
													<?php //echo $this->Html->link(__('<i class="ti ti-close"></i>'), ['action' => 'delete', $page->id], ['escape' => false, 'title' => 'Delete', 'class' => 'btn btn-danger-alt', 'onclick' => "return confirm('Are you sure you want to delete this question?')"]) ?>	
												</td>
                                            </tr>
                                            <?php } 
											
										} else {
										?>
										<tr><td colspan="10" style="text-align: center;">No Question found.</td></tr>
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