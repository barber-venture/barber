<?php 
$this->assign('title', 'Album');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Album','');
?>

<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
              
        <div class="col-sm-12">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Album List</h2>
                        </div>
                        <div class="panel-body">
							<?php
							echo $this->Form->create('', ['url' => ['controller' => 'Albums', 'action' => 'index'],'type' => 'GET', 'class' => 'form-horizontal', 'id' => 'album_search']);
							//pr($user_filter); die;					
							//$this->Form->templates(['inputContainer' => '{{content}}']);
							?>
							<div class="row">
								<div class="col-md-5 col-sm-6">
									<div class="input-group mt-sm">
										<span class="input-group-btn">
											<button type="submit" class="btn"><i class="ti ti-search"></i></button>
										</span>
										<input value="<?php echo (isset($this->request->query['name'])) ? $this->request->query['name'] : ''?>" type="text" id="keyword" value="" placeholder="Search..." class="form-control" name="name">
									</div>
								</div>
					
								<div class="col-md-7 col-sm-6"> 
					
									<div class="col-md-6"></div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-n mt-sm"> 							
											<label class="col-md-3 col-sm-3 col-xs-3 control-label" for="fieldname">User:</label>
											<div class="col-md-6 col-sm-6 col-xs-7">
												<div class="row">					
													<?php echo $this->Form->input('user_id', ['options' => $users, 'type' => 'select',  'id' => 'user_filter', 'label' => false, 'empty' => 'Select User', 'class' => 'form-control', 'default' => (isset($this->request->query['user_id'])) ? $this->request->query['user_id'] : '']); ?>					
												</div>
											</div> 
										</div>
									</div>					
								</div>					
							</div>
							
							<?= $this->Form->end(); ?>
							
                           <div class="table-responsive table-bordered mt-md">
								<table class="table-list table table-bordered table-striped m-n">
										<thead>

											<th>Album Name</th>
											<th>Description</th>
											<th>Status</th>
											<th>Created Date</th>
											<!--<th>Action</th>-->
										</thead>
                                        <tbody>
                                            <?php  //pr($this->request->query); die;
										if (!$Albums->isEmpty()) {
											foreach($Albums as $page){												
											?>				 			           
                                            <tr>
												<td>
												<?php echo  $this->Html->link(__($page['name']), ['action' => 'album_detail', $page->id], ['escape' => false ,'title' => 'View detail']) ?>												
												</td>
                                           
                                                <td>
												<?php echo  $this->Html->link(__($page['description']), ['action' => 'album_detail', $page->id], ['escape' => false ,'title' => 'View detail']) ?>												
												</td>												
												
												<td><?php echo $this->Common->getStatus($page['status']); ?></td>
												
												<td><?php echo date(Configure::read('Site.CakeDateTimeFormatForView'), strtotime($page['created'])); ?></td>
												
                                            </tr>
                                            <?php }
												
										} else {
										?>
										<tr><td colspan="10" style="text-align: center;">No Albums found.</td></tr>
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
<?php $this->Html->scriptStart(['block' => true]); ?>
	$(function(){
		$('#user_filter').change(function(){
			$('#album_search').submit();
		});
	});
 <?php $this->Html->scriptEnd(); ?>
<?php echo  $this->Common->loadJsClass('User'); ?>