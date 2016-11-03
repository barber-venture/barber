<?php 

$this->assign('title', 'Live Chat');
use Cake\Core\Configure;

?>
 
<div id="page-num" attr-page="1" attr-totalpage="1"></div>
<input type="hidden" name="active_user_id" id="active_user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="login_user_id" value="<?php echo $this->request->session()->read('Auth.User.id'); ?>">
<input type="hidden" id="login_user_image" value="<?php echo $loginUserdata['profile_image']; ?>">	
<div class="live-chat-section">
    <div class="container animated3 fadeInTop">
        <div class="row">
			<div class="col-md-5">
				<div class="Livevideo camera" id="my-camera">
					<video width="100%" height="100%" poster="" loop autoplay></video>
				</div>
				
				<div id="peer-camera" class="Livevideo camera hidden" >
					<video width="100%" height="100%" autoplay></video>
				</div>
				
			</div>
  <!--added by pavan -->
			<div id="connect" style="display:none;">
				<h4 >ID: <span id="id"></span></h4>
				<input type="text" name="name" id="name" value="<?php echo $this->request->session()->read('Auth.User.name'); ?>">
				<input type="text" name="peer_id" id="peer_id" value="<?php echo $user_id; ?>">
				<div id="connected_peer_container" class="hidden">
				  Chat with : <span id="connected_peer"></span>
				</div>   
				<!-- <button id="login">Login</button> 
				 <button id="call">Call</button> -->
			</div>
  <!--end of pavan code-->
			<!--<span class="chatheads_user" user_id="<?php //echo $user_id; ?>">-->
			<div class="col-md-7"> 
				<div class="chat-screen-body">
					<div class="sweedyTeamColumn">
						<div id="chat" class="sweedyMsgouter">
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
						  
						</div>
						  
						<div class="sweedyChatMsg" id="message-container">
							<input type="text" name="message" id="message" class="sweedychatinput" placeholder="Your Message">
							<input type="submit" id="send-message" value="Send" class="btn btn-danger btn-msg-send msg_chat">
							<button id="login" class="btn btn-danger btn-msg-send">Start Chat</button>
							<button id="call" class="btn btn-danger btn-msg-send hidden">Call</button>
							<!--<button id="login"></button>-->
						</div>
						
					</div>
				</div>
			</div>
				   
				
		</div>
    </div>
</div>
<!-- Handlebars template for constructing the list of messages -->
<script id="messages-template" type="text/x-handlebars-template">
  {{#each messages}}
  <li>
	<span class="from">{{from}}:</span> {{text}}
  </li>
  {{/each}}
  	
</script>

<?php $this->Html->scriptStart(); ?>
	socket.on('receive message', function (msg) {
        
        var userID = msg[1];
        var txtmsg = msg[0];
        var FriendId = msg[2];
        var Friendimg = ((msg[3] != '') && msg[3] != undefined) ? msg[3] : 'no-user.png';
        
        console.log(txtmsg);
        
        if ($('#active_user_id').val() == FriendId) {

            $('div.no_messgae').remove();

            var imgurl = getImageurl_live(Friendimg);
            var msg_html = '<div class="sweedyMsgwindow"><div class="sweedyuserPic"><img src="' + imgurl + '"></div><div class="sweedyMsgContainer"><p>' + txtmsg + '</p></div></div>';
            $('.sweedyMsgouter').append(msg_html);
            $('.sweedyMsgouter').slimscroll({scrollBy: $('.sweedyMsgouter').prop("scrollHeight") + 'px'});
        } else {

            var last_counter = $('#counter_unread_' + FriendId).html();

            if (last_counter === undefined || last_counter === '') {
                last_counter = 1;
            } else {
                last_counter = parseInt(last_counter) + 1;
            }
            $('#counter_unread_' + FriendId).html(last_counter);

        }
        
        var upmsg = txtmsg.substring(0, 15);
        if ((txtmsg.length) > 15)
            upmsg += '...';
        $('#Chat_li_' + FriendId + ' a div div.userLocation').html(upmsg);

    });

    function getImageurl_live(image_name) {
        var img_url = '<?php echo $this->Common->getUserAlbumImage("no-user.png", 50, 50, 1); ?>';
        img_url = img_url.replace('no-user.png', image_name);
        return img_url;
    }

    function getdatetime_live() {
        return datetime = '<?php echo date("Y-m-d H:i:s"); ?>';
    }
	
	
	$('.msg_chat').click(function(){
		txtmsg = ($('.sweedychatinput').val()).trim();
		if (txtmsg !== '') {
			$('.no_messgae').hide();
			send_message_live(txtmsg, 1);
		}   
	});
	
	 
	$(document).on('keydown', function (e) {
		switch (e.which) {                
			case 13: // left
				txtmsg = ($('.sweedychatinput').val()).trim();
				if (txtmsg !== '') {
					$('.no_messgae').hide();
					send_message_live(txtmsg, 1);
				}  
			break;
			default: return; // exit this handler for other keys
		}
		//e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	
	function send_message_live(txtmsg, is_request){
		
		var datetime = getdatetime_live();           
		var mesgdata = [];
		mesgdata.push(txtmsg);
		mesgdata.push($('#active_user_id').val());
		mesgdata.push(userID);
		mesgdata.push($('#login_user_image').val());
		mesgdata.push(datetime);
		mesgdata.push(is_request);
		
		console.log(mesgdata);            
		var upmsg = txtmsg.substring(0,15);        
		if ((txtmsg.length)> 15) upmsg += '...';
		$('#Chat_li_'+ mesgdata[1] + ' a div div.userLocation').html(upmsg);
		
		socket.emit('chat message', mesgdata);
		
		var myimage = $('#login_user_image').val();
		myimage = ((myimage != '') && myimage != undefined) ? myimage : 'no-user.png';
		var imgurlll = getImageurl_live(myimage);        
		
		$('.sweedyMsgouter').append('<div class="sweedyMsgwindow mecomment"><div class="sweedyuserPic"><img src="'+imgurlll+'"></div><div class="sweedyMsgContainer"><p>'+txtmsg+'</p></div></div>');
		$('.sweedychatinput').val('');
		$('.sweedyMsgouter').slimscroll({ scrollBy: $('.sweedyMsgouter').prop("scrollHeight")+'px' });
		
	}
	
<?php $this->Html->scriptEnd(); ?>