<?php
if (isset($UserTags['id'])) {
    $this->assign('title', 'Edit Tag');
} else {
    $this->assign('title', 'Add Tag');
} 
use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Master Data');
if (isset($UserTags['id'])) {
    $this->Html->addCrumb('Edit Tag');
} else {
    $this->Html->addCrumb('Add Tag');
}
?>

<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div>

        <div class="tab-pane">

            <div class="panel">
                <?php
                echo $this->Form->create($UserTags, ['type' => 'file', 'id' => 'add_page', 'class' => 'form-horizontal tags-form', 'novalidate']);
                ?>  
                <div class="panel-heading">
                    <h2>
                        <?php
                        if (isset($UserTags['id'])) {
                            echo 'Edit Tag';
                        } else {
                            echo 'Add Tag';
                        }
                        ?>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Name']); ?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline icheck">
                                        <?php
                                        echo $this->Form->checkbox('status', array('value'=>'1'), array());
                                        //echo $this->Form->radio('status', Configure::read('SiteSetting.Status'), array());
                                        ?> 
                                    </label>
                                </div>
                            </div>
                        </div>                        
                    </div> 


                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">   

                            <?php
                            if (isset($UserTags['id'])) {
                                $btnName = 'Update';
                            } else {
                                $btnName = 'Save';
                            }
                            ?>
                            <?php echo  $this->Form->button($btnName, ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'MasterDatas', 'action' => 'tagList']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('Master'); ?>