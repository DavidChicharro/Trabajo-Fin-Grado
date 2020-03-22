@extends('layouts.admin-base')

@section('title', 'Administración')
@section('username',$username)

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

@endsection

{{--@section('scripts')--}}
{{--	--}}
{{--@endsection--}}
