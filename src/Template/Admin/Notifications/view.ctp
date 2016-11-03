<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?php echo  __('Actions') ?></li>
        <li><?php echo  $this->Html->link(__('Edit Notification'), ['action' => 'edit', $notification->id]) ?> </li>
        <li><?php echo  $this->Form->postLink(__('Delete Notification'), ['action' => 'delete', $notification->id], ['confirm' => __('Are you sure you want to delete # {0}?', $notification->id)]) ?> </li>
        <li><?php echo  $this->Html->link(__('List Notifications'), ['action' => 'index']) ?> </li>
        <li><?php echo  $this->Html->link(__('New Notification'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="notifications view large-9 medium-8 columns content">
    <h3><?php echo  h($notification->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?php echo  __('Sender Name') ?></th>
            <td><?php echo  h($notification->sender_name) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Sender Email') ?></th>
            <td><?php echo  h($notification->sender_email) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Title') ?></th>
            <td><?php echo  h($notification->title) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Subject') ?></th>
            <td><?php echo  h($notification->subject) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Id') ?></th>
            <td><?php echo  $this->Number->format($notification->id) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Created') ?></th>
            <td><?php echo  h($notification->created) ?></td>
        </tr>
        <tr>
            <th><?php echo  __('Updated') ?></th>
            <td><?php echo  h($notification->updated) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?php echo  __('Message') ?></h4>
        <?php echo  $this->Text->autoParagraph(h($notification->message)); ?>
    </div>
</div>
