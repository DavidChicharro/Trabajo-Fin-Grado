<h2 class="text-center">Inicia sesión @yield('login-admin')</h2>

<form method="post" action="{{ Request::url() }}">
	@csrf
	<div class="form-group">
		<label for="email">E-mail</label>
		<input type="email" name="email" value="{{ old('email') }}" class="form-control">
	</div>

	<div class="form-group">
		<label for="password">Contraseña</label>
		<input type="password" name="password" class="form-control">
	</div>
	<input type="submit" value="Iniciar sesión" class="form-button" disabled>
</form>

@if($errors->any())
	@foreach($errors->all() as $error)
		<script>alert('{{$error}}')</script>
	@endforeach
@endif