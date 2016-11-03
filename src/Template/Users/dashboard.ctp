<?php
$this->assign('title', 'Dashboard');

//$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
use Cake\I18n\Number;
?>
<?php echo  $this->Flash->render(); ?>


<div class="row">
    <div class="col-md-3">
        <div class="info-tile tile-orange">
            <div class="tile-icon"><i class="ti ti-files"></i></div>
            <div class="tile-heading"><span>Total Projects</span></div>
            <div class="tile-body"><span><?php echo $total_projects; ?></span></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-tile tile-success">
            <div class="tile-icon"><i class="application"></i></div>
            <div class="tile-heading"><span>Credit Applications Sent/Approved</span></div>
            <div class="tile-body"><span><?php echo $sent_projects.'/'.$sent_approved_projects; ?></span></div>

        </div>
    </div>
    <div class="col-md-3">
        <div class="info-tile tile-info">
            <div class="tile-icon"><i class="handshake"></i></div>
            <div class="tile-heading"><span>Contracts Sent/Signed</span></div>
            <div class="tile-body"><span><?php echo $sent_contract.'/'.$approved_contract; ?></span></div>

        </div>
    </div>
    <div class="col-md-3">
        <div class="info-tile tile-danger">
            <div class="tile-icon"><i class="ti ti-money"></i></div>
            <div class="tile-heading"><span>Approved Dollar Amount</span></div>
            <div class="tile-body"><span><?php echo  Number::currency($approved_amount, 'USD'); ?></span></div>            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="row mb-sm">
            <div class="col-md-6">
                <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>" class="btn btn-success">All Projects</a>    
                <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard', '?' => ['deleted' => 1]]); ?>" class="btn btn-danger">Archived</a>
                <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'projectsExport']); ?>" class="btn btn-info">Export</a>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading custom-search">
                <h2><div class="custom-search-heading">Projects</div></h2>


                <div class="new__Search">    
                    <div class="new__Search__col_left"> 
                        <div class="newGoSearch" ><?php
                            if (isset($this->request->query)) {
                                $this->request->data = $this->request->query;
                            }

                            echo $this->Form->create('Project', ['type' => 'GET']);
                            $this->Form->templates(['inputContainer' => '{{content}}']);
                            ?>
                            <div class="input-group custom-search-area">
                                <?php
                                echo $this->Form->input('keyword', ['label' => false, 'class' => 'form-control']);
                                echo $this->Form->hidden('sort', ['label' => false]);
                                echo $this->Form->hidden('direction', ['label' => false]);
                                echo $this->Form->hidden('status', ['label' => false]);
                                echo $this->Form->hidden('deleted', ['label' => false]);
                                ?>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-info">Go!</button>
                                </div>
                            </div>
                            <?php echo  $this->Form->end(); ?></div>
                    </div>
                    <div class="new__Search__col_right">
                        <?php
                        $status_title = 'Status';
                        if (isset($this->request->data['status']) && $this->request->data['status'] > 0 && $this->request->data['status'] <= 12) {
                            $status_title = $this->Common->getStatusTitle($this->request->data['status']);
                        }
                        ?>
                        <div class="btn-group pull-right mb filter">
                            <a href="javascript:;" class="btn btn-inverse-alt selecter dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i class="ti ti-filter filter-icon"></i> <?php echo  $status_title; ?>  </a>
                            <a href="javascript:;" class="btn btn-inverse-alt dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <?php echo $this->Html->link('All Projects', ['controller' => 'Users', 'action' => 'dashboard']); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Address Eligible', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 1]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Address Ineligible', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 2]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Payment Calculated', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 3]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Credit App Sent', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 4]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Credit App Received', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 5]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Credit Approved', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 6]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Credit Declined', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 7]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Credit Manual', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 8]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Contract Sent', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 9]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Contract Signed By Customer', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 10]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Contract Signed By JPA', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 11]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Contract Declined By Customer', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 12]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('OK to Proceed', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 13]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Terminated by Customer', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 14]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Certificate of Completion Sent', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 15]]); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('Completed', ['controller' => 'Users', 'action' => 'dashboard', '?' => ['status' => 16]]); ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>    

            </div>
            <div class="panel-body table-responsive">
                <table  class="table-list table table-bordered table-striped m-n" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('Projects.id', 'ID'); ?></th>
                            <?php if ($this->request->session()->read('Auth.User.role_id') == 2) { ?>
                                <th><?php echo $this->Paginator->sort('ContractorDetails.company_name', 'Contractor'); ?></th>
                                <th><?php echo $this->Paginator->sort('Users.name', 'Salesperson'); ?></th>   
                            <?php } else if ($this->request->session()->read('Auth.User.role_id') == 3) { ?>
                                <th><?php echo $this->Paginator->sort('Users.name', 'Sales Person'); ?></th>  
                            <?php } ?>
                            <th><?php echo $this->Paginator->sort('Projects.status', 'Status'); ?></th>
                            <th><?php echo $this->Paginator->sort('Projects.owner_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Projects.address', 'Address'); ?></th>
                            <th><?php echo $this->Paginator->sort('Projects.city', 'City'); ?></th>
                            <th><?php echo $this->Paginator->sort('Projects.zipcode', 'Zipcode'); ?></th>
                            <th>Authorized amt.</th>
                            <th>Project amt.</th>
                            <?php if (in_array($this->request->session()->read('Auth.User.role_id'), array('2','3','4')) ) { ?>
                                <th>Actions</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $role_id=$this->request->session()->read('Auth.User.role_id');
                        if (!$projects->isEmpty()) {
                            foreach ($projects as $project) {
                                ?>
                                <tr> 
                                    <td class="col-lg-1">
                                    <?php if($role_id == 4){
                                        echo $this->Common->getCurrentLink($project);
                                     }else if($role_id==7 && ($project['status']>=6)){
                                        echo $this->Html->link($project['id'], ['controller' => 'Projects', 'action' => 'verifyApplication', $project['id']], ['class' => 'case-id']);
                                     }else{
                                        echo $project['id']; 
                                     } ?>
                                    </td>    
                                    <?php if ($role_id == 2) { ?>
                                        <td><?php echo  $project['user']['contractor']['contractor_detail']['company_name']; ?></td>
                                        <td><?php echo  $project['user']['name']; ?></td>
                                    <?php } else if ($role_id == 3) { ?>     
                                        <td><?php echo  $project['user']['name']; ?></td>                                        
                                    <?php } ?>                                    
                                    <td><?php echo  $this->Common->getStatusTitle($project['status']); ?></td>
                                    <td><?php echo  $this->Common->checkValue($project['owner_name']); ?></td>
                                    <td><?php echo  $this->Common->checkValue($project['address']); ?></td>
                                    <td><?php echo  $this->Common->checkValue($project['city']); ?></td>
                                    <td><?php echo  $this->Common->checkValue($project['zipcode']); ?></td>
                                    <td><?php echo  $this->Common->getProjectAmount($project, 'auth'); ?></td>
                                    <td><?php echo  $this->Common->getProjectAmount($project); ?></td>
                                    <?php if ($role_id == 4) { ?>
                                        <td><?php echo  $this->Common->getNextStep($project); ?>
                                            <?php echo  $this->Common->getProjectArchiveLink($project); ?>
                                        </td>
                                    <?php } else if ($role_id == 2 || $role_id == 3) {  ?>
                                        <td><?php echo  $this->Common->getNextStep($project,'step5'); ?>
                                            <?php echo  $this->Common->getProjectArchiveLink($project); ?>
                                        </td><?php }
                                    ?>  
                                </tr>
                            <?php } ?>  

                        <?php } else { ?>
                            <tr><td colspan="10" style="text-align: center;">No Projects found.</td></tr>
                        <?php } ?>  
                    </tbody>

                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="editable_info" role="status" aria-live="polite"><?php echo  $this->Paginator->counter(['format' => 'Showing {{start}} to {{end}} of {{count}} projects']) ?></div>
                    </div>
                    <?php  if (isset($this->Paginator->params()['pageCount']) && $this->Paginator->params()['pageCount'] > 1) { ?>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_bootstrap" id="editable_paginate">
                                <ul class="pagination pull-right">
                                    <?php echo  $this->Paginator->prev('Previous') ?>
                                    <?php echo  $this->Paginator->numbers() ?>
                                    <?php echo  $this->Paginator->next('Next') ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>