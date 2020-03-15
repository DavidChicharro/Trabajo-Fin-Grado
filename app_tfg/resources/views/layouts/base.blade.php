<html>
<head>
    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('js/bootstrap.min.js')}}"/>
    <link href="{{asset('js/jquery-3.4.1.js')}}"/>

    @yield('stylesheet')
</head>
<body>
    @section('sidebar')
        This is the master sidebar.
    @show

    <div class="container">
        @yield('content')
    </div>
</body>
</html>