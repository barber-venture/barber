<?php
$page_num = $this->Paginator->current($model = null);
if(!empty($ChatMessages)){
	foreach($ChatMessages as $msgs){ ?>	
		<div class="sweedyMsgwindow <?php if(($msgs->user_id == $this->request->session()->read('Auth.User.id')) ) echo 'mecomment'; ?>">
			<div class="sweedyuserPic">
			<?php $imgname = (($msgs->user_id == $this->request->session()->read('Auth.User.id')) ) ? $loginUserdata['profile_image'] : $to_user_detail['profile_image']; ?>
			<img src="<?php echo $this->Common->getUserAlbumImage($imgname ,50,58,1); ?>" />
			</div>
			<div class="sweedyMsgContainer">
				<p><?php echo $msgs->message; ?></p>	
			</div>
		</div>
<?php } ?>
<div id="page-num" attr-page='<?php echo $page_num; ?>' attr-totalpage='<?php echo $this->Paginator->params()['pageCount'] ?>' ></div>
<?php }else{
	?>
		<div class="no_messgae" style="font-size: 15px;text-align: center; margin-top: 200px;"><?php echo $this->Html->image('sad.png');?> No message found</div>
	<?php
}

?>
