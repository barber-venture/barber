<?php
$this->assign('title', 'Dashboard');

use Cake\I18n\Number;
use Cake\Core\Configure;
?>
<?php echo $this->Flash->render(); ?>
<?php
if (isset($Users)) {
    if (!$Users->isEmpty()) {
        foreach ($Users as $value) {
            $width = (in_array($value['user_type'], array(2,3,4))) ? '350' : '250';
			$highlite = ($value['user_type'] == 4) ? 'highlighter' : '';
            ?>
            <li class="<?php echo $highlite; ?>">
            	<?php if($value['user_type'] == 4){ ?><div class="premium_badge"></div> <?php } ?>
                <a href="javascript:void(0)" class="img-wrap exploreThumb" user="<?php echo $value['id'];?>">

                    <img src="<?php echo $this->Common->getUserAlbumImage($value['user_detail']['profile_image'], 266, $width, 1); ?>" />                 
                    <div class="description--preview">
                        <?php if($value['is_online'] == 1){ ?>
                            <div class="led-green"></div> 
                        <?php } ?>
                        <h4>
                            <?php echo substr($value['user_detail']['nike_name'], 0, 20) . ', ' . date_diff(date_create($value['user_detail']['dob']), date_create('today'))->y, "\n";
                            ?>
                        </h4>
                        <?php if($value['user_detail']['address'] != ''){ ?>
                            <h6>From <?php echo $value['user_detail']['address']; ?></h6>
                        <?php } ?>
                    </div>
                </a>
            </li>
        <?php } ?>

        <?php 
//            if ($this->Paginator->hasNext()) {                
                ?>
                <script>
                    $("#nextPage").val(<?php echo $this->Paginator->current() + 1; ?>);
                    $("#totalPage").val(<?php echo $this->Paginator->params()['pageCount']; ?>);
                </script> 
                <?php
//            }
         
        ?>

        <script>
            new GridScrollFx(document.getElementById('grid'), {
                viewportFactor: 0.4
            });
        </script> 
    <?php } else {
        echo 'not';
    }
}
?>