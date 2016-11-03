<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?php echo  __('Actions') ?></li>
        <li><?php echo  $this->Html->link(__('List Supports'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="supports form large-9 medium-8 columns content">
    <?php echo  $this->Form->create($support) ?>
    <fieldset>
        <legend><?php echo  __('Add Support') ?></legend>
        <?php
            echo $this->Form->input('access_via');
            echo $this->Form->input('support_category');
            echo $this->Form->input('email');
            echo $this->Form->input('subject');
            echo $this->Form->input('message');
            echo $this->Form->input('attachment');
        ?>
    </fieldset>
    <?php echo  $this->Form->button(__('Submit')) ?>
    <?php echo  $this->Form->end() ?>
</div>
