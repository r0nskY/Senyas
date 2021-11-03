var map;
var geocoder;
var initLoc;
var markers = [];
var socket = io.connect('http://45.32.107.230:8080');
var alertSound;

function initMap() {
    geocoder = new google.maps.Geocoder();
    var initLoc = {lat: 13.4324, lng: 123.4115};
    this.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: initLoc,
        mapTypeId: 'hybrid',
        labels: true
    });
}

socket.on('new_alert', function(data) {
  console.log(data);
  alert(data, 'alert-container');
  displayModal("New alert has been received.")
  updateMarker(data);
});

socket.on('init_alert', function(data) {
  console.log(data);
  for (var i = 0; i<=data.length - 1; i++){
    alert(data[i],'alert-container');
    updateMarker(data[i]);
}
});

socket.on('track_message', function (data) {
    displayModal(data.message);
});

socket.on('init_ongoing', function(data) {
  //console.log(data);
  for (var i = 0; i<=data.length - 1; i++){
    ongoing(data[i], 'ongoing-container');
}
});
socket.on('updateLocation', function(data){
    updateMarker(data);
/*addMarker(myLatLng);
showMarkers(map);*/

//alert();

});

function change_alert(child, container) {
    child.getElementsByTagName('div')[0].className = 'note-ongoing';
    document.getElementById(container).appendChild(child);
}

// alert -> ongoing_container
function ongoing(data, container) {
    var child = document.getElementById(data.imei);
    if (child === null){
        var div = document.createElement('div');
        var anchor = document.createElement('a');
        anchor.setAttribute('href','http://45.32.107.230/irigacity/trackLocation/'+data.imei);
        anchor.setAttribute('id', data.imei);
        anchor.setAttribute('target', '_blank');
        anchor.setAttribute('onclick','onClickEv(this)');
        anchor.setAttribute('class', 'alert-notif');
        div.textContent = data.contact +" "+ data.name + " " + data.alert_type;
        div.setAttribute('class', 'note-ongoing');
        anchor.appendChild(div);

        document.getElementById(container).appendChild(anchor);
    }
}
//new alert -> alert-container
function alert(data, container) {
   var child = document.getElementById(data.imei);
    if (child === null){
        var div = document.createElement('div');
        var anchor = document.createElement('a');
        anchor.setAttribute('href','http://45.32.107.230/irigacity/trackLocation/' + data.imei);
        anchor.setAttribute('id', data.imei);
        anchor.setAttribute('target', '_blank');
        anchor.setAttribute('onclick','onClickEv(this)');
        anchor.setAttribute('class', 'alert-notif')
        div.textContent = data.contact + " " + data.name + " " + data.alert_type;
        div.setAttribute('class', 'note');
        anchor.appendChild(div);

        document.getElementById(container).appendChild(anchor);
        switch (data.alert_type) {
            case 'GENERAL':
                alertSound = new Audio('../sound/general.mp3');
                alertSound.play();
                break;
            case 'FIRE':
                alertSound = new Audio('../sound/fire.mp3');
                alertSound.play();
                break;
            case 'MEDICAL':
                alertSound = new Audio('../sound/medical.mp3');
                alertSound.play();
                break;
            case 'HIGHRISK':
                alertSound = new Audio('../sound/highrisk.mp3');
                alertSound.play();
                break;
            case 'POLICE':
                alertSound = new Audio('../sound/police.mp3');
                alertSound.play();
                break;
        }
       alertSound.onended = function () {
           if (isHasAlert()) {
               alertSound.play();
           }
       }
    }
  
  //$('#alert-container').html(html);
}

socket.on('connect', function () {
    socket.emit('user_login', {name : 'CommandCenter'});
});

function logout(){
  socket.emit('disconnect');
}

function removeMarker(data){
  for(var i = 0;i<=markers.length-1;i++){
    if (markers[i].id === data){
        markers[i].setMap(null);
        markers.splice(i,1);
        var len = markers.length;
        break;
    }
  }
}

function updateMarker(data){
  //this.map.setCenter({lat:this.lat, lng:this.long, alt:0});
  console.log("lat: " + data.lat + " lng: " + data.lng);
  var mLatLng = new google.maps.LatLng(data.lat, data.lng);
  var marker =  new google.maps.Marker({
      position: mLatLng,
      map: this.map,
      icon: getMarkerIcon(data.alert_type),
      id: data.imei
  });
  //marker.metadata = {id: data.imei};
  marker.addListener('click', function() {
    displayModal(marker.get('id'));
  });
    alertGeocoder(geocoder, map, marker);
  markers.push(marker);
}

function onClickEv(child) {
    change_alert(child, 'ongoing-container');
    socket.emit('accept_alert', child.id);
    removeMarker(child.id);
    if (!isHasAlert()){
        alertSound.pause();
        alertSound.currentTime = 0;
    }
  //document.getElementById('alert-container').removeChild(child);
}

socket.on('end_alert', function (data) {
    var child = document.getElementById(data);
    document.getElementById('ongoing-container').removeChild(child);
});

socket.on('request_end', function (data) {
    if (data !== null){
        displayModal("Requesting to End Alert for " + data.name);
    }
});

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

function isHasAlert() {
    var ao =  document.getElementById('alert-container');
    var alrt = ao.getElementsByTagName('a');
    return alrt.length !==0;
    //for (var e in document.get)
}

function alertGeocoder(geocoder, map, marker) {
    var infoWindow = new google.maps.InfoWindow();
    geocoder.geocode({'location': marker.position}, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                //map.setZoom(11);
                infoWindow.setContent(results[0].formatted_address);
                infoWindow.open(map, marker);
            } else {
                displayModal('No results found');
            }
        } else {
            displayModal('Geocoder failed due to: ' + status);
        }
    });
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
    };
    // console.log(request);
    socket.emit('resp_reg', request);
    /*var div = document.getElementById(imei);
     div.innerHTML = div.innerHTML + '<br><a class="btn btn-success"></a>';*/
}

function displayModal(content) {
    document.getElementById('mt').textContent = "Senyas Iriga";
    document.getElementById('mc').textContent = content;
    $('#alertModal').modal('show');
}