<?php
$this->assign('title', 'Dashboard');

use Cake\I18n\Number;
use Cake\Core\Configure;

echo $this->Html->css(array('front/ion.rangeSlider.css',
    'front/ion.rangeSlider.skinFlat.css'));
?>
<?php echo $this->Flash->render(); ?>

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

<?php echo $this->element('banner'); ?>

<div id="profile-info" class="profile-info-section">
    <div class="container">
        <?php echo $this->element('profile_pic_section'); ?>
        <div class="row">
        
            <section class="col-lg-9 col-sm-12 col-xs-12">
                <section class="grid-wrap">
                    <ul class="grid swipe-rotate" id="grid">
                        <!--************************search result*************************--> 
                    </ul><!-- /grid -->
                    <div class="form-group" style="text-align: center;">
                        <img id="searchLoader" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="position: relative;width: 56px; display: none  ">
 
                    </div>
                    <input type="hidden" id="nextPage" value="1" /> 
                     <input type="hidden" id="totalPage" value="2" /> 
                    
                </section>
            </section>
            <section class="col-lg-3 col-sm-12 col-xs-12">  
                <div class="lookingForBlock">
                    <form>
                        <div class="detail-info">
                            <div class="form-group custom-check-radio">
                                <label for="exampleInputEmail1">I'm looking for</label>
                                <div class="gender-filed">
									<?php $interested_in_gender = explode(',', $user['user_detail']['interested_in_gender']); ?>
                                    <label class="control control--checkbox">
                                        Men
                                        <input type="checkbox" name="lookingFor[]" value="1" class="gender"  <?php if(in_array(1, $interested_in_gender)) echo 'checked'; ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">
                                        Women
                                        <input type="checkbox" value="2"  class="gender"  name="lookingFor[]"  <?php if(in_array(2, $interested_in_gender)) echo 'checked'; ?>/>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Between">Between</label> 
                                <input type="text" id="range" value="" name="range" /> 
                                <input type="hidden" id="fromValue" value="18">
                                <input type="hidden" id="toValue" value="100">
                            </div>


                            <div class="form-group custom-check-radio">
                                <div class="gender-filed between">
                                    <label class="control control--checkbox">
                                        Verified users
                                        <input type="checkbox" name="verified" value="1" id="verifiedUser"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox last">
                                        Discover
                                        <input type="checkbox" name="discover" value="1" id="discover"/>
                                        <div class="control__indicator"></div>
                                    </label>

                                </div>
                            </div>                   
                            <div class="form-group">
                                <input type="email" placeholder="Keyword" id="keyword" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div></div>
<input type="hidden" name="not_found" id="not_found" value="1">
<?php
echo $this->Common->loadJsClass('Search');
?>