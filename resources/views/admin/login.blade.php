<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Khóa Học Lập Trình Laravel Framework 5.x Tại Khoa Phạm">
    <meta name="author" content="">

    <title>Admin - Khoa Phạm</title>
    <link href="source-layout/giao-dien-admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="source-layout/giao-dien-admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="source-layout/giao-dien-admin/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="source-layout/giao-dien-admin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    @if($message=Session::get('message'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message}}</strong>
                    </div>
                    @endif
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="/adminLogin" method="POST">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email"
                                        autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password"
                                        value="">
                                </div>
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="source-layout/giao-dien-admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="source-layout/giao-dien-admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="source-layout/giao-dien-admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="source-layout/giao-dien-admin/dist/js/sb-admin-2.js"></script>
</body>

</html>