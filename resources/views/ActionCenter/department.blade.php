<!DOCTYPE HTML>

<html>
<head>
    <script src="//code.jquery.com/jquery.js"></script>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <title>SENYAS Department</title>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <link href="/css/actioncenter.css" rel="stylesheet" type="text/css">
    {{-- <link href="../css/tracklocation.css" rel="stylesheet" type="text/css">--}}
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *; style-src 'self' 'unsafe-inline' *; img-src * data: 'unsafe-inline'">

</head>

<body>
{{--<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}
                    <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Responder</h4>
                </div>
                <div class="modal-body">

                    <input type = "text" id = "fname" class="form-control" placeholder="First Name">

                    <input style = "margin-top:15px;" type = "text" id = "mname" class="form-control" placeholder="Middle Name">

                    <input style = "margin-top:15px;" type = "text" id = "lname"  class="form-control" placeholder="Last Name">

                    <input style = "margin-top:15px;" type = "text" id = "imei"  class="form-control" placeholder="Gadget IMEI" maxlength="15">

                    <select id = "slcttype" style = "margin-top:15px;" name = "type" class="form-control">
                        <option  disabled selected value="">Station</option>
                        @foreach($resp1 as $item)
                            <option value="{{$item->barangay}}">{{$item->barangay}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="modal-footer">
                    --}}{{--  // <input type="submit" name="submit" class="btn btn-info" value="SA VE">--}}{{--

                    <button type="button" name="submit" class="btn btn-info" value="SAVE" onclick="submit1()" data-dismiss="modal">SAVE</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>--}}
<!-- Modal -->
        <div class="modal fade" id="alertModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mt"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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

<div id="myModal1" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}
                    <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Password</h4>
                </div>
                <div class="modal-body">

                    <input type = "password" name = "old_pass" class="form-control" placeholder="Type Old Password">

                    <input style = "margin-top:15px;" type = "password" name = "New_Password" class="form-control" placeholder="New Password">

                    <input style = "margin-top:15px;" type = "password" name = "Confirm_Pass"  class="form-control" placeholder="Confirm New Password">


                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" class="btn btn-info" value="SAVE">
                    {{--<button type="button" name="submit" class="btn btn-info" value="submit" onclick="submit">SAVE</button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class = "flex-container">
    <div class = "sidebar">
        <div class = "sidebar-header color1">
            <span>ALERTS</span>
        </div>
        {{--<div id="alert-container" class = "notif-container">
        </div>--}}
        <div class = "sidebar-alert-container">
            <div id="alert-container" class = "alert-action">
            </div>
        </div>
        <div class = "sidebar-header color1">
            <span>Station</span>
        </div>

        <div class = "sidebar-alert-brgy">

            @foreach($resp as $item)
               @if($item->status=="active")
                    <div  class = "alert-brgy1">
                        <li> <a id ="{{$item->dep_id}}" onClick="dispatch(this)" class = "btn btn-info alert-brgy1-btn " value ="{{$item->barangay}}">{{$item->barangay}}</a></li>
                        {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                    </div>
               @elseif($item->status=="occupied")
                    <div  class = "alert-brgy1">
                        <li> <a id ="{{$item->dep_id}}" onClick="dispatch(this)" class = "btn btn-success alert-brgy1-btn " value ="{{$item->barangay}}">{{$item->barangay}}</a></li>
                        {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                    </div>
               @else
                    <div  class = "alert-brgy1">
                        <li> <a id ="{{$item->dep_id}}" onClick="dispatch(this)" class = "btn btn-danger alert-brgy1-btn " value ="{{$item->barangay}}">{{$item->barangay}}</a></li>
                        {{--   <a  id='fire'   onClick="dispatch()" class="btn btn-danger dispatch-btn"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a>--}}
                    </div>
               @endif



            @endforeach





        </div>


    </div>
    <div class = "map-container">
        <div class = "control-panel">
            <ul>
                <li title="List Of Responders"><a href="/actioncenter/{{$acname}}/{{$dep}}/responders"><i class="fa fa-fw fa-users control-panel-item"></i></a></li>
                <li title="Change Password"><a  ><i class="fa fa-fw fa-lock control-panel-item" data-toggle="modal" data-target="#myModal1"></i></a></li>
                <li title="Logout"><a href="/logout"><i class="fa fa-fw fa-times control-panel-item"></i></a></li>
            </ul>
        </div>

        <div id="map"></div>



    </div>
</div>
 <script src="http://45.32.107.230:8080/socket.io/socket.io.js"></script>
<script> var act_center = '{{$acname}}'
        var dept = '{{$dep}}'
</script>




<script type = "text/javascript">
function btnclr() {
            @foreach($resp as $item)
              var stat = '{{$item->status}}'
              var clr;
              if(stat == "active"){
                     return "success";

               }
            @endforeach
}


</script>
<script src="/js/dept.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAIC8AGTDYajtF7ps1F4BzHSP8o4-W8_I&callback=initMap"> </script>



</body>
</html>
