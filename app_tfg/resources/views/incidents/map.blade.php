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

		<div>
{{--			{{dd($notifications->count())}}--}}
{{--			@if($user->unreadNotifications->count() > 0)--}}
{{--			@endif--}}
{{--			{{dd($user->unreadNotifications->count())}}--}}
			@foreach($notifications as $notification)
{{--				{{dd($notification->data['sender'], $notification->data['message'])}}--}}
{{--				{{dd($notification->data['message'])}}--}}

{{--				{{dd($notification)}}--}}
{{--				{{$notification->data}}--}}
			@endforeach
		</div>
	</section>

@endsection