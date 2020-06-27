@extends('layouts.base')

@section('title', 'Contactos favoritos')
@section('username',$username)

@section('content')
	<h2 class="section-title pl-1 pl-sm-5 px-md-1">Contactos favoritos</h2>

	@if(Session::has('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{Session::get('error')}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif

	<section class="main-content mx-1 my-4 row">
		<div class="contacts col-8">
			@if(!empty($contacts))
				@foreach($contacts as $contact)
					<article class="incident px-2 py-3 mb-1">
						<table class="w-100" cellpadding="3">
							<tbody>
								<tr>
									<td class="pr-3">
										<h5 class="text-muted">{{$contact['orden_vista']}}</h5>
									</td>
									<td><h5>{{$contact['nombre']}}</h5></td>
									<td>
										<span class="view-more sp-as-lk">+</span>
										<span class="view-less sp-as-lk text-k-red" style="display: none">&#8210;</span>
									</td>
								</tr>
								<tr class="contact-details expanded" style="display: none">
									<td></td>
									<td>
										<p class="">Teléfono: {{$contact['telefono']}}</p>
										<p class="">Email: {{$contact['email']}}</p>
										<span class="sp-as-lk text-k-red" id="delete-fav-contact-{{$contact['fav_contact_id']}}">
											Eliminar contacto favorito
											<img class="img-fluid" src="{{asset('images/icons/papelera.svg')}}" width="25px">
										</span>
									</td>
								</tr>
							</tbody>
						</table>
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
					<a class="btn-add-contacto nav-link" href="{{route('nuevoContacto')}}">Añadir contacto favorito</a>
				</li>

				<li class="nav-item">
					<a class="fc nav-link p-0" href="{{route('deQuienSoyContacto')}}">De quién soy contacto favorito</a>
				</li>

				@if(count($contacts) > 1)
					<li class="nav-item">
						<a class="fc nav-link p-0" href="{{route('ordenarContactosFavoritos')}}">Ordenar contactos</a>
					</li>
				@endif
			</ul>
		</div>
	</section>

@endsection

@section('scripts')
	<script src="{{asset('js/fav_contacts/fav-contacts.js')}}"></script>
@endsection
