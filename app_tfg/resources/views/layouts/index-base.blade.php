<html>
<head>
	<title>{{config('app.name')}} @yield('title')</title>
	<link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/style.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/login.css')}}" rel="stylesheet"/>

	@yield('stylesheet')

</head>
<body>

	<div class="container-fluid mt-5">
		<div class="row">
			@yield('content')
		</div>
	</div>

	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
{{--	<script src="{{asset('js/forms.js')}}"></script>--}}
	<script src="{{asset('js/login.js')}}"></script>
	@yield('scripts')
</body>
</html>