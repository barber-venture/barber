<?php
$this->assign('title', $type.' Question');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Question', ['controller' => 'Faqs', 'action' => 'index']);
$this->Html->addCrumb($type, '');
?>
<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">
            <?php echo  $this->Flash->render(); ?>
        </div>

        <div class="tab-pane">

            <div class="panel">
                <?php
                echo $this->Form->create($question, ['type' => 'file', 'id' => 'add_faq', 'class' => 'form-horizontal  edit-profile-form', 'novalidate']);
                ?>  
                <div class="panel-heading">
                    <h2><?php echo $type; ?> Question</h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Question<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->textarea('question', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Question']); ?>
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
                                        //echo $this->Form->radio('status', Configure::read('SiteSetting.OptionYesNo'), array());
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
                            <?php echo  $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'AdminQuestions', 'action' => 'index']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('Faq'); ?>