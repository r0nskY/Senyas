/**
 * Created by Adsoph on 7/8/2017.
 */

var map;
var initLoc;
var marker;
var socket = io.connect('http://45.32.107.230:8080');
var rMarker = [];

//initialize map
/*function initMap() {
    var initLoc = {lat: 14.1390, lng: 122.7633};
    this.map = new google.maps.Map(document.getElementById('map'), {
        mapTypeId: 'hybrid',
        labels: true
    });

}*/
function initMap() {
    var initLoc = {lat: 13.4324, lng: 123.4115};
    this.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: initLoc,
        mapTypeId: 'hybrid',
        labels: true
    });
    this.marker = new google.maps.Marker({
        map: this.map
    });
}

function updateMarker(data){
    //this.map.setCenter({lat:this.lat, lng:this.long, alt:0});
    console.log("lat: " + data.lat + " lng: " + data.lng);
    var mLatLng = new google.maps.LatLng(data.lat, data.lng);
    this.marker.setPosition(mLatLng);
    //marker.metadata = {id: data.imei};
    marker.addListener('click', function() {
        alert(marker.id);
    });
}

function updateResponderMarker(data){
    //this.map.setCenter({lat:this.lat, lng:this.long, alt:0});
    var rMrk;
    console.log("lat: " + data.lat + " lng: " + data.lng);
    var mLatLng = new google.maps.LatLng(data.lat, data.lng);
    for (var i=0; i <= rMarker.length-1;i++){
        console.log(data.id + "==" + rMarker[i].id);
        if (data.id === rMarker[i].id){
            rMrk = rMarker[i];
            break;
        }
    }
    rMrk.setPosition(mLatLng);
    //marker.metadata = {id: data.imei};
    rMrk.addListener('click', function() {
        alert(rMrk.id);
    });
}
socket.on('connect', function () {
    //Join room
    socket.emit('track_alert', imei);
});

socket.on('init_track', function (data) {
    marker.id = imei;
    marker.setIcon(getMarkerIcon(data.alert_type));
    var mLatLng = new google.maps.LatLng(data.lat, data.lng);
    map.setCenter(mLatLng);
    map.setZoom(13);
    updateMarker(data);
});

socket.on('init_resp', function (data) {
    console.log(data);
    var isMarkerExist;
    for (var i=0; i<=data.length-1;i++){
        isMarkerExist = false;
        var mLatLng = new google.maps.LatLng(data[i].lat,data[i].lng);
        for (var mrk in rMarker){
            if (data[i].id === mrk.id){
                isMarkerExist = true;
                break;
            }
        }
        console.log("Responder id:" + data[i].id);
        if (!isMarkerExist){
            var newRMrk = new google.maps.Marker({
                position : mLatLng,
                map  : map,
                icon : "../../img/ambulance.png",
                id   : data[i].id
            });
            rMarker.push(newRMrk);
        }
    }
});
//socket.emit('count');

function end() {
    socket.emit('end_alert', {imei:imei,date:formatDate(new Date())});
    console.log(new Date());
    console.log(formatDate(new Date()));
    close();
}


function send(){
    var checkboxes = document.getElementsByName("ac[]");
    var arr = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked === true) {
            arr.push(checkboxes[i].value);
            //socket.emit('send_alert_ac', {imei : '{{$imei}}', name : arr[i]});
        }
    }
    var result = socket.emit('send_alert_ac', {imei : imei, name : arr[0]});
    console.log(result);
    if (arr.length !== 0){
        displayModal('Alert has been sent');
    }else{
        displayModal('No alert center selected!');
    }

    console.log(arr[0]);
}

socket.on('already_sent', function (data) {
    displayModal("Alert is already sent to " + data.name.toUpperCase());
});

socket.on('callback', function (data) {
    displayModal(data);
});

socket.on('RESP_ALERT', function (data) {
    var isMarkerExist = false;
    console.log(data);
    for (var i=0;i<=rMarker.length-1;i++){
        if (data.id === rMarker[i].id){
            isMarkerExist = true;
            break;
        }
    }
    if (!isMarkerExist){
        var newRMrk = new google.maps.Marker({
            map : map,
            icon : "/img/ambulance.png",
            id  : data
        });
        rMarker.push(newRMrk);
    }
});
socket.on('location_update',function(data){
    console.log(data);
    updateMarker(data);
});
socket.on('RESP_UPDATE',function(data){
    console.log(data);
    updateResponderMarker(data);
});

socket.on('track_message', function (data) {
    displayModal(data.message);
});

socket.on('incident_status', function (data) {
    var res = document.getElementById(data.imei);
    if (res !== null){
        var name = res.textContent.split(" ");
        displayModal("Barangay confirm the alert for " + name[1] + " " + name[2]);
    }
});

socket.on('already_sent', function (data) {
    displayModal("Alert is already sent to " + data.type.toUpperCase());
});

socket.on('request_end', function (data) {
    if (data !== null){
        displayModal("Requesting to End Alert for " + data.name);
    }
});

function formatDate(date) {
    return  date.getFullYear() + "-" +
            (date.getMonth() + 1) + "-" +
            date.getDate() + " " +
            date.getHours() + ":" +
            date.getMinutes() + ":" +
            date.getSeconds();
}

function submit1(){
    var fname = document.getElementById('fname').value;
    var mname = document.getElementById('mname').value;
    var lname = document.getElementById('lname').value;
    var imei = document.getElementById('imei').value;
    var department = document.getElementById('slctdept');
    var depVal=department.options[department.selectedIndex].value;
    var type = document.getElementById('slcttype');
    var typeVal=type.options[type.selectedIndex].value;

    var request = {
        mname : mname,
        lname : lname,
        fname : fname,
        imei  : imei,
        department : depVal,
        st_id : typeVal,
        act_center : act_center
    }
    // console.log(request);
    socket.emit('resp_reg', request);
    /*var div = document.getElementById(imei);
     div.innerHTML = div.innerHTML + '<br><a class="btn btn-success"></a>';*/
}


function dispatch(resp){
    var data = {
        imei: imei,
        name: act_center,
        type: resp.id,
        value: resp.textContent
    };
    if (data.imei !== null){
        socket.emit('send_alert_resp', data);
    }else{
        alert("No Alert to send to " + resp.textContent + " Responders.");
    }

    /*var div = document.getElementById(imei);
    div.innerHTML = div.innerHTML + '<br><a class="btn btn-success"></a>';*/
}

function sendToDept(dept){
    var data = {
        imei: imei,
        name: act_center,
        type: dept.id
    };
    if (data.imei !== null){
        socket.emit('send_alert_dept', data);
    }else{
        alert("No Alert to send to " + dept.textContent + " Department.");
    }
}

function getMarkerIcon(type) {
    var path = "";
    switch (type){
        case "GENERAL":
            path= "../../img/rescue-2.png";
            break;
        case "FIRE":
            path= "../../img/fire_alert.png";
            break;
        case "MEDICAL":
            path= "../../img/medical_alert.png";
            break;
        case "POLICE":
            path= "../../img/police_alert.png";
            break;
        case "HIGHRISK":
            path= "../../img/highrisk_alert.png";
            break;
        case "P":
            path= "../../img/rescue-2.png";
            break;
    }
    return path;
}

function getRMarkerIcon(type) {
    var path = "";
    switch (type){
        case "1":
            path= "/img/ambulance.png";
            break;
        case "2":
            path= "/img/ambulance.png";
            break;
        case "3":
            path= "/img/ambulance.png";
            break;
        case "4":
            path= "/img/ambulance.png";
            break;
    }
    return path;
}

function sendInfo(){
    var imgUrl = document.getElementById("image").src;
    var title = document.getElementById("infoTitle").value;
    var content = document.getElementById("infoContent").value;

    var info = {
        image : imgUrl,
        title : title,
        content : content,
        filter: act_center
    };

    socket.emit("send_notification", info);
}

function displayModal(content) {
    document.getElementById('mt').textContent = "Senyas Iriga";
    document.getElementById('mc').textContent = content;
    $('#alertModal').modal('show');
}
