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

    <div class="container-fluid">
        <div class="row">
            @section('sidebar')
                <section class="col-3">
                    <img  class="img-fluid" src="{{asset('images/logo/logo.png')}}">
                </section>
            @show

            @yield('content')

            @section('top-right-header')
                <aside class="col-2">
                    <span>Lupa</span>
                    <span>Bell</span>
                    <span>User</span>
                    <p>@yield('username')</p>
                    <a href="/logout">Cerrar sesión</a>
                </aside>
            @show
        </div>
    </div>
</body>
</html>