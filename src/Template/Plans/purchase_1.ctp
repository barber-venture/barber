<?php  
$this->assign('title', 'Purchase Plans');
use Cake\Core\Configure;
?>
<!--discover new activities Start--> 
<section class="discoverSection">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="loginPage">
                    <?php echo $this->Flash->render(); ?>
                    <div class="loginFrom">
                        <h3>Pay to </h3>  
                    </div>

                    <div class="inviteForm">
                        <?php
                        echo $this->Form->create($UserCardDetails, ['id' => 'purchaseForm']);
                        ?>

                        <?php echo $this->Form->input('name_on_card', array('label' => false, 'div' => false, 'placeholder' => 'Name on card', 'autofocus', 'required' => false)); ?> 
                        <div class="clearfix"></div>

                        <select class="selectcontroll valid" name="card_type">
                            <option value="VISA">Visa</option>
                            <option value="MASTERCARD">MasterCard</option>
                            <option value="DISCOVER">Discover Card</option>
                            <option value="AMEX">American Express</option>
                            <option value="SWITCH">Maestro</option>
                            <option value="SOLO">Solo</option>
                        </select>
                        <div class="clearfix"></div>

                        <?php echo $this->Form->input('card_number', array('label' => false, 'div' => false, 'placeholder' => 'Card Number', 'autofocus', 'required' => false)); ?> 
                        <div class="clearfix"></div>

                        <select id="exp_month" name="exp_month" class="selectcontroll dob valid">
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                        <div class="clearfix"></div>

                        <select id="exp-year" class="selectcontroll dob valid" name="exp_year">
                            <?php
                            $currentYear = date('Y');
                            $upcommin_year = $currentYear + 35;
                            for ($i = $currentYear; $i < $upcommin_year; $i++) {
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="clearfix"></div>

                        <?php echo $this->Form->input('cvv', array('label' => false, 'div' => false, 'placeholder' => 'CVV Number', 'autofocus', 'required' => false)); ?>
                        <div class="clearfix"></div>

                        <?php echo $this->Form->hidden('Plan.data', array('label' => false, 'div' => false, 'default' => $data)); ?>

                        <button type='submit' class="effect">Pay Now</button>

                        <?php echo $this->Form->end(); ?>
                    </div>    	                        

                </div>

            </div> 


        </div>
    </div>

</section>