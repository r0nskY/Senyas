var mysql = require('mysql');
var express = require('express');
var bcrypt = require('bcrypt-nodejs');
var app = express();
var server = require('http').Server(app);
//var crypto = require('crypto');
var io = require('socket.io')(server, { pingTimeout: 3000, pingInterval: 1000 });
var port = process.env.PORT || 8080;

var cc = { CommandCenter : '' };
var sms = {socket_id: ''};

var pool = mysql.createPool({
    connectionLimit : 20,
    host : 'localhost',
    user : 'root',
    password : 'adsoph123',
    database : 'senyasiriga'
});
//serve server in port 8080
server.listen(port, function(){
  console.log('Server is now running');
});

/*console.log(__dirname + '../public');
app.use(express.static(__dirname + '../public'));*/

io.on('connection', function(socket){
    console.log('New device is connected: ' + socket.id);

    //register custom socket event
    socket.on('disconnect', onDisconnect);
    socket.on('check_user', onCheckUser);
    socket.on('login_user', onLoginUser);
    socket.on('user_login', onUserLogin);
    socket.on('dept_login', onDeptLogin);
    socket.on('RESP_LOGIN', onRespLogin);
    socket.on('rejoin', onReJoin);
    socket.on('send_notification', onSendNotification);
    socket.on('track_alert', onTrackAlert);
    socket.on('send_alert_dept', onSendAlertDept);
    socket.on('RESPOND_ALERT', onRespondAlert);
    socket.on('location_update', onLocationUpdate);
    socket.on('RESP_UPDATE', onRespUpdate);
    socket.on('send_alert', onSendAlert);
    socket.on('accept_alert', onAcceptAlert);
    socket.on('send_alert_ac', onSendAlertAc);
    socket.on('send_alert_resp', onSendAlertResp);
    socket.on('resp_reg', onRespRegister);
    socket.on('incident_status', onIncidentStatus);
    socket.on('user_reg', onUserRegister);
    socket.on('login_sms', onLoginSms);
    socket.on('check_resp_alert', onCheckRespAlert);
    socket.on('request_end', onRequestEnd);
    socket.on('alert_end', onAlertEnd);
    socket.on('end_alert', onEndAlert);
    socket.on('auto_login', onAutoLogin);
    socket.on('user_end', onUserEndAlert);
    socket.on('track_message', onTrackMessage);

    // socket event functions
    function onDisconnect(){
        console.log('Device is disconnected: ' + socket.id);
        if (socket.id === cc.CommandCenter){
            console.log('CommandCenter');
            cc.CommandCenter = '';
        }else{
            pool.getConnection(function(error, connection){
                if (error) throw error;
                connection.query("UPDATE action_center SET socket_id='' WHERE socket_id='" + socket.id + "'", function(err){
                    if (err) throw err;
                });
                connection.query("UPDATE responder SET socket_id='', status='inactive' WHERE socket_id='" + socket.id + "'", function(err){
                    if (err) throw err;
                });
                connection.query("UPDATE users SET socket_id='' WHERE socket_id='" + socket.id + "'", function(err){
                    if (err) throw err;
                });
                connection.query("UPDATE app_users SET socket_id='' WHERE socket_id='" + socket.id + "'", function(err){
                    if (err) throw err;
                });
                connection.release();
            });
        }
    }
    function onCheckUser(data){
        console.log(data);
        pool.getConnection(function (error, connection) {
            if (error) throw error;
            connection.query("SELECT id, status FROM app_users WHERE imei='" + data +"'", function (err, result) {
                if (err) throw err;
                //console.log(result);
                if (result.length !== 0) {
                    var x = Boolean(result[0].status !== 0);
                    console.log(x);
                    socket.emit('check_user', {isExist: true, isVerify: x});
                    console.log('user exist');
                }else{
                    socket.emit('check_user', {isExist: false, isVerify: false});
                    console.log('user not exist');
                }
                connection.release();
            });
        });
    }
    function onLoginUser(data){
        pool.getConnection(function (error, connection) {
            connection.query("SELECT password FROM app_users WHERE imei=?", [data.imei], function (err, res) {
                if (err) throw  err;
                if (res.length !== 0){
                    bcrypt.compare(data.password, res[0].password, function (error, isMatch) {
                        if (error) throw error;
                        console.log(isMatch);
                        if (isMatch){
                            connection.query("SELECT * FROM app_users WHERE imei=?", [data.imei], function (err, result) {
                                if (err) throw err;
                                //console.log(result);
                                var ssid = getNewSessionId();

                                if (result.length !== 0){
                                    var info = {
                                        isSuccess : true,
                                        imei : result[0].imei,
                                        email : result[0].email,
                                        username : result[0].username,
                                        password : result[0].password,
                                        fname : result[0].fname,
                                        mname : result[0].mname,
                                        lname : result[0].lname,
                                        birthdate : result[0].birthdate,
                                        contact : result[0].contact,
                                        street : result[0].street,
                                        barangay : result[0].barangay,
                                        municipality : result[0].municipality,
                                        province : result[0].province,
                                        reg_date : result[0].created_at,
                                        ssid: ssid
                                    };
                                    connection.query("UPDATE app_users SET session_id='" + ssid + "' WHERE imei='" + data.imei + "'");
                                    socket.emit('login_user', info);

                                    connection.release();
                                }
                            });
                        }else{
                            socket.emit('login_user', {isSuccess : false});
                            connection.release();
                        }
                    });
                }else{
                    socket.emit('login_user', {isSuccess : false});
                    connection.release();
                }
            });
        });
    }
    function onUserLogin(data){
        console.log(data);
        //check user that login is Command Center
        if (data.name === 'CommandCenter'){
            //set cc.CommandCenter = new socket.id
            cc.CommandCenter = socket.id;
            console.log(cc.CommandCenter);
            pool.getConnection(function(error, connection){
                if (error) throw error;

                connection.query("SELECT alerts.imei, tr.lat, tr.lng, alerts.alert_type, CONCAT(app_users.fname, ' ', app_users.lname) as name, app_users.contact FROM alerts JOIN app_users ON alerts.imei=app_users.imei JOIN tracking tr ON alerts.id=tr.alert_id WHERE alerts.status=0", function(err, result){
                    if (err) throw err;
                    console.log(result)
                    socket.emit('init_alert', result);
                });
                connection.query("SELECT alerts.imei, alerts.alert_type, CONCAT(app_users.fname, ' ', app_users.lname) as name, app_users.contact FROM alerts JOIN app_users ON alerts.imei=app_users.imei WHERE alerts.status=1", function(err, result){
                    if (err) throw err;
                    socket.emit('init_ongoing', result);
                    connection.release();
                });
            });
        }else {
            pool.getConnection(function(error, connection){
                if (error) throw error;
                connection.query("UPDATE action_center SET socket_id='" + socket.id + "' WHERE name='" + data.name + "'", function(err, result){
                    if (err) throw err;
                    console.log(result.affectedRows + " record(s) updated");
                });
                connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname, ' ', app_users.lname) as name, app_users.contact, alert_ac.status FROM alerts JOIN app_users on alerts.imei = app_users.imei LEFT JOIN alert_ac ON alerts.id=alert_ac.alert_id WHERE  alert_ac.ac_id IN (SELECT id FROM action_center WHERE name='" + data.name + "') AND alert_ac.status IN (0,1)", function (err, result) {
                    if (err) throw err;
                    socket.emit('alerts_ac', result);
                });
                connection.release();
            });
        }
    }
    function onDeptLogin(data){
        pool.getConnection(function (error, connection) {
            if (error) throw error;
            connection.query("UPDATE users SET socket_id='" + socket.id + "' WHERE position='" + data.dept + "' AND municipality='" + data.name + "'", function (err) {
                if (err) throw err;
            });
            connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname, ' ', app_users.lname) as name, app_users.contact, alert_dept.status FROM alerts JOIN app_users on alerts.imei = app_users.imei LEFT JOIN alert_dept ON alerts.id=alert_dept.alert_id WHERE  alert_dept.dept_id IN (SELECT id FROM users WHERE municipality='" + data.name + "' AND position='" + data.dept + "') AND alert_dept.status IN (0,1)", function (err, result) {
                //connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname, ' ', app_users.lname) as name, app_users.contact FROM alerts JOIN app_users on alerts.imei = app_users.imei WHERE alerts.id='" + result[j].alert_id + "'", function (err, result) {
                if (err) throw err;
                //console.log(result);
                socket.emit('alerts_ac', result);
            });
            connection.release();
        });
    }
    function onRespLogin(data){
        console.log(data);
        pool.getConnection(function (error, connection) {
            connection.query("SELECT resp.id, dept.department, brgy.barangay, ac.name, dep_id FROM responder resp JOIN department dept ON resp.dep_id=dept.id JOIN barangay brgy ON resp.st_id=brgy.id JOIN action_center ac ON resp.ac_id=ac.id WHERE resp_unique_id='" + data.id + "' AND imei='" + data.imei + "'", function (err, rInfo) {
                if (err) throw err;
                console.log(rInfo);
                if (rInfo.length !== 0){
                    connection.query("SELECT al.imei, ar.status, ar.st_id FROM alert_resp ar JOIN alerts al ON ar.alert_id=al.id WHERE (resp_id=(SELECT id FROM responder WHERE imei='" + data.imei + "') AND ar.status=1) OR (ar.st_id=(SELECT st_id FROM responder WHERE imei='" + data.imei + "') AND ar.status=0 AND ar.resp_id=0) ORDER BY ar.status DESC", function (err, result) {
                        if (err) throw err;
                        if (result.length !== 0){
                            switch (result[0].status){
                                case 1:
                                    socket.join(result[0].imei, function () {
                                        connection.query("SELECT tracking.lat, tracking.lng, alerts.alert_type FROM tracking JOIN alerts ON tracking.alert_id=alerts.id WHERE imei='" + result[0].imei + "' AND status=1 ORDER BY tracking.id DESC LIMIT 1", function(err, result){
                                            if (err) throw err;
                                            console.log("select tr: " + result[0]);
                                            socket.emit('init_track', result[0]);
                                        });
                                        connection.query("UPDATE responder SET socket_id='" + socket.id + "', status='occupied' WHERE id='" + rInfo[0].id + "'", function (err, result) {
                                            if (err) throw err.message;
                                            //console.log(result);
                                            connection.release();
                                        });
                                    });
                                    break;
                                case 0:
                                    connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname,' ',app_users.lname) as name, app_users.contact FROM alerts JOIN app_users on alerts.imei = app_users.imei WHERE alerts.id=(SELECT id FROM alerts WHERE imei='" + result[0].imei + "' AND status=1)", function (err, result) {
                                        if (err) throw err.message;
                                       // console.log(result);
                                        if (result.length !==0){
                                            socket.emit('SEND_RESP', result[0]);
                                        }
                                    });
                                    connection.query("UPDATE responder SET socket_id='" + socket.id + "', status='active' WHERE id='" + rInfo[0].id + "'", function (err, result) {
                                        if (err) throw err.message;
                                        //console.log(result);
                                        connection.release();
                                    });
                                    socket.emit('LOGIN_SUCCESS', {status: 1, rtype: rInfo[0].dep_id, type: rInfo[0].barangay, dept: rInfo[0].department, ac: rInfo[0].name});
                                    break;
                            }
                        }else {
                            connection.query("UPDATE responder SET socket_id='" + socket.id + "', status='active' WHERE id='" + rInfo[0].id + "'", function (err, result) {
                                if (err) throw err.message;
                                //console.log(result);
                                connection.release();
                            });
                            socket.emit('LOGIN_SUCCESS', {  status: 1, rtype: rInfo[0].dep_id, type: rInfo[0].barangay, dept: rInfo[0].department, ac: rInfo[0].name});
                        }
                    });
                }else{
                    connection.release();
                    socket.emit('LOGIN_SUCCESS', {status: 0});
                }
            });
        });
    }
    function onReJoin(data){
        pool.getConnection(function (error, connection) {
            connection.query("SELECT id FROM alerts WHERE imei='" + data + "' AND status IN (0,1)", function (err, result) {
                if (err) throw err;
                if (result.length !== 0){
                    socket.join(data, function(){
                        console.log(data + " was reconnected to the room.");
                    });
                }
                connection.release();
            });
        });
    }
    function onSendNotification(data){
        console.log(data);
        pool.getConnection(function (error, connection) {
           if (error) throw error;
           /*connection.query("INSERT INTO news (')")*/
           if (data.filter !== ""){
               connection.query("SELECT socket_id FROM app_users WHERE municipality='" + data.filter + "' AND socket_id<>''", function (err, result) {
                  if (err) throw err;
                  if (result !== null){
                      delete data.filter;
                      console.log(data);
                      for (var i = 0; i <= result.length - 1; i++){
                          socket.to(result[i].socket_id).emit('receive_notification', data);
                      }
                  }
                  connection.release();
               });
           }else{
               connection.query("SELECT socket_id FROM app_users WHERE socket_id<>''", function (err, result) {
                   if (err) throw err;
                   if (result !== null){
                       delete data.filter;
                       console.log(data);
                       for (var i = 0; i <= result.length - 1; i++){
                           socket.to(result[i].socket_id).emit('receive_notification', data);
                       }
                   }
                   connection.release();
               });
           }
        });
        //socket.broadcast.emit('receive_notification', data);
    }
    function onTrackAlert(room, date){ //change from join to track_alert
        socket.join(room, function() {
            console.log(room);
            console.log('some one connect to the room' + room);
            pool.getConnection(function (error, connection) {
                if (error) throw  error;
                connection.query("SELECT tracking.lat, tracking.lng, alerts.alert_type FROM tracking JOIN alerts ON tracking.alert_id=alerts.id WHERE imei='" + room + "' AND status=1 ORDER BY tracking.id DESC LIMIT 1", function(err, result){
                    if (err) throw err;
                    //console.log(result);
                    if (result.length !== 0){
                        console.log(result[0]);
                        socket.emit('init_track', result[0]);
                    }
                });
                //connection.query("SELECT lat, lng, ar.resp_id FROM tracking_resp tr JOIN alert_resp ar ON tr.ar_id=ar.id WHERE ar.alert_id =(SELECT id FROM alerts WHERE imei='" + room + "' AND status IN (0,1)) AND ar.resp_id<>0 AND status=1 AND tr.id IN (SELECT MAX(id) FROM tracking_resp GROUP BY ar_id)", function (err, result) {
                connection.query("SELECT lat, lng, ar.resp_id AS id FROM tracking_resp tr JOIN alert_resp ar ON tr.ar_id=ar.id WHERE ar.resp_id<>0 AND tr.id IN (SELECT MAX(id) FROM tracking_resp WHERE alert_id =(SELECT id FROM alerts WHERE imei='" + room + "' AND status=1) GROUP BY ar_id)", function (err, result) {
                    if (err) throw err;
                    socket.emit('init_resp', result);
                });
                console.log(socket.id);
                connection.query("UPDATE alert_ac SET status=1, accept_time ='"+ date +"' WHERE alert_id IN (SELECT id FROM alerts WHERE imei='" + room + "' AND status=1) AND ac_id IN (SELECT id FROM action_center WHERE socket_id='" + socket.id + "') AND status=0", function (err, result) {
                    //console.log(result);
                });
                connection.query("UPDATE alert_dept SET status=1, accept_time ='"+ date +"' WHERE alert_id IN (SELECT id FROM alerts WHERE imei='" + room + "' AND status=1) AND dept_id IN (SELECT id FROM users WHERE socket_id='" + socket.id + "') AND status=0", function (err, result) {
                    //connection.query("UPDATE alert_dept SET status=1, accept_time ='?' WHERE alert_id IN (SELECT id FROM alerts WHERE imei='?' AND status=1) AND dept_id IN (SELECT id FROM users WHERE socket_id='?') AND status=0",[date, room, socket.id], function (err, result) {
                    if (err) throw error;
                    //console.log(result);
                });
                connection.release();
            });
        });
    }
    function onRespondAlert(room, date){
        console.log("respond_alert" + room);
        socket.join(room, function() {
            console.log("responder join room");
            pool.getConnection(function (error, connection) {
                if (error) throw error;
                connection.query("UPDATE alert_resp SET status=1, accept_time='"+ date +"', resp_id=(SELECT id FROM responder WHERE socket_id='" + socket.id + "') WHERE alert_id= (SELECT id FROM alerts WHERE imei='" + room + "' AND status=1) AND status=0", function (err, result) {
                    if (err) throw err;
                    //console.log(result);
                    if (result.affectedRows === 1){
                        connection.query("UPDATE responder SET status='occupied' WHERE socket_id='" + socket.id + "'", function (err, result) {
                            if (err) throw err;
                            //console.log(result);
                        });
                        connection.query("SELECT tracking.lat, tracking.lng, alert_type FROM tracking JOIN alerts ON tracking.alert_id=alerts.id WHERE imei='" + room + "' AND status=1 ORDER BY tracking.id DESC LIMIT 1", function(err, result){
                            if (err) throw err;
                            console.log("select tr: " + result[0]);
                            socket.emit('init_track', result[0]);
                        });
                        connection.query("SELECT socket_id FROM responder WHERE st_id=(SELECT id FROM barangay WHERE id=(SELECT st_id FROM responder WHERE socket_id='" + socket.id + "')) AND socket_id != '" + socket.id + "'", function (err, result) {
                            if (err) throw err;
                            //console.log(result);
                            for (var i=0; i<=result.length - 1; i++){
                                socket.to(result[i].socket_id).emit('alert_accepted');
                            }
                        });
                        connection.query("SELECT id FROM responder WHERE socket_id='" + socket.id + "' AND dep_id<>'4'", function (err, result) {
                            if (err) throw err;
                            //console.log(result);
                            if (result.length !== 0){
                                socket.to(room).emit('RESP_ALERT', result[0].id);
                            }
                            connection.release();
                        });
                    }else{
                        socket.emit("message", {message: "Someone already accept alert."});
                        connection.release();
                    }
                });
            });
        });
    }
    function onLocationUpdate(data){
        console.log(data);
        console.log('location_update');
        //send location update to all user inside alert room
        socket.to(data.imei).emit('location_update', data);
        pool.getConnection(function (error, connection) {
            if (error) throw error;
            connection.query("SELECT id FROM alerts WHERE imei='"+ data.imei + "' AND status IN (0,1)", function (err, result) {
                if (err) throw err;
                if (result.length !== 0){
                    connection.query("INSERT INTO tracking (alert_id, lat, lng, time) VALUES (?,?,?,?)",[result[0].id, data.lat, data.lng, data.time], function (err) {
                        if (err) throw err;
                        connection.release();
                    });
                }else{
                    connection.release();
                    socket.emit('end_alert',{});
                }
            });
        });
    }
    function onRespUpdate(data){
        //printLog('RESP_UPDATE', data)
        console.log("RESP_UPDATE:" + data.lat + ", " + data.lng + ", " + data.aimei);
        pool.getConnection(function (error, connection) {
            if (error) throw error.message;
            //console.log("SELECT id, resp_id FROM alert_resp WHERE alert_id IN (SELECT id FROM alerts WHERE imei='" + data.aimei + "' AND status=1) AND resp_id IN (SELECT id FROM responder WHERE imei='" + data.imei + "')");
            connection.query("SELECT id, resp_id FROM alert_resp WHERE alert_id IN (SELECT id FROM alerts WHERE imei='" + data.aimei + "' AND status=1) AND resp_id IN (SELECT id FROM responder WHERE imei='" + data.imei + "')", function (err, result) {
                if (err) throw err.message;
                if (result.length !==0){
                    var newLoc = {
                        id  :   result[0].resp_id,
                        lat :   data.lat,
                        lng :   data.lng
                    };
                    connection.query("INSERT INTO tracking_resp (ar_id, lat, lng, time) VALUES (?,?,?,?)",[result[0].id, data.lat, data.lng, data.time], function (err) {
                        if (err) throw err.message;
                        connection.release();
                    });
                    socket.to(data.aimei).emit('RESP_UPDATE', newLoc);
                }else{
                    connection.release();
                }

            });
        });
    }
    function onSendAlert(data){
        pool.getConnection(function(error, connection) { //create Pool connection to db
            //select query to check and get user info
            connection.query("SELECT CONCAT(app_users.fname, ' ', app_users.lname) as name, contact FROM app_users WHERE imei='" + data.imei + "' AND status=1", function(err, info){
                //if statement if user exist
                if (info.length !== 0){
                    connection.query("SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status IN (0,1)", function (err, result) {
                        if (err) throw err;
                        if (result.length === 0){
                            //console.log(cc.CommandCenter);
                            //create new key name in data
                            data['name'] = info[0].name;
                            data['contact'] = info[0].contact;
                            socket.to(cc.CommandCenter).emit('new_alert', data);
                            console.log('alert: ' + cc.CommandCenter);
                            connection.query("INSERT INTO alerts(imei, lat, lng, alert_type, time) VALUES (?,?,?,?,?) ",[data.imei, data.lat, data.lng, data.alert_type, data.time], function(err){
                                if (err) throw err;
                                connection.query("SELECT id FROM alerts WHERE imei=? AND alert_type=? AND status IN (0,1)", [data.imei, data.alert_type], function (err, result) {
                                    if (err) throw err;
                                    if (result.length !== 0){
                                        connection.query("INSERT INTO tracking (alert_id, lat, lng, time) VALUES (?,?,?,?)", [result[0].id, data.lat, data.lng, data.time], function (err) {
                                            if (err) throw err;
                                            connection.release();
                                        });
                                    }else{
                                        connection.release();
                                    }
                                });
                            });
                            socket.join(data.imei, function(){
                                console.log('room has been created!');
                            });
                        }else{
                            connection.release();
                        }
                    });
                }else{
                    //if user is not  user
                    socket.emit('end_alert',{});
                    connection.release();
                    console.log('IMEI is register not recognized. Someone Infiltrated the system. Intruder Alert!')
                }
            });
        });
    }
    function onAcceptAlert(data){
        pool.getConnection(function(error, connection){
            console.log(data);
            connection.query("UPDATE alerts SET status=1 WHERE imei='" + data + "' AND status=0", function (err, result) {
                //console.log(result);
                connection.release();
            });
        });
    }
    function onSendAlertAc(data){
        var alert_id;
        var ac_id;
        var socket_id;
        pool.getConnection(function(error, connection) {
            connection.query("SELECT id FROM alert_ac WHERE ac_id IN (SELECT id FROM action_center WHERE name='" + data.name + "') AND alert_id IN (SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status IN (0,1))", function (err, result) {
                if (err) throw  err;
                if (result.length === 0){
                    connection.query("Select MAX(alerts.id) as alerts_id from alerts where alerts.imei = '" + data.imei + "' and status=1", function(err, result) {
                        if (err) throw err;
                        if (result.length !==0){
                            alert_id = result[0].alerts_id;

                            connection.query("SELECT id as acs_id, socket_id FROM action_center WHERE name='" + data.name + "'", function (err, result) {
                                //console.log(result);
                                socket_id= result.length === 0 ? 0 : result[0].socket_id;
                                ac_id = result[0].acs_id;
                                connection.query("INSERT INTO alert_ac(alert_id, ac_id, status) VALUES (?,?,?) ",[alert_id, ac_id, 0], function(err){
                                    if (err) throw err;
                                });
                                connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname, app_users.lname) as name, app_users.contact FROM alerts JOIN app_users ON alerts.imei = app_users.imei WHERE alerts.imei='" + data.imei + "' AND alerts.status='1'" , function (err, result) {
                                    //console.log(result);
                                    if (err) throw err;
                                    socket.to(socket_id).emit('send_ac', result[0]);
                                    connection.release();
                                });

                            });
                        }
                    });
                }else{
                    connection.release();
                    socket.emit('already_sent', data);
                }
            });
        });
    }
    function onSendAlertDept(data){
        var sid = [];
        var id = "";
        pool.getConnection(function (error, connection) {
            if(error) throw error;
            /*if (!isInt(data.type)){*/
            connection.query("SELECT id FROM alert_dept WHERE dept_id IN(SELECT id FROM users WHERE municipality='" + data.name + "' AND position='" + data.type + "') AND alert_id IN (SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status IN (0,1))", function (err, result) {
                if (err) throw err;
                if (result.length === 0){
                    connection.query("SELECT id, socket_id FROM users WHERE municipality='" + data.name + "' AND position='" + data.type + "'", function (err, result) {
                        if (err) throw err;
                        //console.log(result);
                        sid = result;
                        id = result[0].id;

                        connection.query("INSERT INTO alert_dept (alert_id, dept_id, status) VALUES ((SELECT IF(id IS NULL,0,id) AS id FROM alerts WHERE imei= '" + data.imei +"' AND status=1),?,?)", [id,0], function (err, result) {
                            if (err) throw err;
                            //console.log(result);
                            connection.release();
                        });
                    });
                    connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname,' ',app_users.lname) as name, app_users.contact FROM alerts JOIN app_users on alerts.imei = app_users.imei WHERE alerts.id=(SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status=1)", function (err, result) {
                        if (err) throw err.message;
                        //console.log(result);
                        for (var i = 0; i<=sid.length-1;i++){
                            socket.to(sid[i].socket_id).emit('send_dept', result[0]);
                        }
                        socket.emit('track_message', {'message': 'Alert is successfully send.'});
                    });
                }else {
                    connection.release();
                    socket.emit('track_message', {'message': 'Alert is already send.'});
                }
            });

        });
    }
    function onSendAlertResp(data){
        var sid = [];
        var st_id;
        pool.getConnection(function(error, connection) {
            if(error) throw error;
            connection.query("SELECT st_id, socket_id FROM responder WHERE st_id=(SELECT id FROM barangay WHERE ac_id=(SELECT id FROM action_center WHERE name='" + data.name + "') AND dept_id='" + data.type + "' AND barangay='"+ data.value + "') AND status='active'", function (err, result) {
                if (err) throw err;
                if (result.length !==0){
                    //console.log(result);
                    st_id = result[0].st_id;
                    sid = result;
                    connection.query("SELECT id FROM alert_resp WHERE resp_id=0 AND status=0 AND status<>2", function (err, result) {
                        if (err) throw error;
                        if (result.length === 0){
                            connection.query("INSERT INTO alert_resp (alert_id, st_id, resp_id, status) VALUES ((SELECT IF(id IS NULL,0,id) AS id FROM alerts WHERE imei= '" + data.imei +"' AND status=1),?,?,?)", [st_id, 0, 0], function (err, result) {
                                if (err) throw err;
                                //console.log(result);
                            });
                            console.log(data.imei);
                        }else    {
                            socket.emit('no_resp', {message: "No responder accept the alert."});
                        }
                        connection.query("SELECT alerts.alert_type, alerts.imei, CONCAT(app_users.fname,' ',app_users.lname) as name, app_users.contact FROM alerts JOIN app_users on alerts.imei = app_users.imei WHERE alerts.id=(SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status=1)", function (err, result) {
                            if (err) throw err.message;
                            //console.log(result);
                            for (var i = 0; i<=sid.length-1;i++){
                                socket.to(sid[i].socket_id).emit('SEND_RESP', result[0]);
                            }
                            connection.release();
                        });
                    });
                }else {
                    connection.release();
                    socket.emit('no_resp', {message: "No available responder."});
                }
            });
            /*connection.query("SELECT alert_type as atype, CONCAT(app.fname, ' ', app.lname) as user, alerts.imei as aimie FROM app_users app JOIN alerts ON app.imei =, alerts.imei WHERE alerts.imei='" + data.imei + "'", function(err, result){
                if (err) throw err;
                console.log(result.length);
                console.log("alertinfo: " + result);
                /!*for (var i = 0; i<=sid.length-1;i++){
                    //socket.to(sid[i].socket_id).emit('SEND_RESP', result);
                }*!/
            });*/
        });
    }
    function onRespRegister(data){
        console.log(data);
        pool.getConnection(function(error, connection) {
            connection.query("INSERT INTO responder(imei, fname, mname, lname, resp_unique_id, st_id,  dep_id, ac_id, status) VALUES ('" + data.imei + "','" + data.fname + "','" + data.mname + "','" + data.lname + "','" + uniqueID() + "',(SELECT id as bid from barangay where barangay = '" + data.st_id + "' and ac_id = (SELECT id from action_center where name = '" + data.act_center + "') and dept_id =(SELECT id from department where department = '" + data.department + "')),(SELECT id from department WHERE department ='" + data.department + "'),(SELECT id from action_center WHERE name ='" + data.act_center + "'),'inactive')", function (err, result){
                if (err) throw err;
                //console.log(result);
                /* flash()->overlay('New Responder Successfully Added!', 'Yay');*/
                connection.release();
            });
        });
    }
    function onIncidentStatus(data){
        socket.to(data.imei).emit('incident_status', data);
        console.log('alert_end: ' + data.imei);
        socket.leave(data.imei, function () {
            pool.getConnection(function (error, connection) {
                socket.emit('end_alert', data.imei);
                connection.query("UPDATE responder SET status='active' WHERE id IN (SELECT resp_id FROM alert_resp WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1)) AND status='occupied'", function (err) {
                    console.log("resp updated");
                    if (err) throw err;
                });
                connection.query("UPDATE alert_resp SET status=2 WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1) AND status IN (0,1)",function (err) {
                    console.log("alert resp updated");
                    if (err) throw err.message;
                    connection.release();
                });
            });
        });
    }
    function onUserRegister(data){
         console.log(data);
        pool.getConnection(function(error, connection) {
            var hashpw = bcrypt.hashSync(data.password);
            connection.query("INSERT INTO app_users(imei, username, email, password, fname, mname, lname, birthdate, contact, street, barangay, municipality, province, status, created_at) VALUES ('" + data.imei + "','" + data.username + "','" + data.email + "','" + hashpw + "','" + data.fname + "','" + data.mname + "','" + data.lname + "','" + data.birthdate + "','" + data.contact + "','" + data.street + "','" + data.barangay + "','" + data.municipality + "','" + data.province + "', 0, '" + data.created_at + "')", function (err, result){
                if (err) throw err;
                var x = Boolean(result.affectedRows !== 0);
                socket.emit("registration_status", {isSuccess:x});
                connection.release();
            });
        });
    }
    function onLoginSms(){
        console.log("sms receiver connected");
        sms.socket_id = socket.id;
    }
    function onCheckRespAlert(data){
        pool.getConnection(function (error, connection) {
            if (error) throw error;
            connection.query("SELECT al.imei FROM alert_resp ar JOIN alerts al ON ar.alert_id=al.id WHERE resp_id=(SELECT id FROM responder WHERE imei='" + data.imei + "') AND ar.status=1", function (err, result) {
                if (result.length !== 0){
                    socket.join(result[0].imei, function () {
                        connection.query("SELECT tracking.lat, tracking.lng, alert_type FROM tracking JOIN alerts ON tracking.alert_id=alerts.id WHERE imei='" + result[0].imei + "' AND status=1 ORDER BY tracking.id DESC LIMIT 1", function(err, result){
                            if (err) throw err;
                            console.log("select tr: " + result[0]);
                            socket.emit('init_track', result[0]);
                            connection.release();
                        });
                    });
                }
            });
        });
    }
    function onRequestEnd(data){
        socket.to(cc.CommandCenter).emit('request_end', data);
        socket.to(data.imei).emit('request_end', data);
    }
    function onAlertEnd(data){
        console.log('alert_end: ' + data.imei);
        socket.leave(data.imei, function () {
            pool.getConnection(function (error, connection) {
                socket.emit('end_alert', data.imei);
                connection.query("UPDATE responder SET status='active' WHERE id IN (SELECT resp_id FROM alert_resp WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1)) AND status='occupied'", function (err) {
                    console.log("resp updated");
                    if (err) throw err;
                });
                connection.query("UPDATE alert_resp SET status=2 WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1) AND status IN (0,1)",function (err) {
                    console.log("alert resp updated");
                    if (err) throw err.message;
                    connection.release();
                });
            });
        });
    }
    function onEndAlert(data){
        console.log("end alert " + data);
        pool.getConnection(function (error, connection) {
            if (error) console.log(error.message);
            /*connection.query("SELECT socket_id FROM action_center WHERE id IN (SELECT ac_id FROM alert_ac WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1))", function (err, result) {
                if (err) throw err;
                console.log(result.length);
                //console.log(result);
                for (var i=0;i<=result.length-1;i++){
                    console.log(result[i]);
                    socket.to(result[i].socket_id).emit('end_alert', data.imei);
                    //io.sockets.connected[result[i].socket_id].leave(data.imei);
                }
            });*/
            connection.query("SELECT socket_id FROM users WHERE id IN (SELECT dept_id FROM alert_dept WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1))", function (err, result) {
                if (err) throw err;
                console.log(result.length);
                //console.log(result);
                for (var i=0;i<=result.length-1;i++){
                    console.log(result[i]);
                    socket.to(result[i].socket_id).emit('end_alert', data.imei);
                }
            });
            connection.query("UPDATE alert_ac SET status=2 WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1) AND status IN (0,1)",function (err, result) {
                console.log("alert ac updated");
                //console.log(result);
                if (err) throw err.message;
            });
            connection.query("UPDATE alert_dept SET status=2 WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1) AND status IN (0,1)",function (err, result) {
                console.log("alert ac updated");
                //console.log(result);
                if (err) throw err.message;
            });
            socket.to(data.imei).emit('end_alert', data.imei);
            connection.query("UPDATE responder SET status='active' WHERE id IN (SELECT resp_id FROM alert_resp WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1)) AND status='occupied'", function (err) {
                console.log("resp updated");
                if (err) throw err;
            });
            connection.query("UPDATE alert_resp SET status=2 WHERE alert_id=(SELECT MAX(id) FROM alerts WHERE imei='" + data.imei + "' AND status=1) AND status IN (0,1)",function (err) {
                console.log("alert resp updated");
                if (err) throw err.message;
            });
             /*var client = io.sockets.adapter.rooms[data.imei].sockets;
             client.leave(data.imei, function (error) {
                 console.log("Success leave room");
             });
             console.log(client);*/

            connection.query("SELECT imei, contact FROM app_users WHERE imei='" + data.imei + "'", function (err, result) {
                if (err) throw err;
                var info = {
                    contact : result[0].contact,
                    imei : result[0].imei
                };
                console.log("send sms");
                console.log(info);
                socket.to(sms.socket_id).emit('stop_tracking', info);
            });
            connection.query("UPDATE alerts SET status=2, end_time='" + data.date + "' WHERE imei='" + data.imei + "' AND status=1",function (err) {
                console.log("alerts updated");
                if (err) throw err.message;
                socket.to(cc.CommandCenter).emit('end_alert', data.imei);
            });
            connection.release();
        });
    }
    function onAutoLogin(data) {
        console.log(data);
        pool.getConnection(function (error, connection) {
            connection.query("SELECT session_id, status FROM app_users WHERE imei='" + data.imei + "'",function (err, result) {
                if (err) throw err;
                if (result !== null){
                    if (result[0].session_id === data.id){
                        connection.query("UPDATE app_users SET socket_id='" + socket.id + "' WHERE imei='" + data.imei + "'", function (err) {
                            if (err) throw err;
                        });
                        connection.query("SELECT id FROM alerts WHERE imei='" + data.imei + "' AND status IN (0,1)", function (err, result) {
                            if (err) throw err;
                            if (result.length > 0){
                                socket.join(data.imei);
                            }
                        });
                    }else{
                        socket.emit('check_user', {isExist: true, isVerify: result[0].status});
                    }
                    connection.release();
                }else{
                    socket.disconnect(true);
                    connection.release();
                }
            });
        });
    }
    function onUserEndAlert(data) {

    }

    function onTrackMessage(data) {
        socket.to(cc.CommandCenter).emit('track_message', data);
        socket.to(data.imei).emit('track_message', data);
    }

    //non-socket event functions
    function uniqueID(){
        var randNum = Math.random();
        var str = randNum.toString(36);
        str = str.substr(2,15);
        for (var i=1;i<=2;i++){
            var index = Math.floor(Math.random() * (str.length + 1));
            str = str.substr(0, index) + '$' + str.substr(index + 1);
        }
        return "_" + str
    }
    function getNewSessionId(){
        //return crypto.randomBytes(64).toString('hex');
        var ssid="";
        for(var b=0; 60>b; b++){
            var c = Math.floor(62 * Math.random() + 1);
            10 >= c ? c += 47 : 36 >= c ? c += 54 : 62 >= c && (c += 60);
            ssid += String.fromCharCode(c);
        }
        return ssid;
    }
    function isInt(value){
        var x;
        if (isNaN(value)) {
            return false;
        }
        x = parseFloat(value);
        return (x | 0) === x;
    }
});
