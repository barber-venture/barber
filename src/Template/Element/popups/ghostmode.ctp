

<!-- Profile verification --> 
<!-- Modal -->
<div class="modal fade edit-profile-design popup animated2 fadeInDown2" id="modalGhost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
        <div class="profile-verification-section">
          <div class="rows">
            <h2>Activate ghost mode</h2>
            <div class="verfication-block">
              <p>Are you interested in ghost mode? No problem! As a VIP, you can take advantage of it and many other benefits.</p>
              <div class="btn-block-section">
                <ul>
                  <li style="margin:0 0 0 25%">
                    <!--<a href="<?php echo $this->Common->getLoginFacebookUrl(1); ?>" class="btn btn-lg btn-facebook">Confirm Via Facebook</a>-->
                    <a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'index']); ?>" style="width:auto;" class="btn btn-danger small hvr-rectangle-in"><span class="img setting"></span>Become a VIP</a>
                  </li>
                  
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php  $this->Html->scriptStart(['block' => true]); ?>
    $(function() {
        $('#twitter_button').click(function(){
            window.location.href = SITE_URL + '/users/twitterVerification'
        })
    });
     
<?php $this->Html->scriptEnd(); ?>
