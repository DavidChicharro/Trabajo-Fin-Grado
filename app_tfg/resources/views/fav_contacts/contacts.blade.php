@extends('layouts.base')

@section('title', 'Contactos favoritos')
@section('username',$username)

@section('content')
	<h2>Contactos favoritos</h2>
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
										<span class="sp-as-lk text-k-red">
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
			<a class="btn-add-contacto mt-4" href="/nuevo-contacto-favorito">Añadir contacto favorito</a>
		</div>
	</section>

@endsection

@section('scripts')
	<script src="{{asset('js/fav_contacts/fav-contacts.js')}}"></script>
@endsection
