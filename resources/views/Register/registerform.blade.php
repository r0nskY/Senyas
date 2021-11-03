<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>CNEAS Register</title>
</head>
<body>
<div class="container">
    <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row" style="margin-top:5%;">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel panel-heading">
                        <h3>Register</h3>
                    </div>

                    <div class="panel panel-body">

                        <div class="row">
                            <div class="col-md-4">
                            <span>
                                <label>First Name: </label>
                                <input type="text" name="fname" class="form-control">
                            </span>
                            </div>

                            <div class="col-md-4">
                            <span>
                                <label>Middle Name: </label>
                                <input type="text" name="mname" class="form-control">
                            </span>
                            </div>

                            <div class="col-md-4">
                            <span>
                                <label>Last Name: </label>
                                <input type="text" name="lname" class="form-control">
                            </span>
                            </div>

                        </div>

                        <div class="row" style="margin-top:20px;">

                            <div class="col-md-6">
                                <label>Address:</label>
                                <input type="text" name="street" placeholder="Street" class="form-control"><br>
                                <input type="text" name="barangay" placeholder="Barangay" class="form-control"><br>
                                <input type="text" name="municipality" placeholder="Municipality" class="form-control"><br>
                                <input type="text" name="province" placeholder="Province" class="form-control"><br>
                            </div>

                            <div class="col-md-6">
                                <label>Position:</label>
                                <input type="text" name="position" class="form-control">
                            </div>


                        </div>

                        <div class="row" style="margin-top:20px;">

                            <div class="col-md-6">
                                <label>Municipality:</label>
                                <select  name="ass_mun" class="form-control">
                                    <option selected disabled>Choose</option>
                                    <option value="Basud">Basud</option>
                                    <option value="Capalonga">Capalonga</option>
                                    <option value="Daet">Daet</option>
                                    <option value="Jose Panganiban">Jose Panganiban</option>
                                    <option value="Labo">Labo</option>
                                    <option value="Mercedes">Mercedes</option>
                                    <option value="Paracale">Paracale</option>
                                    <option value="San Lorenzo">San Lorenzo</option>
                                    <option value="San Vicente">San Vicente</option>
                                    <option value="Santa Elena">Santa Elena</option>
                                    <option value="Talisay">Talisay</option>
                                    <option value="Vinzons">Vinzons</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Area Code:</label>
                                <input type="text" name="position" disabled class="form-control">
                            </div>


                        </div>


                        <div class="row" style="margin-top:20px; padding: -55px; ">
                            <div class="col-md-12">
                                <div class="panel panel-info" >
                                    <div class="panel panel-heading">
                                        <h4>Account Details</h4>
                                    </div>

                                    <div class="panel panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Username:</label>
                                                <input class="form-control" name="username" type="text">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Password:</label>
                                                <input class="form-control" name="password" type="password">
                                            </div>

                                        </div>

                                        <div class="row" style="margin-top: 20px;">
                                            <div class="col-md-6">
                                                <label>Email:</label>
                                                <input class="form-control" name="email" type="email">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Birthdate:</label>
                                                <input class="form-control" name="birthdate" type="date">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-12" style="margin-top: 25px;">
                                    <center>
                                        <input type="submit" class="btn btn-primary" value="REGISTER" name="submit">
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>


