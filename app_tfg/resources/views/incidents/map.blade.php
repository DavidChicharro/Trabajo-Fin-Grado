@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)


@section('content')
	<h2>Mapa de incidentes</h2>
	<section class="main-content mx-1">
		<img class="img-fluid w-100" src="{{asset('images/mapa-grx.png')}}">
		<div class="my-3">
			<a href="/mis-publicaciones-incidentes">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/lista-incidentes">Ver lista</a>
		</div>
	</section>

@endsection