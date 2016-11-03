<?php
$this->assign('title', 'Subscription');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Subscription');
$CakeDateFormatForView = Configure::read('Site.CakeDateFormatForView');
$CakeDateTimeFormatForView = Configure::read('Site.CakeDateTimeFormatForView');
?>
<?php echo  $this->Flash->render(); ?>
<div class="panel panel-default" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">

    <div class="panel-heading">
        <h2>All Subscription</h2>      
        <div class="pull-right">
            <a class="btn btn-success" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'addSubscription']); ?>">Add</a>    

        </div>
    </div>
    <div class="panel-body">  
        <div class="table-responsive table-bordered mt-md">
            <table class="table-list table table-bordered table-striped m-n">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Plan Name</th>
                        <th>Plan Price</th>
                        <th>Expiry Date</th>
                        <th>subscription Date</th>
                        <th>Status</th>       
                        <th>Action</th>  
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $usercheck = array();
                    if (!$UserPlanAssociations->isEmpty()) {
                        foreach ($UserPlanAssociations as $value) {
                            ?>				 			           
                            <tr>
                                <td>
                                    <a href="javascript:void(0)" class="UserDetail" data-id="<?php echo $value['user']['id'] ?>"><?php echo isset($value['user']['name']) ? $value['user']['name'] : 'N/A'; ?></a>  </td>
                                <td>
                                    <a href="javascript:void(0)" class="PlanDetail" data-id="<?php echo $value['plan']['id'] ?>">
                                        <?php echo isset($value['plan']['plan_name']) ? $value['plan']['plan_name'] : 'N/A'; ?>
                                    </a></td>
                                <td><?php echo isset($value['plan']['plan_price']) ? $value['plan']['plan_price'] : 'N/A'; ?></td>
                                <td><?php echo isset($value['expiry_date']) ? date($CakeDateFormatForView, strtotime($value['expiry_date'])) : 'N/A'; ?></td>
                                <td><?php echo isset($value['created']) ? date($CakeDateTimeFormatForView, strtotime($value['created'])) : 'N/A'; ?></td>
                                <td> 
                                    <a  style="cursor: pointer" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'changeStatusScuscription', $value->id, $value->status]); ?>" onclick="return confirm('Are you sure perform this action?')"    title="Change Status" >
                                        <?php echo $this->Common->getStatus($value['status']); ?>
                                    </a>                                


                                </td> 
                                <td>
                                    <?php if(!in_array($value['user']['id'], $usercheck)){ ?>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'addSubscription', $value->id]); ?>" title="Edit" class="btn btn-inverse-alt"><i class="ti ti-pencil"></i></a>
                                    <?php
                                    array_push($usercheck, $value['user']['id']);
                                    } ?>
                                </td>



                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="10" style="text-align: center;">No Subscription found.</td></tr>
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



        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> 

<?php echo  $this->Common->loadJsClass('Master'); ?>