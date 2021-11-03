
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
    {{--<link href="/css/style.css" rel="stylesheet" type="text/css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
</head>

<body>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}
                    <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Name Of Responder</h4>
                </div>
                <div class="modal-body">
                    <input type = "text"  name = "fname" id = "fname" class="form-control" placeholder="New Name">


                </div>
                <div class="modal-footer">
                    <label for="submit" class="btn btn-info">SAVE</label>
                       <input style="display: none;" type="submit" id="submit" name="submit" class="btn btn-info" value="SAVE">
                    {{--<button type="submit" id="submit1" name="submit1" class="btn btn-info" value="SAVE" data-dismiss="modal">SAVE</button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>







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
                <li><a href="/actioncenter/{{$acmun1}}/{{$dep}}" data-toggle = "tooltip" title = "Home" data-placement = "bottom"><i class="fa fa-fw fa-home control-panel-item"></i></a></li>
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
            <h2>List Of Responders In {{$acmun}} {{$dep}} Department</h2>
        </div>
        {{-- <div class="panel-searchbox" id = "search_data">
            <input type="search" id="search" placeholder="Search" onkeyup="keysearch()" />
        </div>--}}
        <div class="panel-body" id="dataTabless">

            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Name</th>

                    <th>Ganget IMEI</th>
                    <th>Responder Unique ID</th>
                    <th class="center">Edit Name</th>


                </tr>
                </thead>
                <tbody>
                @foreach($responders as $ac)
                    <tr class="odd gradeX" data-parent="#dataTabless" id = "{{$ac->id}}">
                        <td class="center">{{$ac->fname}}</td>
                        {{-- <td class="center">{{$ac->name}}</td>--}}

                        <td class="center">{{$ac->imei}}</td>
                        <td class="center">{{$ac->resp_unique_id}}</td>
                        <td class="center"><center><button type="button" name="button1" class="btn btn-danger" id="{{$ac->st_id}}" value="{{$ac->id}}" onclick="edt(this)" data-toggle="modal" data-target="#myModal">EDIT</button></center></td>

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

{{--<script>

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


</script>--}}
<script>
    function edt(idd){
        var rowid = document.getElementById(idd.value);
        var cells = rowid.getElementsByTagName("td")

        document.getElementById("fname").value = cells[0].innerText;
        document.getElementById("submit").value = idd.id;

        console.log(idd.value);
    }

    $('#flash-overlay-modal').modal();

</script>

</body>
</html>

