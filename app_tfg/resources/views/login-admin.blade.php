@extends('layouts.index-base')

@section('title', ' - Administración')
@section('login-admin', '(Administración)')

@section('content')

	<a href="/" class="mx-auto">
		<img class="img-fluid p-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="400px">
	</a>

	<div class="row col-12 mx-auto">
		<section class="form-section mx-auto">
			@include('layouts.login')
		</section>
	</div>

@endsection