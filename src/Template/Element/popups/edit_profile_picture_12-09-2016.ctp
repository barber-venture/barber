<?php
use Cake\Core\Configure;
?>
<!-- Edit profile picture --> 
<!-- Modal -->
<div class="modal fade edit-profile-design popup animated2" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
        <div class="profile-verification-section">
          <div class="rows">
            <h2>Edit profile picture</h2>
            <div class="profile-picture-block" id="upload_image_parent">
              <p>Would you like to add an additional profile photo or change your cover photo? No problem! You can currently save five profile photos in your profile.</p>
              <div class="upload-img">
                <ul id="upload_ul">                                    
                  <?php
                  $i=-1;
                  foreach($user['album_images'] as $i=>$imgs){
                  ?>
                     <li id="li_<?php echo $i; ?>">
                        <?php
                        $class = ($imgs['image_name'] == $user['user_detail']['profile_image']) ? 'star_fill' : 'star_empty';
                        $title = ($imgs['image_name'] == $user['user_detail']['profile_image']) ? 'Profile Picture' : 'Album Picture';
                        ?>
                        <button img_name="<?php echo $imgs['image_name']; ?>" class="star <?php echo $class; ?>" img_id="<?php echo $imgs['id']; ?>"></button>
                        <a href="javascript:void(0);"><span>
                          <img title="<?php echo $title;?>" src="<?php echo $this->Common->getUserAlbumImage($imgs['image_name'],200,218,1,'000000'); ?>" />
                        </span></a>
                        <button class="close delete_image" img_id="<?php echo $imgs['id']; ?>"><span aria-hidden="true">x<!--�--></span></button>
                     </li>
                  <?php
                  }
                  $no_of_images = ++$i;
                  
                  ?>                  
                  
                  <li class="loaderpic" style="<?php if($no_of_images == (Configure::read('Site.image_upload_limit_for_normal_user'))) echo 'display:none'; ?>">
                    <a href="#"><span>Upload photo</span>                        
                      <form name="uploadImage_form" enctype="multipart/form-data" id="uploadImage_form">
                        <input type="file" class="btn-file"  required="required" name="uploadImage" id="uploadImage">
                      </form>
                    </a>
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
 
<script> $(function() {  
     
      $('#preview1').imagepreview({
         input: '#uploadImage',
         reset: '#reset1',
         preview: '#preview1'
      });      
      
      $(document).on('click', 'button.star_empty', function(){
         $this = $(this);                         
         $.ajax({
            type: 'post',
            url: SITE_URL + 'ajax/makeprofilepic',
            data:{'id' : $this.attr('img_id')},
            dataType:"json",
            beforeSend: function (xhr) {
               $this.parent().block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
            },
            success: function(res){
               if (res.status == '1') {                  
                  var img_name = $this.attr('img_name');
                  setImage(img_name);
                  $('.star_fill').addClass('star_empty').removeClass('star_fill');
                  $this.addClass('star_fill').removeClass('star_empty');
                  new PNotify({
                      title: 'Success',
                      text: 'Profile image successfully updated.',
                      type: 'success',
                      icon: 'fa fa-check',
                      styling: 'fontawesome',
                      hide: true,
                      delay: 1000
                  });
               }else{
                     new PNotify({
                         title: 'Error',
                         text: 'Error occured.',
                         type: 'error',
                         icon: 'fa fa-close',
                         styling: 'fontawesome',
                         hide: true,
                         delay: 1000
                     });
               }
               $this.parent().unblock();                        
            }
         });
      });
      
      $(document).on('click', 'button.delete_image',  function(){
         $this = $(this);
         $.ajax({
            type: 'post',
            url: SITE_URL + 'ajax/removeimage',
            data:{'id' : $this.attr('img_id')},
            dataType:"json",
            beforeSend: function (xhr) {
               $this.parent().block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});
            },
            success: function(res){
               if (res.status == '1') {
                  $this.parent().remove();
                  if (res.delete_up_icon == 1){
                     $('.loaderpic').show();
                  }
                  if(res.img_id > 0){
                     $('.star_empty').each(function(){
                        if($(this).attr('img_id') == res.img_id){
                           $(this).removeClass('star_empty').addClass('star_fill');
                           var img_name = $(this).attr('img_name');
                           setImage(img_name);
                        }
                     });                     
                  }
                  li_id = ($('#upload_ul > li').size())-1;
                  if(li_id == 0){
                     setImage('no-user.png', 'cover1.jpg');
                  }
                  removeThumb($this.attr('img_id'));
                  
                  new PNotify({
                      title: 'Success',
                      text: 'Image successfully deleted.',
                      type: 'success',
                      icon: 'fa fa-check',
                      styling: 'fontawesome',
                      hide: true,
                      delay: 1000
                  });
               }else{
                     new PNotify({
                         title: 'Error',
                         text: 'Error in image delete.',
                         type: 'error',
                         icon: 'fa fa-close',
                         styling: 'fontawesome',
                         hide: true,
                         delay: 1000
                     });
               }
               $this.parent().unblock();                        
            }
         });
      });
  });
  
   setpopupImage = function (img_name, li_id){
      if(img_name){
            var img_url = '<?php echo $this->Common->getUserAlbumImage("no-user.png",200,218,1); ?>';
            img_url = img_url.replace('no-user.png', img_name);
            var star_class =  (li_id == 0) ? 'star_fill' : 'star_empty';                        
            var htmll = '<li id="li_' + li_id +'"><button img_name="" class="star '+star_class+'" img_id=""></button><a href="#"><span><img src="' + img_url + '" /></span></a><button img_name="" img_id="" class="close delete_image" type="button"><span>x</span></button></li>';
            if(li_id == 0)
                $('#upload_ul').prepend(htmll);
            else
                $('#upload_ul > li:nth-child('+ li_id +')').after(htmll);
      }   
   }
   
   setImage = function (img_name, cover_name){
      if(cover_name == undefined) cover_name = '';
       if(cover_name == ''){   cover_name = img_name; }
       //updating profile img
       var img_url = '<?php echo $this->Common->getUserAlbumImage("no-user.png",200,218,1); ?>';
       img_url = img_url.replace('no-user.png', img_name);
       $('.profile-photo-block > div > span > img').attr('src', img_url);
       
       //updating banner img
       var img_url = '<?php echo $this->Common->getUserCoverImage("no-user.png"); ?>';
       img_url = img_url.replace('no-user.png', cover_name);
       $('#profile-banner > img').attr('src', img_url);
       
   }   
   
   function setThumbImage(n_no, image_name, img_id){     
      var img_url = '<?php echo $this->Common->getUserAlbumImage("no-user.png", 94, 80, 1); ?>';
      img_url = img_url.replace('no-user.png', image_name);
      var htmlr = "<img src='" + img_url + "' title='" + image_name + "'>";           
      $('#thumb_parent li:nth-child('+ n_no +') a span').html(htmlr);
      $('#thumb_parent li:nth-child('+ n_no +')').attr('id', 'thumb_li_'+img_id);
   }
   
   function removeThumb(n_no){
      $('#thumb_li_'+n_no).remove();
      $('#thumb_parent ul').append('<li><a data-target="#myModal3" data-toggle="modal"><span>Upload photo</span></a></li>');
   }
  
<?php $this->Html->scriptEnd(); ?>