@extends('layouts.base')

@section('title', 'Ordenar contactos favoritos')
@section('username',$username)

@section('content')
	<h2 class="section-title pl-1 pl-sm-5 px-md-1">Ordenar contactos favoritos</h2>

	<div id="alert-success" class="alert alert-primary alert-dismissible fade show d-none" role="alert">
		Los contactos se han ordenado satisfactoriamente
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div id="alert-error" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
		¡Ha ocurrido un error! Los contactos no se han podido ordenar. Por favor, recargue la página e inténtelo de nuevo.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<section class="main-content mx-1 my-4 row">
		<div id="sortable" class="contacts col-8">
			@if(!empty($contacts))
				@foreach($contacts as $contact)
					<article class="incident order-contact p-2 mx-1 my-2 row" id="{{$contact['fav_contact_id']}}">
						<h5 class="list-order text-muted col-1 my-1">{{$contact['orden_vista']}}</h5>
						<h5 class="col my-1">{{$contact['nombre']}}</h5>
						<div class="col-2 mt-2">
							<img class="icon-order-contact" src="{{asset('images/icons/menu.svg')}}">
						</div>
					</article>
				@endforeach
			@else
				<article class="incident pt-3 text-center">
					<p>No tienes ningún contacto favorito</p>
				</article>
			@endif
		</div>

		<div class="contacts-menu col-4 p-1">
			<ul class="nav flex-column">
				<li class="nav-item">
					<a id="btn-sort-contacts" class="btn-add-contacto nav-link" href="#">Ordenar contactos</a>
				</li>
			</ul>
		</div>
	</section>

@endsection

@section('scripts')
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="{{asset('js/fav_contacts/fav-contacts.js')}}"></script>
@endsection
