
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->

    <link href="/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
     <link href="/css/actioncenter.css" rel="stylesheet" type="text/css">
    <!-- DataTables Responsive CSS -->
    <link href="/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
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
                   <li><a href="/actioncenter/{assmun}" data-toggle = "tooltip" title = "Home" data-placement = "bottom"><i class="fa fa-fw fa-home control-panel-item"></i></a></li>
                   <li><a href="/logout" data-toggle = "tooltip" title = "Logout" data-placement = "bottom"><i class="fa fa-fw fa-times control-panel-item"></i></a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class = "panel panel-default">
            <div class="panel-heading">
                <h2>List Of Responders In {{$acmun}}</h2>
            </div>
            <div class="panel-body" id="dataTabless">

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                   <thead>
                   <tr>
                       <th>Name</th>
                       <th>Responder Type</th>
                       <th>Ganget IMEI</th>
                       <th>Responder Unique ID</th>


                   </tr>
                   </thead>
                   <tbody>
                   @foreach($responders as $ac)
                       <tr class="odd gradeX" data-parent="#dataTabless">
                           <td class="center">{{$ac->fname}} {{$ac->mname}} {{$ac->lname}}</td>
                          {{-- <td class="center">{{$ac->name}}</td>--}}
                           <td class="center">{{$ac->type}}</td>
                           <td class="center">{{$ac->imei}}</td>
                           <td class="center">{{$ac->resp_unique_id}}</td>


                       </tr>
                   @endforeach
                   </tbody>
               </table>
                <!-- /.table-responsive -->
            </div>
        </div>



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
</body>
</html>

