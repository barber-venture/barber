<?php
$this->assign('title', 'Dashboard');

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
use Cake\I18n\Number;
?>
<?php echo  $this->Flash->render(); ?>

<div class="row">
	<div class="col-md-3">
		<div class="info-tile tile-orange" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
			<div class="tile-icon"><i class="ti ti-user"></i></div>
			<div class="tile-heading"><span>Users</span></div>
			<div class="tile-body"><span><?php echo $this->Html->link($userCount, ['controller' => 'users', 'action' => 'view_user'], ['escape' => false ]); ?></span></div>
		</div>
	</div>
	 	
</div>
 