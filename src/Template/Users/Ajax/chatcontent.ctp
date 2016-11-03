<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<div class="modal-body">
    <div class="container chat-screen-body">
        <div class="row">
            <div class="col-sm-3 sweedyMassenger">
                <h2>Sweedy-Messenger</h2>
                <div class="sweedyMassengerColumn">
                    <div id="sweedyTabs">
                        <ul class="resp-tabs-list">
                            <li id="chat_tab">Chat</li>
                            <li id="req_tab">Requests</li>
                        </ul>
                        <div class="resp-tabs-container">
                            <div class="chat-details">
                                <ul class="chattingList scrollingList chatheads">
                                    <?php
                                    $i = 1;
                                    foreach ($ActiveChats as $actChat) {
                                        if (!$actChat['is_request']) {
                                            ?>
                                            <li id="Chat_li_<?php echo $actChat['user']->id; ?>" class="chatheads_user <?php
                                            if ($i == 1)
                                                echo 'active';
                                            $i++;
                                            ?>" user_id="<?php echo $actChat['user']->id; ?>"> 
                                                    
                                                    <span style="display:<?php if($actChat['unread_messages'] > 0) echo 'block'; else echo 'none'; ?>;" id="counter_unread_<?php echo $actChat['user']->id; ?>" class='unread_counter'><?php if($actChat['unread_messages'] > 0) echo $actChat['unread_messages']; ?></span>
                                                    <div class="userSmallPic">
                                                    
                                                        <img src="<?php echo $this->Common->getUserAlbumImage($actChat->profile_image, 50, 50, 1); ?>" />
                                                                                                                
                                                        <!--<span style="display:block;" class='unread_counter'>6</span>-->
                                                    </div>
                                                   
                                                    <div class="userContent">
                                                        <div class="userName"><?php
                                                        $name = ($actChat['user']->role_id == 2) ? $actChat['nike_name'] : $actChat['user']->name;
                                                        echo substr($name, 0, 6);
                                                        if($actChat->age > 0)
                                                        echo ', ' . $actChat->age;
                                                        ?> 														
                                                            <?php
                                                            
                                                            $show = ($actChat['user']->is_online == 1) ? 'display:inline;' : 'display:none';
                                                            echo $this->Html->image('online.png', ['style' => $show, 'class' => 'onlineimg' ]);
                                                            ?> 
                                                            <span class="pull-right"> 
                                                                <?php echo $this->Html->link($this->Html->image('flag.png'), '/pages/contact_us/'.$this->Common->encrypt($actChat['user']->id), ['escape' => false, 'Title' => 'Report User'])
                                                            ?>&nbsp;
                                                                <span title="Unlatch User" style="cursor:pointer;" class="unlatch_user" data-id="<?php echo $actChat['user']->id; ?>">X</span>
                                                            </span>
                                                        </div>
                                                        <div class="userLocation"><?php echo ($actChat['last_message'] != '') ? $actChat['last_message'] : '&nbsp;'; ?> </div>
                                                    </div>
                                                
                                            </li>
                                            <?php
                                        }
                                    }
                                    if ($i == 1) {
                                        ?>
                                        <div style="font-size: 15px;text-align: center;">No active chats</div>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="Requests-details">
                                <ul class="chattingList requests scrollingList chatheads">
                                    <?php
                                    $j = 0;
                                    foreach ($ActiveChats as $actChat) {
                                        if ($actChat['is_request']) {
                                            $j++;
                                            ?>
                                            <li id="Chat_li_<?php echo $actChat['user']->id; ?>" class="chat_request chatheads_user" user_id="<?php echo $actChat['user']->id; ?>">
                                                <span style="display:<?php if($actChat['unread_messages'] > 0) echo 'block'; else echo 'none'; ?>;" id="counter_unread_<?php echo $actChat['user']->id; ?>" class='unread_counter'><?php if($actChat['unread_messages'] > 0) echo $actChat['unread_messages']; ?></span>
                                                <div class="userSmallPic">
                                                    <img src="<?php echo $this->Common->getUserAlbumImage($actChat->profile_image, 50, 50, 1); ?>" />                                                   
                                                    
                                                </div>
                                                    
                                                <div class="userContent">
                                                    <div class="userName"><?php echo substr($actChat['nike_name'], 0, 6) . ', ' . $actChat->age; ?> 												        <?php
                                                        if ($actChat['user']->is_online == 1)
                                                            echo $this->Html->image('online.png');
                                                        ?>
                                                    </div>
                                                    <div class="userLocation">&nbsp; </div>
                                                </div>                                                
                                            </li>
                                            <?php
                                        }
                                    }
                                    if ($j == 0) {
                                        ?>
                                        <div style="font-size: 15px;text-align: center;">No chat requests</div>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-sm-6 sweedyTeam">
                <h2>Sweedy Team</h2>
                <div class="sweedyTeamColumn">
                    <img id="loadergreen1" src="<?php echo SITE_FULL_URL; ?>img/greenloader.gif" style="display:none;left: 550px;position: fixed;top: 250px;">
                    <input type="hidden" id="active_user_id" value="">
                    <input type="hidden" id="login_user_id" value="<?php echo $this->request->session()->read('Auth.User.id'); ?>">
                    <input type="hidden" id="login_user_image" value="<?php echo $loginUserdata['profile_image']; ?>">					
                    <div class="sweedyMsgouter">
                        <div class="no_messgae" style="font-size: 15px;text-align: center; margin-top: 200px;"><img src="<?php echo SITE_FULL_URL; ?>img/sad.png"> No messages found.</div>
                    </div>
                    <div class="sweedyChatMsg">
                        <input type="text" placeholder="Your Message" class="sweedychatinput">
                        <input id="popup_msg_send" type="submit" class="btn btn-danger btn-msg-send" value="Send">
                    </div>
                </div>    
            </div>
            <div class="col-sm-3 sweedyDetails">
                
               <h2> 
                <?php if($this->request->session()->read('Auth.User.user_type') == 4){ ?>
                    <a id="video_chat_link" style="text-decoration: none;" href="javascript:void(0);"><i class="fa fa-video-camera"></i> video</a>
                <?php }else{ ?>
                    <a style="text-decoration: none;" href="javascript:alert('You should be a premium member to start video chat.');"><i class="fa fa-video-camera"></i> video</a>
                <?php } ?>
            </h2>
               
                <div class="sweedyDetailsColumn">
                    <ul class="chattingList">
                        <li>
                            <div class="userSmallPic"><?php echo $this->Html->image('small-pic.png'); ?></div>
                            <div class="userContent">
                                <div class="userName">Sweedy Team</div>
                                <div class="userLocation">Canada</div>
                            </div>
                        </li>
                    </ul>
                    <div class="aboutBox">
                        <h3>About me</h3> 
                        <p>Printer took a galley of tcenturies, but also  typesetting, remaining essentially unchanged. </p>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>

<script>
            
    function getImageurl(image_name) {
        var img_url = '<?php echo $this->Common->getUserAlbumImage("no-user.png", 50, 50, 1); ?>';
        img_url = img_url.replace('no-user.png', image_name);
        return img_url;
    }

    function getdatetime() {
        return datetime = '<?php echo date("Y-m-d H:i:s"); ?>';
    }

</script>