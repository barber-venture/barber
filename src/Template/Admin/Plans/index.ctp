<?php
$this->assign('title', 'Plan');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('All Plans');
?>
<?php echo  $this->Flash->render(); ?>
<div class="panel panel-default" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">

    <div class="panel-heading">
        <h2>All Plans</h2>
    </div>
    <div class="panel-body">  
        <div class="table-responsive table-bordered mt-md">
            <table class="table-list table table-bordered table-striped m-n">
                <thead>
                    <tr>
                        <th>Plan Name</th>
                        <th>Plan Duration</th>
                        <th>Plan Price</th>
                        <th>Description</th>                                        
                        <!--<th>Action</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$Plans->isEmpty()) {
                        $PlanDuration = Configure::read('PlanDuration');

                        foreach ($Plans as $value) {
                            ?>				 			           
                            <tr>
                                <td><?php echo $value['plan_name']; ?></td>

                                <td><?php echo $value['duration'].' Months'; ?></td>

                                <td><?php echo $value['plan_price']; ?></td>   

                                <td><?php echo $value['description']; ?></td> 
                                <!--
                                <td> 
                                    <a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'addEdit', $value->id]); ?>" title="Edit" class="btn btn-inverse-alt"><i class="ti ti-pencil"></i></a>


                                </td>
                                -->
       
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="10" style="text-align: center;">No Plans found.</td></tr>
                    <?php } ?>


                </tbody>
            </table>



        </div>
      <?php echo $this->Element('Admin/pagination'); ?>
    </div>




</div> 

