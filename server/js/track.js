/**
 * Created by Adsoph on 7/8/2017.
 */

var map;
var initLoc;
var marker;
var socket = io.connect('http://45.77.43.232:8080');

//initialize map
function initMap() {
    var initLoc = {lat: 14.1390, lng: 122.7633};
    this.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: initLoc
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
        alert(marker.get('id'));
    });
}


//Join room
socket.emit('join', imei);
//socket.emit('count');


function send(){
    var checkboxes = document.getElementsByName("ac[]");
    var arr = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked === true) {
            arr.push(checkboxes[i].value);
        }
    }
    socket.emit('send_alert_ac', {imei : imei, name : arr[0]});

    alert('Alert has been sent');
    console.log(arr[0]);
}



socket.on('location_update',function(data){
    console.log(data)
    updateMarker(data);
});


