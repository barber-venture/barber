<?php Use Cake\Core\Configure; ?>
<!-- Main table -->
<table id="background-table" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td align="center" bgcolor="#EEEEEE">
				<!-- Container table -->
                <table class="w640" style="margin:0 10px;" border="0" cellpadding="0" cellspacing="0" width="640">
                    <tbody>
						<!-- Leave Blank Row -->
                        <tr>
                            <td class="w640" height="60" width="640"></td>
                        </tr>
						
						<!-- Header Row -->
                        <tr>
                            <td class="w640" width="640">
								<!-- Header Table -->
                                <table id="top-bar" class="w640" bgcolor="#ffffff"  border="0" cellpadding="0" cellspacing="0" width="640" style="background-position: 0 100px; height: 80px;overflow:hidden;">
                                    <tbody>
                                        <tr>
                                            <td class="w15" width="1"></td>
                                            <td class="w325" align="left" valign="middle">
                                                <h1 style="color: rgb(255, 255, 255); vertical-align:middle; margin:0px; padding:0px;">
                                                    <img  style="margin-left: 10px;" align='left' src='<?php echo SITE_FULL_URL; ?>img/sitelogo.png' alt='<?php echo Configure::read('Site.title'); ?>' />
												</h1>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                  
                            </td>
                        </tr>
                        <tr id="simple-content-row">
                            <td class="w640" bgcolor="#fafafa" width="640">
								<!-- Email Messanger Table -->
                                <table class="w640" border="0" cellpadding="0" cellspacing="0" width="640">
                                    <tbody>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580" width="580"><?php echo $content; ?></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>                  
                            </td>
                        </tr>
						<!-- Leave Blank Row -->
                        <tr>
                            <td class="w640" bgcolor="#ffffff" height="15" width="640"></td>
                        </tr>
						<!-- Leave Blank Row -->
						
						<!-- Footer Row -->
                        <tr>
                            <td class="w640" width="640">
                                <table id="footer" class="w640" bgcolor="#191919" border="0" cellpadding="0" cellspacing="0" width="640">
                                    <tbody>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580 h0" height="15" width="360"></td>
                                            <td class="w0" width="60"></td>
                                            <td class="w0" width="160"></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580" valign="top" width="360"> 
                                                <p class="footer-content-left" align="left" style="color: #BCBEC0; font-size: 12px; line-height: 15px;">
                                                    <preferences lang="en">
														&copy; Copyright <?php echo date('Y'); ?> <?php echo Configure::read('Site.title'); ?>. All Rights Reserved.
													</preferences>
                                                </p>
                                            </td>
                                            <td class="hide w0" width="60"></td>
                                            <td class="hide w0" valign="top" width="160"><p id="street-address" class="footer-content-right" align="right"></p></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580 h0" height="15" width="360"></td>
                                            <td class="w0" width="60"></td>
                                            <td class="w0" width="160"></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>                  
                            </td>
                        </tr>
                        <!-- Footer Row -->
						<tr>
                            <td class="w640" height="60" width="640"></td>
                        </tr>
                    </tbody>
                </table>          
            </td>
        </tr>
    </tbody>
</table>
<?php
//exit;
?>

