<html>
<head>
	<title>{{config('app.name')}} - @yield('title')</title>
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
					<img  class="img-fluid" src="{{asset('images/logo/logo.png')}}">

					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link {{(request()->is('mapa-incidentes') or request()->is('lista-incidentes')) ? 'active':''}}" id="nav-incidents" data-toggle="pill" href="/mapa-incidentes" role="tab" aria-selected="true">Incidentes</a>
						<a class="nav-link {{(request()->is('usuarios')) ? 'active':''}}" id="nav-fav-contacts" data-toggle="pill" href="/usuarios" role="tab" aria-selected="false">Usuarios</a>
						<a class="nav-link {{(request()->is('analizar-incidentes')) ? 'active':''}}" id="nav-areas-interest" data-toggle="pill" href="/analizar-incidentes" role="tab" aria-selected="false">Analizar incidentes</a>
					</div>
				</section>
			@show

			<main class="offset-3 col-9 col-sm-7 col-lg-6 mt-2 mt-lg-4 px-1 py-3 px-md-3 p-lg-4">
				@yield('content')
			</main>

			@section('top-right-header')
				<aside class="top-right-aside col-2 offset-lg-1 p-0 pr-md-2 pr-xl-4 d-none d-sm-block text-right">
					<div class="icon-group mt-2">
						<a href="/" id="user" class="icon my-2 my-lg-0">
							<img class="icon-img" src="{{asset('images/icons/admin.svg')}}">
						</a>
					</div>

					<div class="user-logout">
						<p class="m-1">@yield('username')</p>
						<a href="/logout">Cerrar sesión</a>
					</div>

					@yield('filter')
				</aside>
			@show
		</div>
	</div>

	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
	@yield('scripts')
</body>
</html>