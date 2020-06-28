@extends('layouts.base')

@section('title', 'De quién soy contacto favorito')
@section('username',$username)

@section('content')
	<h2 class="section-title pl-1 pl-sm-5 px-md-1">De quién soy contacto favorito</h2>
	<section class="main-content mx-1 my-4 row">
		<div class="contacts col-8">
			@if(!empty($contacts))
				@foreach($contacts as $contact)
					<article class="incident px-2 py-3 mb-1">
						<table class="w-100">
							<tbody>
								<tr>
									<td class="pl-4" style="width: 80%"><h5>{{$contact['nombre']}}</h5></td>
									<td style="width: 10%">
										<span class="view-more sp-as-lk">+</span>
										<span class="view-less sp-as-lk text-k-red" style="display: none">&#8210;</span>
									</td>
								</tr>
								<tr class="contact-details expanded" style="display: none">
									<td class="pl-4">
										<p class="">Teléfono: {{$contact['telefono']}}</p>
										<p class="">Email: {{$contact['email']}}</p>
										<span class="sp-as-lk text-k-red" id="i-delete-fav-contact-{{$contact['fav_contact_id']}}">
												Eliminarme como contacto favorito
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
					<p>No eres contacto favorito de nadie</p>
				</article>
			@endif
		</div>


		<div class="contacts-menu col-4 p-1">
			<ul class="nav flex-column">
				<li class="nav-item">
					<a class="btn-add-contacto nav-link" href="{{route('nuevoContacto')}}">Añadir contacto favorito</a>
				</li>
			</ul>
		</div>
	</section>

@endsection

@section('scripts')
	<script src="{{asset('js/fav_contacts/fav-contacts.js')}}"></script>
@endsection
