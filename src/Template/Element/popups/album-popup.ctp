<div id="popup" class="album-popup" style="display: none;">
	<div class="album-popup-body">
        <a class="close-popup" id="closedetailview"><span>X</span></a>
        <div id="load_content" class="profile-modal-content"></div>	
        <div class="refertoFriend-popup" id="refertoFriend" style="display:none;">
            <a class="close-popup" id="close_suggest"><span>X</span></a>	
            <div class="tab-title">
                <h4>Suggest A Friend</h4>
            </div>
            <div class="verticle-tabs-content">
				<form id="suggest_friend_form">
				  <div class="form-group">
				  <input type="text" class="form-control" name="friend_name" id="friend_name" placeholder="Friend's Name">
				  </div>
				  <div class="form-group">
				  <input type="text" class="form-control" name="friend_email" id="friend_email" placeholder="Email Address">
				  <input type="hidden" name="suggested_user_id" id="suggested_user_id" >
				  </div>
				  <div class="form-group text-right">
				  <input type="submit" value="Submit" class="btn btn-danger small hvr-rectangle-in">
				  </div>
				</form>
				<div class="alert alert-dismissable alert-success success" style="display:none">
					<i class="ti ti-close"></i>  SuccessFully Referred.
				</div>
				<div class="alert alert-dismissable alert-danger danger" style="display:none">
					<i class="ti ti-close"></i>  Could Not Refer.
				</div>
			</div>
        </div>
    </div>
</div>