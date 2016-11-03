<div class="modal-header">
    <h2> Replay:- <?php echo h($support->email) ?></h2>
</div>
<?php
echo $this->Form->create($SupportReplays, ['type' => 'file', 'id' => 'SupportReplays', 'class' => 'form-horizontal SupportReplays', 'novalidate']);
$this->Form->templates(['inputContainer' => '{{content}}']);
?>  
<?php
            echo $this->Form->input('support_id', [
                'type' => 'hidden',
                'value' => $support->id,
                 
            ]);
            ?>
<div class="modal-body"> 
    <div class="form-group">  
        <label for="form-name" class="col-sm-2 control-label">Subject<span class="mandatory"> *</span></label>
        <div class="col-sm-8 tabular-border">
            <?php echo $this->Form->input('subject', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Subject']); ?>
        </div> 
    </div>

    <div class="form-group">    
        <label for="form-name" class="col-sm-2 control-label">Message<span class="mandatory"> *</span></label>
        <div class="col-sm-8 tabular-border">
            <?php
            echo $this->Form->input('message', [
                'type' => 'textarea',
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'Message'
            ]);
            ?>
        </div>    
    </div>



    <div class="form-group"> 
        <label for="form-name" class="col-sm-2 control-label">Attachment</label>
        <div class="col-sm-8 tabular-border">
            <?php
            echo $this->Form->input('attachment', [
                'type' => 'file',
                'label' => false,
                'required' => false,
                'class' => ''
            ]);
            ?>
        </div> 
    </div>
</div>


<div class="modal-footer">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">   

            <?php echo $this->Form->button('Send', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?> 