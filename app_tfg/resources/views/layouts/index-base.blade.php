<html>
<head>
	<title>{{config('app.name')}} @yield('title')</title>
	<link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/style.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/login.css')}}" rel="stylesheet"/>

	@yield('stylesheet')

</head>
<body>

	<div class="container-fluid mt-2 mt-md-4">
		<div class="row">
			@yield('content')
		</div>
	</div>

	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
	@yield('scripts')
</body>
</html>
