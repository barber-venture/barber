<div class="row">
	<div class="col-sm-6">
		<div class="dataTables_info" id="editable_info" role="status" aria-live="polite"><?php echo  $this->Paginator->counter(['format' => 'Showing {{start}} to {{end}} of {{count}} records']) ?></div>
	</div>
	<?php if ($this->Paginator->counter(['format' => '{{pages}}']) > 1) { ?>
		<div class="col-sm-6">
			<div class="dataTables_paginate paging_bootstrap" id="editable_paginate">
				<ul class="pagination pull-right">
					<?php echo  $this->Paginator->prev('Previous') ?>
					<?php echo  $this->Paginator->numbers() ?>
					<?php echo  $this->Paginator->next('Next') ?>
				</ul>
			</div>
		</div>
	<?php } ?>
</div>