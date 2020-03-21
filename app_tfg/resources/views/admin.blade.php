<html>
	<head>
		<title>{{config('app.name')}} - Admin</title>
		<link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>
	</head>
	<body>
		<img src="{{asset('images/logo/logo.png')}}">
		@isset($session)
			<div class="container">
				@if(Session::has('message'))
					{{--Inicio de sesión correcto--}}
					<div>
						<p>{{Session::get('message')}}</p>
					</div>
				@endif

				<div>
					{{$username}}
				</div>
				@yield('content')
			</div>
		@endisset

		@if(is_null($session))
			<h2>Inicia sesión (Administrador)</h2>

			<form method="post" action="{{ Request::url() }}">
				@csrf
				<label for="email">E-mail</label>
				<input type="text" name="email" value="{{ old('email') }}"><br>

				<label for="password">Contraseña</label>
				<input type="password" name="password"><br>
				<br><br>
				<input type="submit" value="Iniciar sesión">
			</form>

			@if($errors->any())
				@foreach($errors->all() as $error)
					<script>alert('{{$error}}')</script>
				@endforeach
			@endif
		@endif
	</body>
</html>