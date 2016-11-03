<?php
if ($this->request->session()->read('Auth.User.id') != "") {
    if ($this->request->session()->read('Auth.User.is_verify_mobile') != 1 && $this->request->session()->read('Auth.User.role_id') == 2) {
        echo $this->element('popups/mobile_popup');
		echo $this->Common->loadJsClass('optClass');
				
    } 
}
?>
<footer id="pageFooter" class="footer">
    <div class="footerNav">
        <ul>
            <li><?php echo $this->Html->link('About us', '/pages/about-us'); ?></li>
            <li><?php echo $this->Html->link('Company', '/pages/company'); ?></li>
            <li><?php echo $this->Html->link('FAQ', '/pages/faq'); ?></li>
            <li><?php echo $this->Html->link('Blog', '/blog'); ?></li>
            <li><?php echo $this->Html->link('Contact us', '/pages/contact_us'); ?></li>
            <li><?php echo $this->Html->link('Data security regulations', '/pages/data-security-regulations'); ?></li>
            <li><?php echo $this->Html->link('Legal notice', '/pages/legal-notice'); ?></li>
            <li><?php echo $this->Html->link('Privacy policy', '/pages/privacy-policy'); ?></li>
            <li><?php echo $this->Html->link('Terms of serivce', '/pages/terms-of-service'); ?></li>
        </ul>
    </div>
    <div class="socialLinks">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul>
                        <li><a title="Facebook" href="https://www.facebook.com/sweeedycom-1315847518456139/" target="_blank"><i class="socialicons fb"></i></a></li>
                        <li><a title="Twitter" href="https://twitter.com/Sweeedydotcom" target="_blank"><i class="socialicons tw"></i></a></li>
                        <li><a title="Youtube" href="https://www.youtube.com/channel/UCdTGjvPCCK3UY5UFnyb0IKA" target="_blank"><i class="socialicons utube"></i></a></li>
                        <li><a title="Instagram" href="https://www.instagram.com/sweeedydotcom/" target="_blank"><i class="socialicons instagram"></i></a></li>
                    </ul>
                    <p class="copyright">&copy; Sweeedy <?php echo date('Y'); ?>. All rights reserved</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->