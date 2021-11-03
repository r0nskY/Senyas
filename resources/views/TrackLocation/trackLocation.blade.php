<!DOCTYPE html>
<html>
<head>
     <title>SENYAS - Alert Tracking</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <link href="/css/tracklocation.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

</head>

<body>
        <!-- Modal-->
        <div class="modal fade" id="alertModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                        <h4 class="modal-title" id="mt"></h4>

                    </div>
                    <div class="modal-body">
                        <p id="mc"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}
                    <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Medical</h4>
                </div>
                <div class="modal-body">
                    @foreach($med as $meditem)

                        @if($meditem->status=="active")
                            <div  class = "alert-brgy1">
                                <li> <a id ="{{$meditem->dep_id}}" onClick="dispatch(this)" class = "btn btn-info alert-brgy1-btn " value ="{{$meditem->barangay}}">{{$meditem->barangay}}</a></li>
                                {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                            </div>
                        @elseif($meditem->status=="occupied")
                            <div  class = "alert-brgy1">
                                <li> <a id ="{{$meditem->dep_id}}" onClick="dispatch(this)" class = "btn btn-success alert-brgy1-btn " value ="{{$meditem->barangay}}">{{$meditem->barangay}}</a></li>
                                {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                            </div>
                        @else
                            <div  class = "alert-brgy1">
                                <li> <a id ="{{$meditem->dep_id}}" onClick="dispatch(this)" class = "btn btn-danger alert-brgy1-btn " value ="{{$meditem->barangay}}">{{$meditem->barangay}}</a></li>
                                {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                            </div>
                        @endif


                        {{--  <button id="1" style = "margin-top:15px;" type="button" name="submit" class="form-control btn-danger" onclick="dispatch(this)" data-dismiss="modal" value ="{{$meditem->barangay}}">{{$meditem->barangay}}</button>--}}
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class = "flex-container">
    <div class = "sidebar-track">
        <div class = "dispatch-sidebar-header color1">
            <span>Alert Info</span>
        </div>
        <div class = "dispatch2">
            <div class = "center container" style="padding:10px">
                <div>
                    @foreach($alertinfo as $alertinfo1)
                    <span class="h4">Name: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp {{$alertinfo1->fname}} {{$alertinfo1->mname}} {{$alertinfo1->lname}}</span><br>
                    <span class="h4">Address: &nbsp &nbsp &nbsp {{$alertinfo1->street}}, {{$alertinfo1->barangay}}</span><br>
                    <span class="h4">Contact: &nbsp &nbsp &nbsp {{$alertinfo1->contact}}</span><br>
                    <span class="h4">Alert Type:&nbsp {{$alertinfo1->alert_type}}</span><br>
                    <span class="h4">Time: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp {{$alertinfo1->time}}</span><br>

                    @endforeach
                </div>
            </div>
            </div>
        <div class = "dispatch-sidebar-header color1">
            <span>Dispatch</span>
        </div>
        <div class = "dispatch ">
            <div class = "center checkboxes">
                <li><a  id='medical' data-toggle="modal" data-target="#myModal2" class="btn btn-danger dispatch-btn"><img src = "/img/medical.png" class = "img-responsive"/><span>Medical</span></a></li>
            </div>
            <div class = "center checkboxes">
              <li><a  id='fire'   onClick="sendToDept(this)" class="btn btn-danger dispatch-btn"><img src = "/img/fire.png" class = "img-responsive"/><span>Fire</span></a></li>
            </div>
            <div class = "center checkboxes">
               <li> <a  id='police' onClick="sendToDept(this)" class="btn btn-danger dispatch-btn">  <img src = "/img/police.png" class = "img-responsive"/> <span>POLICE</span> </a> </li>
            </div>

           {{-- <div class = "button-container nopadding">
                <button class="btn dispatch-btn" onclick="send()">Alert Action Centers</button>
            </div>--}}
        </div>
        <div class = "sidebar-track-brgy">
            <div class = "dispatch-sidebar-header color1">
                <span>Barangay</span>
            </div>

        </div>
        <div class = "dispatch3">
            @foreach($brgy as $item)

                <div  class = "alert-brgy1">
                    <li> <a id="4" class="btn btn-info alert-brgy1-btn" value="{{$item->barangay}}" onclick="dispatch(this)">{{$item->barangay}}</a></li>

                </div>
            @endforeach

            {{-- <div class = "button-container nopadding">
                 <button class="btn dispatch-btn" onclick="send()">Alert Action Centers</button>
             </div>--}}
        </div>


    </div>
    <div class = "map-container-track">
        {{--<div class = "control-panel">


        </div>--}}
        <div class = "endbtn1">
            <ul>
                <li> <a  id='END' onClick="end()" class="btn btn-danger"><span>END TRACKING</span><img src = "/img/end.png" /></a></li>
            </ul>
        </div>

        <div id="map">
        </div>

        {{--  <div id="map">

          </div>--}}


       {{-- <div  class = "incident-timeline">
            <div class = "info-header" id = "details-header">
                <span>Incident Timeline</span>
            </div>
            <div id ="incident-timeline-content">
                <table id="incident-timeline-table" class = "compact row-border">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Origin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>17:55</td>
                            <td>Emergency Alarm Received</td>
                            <td>Caller</td>
                        </tr>
                        <tr>
                            <td>17:56</td>
                            <td>Emergency Team Dispatched</td>
                            <td>Response Team</td>
                        </tr>
                        <tr>
                            <td>17:58</td>
                            <td>Location Changed</td>
                            <td>Caller</td>
                        </tr>
                        <tr>
                            <td>17:59</td>
                            <td>Location Changed</td>
                            <td>Caller</td>
                        </tr>
                        <tr>
                            <td>18:02</td>
                            <td>Location Changed</td>
                            <td>Caller</td>
                        </tr>
                        <tr>
                            <td>18:03</td>
                            <td>Rescue Team Arrival</td>
                            <td>Response Team</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>--}}
    </div>
    <script src="http://45.32.107.230:8080/socket.io/socket.io.js"></script>
    <script>
        var imei = '{{$imei}}';
       var act_center = '{{$acname}}';

    </script>
    <script>  $('#flash-overlay-modal').modal();</script>
    <script src="/js/track.js"></script>
    <!--script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script-->
    <script src="//code.jquery.com/jquery.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAIC8AGTDYajtF7ps1F4BzHSP8o4-W8_I&callback=initMap"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>


</html>
