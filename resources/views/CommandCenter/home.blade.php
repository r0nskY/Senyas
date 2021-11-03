<!DOCTYPE html>
<html>
<head>
    <title>CNEAS Command Center</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet" type="text/css">

</head>
<body>

<!--         <div class="col-md-4">
            <div id="alert-container" style="background-color: grey; width: 500px; height: 500px;" >
            </div>
        </div>

        <div class="col-md-4">
            <div class="content">
                <div id="map" style="height: 500px; width:100%;"></div>
            </div>
        </div>
    -->

    <div class = "flex-container">
        <div class = "sidebar">
            <div class = "sidebar-header color1">
                <span>alerts</span>
            </div>
            <div id = "alert-container" class = "notif-container">

            </div>

            <div class = "sidebar-header color1">
                <span>ongoing</span>
            </div>

            <div id = "ongoing-container" class = "notif-container">

            </div>
            {{--<div class = "sidebar-header color1">
                <span>completed</span>
            </div>
            <div id = "completed-container" class = "notif-container">

            </div>--}}
        </div>
        <div class = "map-container">
            <div class = "control-panel">
                <ul>
                    <li><a href="/reports"><i class="fa fa-fw fa-list control-panel-item"></i></a></li>
                    <li><a href="/app_user"><i class="fa fa-fw fa-users control-panel-item"></i></a></li>

                    <li><a href="/logout" onclick="logout()"><i class="fa fa-fw fa-times control-panel-item"></i></a></li>
                </ul>
            </div>
            <div id="map"></div>

        </div>
    </div>

    <script src="http://localhost:8080/socket.io/socket.io.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="../js/event.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCl4XLR4R8MXOt_8LyJjcjxYoHQeJmb7mw&callback=initMap"></script>
</body>
</html>
