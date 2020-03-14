<html>
<head>
	<title>App Name - @yield('title')</title>
</head>
	<body>
		<div class="container">
			<section>
				<h2>Registro</h2>

				<form method="post" action="{{ Request::url() }}">
					@csrf
					<label for="email">E-mail</label>
					<input type="text" name="email"><br>

					<label for="password">Contraseña</label>
					<input type="password" name="password"><br>

					<label for="conf_password">Confirmar contraseña</label>
					<input type="password" name="conf_password"><br>

					<br>
					<input type="submit" value="Siguiente">
				</form>

				<hr>
				<p>¿Ya tienes cuenta?</p>
				<a href="/">Inicia sesión</a>
			</section>
		</div>

		@if($errors->any())
			@foreach($errors->all() as $error)
				<script>alert('{{$error}}')</script>
			@endforeach
		@endif
	</body>
</html>