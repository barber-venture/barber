//var app = require('./node_modules/express')();
//var http = require('http').Server(app);
//var io = require('./node_modules/socket.io')(http);
//----------------------------Old Code ------------------------


var app = require('./node_modules/express')();
var fs = require('fs');

// This line is from the Node.js HTTPS documentation.
//var options = {
//  key: fs.readFileSync('/var/cpanel/ssl/installed/keys/aac35_e6355_2f8acfd1260729b79334a4cc469dcafa.key'),
//  cert: fs.readFileSync('/var/cpanel/ssl/installed/certs/sweeedy_com_aac35_e6355_1503694418_efc1f2d98fc020bf06fdddfeb758b3e1.crt')
//};
//var https = require('https').createServer(options,app);


var http = require('http').createServer(app);
var io = require('./node_modules/socket.io')(http);
//var io = require('./node_modules/socket.io')(https);

var mysql = require('mysql');
var config = require('./db_config');
forgeterDb = config.forgeterDb();
 var chatuser_config = {
    connectionLimit: 20,
    host: forgeterDb.DB_HOST,
    user: forgeterDb.DB_USER,
    password: forgeterDb.DB_PASSWORD,
    database: forgeterDb.DB_NAME,
    dateStrings: true
};
var chatuserPool = mysql.createPool(chatuser_config);

var userArr = {};
var socketsArr = {};

io.on('connection', function(socket){
    socket.on('chat message', function(msg){
                
        var queryString = "INSERT INTO chat_messages (`id`, `user_id`, `to_user_id`, `message`, `status`, `is_read`, `created`) VALUES (NULL, '" + msg[2] +"', '" + msg[1] +"', '" + msg[0] +"', '1', '0', '"+ msg[4] +"');";
        
        var messagee = msg[0];
        var upmsg = messagee.substring(0,15);        
        if ((messagee.length)> 15) {
            upmsg += '...';
        }
        var update_is_request = (msg[5] == "1") ?  " , is_request = '0'" : "";
              
        var updatemsg = "UPDATE `active_chats` SET `last_message` = '" + upmsg + "' "+ update_is_request +" WHERE user_id = '" + msg[2] + "' AND to_user_id = '" + msg[1] + "'" ;
        
        var updatemsgforfriend = "UPDATE `active_chats` SET `last_message` = '" + upmsg + "' " + update_is_request +" WHERE user_id = '" + msg[1] + "' AND to_user_id = '" + msg[2] + "'" ;
        
        
        chatuserPool.getConnection(function (err, connection) {
            if (err) {
                //callback(true, err);
            } else {
                connection.query(queryString, function (err, rows, fields) {
                    if (err) {
                        //callback(true, "Error in fetching data");
                    } else {
                        if (rows[0] === undefined) {
                            //callback(true, "No User found");
                        } else {                            
                            insertObj = [];
                            for (i=0;i < rows.length; i++ ) {
                                insertObj.push(rows[i].friend_id);
                            } userArr[userId] = insertObj;                            
                        }
                    }
                });
                connection.query(updatemsg, function (err, rows, fields) {
                    if (err) {
                        //callback(true, "Error in fetching data");
                    } else {
                        if (rows[0] === undefined) {
                            //callback(true, "No User found");
                        } else {                            
                            insertObj = [];
                            for (i=0;i < rows.length; i++ ) {
                                insertObj.push(rows[i].friend_id);
                            } userArr[userId] = insertObj;                            
                        }
                    }
                });                
                connection.query(updatemsgforfriend, function (err, rows, fields) {
                    if (err) {
                        //callback(true, "Error in fetching data");
                    } else {
                        if (rows[0] === undefined) {
                            //callback(true, "No User found");
                        } else {                            
                            insertObj = [];
                            for (i=0;i < rows.length; i++ ) {
                                insertObj.push(rows[i].friend_id);
                            } userArr[userId] = insertObj;                            
                        }
                    }
                });
               
               connection.release();
            }
        });
        
        //console.log(socketsArr[msg[1]]['is_popup']);
        
        if ( socketsArr[msg[1]] != undefined) {
            socketsArr[msg[1]].emit('receive message', msg);           
        }else{
            
              var update_count = "UPDATE `active_chats` SET unread_messages = unread_messages + 1  WHERE user_id = '" + msg[1] + "' AND to_user_id = '" + msg[2] + "'" ;
                        
            chatuserPool.getConnection(function (err, connection) {
                if (err) {
                    //callback(true, err);
                } else {                            
                    connection.query(update_count, function (err, rows, fields) {
                        if (err) {
                            //callback(true, "Error in fetching data");
                        } else {
                            if (rows[0] === undefined) {
                                //callback(true, "No User found");
                            } else {                            
                                insertObj = [];
                                for (i=0;i < rows.length; i++ ) {
                                    insertObj.push(rows[i].friend_id);
                                } userArr[userId] = insertObj;                            
                            }
                        }
                    });                   
                   connection.release();
                }
            });
        }
    });
       
    socket.on('userId', function(userId){       
        socketsArr[userId] = {};
        socketsArr[userId] = socket;
        userArr[socket.id] = userId;
        socketsArr[userId.is_popup] = 0;
        //console.log(socketsArr);
        //socket.broadcast.emit('receive_new_user', userId);
    });
    
    socket.on('popupClosed', function(msg){
        
        console.log('Not View');
        var update_count = "UPDATE `active_chats` SET unread_messages = unread_messages + 1  WHERE user_id = '" + msg[1] + "' AND to_user_id = '" + msg[2] + "'" ;
                        
            chatuserPool.getConnection(function (err, connection) {
                if (err) {
                    //callback(true, err);
                } else {                            
                    connection.query(update_count, function (err, rows, fields) {
                        if (err) {
                            //callback(true, "Error in fetching data");
                        } else {
                            if (rows[0] === undefined) {
                                //callback(true, "No User found");
                            } else {                            
                                insertObj = [];
                                for (i=0;i < rows.length; i++ ) {
                                    insertObj.push(rows[i].friend_id);
                                } userArr[userId] = insertObj;                            
                            }
                        }
                    });                   
                   connection.release();
                }
            });
    });  
    
    socket.on('check_active_user', function(frnd_id,userID){
        
        if(socketsArr[frnd_id] != undefined){
            console.log(frnd_id);
            socketsArr[frnd_id].emit('receive_active_user', userArr);
            socketsArr[userID].emit('receive_active_user', userArr);
        }else{
            console.log('Not');
        }
        socket.broadcast.emit('receive_active_user', userID);
        
    });
    
    socket.on('error', function(err){
      console.log(err);
    });
    
     socket.on('destroy', function(err){
      console.log(err);
    });
        
    socket.on('disconnect', function(){        
        delete socketsArr[userArr[socket.id]];
        socket.broadcast.emit('deactive_user', userArr[socket.id]);
        delete userArr[socket.id];        
    });
    
});

http.listen('3131', function(){
  console.log('listening on *:3131');
});
