var map;
var initLoc;
var marker;
var socket = io.connect('http://45.32.107.230:8080');
var rMarker = [];
var imei = null;
var alertSound;

//socket.emit('user_login', {name : act_center});

socket.on('send_ac', function (data) {
    console.log(data);
    marker.setIcon(getMarkerIcon(data.alert_type));
    display_alert(data);
    alert("NEW ALERT");
});

socket.on('connect', function () {
    socket.emit('user_login', {name : act_center});
    if ( imei !== null){
        alertClick(imei);
    }
});

socket.on('already_sent', function (data) {
    alert("Alert is already sent to " + data.type.toUpperCase());
});

socket.on('init_track', function (data) {
    marker.setIcon(getMarkerIcon(data.alert_type));
    var mLatLng = new google.maps.LatLng(data.lat, data.lng);
    map.setCenter(mLatLng);
    map.setZoom(18);
    updateMarker(mLatLng);
});
socket.on('init_resp', function (data) {
    for (var mrk in rMarker){
        rMarker[mrk].setMap(null);
    }
    rMarker = [];
    if (data.length !== 0){
        for (var i=0; i<=data.length-1;i++){
            var mLatLng = new google.maps.LatLng(data[i].lat,data[i].lng);
            var newRMrk = new google.maps.Marker({
                position : mLatLng,
                map  : map,
                icon : "../img/ambulance.png",
                id   :data[i].id
            });
            rMarker.push(newRMrk);
        }
    }
});

socket.on('alerts_ac', function (data) {
    //console.log(data);
    var child;
    for (var i = 0; i<=data.length - 1; i++){
        console.log(data[i]);
        child = document.getElementById(data[i].imei);
        if (child === null) {
            display_alert(data[i], 'alert-container');
        }
    }
});

//initialize map
function initMap() {
    initLoc = {lat: 13.4324, lng: 123.4115};
    this.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: initLoc,
        mapTypeId: 'hybrid',
        labels: true
    });
    this.marker = new google.maps.Marker({
        map: this.map
    });
    marker.addListener('click', function() {
        alert(marker.id);
    });
}


function updateMarker(data){
    //this.map.setCenter({lat:this.lat, lng:this.long, alt:0});
    //console.log("lat: " + data.lat + " lng: " + data.lng);
    this.marker.setPosition(data);
    marker.id = imei;
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

function change_alert(id) {
    document.getElementById(id).className= 'note-ongoing';
}

function display_alert(data){
    var child = document.getElementById(data.imei);
    if (child === null){
        var div = document.createElement('div');
        var mybr = document.createElement('br');
        div.textContent = data.contact + " " + data.name + " " + data.alert_type;
        div.setAttribute('onclick','alertClick(this.id)');
        if (data.status !== 1) {
            div.setAttribute('class', 'note');
        }else {
            div.setAttribute('class', 'note-ongoing');
        }

        div.setAttribute('id', data.imei);
        document.getElementById('alert-container').appendChild(div);
        if (data.status !== 1){
            switch (data.alert_type) {
                case 'GENERAL':
                    alertSound =new Audio('/sound/general.mp3');
                    alertSound.play();
                    break;
                case 'FIRE':
                    alertSound = new Audio('/sound/fire.mp3');
                    alertSound.play();
                    break;
                case 'MEDICAL':
                    alertSound = new Audio('/sound/medical.mp3');
                    alertSound.play();
                    break;
                case 'POLICE':
                    alertSound = new Audio('/sound/police.mp3');
                    alertSound.play();
                    break;
                case 'HIGHRISK':
                    alertSound = new Audio('/sound/highrisk.mp3');
                    alertSound.play();
                    break;
            }
        }
        if (alertSound !== undefined){
            alertSound.onended = function () {
                if (isHasAlert()) {
                    alertSound.play();
                }
            }
        }
    }
}

function alertClick(id){
    //alert(id);
    change_alert(id);
    socket.emit('track_alert', id, formatDate(new Date()));
    //socket.emit('count');
    imei = id;
    if (isHasAlert()){
        alertSound.pause();
        alertSound.currentTime = 0;
    }
   /* var div = document.getElementById(id);
    div.innerHTML = div.innerHTML + '<a class="btn btn-success"></a>';*/
}

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
            icon : "../img/ambulance.png",
            id  : data
        });
        rMarker.push(newRMrk);
    }
});
socket.on('RESP_UPDATE',function(data){
    console.log(data);
    updateResponderMarker(data);
});

socket.on('end_alert', function (data) {
    var child = document.getElementById(data);
    if (data === imei){
        marker.setPosition(null);
        for (var mrk in rMarker){
            rMarker[mrk].setMap(null);
        }
        map.setCenter(initLoc);
        map.setZoom(14);
        rMarker = [];
        imei = null;
    }

    if (child !== null){
        document.getElementById('alert-container').removeChild(child);
    }
});

function isHasAlert() {
    var ao =  document.getElementById('alert-container');
    var alrt = ao.getElementsByClassName('note');
    return alrt.length > 0;
    //for (var e in document.get)
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

socket.on('location_update',function(data){
    var mLatLng = new google.maps.LatLng(data.lat, data.lng);
    console.log(data);
    updateMarker(mLatLng);
});

socket.on('incident_status', function (data) {
    var res = document.getElementById(data.imei);
    if (res !== null){
        var name = res.textContent.split(" ");
        alert("Barangay confirm alert for " + name[1] + " " + name[2]);
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
function getMarkerIcon(type) {
    var path = "";
    switch (type){
        case "GENERAL":
            path= "../img/rescue-2.png";
            break;
        case "FIRE":
            path= "../img/fire_alert.png";
            break;
        case "MEDICAL":
            path= "../img/medical_alert.png";
            break;
        case "POLICE":
            path= "../img/police_alert.png";
            break;
        case "HIGHRISK":
            path= "../img/highrisk_alert.png";
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