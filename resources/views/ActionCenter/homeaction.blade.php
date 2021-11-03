<!DOCTYPE HTML>

<html>
    <head>
        <title>SENYAS Action Center</title>

        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link href="../css/style.css" rel="stylesheet" type="text/css">
        <link href="../css/actioncenter.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    </head>

    <body>
        {{--@include('flash::message')--}}
        <div id="myModal" class="modal fade" role="dialog">
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
                            <select id = "slctdept" style = "margin-top:15px;" name = "department" class="form-control" onchange="populate('slctdept','slcttype')">
                                <option disabled selected value="">Choose Department</option>
                                <option value="MEDICAL">MEDICAL</option>
                                <option value="FIRE">FIRE</option>
                                <option value="POLICE">POLICE</option>
                                <option value="BARANGAY">BARANGAY</option>
                            </select>
                            <select id = "slcttype" style = "margin-top:15px;" name = "type" class="form-control">
                              <option  disabled selected value="">Responder Type</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            {{--  // <input type="submit" name="submit" class="btn btn-info" value="SA VE">--}}
                            <button type="button" name="submit" class="btn btn-info" value="SAVE" onclick="submit1()" data-dismiss="modal">SAVE</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
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
                                <button id="1" style = "margin-top:15px;" type="button" name="submit" class="form-control btn-danger" onclick="dispatch(this)" data-dismiss="modal" value ="{{$meditem->barangay}}">{{$meditem->barangay}}</button>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="myModal3" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">FIRE</h4>
                        </div>
                        <div class="modal-body">
                            @foreach($fire as $fireitem)
                                <button id="2" style = "margin-top:15px;" type="button" name="submit" class="form-control btn-danger" onclick="dispatch(this)" data-dismiss="modal" value ="{{$fireitem->barangay}}">{{$fireitem->barangay}}</button>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="myModal4" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form action="{{ route('auth.upload', "$acname") }}"  id="form" method="post" enctype="multipart/form-data" target="iframe">
                    {{ csrf_field() }}
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">News And Announcement</h4>
                        </div>
                        <div class="modal-body">
                            <input style = "margin-top:15px;" type = "text" id = "infoTitle"  class="form-control" placeholder="Title">
                            <textarea placeholder="Type Content Here." id="infoContent" style = " margin-top:15px; width:  100%; min-height: 200px; resize: none;"></textarea>


                            <label for="file" class="btn btn-primary" style="cursor:pointer; margin-top:15px;" >Browse For Image</label>
                            <p id="demo"></p>
                            <input class="btn btn-primary" type="file" accept="image/*" id = "file" onchange="nameee()" name = "file" style="display: none;"/>
                            <input style = "margin-top:5px;" type="submit" name="submit6" id="submit6"  class="btn btn-primary" value = "Upload Image" />
                            <p id="message"></p>
                            <center>
                                <img  style = "min-width: 200px; height: 200px;" id="image" >
                            </center>
                            </br></br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="submit5" name="submit5" class="btn btn-info" value="" onclick="sendInfo()">SEND NEWS</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
                <iframe style="display: none;" name = "iframe"></iframe>
            </div>
        </div>

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

        <div class = "flex-container">
            <div class = "sidebar">
                <div class = "sidebar-header color1">
                    <span>ALERTS</span>
                </div>
                <div class = "sidebar-alert-container">
                    <div id="alert-container" class = "alert-action"></div>
                    {{-- <div class = "button-container">
                    <button class="btn endtrack-btn" onclick="send()">Request End Tracking</button>
                    </div>--}}
                </div>
                <div class = "sidebar-header color1">
                    <span>On-Going</span>
                </div>
                <div id = "ongoing-container" class = "notif-container">

                </div>
            </div>
            <div class = "map-container">
                <div class = "control-panel">
                    <ul>
                        <li title="Reports"><a href="/actioncenter/{{$acname}}/reports"><i class="fa fa-fw fa-list control-panel-item"></i></a></li>
                        <li title="List Of Responders"><a href="/actioncenter/{{$acname}}/responders"><i class="fa fa-fw fa-users control-panel-item"></i></a></li>
                        <li title="List Of App Users"><a href="/{{$acname}}/appuser"><i class="fa fa-fw fa-users control-panel-item"></i></a></li>
                        <li title="Change Password"><a ><i class="fa fa-fw fa-lock control-panel-item"data-toggle="modal" data-target="#myModal1"></i></a></li>
                        <li title="Add New Responder"><a  ><i class="fa fa-fw fa-user control-panel-item" data-toggle="modal" data-target="#myModal"></i></a></li>
                        <li title="NEWS AND ANNOUNCEMENTS"><a ><i class="fa fa-fw fa-bullhorn control-panel-item" data-toggle="modal" data-target="#myModal4"></i></a></li>
                        <li title="Logout"><a href="/logout"><i class="fa fa-fw fa-times control-panel-item"></i></a></li>
                    </ul>
                </div>
               {{-- <div class = "control-panel-2">
                    <ul>
                        <li><a  id='medical' class="btn btn-danger dispatch-btn" data-toggle="modal" data-target="#myModal2"><img src = "../img/medical.png" class = "img-responsive"/><span>Medical</span></a></li>
                        <li><a  id='fire' class="btn btn-danger dispatch-btn" data-toggle="modal" data-target="#myModal3"><img src = "../img/fire.png" class = "img-responsive"/><span>Fire</span></a></li>
                        <li><a  id='police' onClick="sendToDept(this)" class="btn btn-danger dispatch-btn"><img src = "../img/police.png" class = "img-responsive"/><span>Police</span></a></li>
                    </ul>
                </div>--}}
                <div id="map"></div>
            </div>
        </div>
        <script src="http://45.32.107.230:8080/socket.io/socket.io.js"></script>
        <script>
            var act_center = '{{$acname}}';
            function populate(s1,s2){
                var s1 = document.getElementById(s1);
                var s2 = document.getElementById(s2);
                s2.innerHTML="";
                if(s1.value=="MEDICAL"){
                    @foreach($med1 as $meditems1)
                        opt1 = document.createElement('option');
                        opt1.id = 'opttype';
                        opt1.value = '{{$meditems1->barangay}}';
                        opt1.text = '{{$meditems1->barangay}}';
                        s2.appendChild(opt1);
                    @endforeach
                } else if(s1.value=="FIRE"){
                    @foreach($fire1 as $fireitems1)
                        opt1 = document.createElement('option');
                        opt1.id = 'opttype';
                        opt1.value = '{{$fireitems1->barangay}}';
                        opt1.text = '{{$fireitems1->barangay}}';
                        s2.appendChild(opt1);
                    @endforeach
                } else if(s1.value=="POLICE"){
                    @foreach($pnp1 as $pnpitems1)
                            opt1 = document.createElement('option');
                    opt1.id = 'opttype';
                    opt1.value = '{{$pnpitems1->barangay}}';
                    opt1.text = '{{$pnpitems1->barangay}}';
                    s2.appendChild(opt1);
                    @endforeach

                } else if(s1.value=="BARANGAY"){
                    @foreach($brgy1 as $itemss)
                        opt1 = document.createElement('option');
                        opt1.id = 'opttype';
                        opt1.value = '{{$itemss->barangay}}';
                        opt1.text = '{{$itemss->barangay}}';
                        s2.appendChild(opt1);
                    @endforeach
                }
            }

            function updatepicture(pic) {
                document.getElementById("image").setAttribute("src", pic);
            }

            function nameee() {
                var x = document.getElementById("file").value;
                document.getElementById("demo").innerHTML = x;
            }

            $('#flash-overlay-modal').modal();
        </script>
        {{--<script src="/js/action.js"></script>--}}
        <script src="../js/event.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAIC8AGTDYajtF7ps1F4BzHSP8o4-W8_I&callback=initMap"> </script>
        <script src="//code.jquery.com/jquery.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
