
<?php
if (!empty($Users)) {
    ?>
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h2 class="modal-title">User Detail</h2>
    </div>
    <div class="modal-body ">
        <div class="row">
            <div class="form-group ">
                <label class="col-sm-3 control-label" for="focusedinput">User Name:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users->name; ?></label>
                <label class="col-sm-3 control-label" for="focusedinput">Email Id:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users->email; ?></label>
            </div>  
        </div>
        <div class="row">
            <div class="form-group ">
                <label class="col-sm-3 control-label" for="focusedinput">Nike Name:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users['user_detail']['nike_name']; ?></label>
                <label class="col-sm-3 control-label" for="focusedinput">Phone Number:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users['user_detail']['phone']; ?></label>
            </div>
        </div>
        <div class="row">
            <div class="form-group ">
                <label class="col-sm-3 control-label" for="focusedinput">Mobile Number:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users['user_detail']['mobile']; ?></label>
                <label class="col-sm-3 control-label" for="focusedinput">About:-</label>
                <label class="col-sm-3 control-label" for="focusedinput"><?php echo $Users['user_detail']['about_me']; ?></label>
            </div>
        </div> 
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button> 
    </div>
<?php } ?>