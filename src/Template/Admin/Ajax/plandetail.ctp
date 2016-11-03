
<?php
if (!empty($Plans)) {
    ?>
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h2 class="modal-title">Plan Detail</h2>
    </div>
    <div class="modal-body ">
        <div class="row">
            <div class="form-group ">
                <label class="col-sm-3 control-label" for="focusedinput">Plans Name:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Plans->plan_name; ?></label>
                <label class="col-sm-3 control-label" for="focusedinput">Plan Duration:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $PlanDuration[$Plans->duration]; ?></label>
            </div>  
        </div>
        <div class="row">
            <div class="form-group ">
                <label class="col-sm-3 control-label" for="focusedinput">Plans Price:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Plans->plan_price; ?></label>
                <label class="col-sm-3 control-label" for="focusedinput">Plan description:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo  $Plans->description; ?></label>
            </div>  
        </div>
        
          
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button> 
    </div>
<?php } ?>