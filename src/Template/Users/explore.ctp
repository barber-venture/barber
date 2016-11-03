<script src="<?php echo SITE_FULL_URL; ?>js/masonry.pkgd.js"></script>
<script src="<?php echo SITE_FULL_URL; ?>js/imagesloaded.pkgd.js"></script>
<?php
$this->assign('title', 'Explore');
use Cake\Core\Configure;
echo $this->Html->css(array('front/ion.rangeSlider.css',
    'front/ion.rangeSlider.skinFlat.css'));
?>

<!--<div id="popup" style="display: none;" class="album-popup">
	<div id="load_content"></div>	
</div>-->

<?php
echo $this->element('popups/album-popup'); 
echo $this->element('banner');
?>

<div id="profile-info" class="profile-info-section explore-page">
  	<div class="container">
    	<?php echo $this->element('profile_pic_section'); ?>
    	<div class="row">
		
			<section class="col-lg-9 col-sm-12 col-xs-12 selected exploreParent"><?php //echo $this->element('explore'); ?></section>
		
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
                                <input type="text" placeholder="Keyword" id="keyword" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </section>
       
		</div>
  </div>
</div>
<!-- end profile banner -->


<?php   
    echo $this->Common->loadJsClass('Explore');
?>