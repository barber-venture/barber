

<!-- Profile verification --> 
<!-- Modal -->
<div class="modal fade edit-profile-design popup animated2 fadeInDown2" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
        <div class="profile-verification-section">
          <div class="rows">
            <h2>Profile Verification</h2>
            <div class="verfication-block">
              <h3>Hello <?php echo $user['name']; ?></h3>
              <p>Kindly verify your Sweeedy account in order to authenticate your identity and also enhance your chances of being found. Again, by verifying your account you are contributing to the general safety of the website and we promise never to post anything on your news feed without your consent. Thank you.</p>
              <div class="btn-block-section">
                <ul>
                  <li>
                    <!--<input type="submit" value="Confirm Via Facebook" class="btn btn-lg btn-facebook">-->
                    <a href="<?php echo $this->Common->getLoginFacebookUrl(1); ?>" class="btn btn-lg btn-facebook">Confirm Via Facebook</a>
                  </li>
                  <li>
                    <!--<input type="button" id="twitter_button" value="Confirm Via Twitter" class="btn btn-lg btn-twitter">-->
                    <?php echo $this->Html->link('Confirm Via Twitter', ['controller' => 'users', 'action' => 'twitterVerification'], ['class' => 'btn btn-lg btn-twitter']); ?>
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
