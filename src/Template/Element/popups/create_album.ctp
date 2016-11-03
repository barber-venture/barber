<?php

use Cake\Core\Configure;
?>

<div class="profile-verification-section">
    <div class="rows">
        <h2>Create Album</h2>
        <div class="profile-picture-block" id="upload_image_parent">
            <?php $up_txt = (($this->request->session()->read('Auth.User.role_id') == 3) || ($this->request->session()->read('Auth.User.user_type') == 1)) ? '1 albums with 5 photos' : '4  albums with 5 photos each'; ?>
            <p>Would you like to add an additional Album? No problem! You can currently create <?php echo $up_txt; ?>.</p>
            <div class="upload-img">
                
                  <!----------------------------------------------------->
                       
                        <?= $this->Form->create('Album', ['class' => 'login-form', 'id' => 'createAlbumForm']) ?>   

                        <div class="form-group"> 
                            <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Album Name']); ?>

                        </div>
                     
                      <div class="form-group"> 
                            <?php echo $this->Form->input('description', ['label' => false,'type'=>'textarea', 'class' => 'form-control', 'placeholder' => 'Album Description']); ?>

                        </div>
                       
                     

                        <div class="form-group">
                            <?= $this->Form->submit('Create', ['class' => 'btn btn-lg btn-danger']); ?>
                            <img id="loadergreen" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="left: 185px;   position: fixed;    top: 125px; display: none">
                        </div>        
                       
                        <?= $this->Form->end(); ?>
                    
                
                
                <!--jshfjsnfsdfj-->
            </div>
        </div>
    </div>
</div>
<?php //echo $this->Common->loadJsClass('createAlbums');  ?>