<?php 
$this->assign('title', 'Pages');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Pages','');
?>

<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
              
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>PAge List</h2>
                        </div>
                        <div class="panel-body">

                            <div class="table-responsive table-bordered mt-md">
								<table class="table-list table table-bordered table-striped m-n">
										<thead>
											<th>Head Line</th>
											<th>Title</th>
											<th>Keyword</th>
											<th>Description</th>
											<th>status</th>
											<th>Action</th>
										</thead>
                                        <tbody>
                                            <?php
										if (!$pages->isEmpty()) {
											foreach($pages as $page){												
											?>				 			           
                                            <tr>
                                                <td><?php echo $page['page_headline']; ?></td>
                                           
                                                <td><?php echo $page['page_title']; ?></td>
                                           
                                                <td><?php echo $page['keyword']; ?></td>   
												
												<td><?php echo $page['description']; ?></td>
												
												<td><?php echo $this->Common->getStatus($page['status']); ?></td>
												
												<td>
													<?php echo $this->Html->link(__('<i class="ti ti-pencil"></i>'), ['action' => 'add', $page->id], ['escape' => false, 'title' => 'Edit', 'class' => 'btn btn-inverse-alt']) ?>													
													&nbsp;
													<?php echo $this->Html->link(__('<i class="ti ti-close"></i>'), ['action' => 'delete', $page->id], ['class' => 'btn btn-danger-alt','escape' => false, 'title' => 'Delete', 'onclick' => "return confirm('Are you sure you want to delete this page?')"]) ?>	
												</td>
                                            </tr>
                                            <?php }
											} else {
											?>
											<tr><td colspan="10" style="text-align: center;">No Pages found.</td></tr>
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