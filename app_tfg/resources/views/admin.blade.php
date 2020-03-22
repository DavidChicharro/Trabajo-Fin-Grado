@extends('layouts.admin-base')

@section('title', 'Administración')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/login.css')}}" rel="stylesheet"/>
@endsection

@section('content')

	@isset($session)
		<div class="container">
			@if(Session::has('message'))
				{{--Inicio de sesión correcto--}}
				<div>
					<p>{{Session::get('message')}}</p>
				</div>
			@endif

		</div>
	@endisset
	<h2>Administración - Inicio</h2>
	<ul class="admin-list mt-4 pl-1">
		<li class="li-as-lk py-2">Configurar caducidad de incidentes</li>
		<li class="li-as-lk py-2">Configurar número máximo de contactos favoritos</li>
		<li class="li-as-lk py-2">Configurar zonas de interés</li>
		<li class="li-as-lk py-2">Dar de alta administrador</li>
	</ul>

@endsection

{{--@section('scripts')--}}
{{--	--}}
{{--@endsection--}}
