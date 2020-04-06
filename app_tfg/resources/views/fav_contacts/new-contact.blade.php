@extends('layouts.base')

@section('title', 'Contactos favoritos')
@section('username',$username)

@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
@endsection

@section('content')
	<h2>Contactos favoritos</h2>
	<section class="main-content mx-1 my-4 row">
		<article class="px-3 pr-5 col-md-9" id="search-contact">
			<div class="form-row">
				<input type="text" id="contact-search" class="form-control col-8" placeholder="&#x1F50D; Busca por e-mail o teléfono">
				<button id="btn-search-contact" class="btn form-button col-3 ml-2">Buscar</button>
			</div>
		</article>

		<article class="p-3 my-2 col-md-9 d-none" id="contacts">
		</article>
	</section>

@endsection

@section('scripts')
	<script>
        var addContactIcon = '{{ URL::asset('/images/icons/nuevo-contacto.svg') }}';
	</script>

	<script src="{{asset('js/fav_contacts/fav-contacts.js')}}"></script>
@endsection
