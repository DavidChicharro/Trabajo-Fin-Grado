@extends('layouts.index-base')

@section('content')

	<img class="img-fluid px-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="350px">

	<div class="main-content row col-12">
		<section class="col-7">
			<img class="img-fluid mx-auto d-block pt-4" src="{{asset('images/img-remove.svg')}}" alt=imagen">
		</section>
		<section class="offset-1 col-3">
			@include('layouts.login')

			<article class="reg-log text-center p-3 mt-2">
				<span>¿No tienes cuenta?</span><br>
				<a href="/registro">Regístrate</a>
			</article>
		</section>
	</div>

@endsection