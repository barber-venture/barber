<?php
if (isset($plan['id'])) {
    $this->assign('title', 'Edit Plan');
} else {
    $this->assign('title', 'Add Plan');
}

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Plans', ['controller' => 'Plans', 'action' => 'index']);
if (isset($plan['id'])) {
    $this->Html->addCrumb('Edit Plan');
} else {
    $this->Html->addCrumb('Add Plan');
}
?>

<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">
           
        </div>

        <div class="tab-pane">

            <div class="panel">
                <?php
                echo $this->Form->create($plan, ['type' => 'file', 'id' => 'add_page', 'class' => 'form-horizontal plan-form', 'novalidate']);
                ?>  
                <div class="panel-heading">
                    <h2><?php
                        if (isset($plan['id'])) {
                            echo 'Edit Plan';
                        } else {
                            echo 'Add Plan';
                        }
                        ?></h2>
                </div>
                <div class="panel-body">
                     <?php echo  $this->Flash->render(); ?>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Plan Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('plan_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Plan Name']); ?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Duration<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('duration', [
                                        'label' => false,
                                        'options' => Configure::read('PlanDuration'),
                                        'class' => 'form-control',
                                        'empty' => 'Please select duration'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Plan Price<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('plan_price', [
                                        'label' => false,
                                        'class' => 'form-control',
                                        'placeholder' => 'Plan Price',
                                        'required' => false,
                                        'type' => 'text',
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">&nbsp;</div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Description<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    echo $this->Form->input('description', [
                                        'label' => false,
                                        'type' => 'textarea',
                                        'class' => 'form-control',
                                        'placeholder' => 'Plan Description',
                                        'maxlength' => '255',
                                        'required' => false,
                                        'rows' => '2'
                                    ]);
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">&nbsp;</div>


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

                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Plans', 'action' => 'index']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('Plans'); ?>