<?php
$this->assign('title', 'Edit Notification');
use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Notification', ['controller' => 'Notifications', 'action' => 'index']);
$this->Html->addCrumb('Edit Notifications');

?>

<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div>

        <div class="tab-pane">

            <div class="panel">
                <?php
                echo $this->Form->create($notification, ['class' => 'form-horizontal notification-form', 'novalidate']);
                $this->Form->templates(['inputContainer' => '{{content}}']);
                ?>  
                <div class="panel-heading">
                    <h2> Edit Notification</h2>
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Sender Name<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('sender_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Sender Name']); ?>
                                </div>
                            </div>
                        </div> 
                    </div> 
               
                
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Sender Email<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('sender_email', [
                                        'type'=>'text',
                                        'label' => false, 
                                        'class' => 'form-control',
                                        'placeholder' => 'Sender Email'
                                        ]); ?>
                                </div>
                            </div>
                        </div> 
                    </div> 
                 
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">title<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('title', [
                                        'type'=>'text',
                                        'label' => false, 
                                        'class' => 'form-control',
                                        'placeholder' => 'Title'
                                        ]); ?>
                                </div>
                            </div>
                        </div> 
                    </div> 
                
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Subject<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('subject', [
                                        'type'=>'text',
                                        'label' => false, 
                                        'class' => 'form-control',
                                        'placeholder' => 'Subject'
                                        ]); ?>
                                </div>
                            </div>
                        </div> 
                    </div> 
                    <div class="row"  > 
                         
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="form-mobile">message<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                     <?php
                                    echo $this->Form->input('message', [
                                        'type'=>'textarea',
                                        'label' => false, 
                                        'class' => 'form-control',
                                        'placeholder' => 'Message',
                                        'rows'=>'5',
                                        'maxlength'=>'250', 
                                        'div'=>false
                                        ]); ?>
                                                            
                                </div>
                            </div> 

                        </div> 
                          
                    </div> 
               </div>
              
                <div class="panel-footer" >
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">   
                           
                            <?php echo  $this->Form->button('Update', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>

                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Notifications', 'action' => 'index']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div><?php echo  $this->Common->loadJsClass('Notification'); ?>