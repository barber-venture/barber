<?php
$this->assign('title', $type.' Page');
use Cake\Core\Configure;
$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Page', ['controller' => 'Pages', 'action' => 'index']);
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
                echo $this->Form->create($page, ['type' => 'file', 'id' => 'add_page', 'class' => 'form-horizontal  edit-profile-form', 'novalidate']);
               // echo $this->Form->hidden('old_image', ['value' => $user['image']]);
                ?>  
                <div class="panel-heading">
                    <h2><?php echo $type; ?> Page</h2>
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Page Title<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('page_title', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Page Title']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Page Headline<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('page_headline', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Page Headline']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Keyword<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->input('keyword', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Keyword']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label">Description<span class="mandatory"> *</span></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Form->textarea('description', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Description', 'rows' => '2']); ?>
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
                    
                    <div class="row" style="min-height:250px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="form-name" class="col-sm-2 control-label">Content</label>
                                    <div class="col-sm-8 tabular-border">
                                        <?php echo $this->Form->input('content', ['id' => 'summernote', 'label' => false, 'type' => 'textarea',  'class' => 'form-control summernote', 'placeholder' => 'Content']); ?>                                       
                                    </div>
                            </div>
                         </div>                                       
                    </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">                                    
                            <?php echo  $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            <a class="btn-default btn" href="<?php echo  $this->Url->build(['controller' => 'Pages', 'action' => 'index']); ?>">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo  $this->Form->end(); ?>
            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div>
<?php echo  $this->Common->loadJsClass('Page'); ?>