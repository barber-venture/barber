<?php
$this->assign('title', 'Dashboard');

use Cake\I18n\Number;
use Cake\Core\Configure;
?>
<?php echo $this->Flash->render(); ?>
<?php
if (isset($Users)) {
    if (!empty($Users)) {
        foreach ($Users as $value) {
            $width = ($value['user_type'] === 2) ? '350' : '250';
            ?>
            <li>
                <a href="<?php echo $this->Url->build(['action' => 'photographer_profile']) . '/' . $value['id'];?>" class="img-wrap">
                    <img src="<?php echo $this->Common->getUserAlbumImage($value['user_detail']['profile_image'], 266, $width, 1); ?>" />                 
                    <div class="description--preview">
                        <h4>
                            <?php echo $value['name']; ?>
                        </h4>
                        <h6>From <?php echo $value['user_detail']['address']; ?></h6></div>
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
    <?php } else { ?>
        <li>
            No more profiles found.
        </li>
        <?php
    }
}
?>


