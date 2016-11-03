<style>
* { box-sizing: border-box; }
/* force scrollbar */
html { overflow-y: scroll; }
body { font-family: sans-serif; }
/* ---- grid ---- */
/* clear fix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}
/* ---- .grid-item ---- */
.grid-sizer,
.grid-item {
  width: 50%;
}
.grid-item {
  float: left;
  border: 5px solid #FFFFFF;
}
.grid-item img {
  display: block;
  width: 100%;
}
.album-popup{
	position: absolute;
    left: 21%;
    width: 800px;
    border: 1px solid red;
    height: auto;
    padding: 10px;
	z-index: 999;
	background:#FFFFFF;
	 
}
.js .grid li {
    display: block;
    float: left;
}
</style>
<script src="<?php echo SITE_FULL_URL; ?>js/masonry.pkgd.js"></script>
<script src="<?php echo SITE_FULL_URL; ?>js/imagesloaded.pkgd.js"></script>
<?php
$this->assign('title', 'Dashboard');

use Cake\I18n\Number;
use Cake\Core\Configure;

echo $this->Html->css(array('front/ion.rangeSlider.css',
    'front/ion.rangeSlider.skinFlat.css'));
?>
<?php echo $this->Flash->render(); ?>

<div id="popup" style="display: none;" class="album-popup">
	<div id="load_content"></div>	
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
            
		</div>
    </div></div>
<?php
echo $this->Common->loadJsClass('Photographer');
?>
