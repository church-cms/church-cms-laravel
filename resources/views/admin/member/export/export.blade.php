<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                @include('partials.message')
            </div>
            <div class="panel-body">
                <a href="{{ url('admin/exportUsers') }}"><button class="btn btn-success">Export</button></a>

            </div>
        </div>
    </div>
</body>

</html>
