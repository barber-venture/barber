<?php

use Cake\Core\Configure;
?>

<?php
$i = -1;
foreach ($Album as $i => $imgs) {
    ?>
    <li id="li_<?php echo $i; ?>">
        <?php
        $class = ($imgs['image_name'] == $user['user_detail']['profile_image']) ? 'star_fill' : 'star_empty';
        $title = ($imgs['image_name'] == $user['user_detail']['profile_image']) ? 'Profile Picture' : 'Album Picture';
        ?>
        <button img_name="<?php echo $imgs['image_name']; ?>" class="star <?php echo $class; ?>" img_id="<?php echo $imgs['id']; ?>"></button>
        <a href="javascript:void(0);"><span>
                <img title="<?php echo $title; ?>" src="<?php echo $this->Common->getUserAlbumImage($imgs['image_name'], 200, 218, 1, '000000'); ?>" />
            </span></a>
        <button class="close delete_image" img_id="<?php echo $imgs['id']; ?>"><span aria-hidden="true">x<!--�--></span></button>
    </li>
    <?php
}
$no_of_images = ++$i;
?>                  

<li class="loaderpic" style="<?php if ($no_of_images == (Configure::read('Site.image_upload_limit_for_normal_user'))) echo 'display:none'; ?>">
    <a href="#"><span>Upload photo</span>                        
        <form name="uploadImage_form" enctype="multipart/form-data" id="uploadImage_form">
            <input type="file" class="btn-file"  required="required" name="uploadImage" id="uploadImage">
        </form>
    </a>
</li>

<script>
    $(function() {

    $('#preview1').imagepreview({
    input: '#uploadImage',
            reset: '#reset1',
            preview: '#preview1'
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
  
</script>