
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->

    <link href="/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class = "dash-control-panel pull-right">
                   <li><a href="/home" data-toggle = "tooltip" title = "Home" data-placement = "bottom"><i class="fa fa-fw fa-home control-panel-item"></i></a></li>
                   <li><a href="/logout" data-toggle = "tooltip" title = "Logout" data-placement = "bottom"><i class="fa fa-fw fa-times control-panel-item"></i></a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}
        <div class = "panel panel-default">

            <div class="panel-heading">
                <h2>App Users For Verification</h2>
            </div>
            <div class="panel-searchbox" id = "search_data">
                <input type="search" id="search" placeholder="Search" onkeyup="keysearch()" />
            </div>
            <div class="panel-body" id="dataTabless">

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                   <thead>
                   <tr>

                       <th>Account Name</th>
                       <th>User Name</th>
                       <th>Email</th>
                       <th>Birthday</th>
                       <th>Contact No.</th>
                       <th>Address</th>
                       <th>Status</th>


                   </tr>
                   </thead>

                   <tbody>

                   @foreach($app_user as $ac)
                       <tr class="odd gradeX" data-parent="#dataTabless" id = "{{$ac->id}}">

                           <td class="center">{{$ac->username}}</td>
                           <td class="center">{{$ac->fname}} {{$ac->mname}} {{$ac->lname}}</td>
                           {{-- <td class="center">{{$ac->name}}</td>--}}
                           <td class="center">{{$ac->email}}</td>
                           <td class="center">{{$ac->birthdate}}</td>
                           <td class="center">{{$ac->contact}}</td>
                           <td class="center">{{$ac->barangay}} {{$ac->municipality}} {{$ac->province}}</td>
                           <td class="center"><button type="submit" name="submit" class="btn btn-info" value="{{$ac->imei}}" >ACTIVATE</button></td>

                       </tr>
                   @endforeach

                   </tbody>

               </table>
                <!-- /.table-responsive -->
            </div>

        </div>

            <div class = "panel panel-default">

                <div class="panel-heading">
                    <h2>List Of Users</h2>
                </div>
                <div class="panel-searchbox" id = "search_data">
                    <input type="search" id="search1" placeholder="Search" onkeyup="keysearch1()" />
                </div>
                <div class="panel-body" id="dataTabless1">

                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example1">
                        <thead>
                        <tr>

                            <th>Account Name</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Birthday</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Status</th>


                        </tr>
                        </thead>

                        <tbody>

                        @foreach($app_user1 as $ac)
                            <tr class="odd gradeX" data-parent="#dataTabless1" id = "{{$ac->id}}">

                                <td class="center">{{$ac->username}}</td>
                                <td class="center">{{$ac->fname}} {{$ac->mname}} {{$ac->lname}}</td>
                                {{-- <td class="center">{{$ac->name}}</td>--}}
                                <td class="center">{{$ac->email}}</td>
                                <td class="center">{{$ac->birthdate}}</td>
                                <td class="center">{{$ac->contact}}</td>
                                <td class="center">{{$ac->barangay}} {{$ac->municipality}} {{$ac->province}}</td>
                                <td class="center"><button type="submit1" name="submit1" class="btn btn-success" value="{{$ac->imei}}" >DEACTIVATE</button></td>

                            </tr>
                        @endforeach

                        </tbody>

                    </table>
                    <!-- /.table-responsive -->
                </div>

            </div>


        </form>
    </div>







    <!-- jQuery -->
    <script src="/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="/vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
   {{-- <script src="../dist/js/sb-admin-2.js"></script>--}}
  {{-- // <script> var act_center = '{{$acname}}' </script>--}}
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });

            $('#dataTables-example1').DataTable({
                responsive: true
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>

       function keysearch() {
           var input, filter, table, tr, td, i;
           input = document.getElementById("search");
           filter = input.value.toUpperCase();

           table = document.getElementById("dataTables-example");
           tr = table.getElementsByTagName("tr");

           // Loop through all table rows, and hide those who don't match the search query
           for (i = 0; i < tr.length; i++) {
               td = tr[i].getElementsByTagName("td")[0];
               if (td) {
                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                       tr[i].style.display = "";
                   } else{
                       td = tr[i].getElementsByTagName("td")[1];
                       if (td) {
                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                               tr[i].style.display = "";
                           }else{
                               td = tr[i].getElementsByTagName("td")[2];
                               if (td) {
                                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                       tr[i].style.display = "";
                                   } else{
                                       td = tr[i].getElementsByTagName("td")[3];
                                       if (td) {
                                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                               tr[i].style.display = "";
                                           } else {
                                               td = tr[i].getElementsByTagName("td")[4];
                                               if (td) {
                                                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                                       tr[i].style.display = "";
                                                   } else {
                                                       td = tr[i].getElementsByTagName("td")[5];
                                                       if (td) {
                                                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                                               tr[i].style.display = "";
                                                           } else {
                                                               tr[i].style.display = "none";
                                                           }

                                                       }
                                                   }

                                               }
                                           }

                                       }

                                   }

                               }


                           }

                       }


                   }

               }


           }

           
       }

       function keysearch1() {
           var input, filter, table, tr, td, i;
           input = document.getElementById("search1");
           filter = input.value.toUpperCase();

           table = document.getElementById("dataTables-example1");
           tr = table.getElementsByTagName("tr");

           // Loop through all table rows, and hide those who don't match the search query
           for (i = 0; i < tr.length; i++) {
               td = tr[i].getElementsByTagName("td")[0];
               if (td) {
                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                       tr[i].style.display = "";
                   } else{
                       td = tr[i].getElementsByTagName("td")[1];
                       if (td) {
                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                               tr[i].style.display = "";
                           }else{
                               td = tr[i].getElementsByTagName("td")[2];
                               if (td) {
                                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                       tr[i].style.display = "";
                                   } else{
                                       td = tr[i].getElementsByTagName("td")[3];
                                       if (td) {
                                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                               tr[i].style.display = "";
                                           } else {
                                               td = tr[i].getElementsByTagName("td")[4];
                                               if (td) {
                                                   if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                                       tr[i].style.display = "";
                                                   } else {
                                                       td = tr[i].getElementsByTagName("td")[5];
                                                       if (td) {
                                                           if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                                               tr[i].style.display = "";
                                                           } else {
                                                               tr[i].style.display = "none";
                                                           }

                                                       }
                                                   }

                                               }
                                           }

                                       }

                                   }

                               }


                           }

                       }


                   }

               }


           }


       }
    </script>
     {{--<script>

       function keysearch(sel, filter) {
           var a, b, c, i, ii, iii, hit;
             a = document.getElementById('dataTables-example');
             for (i = 0; i < a.length; i++) {
                 b = document.getElementsByTagName(sel);
                 for (ii = 0; ii < b.length; ii++) {
                      hit = 0;
                      if (b[ii].innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                             hit = 1;
                        }
                     c = b[ii].getElementsByTagName("*");
                     for (iii = 0; iii < c.length; iii++) {
                        if (c[iii].innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                             hit = 1;
                        }
                     }
                    if (hit == 1) {
                         b[ii].style.display = "";
                    } else {
                         b[ii].style.display = "none";
                    }
                 }
            }

       }


    </script>--}}


</body>
</html>

