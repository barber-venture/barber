<?php

use Cake\Core\Configure; ?><!-- Edit Profile Design --> 
<!-- Modal -->
<div class="modal fade edit-profile-design basic-detail-popup animated2 fadeInDown2" id="moblienumber" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="profile-edit-right basic-detail new-basic-info">
                    <div class="rows">
                        <h2>Mobile Number Verification</h2>
                        <section>
                            <ul>
                                <li class="icon5"> 
                                    <div class="detail-info Interested2" style="display:block;">                       
                                        <form id="sendOtpCode">
											<div class="alert alert-dismissable alert-success" style="display:none" ><span>OTP successfully sent.</span></div>
											<div class="alert alert-dismissable alert-danger" style="display:none" ><span>Could not sent OTP.</span></div>
                                            <div class="form-group">
                                                <div class="row">
													<div class="col-sm-5">
                                                    	<label for="">Country Code</label>
														<input type="text" id="countrycode" name="countrycode"  class="form-control" placeholder="">
													</div>
													<div class="col-sm-7">
                                                    	<label for="">Mobile No.</label>
														<input type="text" id="mobilenumber" name="mobilenumber"  class="form-control" placeholder="">
													</div>
                                                 </div>
                                             </div>
                                            <div class="row">
												 <div class="form-group">
													<div class="col-sm-12">
														<button title="Check your phone for SMS Code" type="submit" class="btn btn-default hvr-rectangle-in" >Send SMS Code</button>
													</div>
                                            	</div>
                                           </div>     
                                        </form>
                                        <form id="sendOtpCodeVer"> 
                                            <div class="form-group">
                                               	<div class="row">
													<div class="col-sm-5">
                                                    	<label for="">Enter SMS Code</label>
														<input type="text" id="otpcode" name="otpcode"class="form-control" placeholder="">
													</div>
												
													<div class="col-sm-7 align-right">
                                                    <label>&nbsp;</label>
														<button type="submit" class="btn btn-default hvr-rectangle-in">Verify Mobile No.</button>
													</div>
                                                </div>
                                            </div>                                            
                                        </form>
                                    </div>
                                </li>       
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>