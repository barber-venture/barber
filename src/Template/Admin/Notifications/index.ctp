<?php
$this->assign('title', 'Notification');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Notification'); 
?>
<?php echo  $this->Flash->render(); ?>
<div class="panel panel-default" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">

    <div class="panel-heading">
        <h2>All Notification</h2>
      
    </div>
    <div class="panel-body">  
        <div class="table-responsive table-bordered mt-md">
            <table class="table-list table table-bordered table-striped m-n">
                <thead>
                    <tr>
                        <th>Sender Name</th>
                        <th>Sender Email</th>
                        <th>Subject</th>                                                        
                        <th>Message</th>  
                        <th>Action</th>  
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if (isset($notifications)) {

                        foreach ($notifications as $value) {                           
                            ?>				 			           
                            <tr>
                                <td><?php echo isset($value->sender_name) ? $value->sender_name : ''; ?></td>
                                <td><?php echo isset($value->sender_email) ? $value->sender_email : ''; ?></td>
                                <td><?php echo isset($value->subject) ? $value->subject : ''; ?></td>
                                <td><?php echo isset($value->message) ? $value->message : ''; ?></td>
                                
                               
                                <td> 
                                    <a href="<?php echo $this->Url->build(['controller' => 'notifications', 'action' => 'edit', $value->id]); ?>" title="Edit" class="btn btn-inverse-alt"><i class="ti ti-pencil"></i></a>
 
                                </td>	

                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="10" style="text-align: center;">No Notification found.</td></tr>
                    <?php } ?>


                </tbody>
            </table>



        </div>
     <?php echo $this->Element('Admin/pagination'); ?>
    </div>




</div> 

