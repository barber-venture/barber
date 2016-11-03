<?php
$this->assign('title', 'All Tags');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Master Data');
$this->Html->addCrumb('All Tags');
?>
<?php echo  $this->Flash->render(); ?>
<div class="panel panel-default" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">

    <div class="panel-heading">
        <h2>All Tags</h2>
        <div class="pull-right">
            <a class="btn btn-success" href="<?php echo $this->Url->build(['controller' => 'MasterDatas', 'action' => 'addTag']); ?>">Add Tag</a>    

        </div>
    </div>
    <div class="panel-body">  
        <div class="table-responsive table-bordered mt-md">
            <table class="table-list table table-bordered table-striped m-n">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>                                                              
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    if (!$UserTags->isEmpty()) {
                        foreach ($UserTags as $value) {
                            ?>				 			           
                            <tr>
                                <td><?php echo $value['name']; ?></td>

                                <td> 
                                    <?php echo $this->Common->getStatus($value['status']); ?>
                                </td> 

                                <td> 
                                    <a href="<?php echo $this->Url->build(['controller' => 'MasterDatas', 'action' => 'addTag', $value->id]); ?>" title="Edit" class="btn btn-inverse-alt"><i class="ti ti-pencil"></i></a>

                                    <a href="<?php echo $this->Url->build(['controller' => 'MasterDatas', 'action' => 'delectTag', $value->id]); ?>"  onclick="return confirm('User may be associated with this tag still you want to delete it?')" title="Delete" class="btn btn-danger-alt"><i class="ti ti-close"></i></a>                                

                                </td>	

                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="10" style="text-align: center;">No Tags found.</td></tr>
                    <?php } ?>


                </tbody>
            </table>



        </div>
        <?php echo $this->Element('Admin/pagination'); ?>
    </div>




</div> 

