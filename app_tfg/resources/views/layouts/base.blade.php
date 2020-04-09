<html>
<head>
    <title>{{config('app.name')}} - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/style.css')}}" rel="stylesheet"/>

    @yield('stylesheet')

</head>
<body>

    <div class="container-fluid">
        <div class="row">
            @section('sidebar')
                <section class="col-3 px-1 px-md-3 lateral-nav">
                    <img class="img-fluid" src="{{asset('images/logo/logo.png')}}" alt="logo">

                    <div class="nav flex-column nav-pills" id="v-pills-tab" {{--role="tablist"--}} aria-orientation="vertical">
                        <a class="nav-link {{(request()->is('mapa-incidentes') or request()->is('lista-incidentes')) ? 'active':''}}" id="nav-incidents" href="/mapa-incidentes" role="tab" aria-selected="true">Incidentes</a>
                        <a class="nav-link {{(request()->is('contactos-favoritos')) ? 'active':''}}" id="nav-fav-contacts" href="/contactos-favoritos" role="tab" aria-selected="false">Contactos favoritos</a>
                        <a class="nav-link {{(request()->is('zonas-interes')) ? 'active':''}}" id="nav-areas-interest" href="/zonas-interes" role="tab" aria-selected="false">Zonas de interés</a>
                    </div>
                </section>
            @show

            <main class="offset-3 col-9 col-sm-7 col-lg-6 mt-2 mt-lg-4 px-1 py-3 px-md-3 p-lg-4">
                @yield('content')
            </main>

            @section('top-right-header')
                <aside class="top-right-aside col-2 offset-lg-1 p-0 pr-md-2 pr-xl-4 d-none d-sm-block">
                    <div class="icon-group mt-2 float-right">
                        <a href="#" id="lupa" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/lupa.svg')}}">
                        </a>

                        <a tabindex="0" id="campana" class="btn icon my-2 my-lg-0" role="button" data-toggle="popover">
                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">
                            @if($notifications->count() > 0)
                                <span id="notif-badge" class="badge badge-pill badge-danger">
                                    {{$notifications->count()}}
                                </span>
                            @endif
                        </a>

{{--                        <a href="#" id="campana" class="icon my-2 my-lg-0">--}}
{{--                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">--}}
{{--                        </a>--}}
                        <a href="/zona-personal" id="user" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/user.svg')}}">
                        </a>
                    </div>

                    <div class="user-logout float-right">
                        <p class="m-1">@yield('username')</p>
                        <a href="/logout">Cerrar sesión</a>
                    </div>

                    @yield('filter')
                </aside>
            @show
        </div>
    </div>

    <div id="popover-content" {{--class="d-none"--}}>
{{--        {{dd($notifications)}}--}}
        @foreach($notifications as $notification)
{{--            {{dd($notification->data['notification_type'])}}--}}
{{--            {{dd(print_r($notification['data']))}}--}}
{{--            @php $notif = json_encode($notification->data) @endphp--}}
{{--        {{dd($notif['notification_type'])}}--}}
{{--        {{$notification->data[notification_type]}}--}}
{{--            {{dd($notification->data['notification_type'])}}--}}
            <div>
            @isset($notification->data['notification_type'])
                @if($notification->data['notification_type'] == "befavcontact")
                    <img class="float-left mr-1" src="{{asset('images/icons/nuevo-contacto.svg')}}" width="20px">
                    <span><b>
                        <abbr title="{{$notification->data['sender_email']}}">{{$notification->data['sender_name']}}</abbr> {{$notification->data['message']}}
                    </b></span>
                    <div class="text-center">
                        <button id="bfc-{{$notification->data['sender_id']}}-{{$notification->data['recipient_id']}}"
                                class="btn btn-success pls-prs-me mr-3">Aceptar</button>
                        <button id="notfc-{{$notification->data['sender_id']}}-{{$notification->data['recipient_id']}}"
                                class="btn btn-danger pls-prs-me ml-3">Rechazar</button>
                    </div>
                @endif
            @endisset
            </div>
        @endforeach
{{--        <span>Carlitos quiere que seais amiwis</span>--}}
{{--        <button class="btn pls-prs-me">botoncito</button>--}}
    </div>

    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script>
        $(function(){
            // var content = $('#popover-content');
            // Enables popover
            $("[data-toggle=popover]").popover({
                html: true,
                placement: 'bottom',
                content: function () {
                    return $('#popover-content').html();
                },
                title: function () {
                    return '<h5 class="text-center">Notificaciones</h5>';
                }
            });
            // .on('show.bs.popover', function () {
            //
            // });
        });
        //
        // $(document).on("click",".pls-prs-me", function () {
        //     alert('aquí está el tiburón');
        // });
        // $('[id*=bfc-]');
        $(document).on("click","[id*=bfc-]", function () {
            // alert('ACEPTO: aquí está el tiburón');
            //En la relación de contactos favoritos cambiar
            // son_contactos: si 0->1
            let splitId = $(this).attr('id').split('-');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/accept_favourite_contact',
                data: {
                    'userId': splitId[1],
                    'favContactId': splitId[2]
                },
                type: 'post',
                success: function (response) {
                    console.log(response);
                    // Que desaparezca la notificación -> marcar como leída
                    // expandIncident(tabla, response.incidente);
                },
                statusCode: {
                    404: function () {
                        alert('web not found');
                    }
                },
            });
        });

        $(document).on("click","[id*=notfc-]", function () {
            alert('Rechazo: aquí está el tiburón');
            //En la relación de contactos favoritos cambiar
            // contador: ++
        });

        // $('.pls-prs-me').click(function() {
        //     alert('aquí está el tiburón');
        // });
    </script>
    @yield('scripts')
</body>
</html>