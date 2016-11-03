
var chat = function () {
    
    var chatfunction = function () {
        
        var is_request = '0';        
        $('body').on('click', '#req_tab', function(){
            is_request = '1';
            $('.sweedyMsgouter').html('<div class="no_messgae" style="font-size: 15px;text-align: center; margin-top: 200px;"><img alt="" src="'+ SITE_URL +'img/sad.png"> No messages found.</div>');
             var u_id = '';          
             u_id = $('.Requests-details .chatheads_user').first().attr('user_id');
             $('.Requests-details .chatheads_user').first().addClass('active');
             $('#active_user_id').val(u_id);
            
            if (u_id > 0) {
                $.ajax({
                    url: SITE_URL + 'users/getchatheaduserdetail',
                    data: {'to_user_id' : u_id},
                    async: true,                   
                    success: function(res){
                         $('.sweedyDetailsColumn').html(res);                  
                    },
                    error: function(){}
                });
            }else{
                 $('.sweedyDetailsColumn').html(''); 
            }            
            //$('.sweedychatinput').hide();
            //$('.btn-msg-send').hide();            
        });
        
        $('body').on('click', '#video_chat_link', function(){
            var user_id = $('#active_user_id').val();
            window.location = SITE_URL + 'users/liveChat/' + user_id;
        });
        
        $('body').on('click', '#chat_tab', function(){
            is_request = '0';
            $('.chatheads_user').first().trigger('click');
        //    $('.sweedychatinput').show();
        //    $('.btn-msg-send').show();
        });        
        last_page = 1;
             
        $('.comment').click(function(){
           $.ajax({
                url: SITE_URL + 'users/chatcontent',
                data: '',
                async: true,
                beforeSend: function (response) {
                    $("#loadergreen").show();
                },
                success: function(res){
                    $("#loadergreen").hide();
                    
                    $('#myModalchat div div').html(res);
                    $('#myModalchat').modal({
                        show: 'true'
                    });   
                    $('#sweedyTabs').easyResponsiveTabs({
                        type: 'default', //Types: default, vertical, accordion           
                        width: 'auto', //auto or any width like 600px
                        fit: true   // 100% fit in a container
                    });
                    
                    $('.chatheads_user').first().trigger('click');
                    
                    $('.sweedyMsgouter').slimscroll({
                        wheelStep: 10,
                        height: '432px',
                        color: '#ed1a3b',
                        scrollBy: $('.sweedyMsgouter').prop("scrollHeight")+'px'
                    });                    
                    $('.chatheads').slimscroll({
                        wheelStep: 2,
                        height: '432px',
                        color: '#ed1a3b',
                    });
                                
                },
                error: function(){}
            }); 
        });
        
        $('body').on('click', '.chatheads_user', function(){
            
            $this = $(this);
            id = $this.attr('user_id');
            //console.log(id + '--id');
            
            //$('#counter_unread_'+id).html('');
            last_page = 1;
            page = 1;
            total_page = 1;
            $('#page-num').attr('attr-totalpage','');
            $('#page-num').attr('attr-page','');
            
            if ((id == $('#active_user_id').val()) ) return false;
            
            //$('#counter_unread_' + id).css('display', 'none');
            $('.chatheads_user').removeClass('active');            
            $('#active_user_id').val(id);
            $('.sweedychatinput').val('');
            $this.addClass('active');
                     
            getUserChat(id, 0);
           
        });
        
        //
        //$('body').on('click', '.chat_request', function(){ 
        //    $this = $(this);
        //    $.ajax({
        //        url: SITE_URL + 'users/activatechat',
        //        data: {'to_user_id' : $this.attr('user_id')},
        //        async: true,    
        //        success: function(res){
        //            $('.chatheads_user').removeClass('active');
        //            $this.addClass('active');
        //            $this.removeClass('chat_request');
        //            $('#active_user_id').val($this.attr('user_id'));
        //            $('.sweedychatinput').show();
        //            $('.btn-msg-send').show();
        //            //alert('Request successfully accepted. Now u can send message to this user.');
        //        },
        //        error: function(){}
        //    });
        //});
        
        //remove activate chat view too
        //\\192.168.0.149\htdocs\dating\src\Template\Users\Ajax\activatechat.ctp
        
        $('body').on('click', '.chat_request', function(){
            $this = $(this);
            $('.chatheads_user').removeClass('active');
            $this.addClass('active');
            //$('.sweedychatinput').show();
            //$('.btn-msg-send').show();
        });
        
        $('body').on('click', '#popup_msg_send', function(){
            txtmsg = ($('.sweedychatinput').val()).trim();
            if (txtmsg !== '') {
                $('.no_messgae').hide();
                send_message(txtmsg, is_request);
            }            
        });
        
        
        $(document).on('keydown', function (e) {
            switch (e.which) {                
                case 13: // left
                    txtmsg = ($('.sweedychatinput').val()).trim();
                    if (txtmsg !== '') {
                        $('.no_messgae').hide();
                        send_message(txtmsg, is_request);
                    }  
                break;
                default: return; // exit this handler for other keys
            }
            //e.preventDefault(); // prevent the default action (scroll / move caret)
        });
        
        
    
        
//        $(document).on('scroll', '.sweedyMsgouter', function(){ 
//        	var div = $(this);
//            alert("test");
//            console.log(div[0].scrollHeight);
//            
//			if (div[0].scrollHeight - div.scrollTop() == div.height())
//			{
//				alert("Reached the bottom!");
//			}
//			else if(div.scrollTop() == 0)
//			{
//                alert("Reached the TOP!");
//			  //var userid = userID;
//			  //var check = get_message(userid,div.attr('attr_load_id'),1);
//			}
//		});
        
        
        function getUserChat(data_id, is_scroll){
            
            $('#counter_unread_'+data_id).html('');
            $('#counter_unread_'+data_id).css('display', 'none');
            
            var page = '';
            if(($('#page-num').attr('attr-page') == undefined) || ($('#page-num').attr('attr-page') == '')){
                page = 1;
                last_page = 0;
            }else{
                page = parseInt($('#page-num').attr('attr-page')) + 1;
            }
            
            if(($('#page-num').attr('attr-totalpage') == undefined) || ($('#page-num').attr('attr-totalpage') == '')){
                total_page = 1;
            }else{
                total_page = parseInt($('#page-num').attr('attr-totalpage'));
            }
                        
            if(page > last_page && total_page >= page){
                //console.log('ok'+last_page);
                last_page = page;
                $.ajax({
                   url: SITE_URL + 'users/getuserchatmessages',
                   data: {'to_user_id' : data_id, 'page_num' : page, 'total_page' : total_page},
                   async: true,
                   beforeSend: function () {
                       $("#loadergreen1").show();
                   },
                   success: function(res){
                       $("#loadergreen1").hide();
                       //$('#messages_'+friendID).prepend(listHistory);
                        if (is_scroll) {
                            $('.sweedyMsgouter').prepend(res); 
                        }else{
                            $('.sweedyMsgouter').html(res); 
                        }
                       
                        if(page == 1){
                            $('.sweedyMsgouter').slimscroll({ scrollBy: $('.sweedyMsgouter').prop("scrollHeight")+'px' });
                        }else{
                             var el = $('#page-num'); //set element ~?
                             var elPosition = el.position();
                             $('.sweedyMsgouter').slimScroll({
                                 scrollTo: elPosition.top + 'px',
                             });                         
                        }                       
                       
                       $('.sweedyMsgouter').slimScroll().bind('slimscroll', function(event, pos){
                           event.preventDefault();
                           if(pos === 'top'){
                                if(page == last_page && total_page >= page){
                                   getUserChat(id, 1);
                                }
                               //console.log("Reached " + pos);
                               //var userid = userID;
                               //var check = get_message(userid,div.attr('attr_load_id'),1);
                           }
                       });
                       
                   },
                   error: function(){}
               });
                
                $.ajax({
                   url: SITE_URL + 'users/getchatheaduserdetail',
                   data: {'to_user_id' : data_id},
                   async: true,
                   //beforeSend: function () {
                   //    $("#loadergreen1").show();
                   //},
                   success: function(res){
                       //$("#loadergreen1").hide();
                        $('.sweedyDetailsColumn').html(res);                  
                   },
                   error: function(){}
               });
                
            }            

        }
        
        function send_message(txtmsg, is_request){
            
            //console.log(txtmsg + '---' +  is_request);
            //console.log($('#active_user_id').val() + '==');
            
            if ($('#active_user_id').val() == '') {
                return false;
            }
            var datetime = getdatetime();           
            var mesgdata = [];
            mesgdata.push(txtmsg);
            mesgdata.push($('#active_user_id').val());
            mesgdata.push(userID);
            mesgdata.push($('#login_user_image').val());
            mesgdata.push(datetime);
            mesgdata.push(is_request);
            
            //console.log(mesgdata);
            
            var upmsg = txtmsg.substring(0,15);        
            if ((txtmsg.length)> 15) upmsg += '...';
            $('#Chat_li_'+ mesgdata[1] + ' a div div.userLocation').html(upmsg);
            
            socket.emit('chat message', mesgdata);
            
            var myimage = $('#login_user_image').val();
            myimage = ((myimage != '') && myimage != undefined) ? myimage : 'no-user.png';
            var imgurlll = getImageurl(myimage);        
            
            $('.sweedyMsgouter').append('<div class="sweedyMsgwindow mecomment"><div class="sweedyuserPic"><img src="'+imgurlll+'"></div><div class="sweedyMsgContainer"><p>'+txtmsg+'</p></div></div>');
            $('.sweedychatinput').val('');
            $('.sweedyMsgouter').slimscroll({ scrollBy: $('.sweedyMsgouter').prop("scrollHeight")+'px' });
            
        }
        
        $('body').on('click', '.unlatch_user', function(){
            var $this = $(this);
            var status = confirm("Do you really want to unlatch this user?");
            if (status) {
                var id  = $this.data('id');
                console.log(id); //return false;
                $.ajax({
                    type: 'GET',
                    url: SITE_URL + 'ajax/unlatch_user',
                    data:{'id' : id},                    
                    beforeSend: function (xhr) {
                        $('.sweedyTeamColumn').block({message: '<img src="' + SITE_URL + 'img/ajax-sm.gif" style="margin:19px -45px;">'});                       
                    },
                    success: function(res){
                        if (res) {                            
                            $('#Chat_li_'+id).remove();
                            var heads = $('.chatheads_user').length;
                            console.log(heads + '---heads');
                            if (heads > 0) {
                                $('.chatheads_user').first().trigger('click');
                            }else{
                                $('#active_user_id').val('')
                                $('.sweedyMsgouter').html('<div class="no_messgae" style="font-size: 15px;text-align: center; margin-top: 200px;"><img alt="" src="'+ SITE_URL +'img/sad.png"> No messages found.</div>');
                            }                            
                            $('.sweedyTeamColumn').unblock();
                        }                        
                    }
                });
            }
        });
        
    }
    return {
        init: function () {
            chatfunction();
        }
    }
}();
