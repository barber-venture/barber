<?php
if (isset($UserPlanAssociations['id'])) {
    $this->assign('title', 'Edit Subscription');
} else {
    $this->assign('title', 'Add Subscription');
}

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Subscription', ['controller' => 'Users', 'action' => 'subscription']);
if (isset($UserPlanAssociations['id'])) {
    $this->Html->addCrumb('Edit Subscription');
} else {
    $this->Html->addCrumb('Add Subscription');
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
                echo $this->Form->create($UserPlanAssociations, ['id' => 'add_page', 'class' => 'form-horizontal plan-asso-form', 'novalidate']);
                ?>  
                <div class="panel-heading">
                    <h2><?php
                if (isset($UserPlanAssociations['id'])) {
                    echo 'Edit Subscription';
                } else {
                    echo 'Add Subscription';
                }
                ?></h2>
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Select User<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('user_id', [
                                        'label' => false,
                                        'class' => 'form-control',
                                        'options' => $userList,
                                        'empty' => 'Please select user'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Select Plan <span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('plan_id', [
                                        'label' => false,
                                        'options' => $PlanList,
                                        'class' => 'form-control',
                                        'empty' => 'Please select plan'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div> 
                    </div>  
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">   
                            <?php
                            if (isset($plan['id'])) {
                                $btnName = 'Update';
                            } else {
                                $btnName = 'Save';
                            }
                            ?>
                            <?php echo  $this->Form->button($btnName, ['type' => 'submit', 'class' => 'btn btn-primary']); ?>

                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Users', 'action' => 'subscription']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
   <?php // echo $this->Html->script(array(
             
//            '../plugins/form-select2/select2.min',
          
//        )); ?> 
<?php echo  $this->Common->loadJsClass('Plans'); ?>