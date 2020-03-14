<html>
<head>
	<title>App Name - @yield('title')</title>
</head>
	<body>
		<div class="container">
			<section>
				<h2>Registro</h2>

				@if($errors->any())
					@foreach($errors->all() as $error)
					   {{ $error }}
					@endforeach
				@endif

				<form method="post" action="{{ Request::url() }}">
					@csrf
					<label for="email">E-mail</label>
					<input type="text" name="email"><br>

					<label for="password">Contraseña</label>
					<input type="password" name="password"><br>

					<label for="conf_password">Confirmar contraseña</label>
					<input type="password" name="conf_password"><br>

					<!-- <label for="nombre">Nombre</label>
					<input type="text" name="nombre"><br>

					<label for="apellidos">Apellidos</label>
					<input type="text" name="apellidos"><br>

					<label for="dni">D.N.I.</label>
					<input type="text" name="dni"><br>

					<label for="fecha_nacimiento">Fecha de nacimiento</label>
					<input type="date" name="fecha_nacimiento"><br>

					<label for="telefono">Número de teléfono móvil</label>
					<input type="tel" name="telefono"><br> -->
					<br><br>
					<input type="submit" value="Siguiente">
				</form>
			</section>
		</div>
	</body>
</html>