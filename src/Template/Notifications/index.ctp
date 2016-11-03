<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?php echo  __('Actions') ?></li>
        <li><?php echo  $this->Html->link(__('New Notification'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="notifications index large-9 medium-8 columns content">
    <h3><?php echo  __('Notifications') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo  $this->Paginator->sort('id') ?></th>
                <th><?php echo  $this->Paginator->sort('sender_name') ?></th>
                <th><?php echo  $this->Paginator->sort('sender_email') ?></th>
                <th><?php echo  $this->Paginator->sort('title') ?></th>
                <th><?php echo  $this->Paginator->sort('subject') ?></th>
                <th><?php echo  $this->Paginator->sort('created') ?></th>
                <th><?php echo  $this->Paginator->sort('updated') ?></th>
                <th class="actions"><?php echo  __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notifications as $notification): ?>
            <tr>
                <td><?php echo  $this->Number->format($notification->id) ?></td>
                <td><?php echo  h($notification->sender_name) ?></td>
                <td><?php echo  h($notification->sender_email) ?></td>
                <td><?php echo  h($notification->title) ?></td>
                <td><?php echo  h($notification->subject) ?></td>
                <td><?php echo  h($notification->created) ?></td>
                <td><?php echo  h($notification->updated) ?></td>
                <td class="actions">
                    <?php echo  $this->Html->link(__('View'), ['action' => 'view', $notification->id]) ?>
                    <?php echo  $this->Html->link(__('Edit'), ['action' => 'edit', $notification->id]) ?>
                    <?php echo  $this->Form->postLink(__('Delete'), ['action' => 'delete', $notification->id], ['confirm' => __('Are you sure you want to delete # {0}?', $notification->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?php echo  $this->Paginator->prev('< ' . __('previous')) ?>
            <?php echo  $this->Paginator->numbers() ?>
            <?php echo  $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?php echo  $this->Paginator->counter() ?></p>
    </div>
</div>
