var map;
var initLoc;
var markers = [];
var socket = io.connect('http://45.77.43.232:8080');

//sounds
var highrisk= new Audio('../sound/highrisk.mp3');
var general = new Audio('../sound/general.mp3');
var fire = new Audio('../sound/fire.mp3');
var police = new Audio('../sound/police.mp3');
var medical = new Audio('../sound/medical.mp3');

socket.emit('user_login', {name : 'CommandCenter'});

function initMap() {
    var initLoc = {lat: 14.1390, lng: 122.7633};
    this.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: initLoc
    });
}


socket.on('new_alert', function(data) {
  console.log(data);
  alert(data, 'alert-container');
  updateMarker(data);
});

socket.on('init_alert', function(data) {
  //console.log(data);
  for (var i = 0; i<=data.length - 1; i++){
    alert(data[i],'alert-container');
    updateMarker(data[i]);
  }
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

// alert -> ongoing_container
function ongoing(data, container) {
  var div = document.createElement('div');
  var anchor = document.createElement('a');
  anchor.setAttribute('href','/trackLocation/'+data.imei);
  anchor.setAttribute('id', data.imei);
  anchor.setAttribute('target', '_blank');
  anchor.setAttribute('onclick','onClickEv(this)');
  anchor.setAttribute('class', 'alert-notif')
  div.textContent = data.imei +" "+ data.name + " " + data.emtype;
  div.setAttribute('class', 'note');
  anchor.appendChild(div);

  document.getElementById(container).appendChild(anchor);
}
//new alert -> alert-container
function alert(data, container) {
  var div = document.createElement('div');
  var anchor = document.createElement('a');
  anchor.setAttribute('href','/trackLocation/'+data.imei);
  anchor.setAttribute('id', data.imei);
  anchor.setAttribute('target', '_blank');
  anchor.setAttribute('onclick','onClickEv(this)');
  anchor.setAttribute('class', 'alert-notif')
  div.textContent = data.imei +" "+ data.name + " " + data.alert_type;
  div.setAttribute('class', 'note');
  anchor.appendChild(div);

  document.getElementById(container).appendChild(anchor);

  switch (data.alert_type) {
    case 'GENERAL':
      general.play();
      break;
    case 'FIRE':
      fire.play();
      break;
    case 'MEDICAL':
      medical.play();
      break;
    case 'HIGHRISK':
      highrisk.play();
      break;
  }
  //$('#alert-container').html(html);
}

function logout(){
  socket.emit('disconnect');
}

function removeMarker(data){
  for(var i = 0;i<=markers.length-1;i++){
    if (markers[i].id == data){
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
      id: data.imei
  });
  //marker.metadata = {id: data.imei};
  marker.addListener('click', function() {
    alert(marker.get('id'));
  });
  markers.push(marker);
}

function onClickEv(child) {
  socket.emit('accept_alert', child.id);
  removeMarker(child.id);
  //alert(child, 'ongoing-container');
  document.getElementById('alert-container').removeChild(child);
}
