<?php
$this->assign('title', 'Purchase Plans');

use Cake\Core\Configure;

echo $this->Html->css(array(
    'front/paypal.css'));

?>
<script>
    var amount ='<?php echo $Plan->plan_price; ?>';
    var duration ='<?php echo $Plan->duration; ?>';
    var planid ='<?php echo str_replace("+", ":", $this->Common->encrypt($Plan->id)); ?>';
</script>
<!--discover new activities Start--> 
<div id="profile-info" class="plans-section payment-steps">
    <div class="container animated3 fadeInTop">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step step-one step-active">
                            <a href="#step-1" type="button" class="btn btn-circle btn-default">1</a>
                            <p>Payment Method</p>
                        </div>
                        <div class="stepwizard-step step-two step-active">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
                            <p>Pay Now</p>
                        </div>
                        <div class="stepwizard-step step-three">
                            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                            <p>Confirmation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-3 w215">
                <div class="leftplansDetails">
                    <div class="selectedPlan">
                        <ul>
                            <li class="plan-active">Selected Plan</li>
                            <li><span>
                                    
                                 <?php echo $this->Html->image('star.png'); ?></span><?php echo $Plan->duration; ?> Month Plan</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-sm-9 w72-percent">
                <div class="paymentProcess">
                    <ul  class="nav nav-pills">
                        <li class="active">
                            <a class="active" href="#Ghanaian" data-toggle="tab">Mobile Money</a>
                        </li>
                        <li ><a href="#PayPal" data-toggle="tab">PayPal</a>
                        </li>
                        <li><a href="#Visa" data-toggle="tab">Visa / MasterCard</a>
                        </li>
                        <li><a href="#iDeal" data-toggle="tab">iDeal Payment</a>
                        </li>
                    </ul>
                    <div class="tab-content clearfix">
                        <div class="tab-pane paypal-payment active" id="Ghanaian">
                            <div class="row">

                                <div class="col-lg-6 col-sm-12">		
                                    <div class="payGateway">Pay securely now with Slydepay</div>
                                    <div class="preferedPlan">Package <?php echo $Plan->duration; ?> months Premier *</div>
                                    <div class="paymentTable">
                                        <table class="table table-hover">
<!--                                            <tr>
                                                <td>Total </td>
                                                <td>(€<?php echo $Plan->plan_price; ?> euro)</td>
                                            </tr>-->
                                            <tr>
                                                <td><strong>Amount </strong></td>
                                                <td><strong>(€<?php echo $Plan->plan_price; ?> euro)</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">	
                                    <div class="paymentLogo">
                                        
                                         <?php // echo $this->Html->image('paypal.png'); ?>
                                    </div>

                                    <a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'slydepay', $Plan->id]); ?>" class="btn btn-danger btn-continue">Continue</a>

                                </div>

                            </div>
                        </div>

                        <!----------------------------- paypal express checkout payment --------------------------- -->
                        <div class="tab-pane paypal-payment " id="PayPal">
                            <div class="row">

                                <div class="col-lg-6 col-sm-12">		
                                    <div class="payGateway">Pay securely now with PayPal</div>
                                    <div class="preferedPlan">Package <?php echo $Plan->duration; ?> months Premier *</div>
                                    <div class="paymentTable">
                                        <table class="table table-hover">
                                            <tr>
                                                <td>Total </td>
                                                <td>(€<?php echo $Plan->plan_price; ?> euro)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td><strong>(€<?php echo $Plan->plan_price; ?> euro)</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">	
                                    <div class="paymentLogo">
                                       <?php echo $this->Html->image('paypal.png'); ?>
                                    </div>

                                    <a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'expresscheckout', $Plan->id]); ?>" class="btn btn-danger btn-continue">Continue</a>

                                </div>

                            </div>
                        </div>
                        <!----------------------------- paypal credit card payment --------------------------- -->
                        <div class="tab-pane visa-payment" id="Visa">
                            <div class="payGatewayVisa"><label>Credit card (VIsa, Mastercard)</label></div>
                            <form method="post" class="visa-form" id="paymentForm">
                                <input type="hidden" name="card_type" id="card_type" value=""/>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div id="orderInfo" style="display: none;"></div>
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label>Card number <span>The 16 digits on front of your card</span></label>
                                            <input type="text" id="card_number" name="card_number" class="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">	
                                            <div class="pull-left">
                                                <label>Expiration date </label>                                                
                                                <input type="text" placeholder="MM" maxlength="5"  class="  w162"  id="expiry_month" name="expiry_month">
                                            </div>
                                            <div class="pull-left marL10">
                                                <label>&nbsp;</label>
                                                <input type="text" placeholder="YYYY" class=" w162" maxlength="5" id="expiry_year" name="expiry_year">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="pull-left marLM15">	
                                                <label>CVV/CVC2 </label>
                                                <input type="password" maxlength="3" id="cvv" name="cvv" class="w162" >

                                                <span class="cvv-number">The last 3 digit displayed on the backof your card</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group marB10">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label>Full name on card</label>                                            
                                            <input type="text" class="form-control" id="name_on_card" name="name_on_card">
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group marB0 text-right">                                   
                                    <input type="button" name="card_submit" id="cardSubmitBtn" value="Proceed" class="btn btn-danger btn-continue  " disabled="true" >
                                </div>
                            </form>
                        </div>
                         <!----------------------------- iDEAL payment Gateway Integration --------------------------- -->
                        <div class="tab-pane" id="iDeal">
                            <!-- CODE_START --> 
                                <script type="text/javascript"> 
                                <!-- Begin 
                                 
                                var Amount = "<?php echo $Plan->plan_price; ?>"; 
                                var PSPID = "<?php echo ACC_PSPID; ?>";  
                                var AM; 
                                 
                                if (isNaN(Amount)) 
                                { 
                                    alert("Amount not a number: " + Amount + " !"); 
                                    AM = "" 
                                } 
                                else 
                                { 
                                    AM = Math.round(parseFloat(Amount)*100); 
                                } 
                                 
                                var orderID = "1"; 
                                mydate = new Date(); 
                                tv = mydate.getYear() % 10; 
                                orderID = orderID + tv; 
                                tv = (mydate.getMonth() * 31) + mydate.getDate(); 
                                orderID = orderID + ((tv < 10) ? '0' : '') + ((tv < 100) ? '0' : '') + tv; 
                                tv = (mydate.getHours() * 3600) + (mydate.getMinutes() * 60) + mydate.getSeconds(); 
                                orderID = orderID + ((tv < 10) ? '0' : '') + ((tv < 100) ? '0' : '') + ((tv < 1000) ? '0' : '') + ((tv < 10000) ? '0' : '') + tv; 
                                tvplus = Math.round(Math.random() * 9); 
                                // End --> 
                                </script> 
                                <form method="post" action="https://internetkassa.abnamro.nl/ncol/<?php echo IDEAL_MODE; ?>/orderstandard.asp" id="form1" name="form1"> 
                                <script type="text/javascript"> 
                                document.write("<input type=\"hidden\" NAME=\"PSPID\" value=\"" + PSPID + "\" />"); 
                                document.write("<input type=\"hidden\" NAME=\"orderID\" value=\"" + (orderID + ((tvplus + 1) % 10)) + "\" />"); 
                                document.write("<input type=\"hidden\" NAME=\"amount\" value=\"" + AM + "\" />"); 
                                </script> 
                                                                  
                                <input type="hidden" name="currency" value="EUR" /> 
                                <input type="hidden" name="language" value="en_US" /> 
                                <input type="hidden" name="PM" value="iDEAL" /> 
                                 
                                <input type="hidden" name="accepturl" value="<?php echo SITE_FULL_URL; ?>/plans/iDEALSuccess/<?php echo $Plan->id."/".$Plan->plan_price; ?>" />
                                <input type="hidden" name="declineurl" value="<?php echo SITE_FULL_URL; ?>/plans" />
                                <input type="hidden" name="exceptionurl" value="<?php echo SITE_FULL_URL; ?>/plans" />
                                <input type="hidden" name="cancelurl" value="<?php echo SITE_FULL_URL; ?>/plans" />
                                <input type="hidden" name="homeurl" value="<?php echo SITE_FULL_URL; ?>/plans">
                                 
                                <button class="iDEALeasy" type="submit" name="submit1" value="submit"> 
                                Betalen met<br /> 
                                <img src="https://internetkassa.abnamro.nl/images/iDEAL_easy.gif" alt="iDEAL"  /> 
                                </button> 
                                </form> 
                                <!-- CODE_END --> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->script(array(
    'jquery.creditCardValidator',
    'paypal',
        ), ['block' => true]);
?>