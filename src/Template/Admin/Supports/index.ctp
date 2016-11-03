<?php
$this->assign('title', 'Support');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Support');
?>
<?php echo $this->Flash->render(); ?>
<div class="panel panel-default" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">

    <div class="panel-heading">
        <h2>All Support</h2>      
    </div>
    <div class="panel-body">  
        <div class="table-responsive table-bordered mt-md">
            <table class="table-list table table-bordered table-striped m-n">
                <thead>
                    <tr>

                        <th><?php echo $this->Paginator->sort('id') ?></th>
                        <th><?php echo $this->Paginator->sort('access_via') ?></th>
                        <th><?php echo $this->Paginator->sort('support_category') ?></th>
                        <th><?php echo $this->Paginator->sort('email') ?></th>
                        <th><?php echo $this->Paginator->sort('subject') ?></th>
                        <!--<th><?php echo $this->Paginator->sort('attachment') ?></th>-->
                        <th><?php echo $this->Paginator->sort('created') ?></th>
                        <th class="actions"><?php echo __('Actions') ?></th>

                    </tr>
                </thead>
                <tbody> 
                    <?php
                    $AccessVia = Configure::read('AccessVia');

                    $SupportCategory = Configure::read('SupportCategory');
                    if (isset($supports)) {
                        
                        foreach ($supports as $support):
                            ?>
                            <tr>
                                <td><?php echo $this->Number->format($support->id) ?></td>
                                <td><?php echo $AccessVia[$this->Number->format($support->access_via)] ?></td>
                                <td><?php echo $SupportCategory[$this->Number->format($support->support_category)] ?></td>
                                <td><?php echo h($support->email) ?></td>
                                <td><?php echo h($support->subject) ?></td>
                                <!--<td><?php //echo h($support->attachment) ?></td>-->
                                <td><?php echo h($support->created) ?></td>                               
                                <td class="actions">
                                    <a title="View Detail"  class="btn btn-info-alt" href="<?php echo $this->Url->build(['controller' => 'Supports', 'action' => 'view', $support->id]); ?>">
                                        <i class="ti ti-eye"></i>
                                    </a> 
                                    <a title="Replay" id="replay"  href="javascript:void(0)" class="btn btn-success-alt" data-id="<?php echo $support->id ?>">
                                        <i class="ti ti-share"></i>
                                    </a>
                                </td> 
                            </tr>
                            <?php
                        endforeach;
                    } else {
                        ?>
                        <tr><td colspan="10" style="text-align: center;">No Support found.</td></tr>
                    <?php } ?>


                </tbody>
            </table>



        </div>
        <?php echo $this->Element('Admin/pagination'); ?>
    </div>




</div> 


<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="UserDetailModel" class="modal fade "  >
    <div class="modal-backdrop fade in"></div>
    <div class="modal-dialog">
        <div class="modal-content modal-body-userDetail">



        </div>  
    </div> 
</div>
 
<?php echo  $this->Common->loadJsClass('Master'); ?>
      
