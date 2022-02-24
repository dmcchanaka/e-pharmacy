<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POINT OF SALES | Login</title>

    <link href="{{ asset('template/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('template/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{ asset('template/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css')}}" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }
        body {
        /* display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px; */
        background-image: url("{{ asset('template/images/home.jpg') }}");
        background-repeat: no-repeat;
        background-size: 100%;
        background-color:#ffbf00;
    }
    </style>
</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown" style="margin-left: -100px !important">
        @yield('content')
    </div>

</body>

</html>
