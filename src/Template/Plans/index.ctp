<?php
$this->assign('title', 'Plans');
use Cake\Core\Configure;
//pr($Plans);
?>

<div id="profile-info" class="plans-section">
    <div class="container container-small animated3 fadeInTop">
        <div class="row">
            <?php echo $this->Flash->render(); ?>
            <div class="col-lg-3 col-sm-3">
                <div class="planbox free">                	
                    <div class="planboxInner">
                        <div class="heading">Free Users</div>
                        <div class="planboxContent">
                            Free
                        </div>
                        <div class="planboxDesc">
                            <p>absolutely
                                <span>Free</span></p>
                            <div class="planboxBtn">
                                <a href="<?php echo isset($Plans[0]['id']) ? $Plans[0]['id'] : '' ?>" class="btn btn-default benifits" data-toggle="modal" data-target="#planPopupFree">Benefits</a>


                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="planbox months2">
                    <div class="planboxInner">
                        <div class="heading">2 Months</div>
                        <div class="planboxContent">
                            €<?php echo $Plans[1]['weekly_plan_price']; ?><span>per week</span>
                        </div>
                        <div class="planboxDesc">
                            <p>for
                                <span>€<?php echo isset($Plans[1]['plan_price']) ? $Plans[1]['plan_price'] : '' ?> euro</span></p>
                            <div class="planboxBtn">
                                <a href="<?php echo isset($Plans[1]['id']) ? $Plans[1]['id'] : '' ?>" class="btn btn-default benifits" data-toggle="modal" data-target="#planPopup2to4">Benefits</a>
                                <?php
                                $message = 'Checkout';
                                $disable = '';
                                if (!empty($addArray)) {
                                    if ($Plans[1]['id'] == $addArray['id']) {
                                        $disable = 'disabled="disabled"';
                                        $message = 'Activated';
                                    }
                                }
                                ?>

                                <a  <?php echo $disable; ?> href="<?php if($message != 'Activated'){ $id = isset($Plans[1]['id']) ? ($Plans[1]['id']) : '';
                                echo SITE_FULL_URL . 'plans' . DS . 'purchase' . DS . $this->Common->encrypt($id); }else{ echo "javascript:;"; } ?>" class="btn btn-default checkout selectPlanClass"><?php echo $message ?></a>

                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="planbox months4">
                    <div class="planboxInner">
                        <div class="heading">4 Months</div>
                        <div class="planboxContent">
                            €<?php echo $Plans[2]['weekly_plan_price']; ?><span>per week</span>
                        </div>
                        <div class="planboxDesc">
                            <p>for
                                <span>€<?php echo isset($Plans[2]['plan_price']) ? $Plans[2]['plan_price'] : '' ?> euro</span></p>
                            <div class="planboxBtn">
                                <a href="#<?php echo isset($Plans[2]['id']) ? $Plans[2]['id'] : '' ?>" class="btn btn-default benifits" data-toggle="modal" data-target="#planPopup2to4">Benefits</a>
                                <?php
                                $message = 'Checkout';
                                $disable = '';
                                if (!empty($addArray)) {
                                    if ($Plans[2]['id'] == $addArray['id']) {
                                        $disable = 'disabled="disabled"';
                                        $message = 'Activated';
                                    }
                                }
                                ?>

                                <a <?php echo $disable; ?> href="<?php if($message != 'Activated'){ $id = isset($Plans[2]['id']) ? ($Plans[2]['id']) : '';
                                                            echo SITE_FULL_URL . 'plans' . DS . 'purchase' . DS . $this->Common->encrypt($id); }else{ echo "javascript:;"; } ?>" class="btn btn-default checkout selectPlanClass"><?php echo $message ?></a>

                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="planbox yearly">
                    <div class="bestDeal">Best Deal</div>
                    <!--<div class="offerBanner"></div>-->
                    <div class="planboxInner">
                        <div class="heading">12 Months&nbsp;&nbsp;</div>
                        <div class="planboxContent">
                            €<?php echo $Plans[3]['weekly_plan_price']; ?><span>per week</span>
                        </div>
                        <div class="planboxDesc">
                            <p>for
                                <span>€<?php echo isset($Plans[3]['plan_price']) ? $Plans[3]['plan_price'] : '' ?> euro</span></p>
                            <div class="planboxBtn">
                                <a href="#<?php echo isset($Plans[3]['id']) ? $Plans[3]['id'] : '' ?>" class="btn btn-default benifits" data-toggle="modal" data-target="#planPopup12">Benefits</a>

                                <?php
                                $message = 'Checkout';
                                $disable = '';
                                if (!empty($addArray)) {
                                    if ($Plans[3]['id'] == $addArray['id']) {
                                        $disable = 'disabled="disabled"';
                                        $message = 'Activated';
                                    }
                                }

                                ?><a <?php echo $disable; ?> href="<?php if($message != 'Activated'){ $id = isset($Plans[3]['id']) ? ($Plans[3]['id']) : '';
                                    echo SITE_FULL_URL . 'plans' . DS . 'purchase' . DS . str_replace("+", ":", $this->Common->encrypt($id));
				}else{ echo "javascript:;"; } ?>" class="btn btn-default checkout selectPlanClass"><?php echo $message ?>  </a>  

                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div> 
<div class="modal fade planPopup animated2 fadeInDown2" id="planPopupFree" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <figure>
<?php echo $this->Html->image('plans/Free-user-benefits-infographic.jpg'); ?>
                </figure>
            </div>
        </div>
    </div>
</div>
<div class="modal fade planPopup animated2 fadeInDown2" id="planPopup2to4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <figure>
<?php echo $this->Html->image('plans/2and4month-benefits-infographic.jpg'); ?>
                </figure>
            </div>
        </div>
    </div>
</div>

<div class="modal fade planPopup animated2 fadeInDown2" id="planPopup12" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <figure>
<?php echo $this->Html->image('plans/12month-benefits-infographic.jpg'); ?>
                </figure>
            </div>
        </div>
    </div>
</div>
<?php $this->Html->scriptStart(['block' => true]); ?>

$().ready(function () {
$('.selectPlanClass').click(function () {
$('.planboxDesc').css('background', '#fff');
var selectedPlan = $(this).data('planid');
$(this).closest("div.planboxDesc").css('background', '#eee');
$('#PlanPlanId').val(selectedPlan);
});
});

<?php $this->Html->scriptEnd(); ?>