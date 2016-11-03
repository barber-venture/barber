<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?php echo  __('Actions') ?></li>
        <li><?php echo  $this->Html->link(__('List Notifications'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="notifications form large-9 medium-8 columns content">
    <?php echo  $this->Form->create($notification) ?>
    <fieldset>
        <legend><?php echo  __('Add Notification') ?></legend>
        <?php
            echo $this->Form->input('sender_name');
            echo $this->Form->input('sender_email');
            echo $this->Form->input('title');
            echo $this->Form->input('subject');
            echo $this->Form->input('message');
        ?>
    </fieldset>
    <?php echo  $this->Form->button(__('Submit')) ?>
    <?php echo  $this->Form->end() ?>
</div>
