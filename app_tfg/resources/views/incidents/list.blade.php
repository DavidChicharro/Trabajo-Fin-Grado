@extends('layouts.base')

@section('title', 'Lista de incidentes')
@section('username',$username)

@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

@endsection

@section('filter')
	<div class="filter float-right w-100 mt-2 pr-1 pr-md-0">
		<form class="" method="get" action="{{ Request::url() }}">
		<div class="form-group">
			<select class="form-control selectpicker" name="tipos_incidentes[]" id="tipos-incid" title="Tipos incidentes" multiple data-live-search="true" data-selected-text-format="count > 3">
				@foreach($incidentTypes as $id => $incid)
					<option value="{{$id}}">{{ucfirst(strtolower($incid))}}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="desde">Desde</label>
			<input type="date" name="desde" class="form-control">
		</div>

		<div class="form-group">
			<label for="hasta">Hasta</label>
			<input type="date" name="hasta" class="form-control">
		</div>

		<input type="submit" value="Filtrar" class="form-button">
		</form>

		<a class="btn-add-incidcente mt-4" href="{{route('nuevoIncidente')}}">Añadir incidente</a>
	</div>
@endsection

@section('content')
	<h2 class="section-title pl-2 pl-sm-5 px-md-1">Lista de incidentes</h2>
	<section class="main-content mx-1">
		<div class="my-3">
			<a href="{{route('incidentesSubidos')}}">Mis publicaciones de incidentes</a>
			<a class="float-right" href="{{route('mapaIncidentes')}}">Ver mapa</a>
		</div>

		@if(!empty($appliedFilter))
			<article class="applied-filter my-2 px-2">
				@isset($appliedFilter['rango'])
				<p class="my-0">
					<b>Intervalo de fecha: </b>
					@dateFormat($appliedFilter['rango'][0]) -
					@dateFormat($appliedFilter['rango'][1])
				</p>
				@endisset
				@isset($appliedFilter['delitos'])
					<p class="my-0"><b>Tipos de incidentes: </b>
						@foreach($appliedFilter['delitos'] as $del)
							{{$incidentTypes[$del]}}@if(!$loop->last), @endif
						@endforeach
					</p>
				@endisset
			</article>
		@endif

		<div class="incidents">
			@if(!empty($incidents))
				@foreach($incidents as $inc)
					<article class="incident px-2 py-3 mb-1">
						<table class="w-100" cellpadding="8">
							<tbody>
								<tr>
									<td><h5>{{ucfirst($inc['incidente'])}}</h5></td>
									<td class="w-25"><small class="float-right">@dateTimeFormat($inc['fecha_hora'])</small></td>
								</tr>
								<tr>
									<td class="pt-2">{{$inc['nombre_lugar']}}</td>
									<td class="w-25 pt-2 text-right">
										<span id="vm-{{$inc['id']}}-{{$inc['delito']}}"
										      class="view-more text-right sp-as-lk">
											Ver más
										</span>
									</td>
								</tr>
							</tbody>
						</table>
					</article>
				@endforeach
				<div class="m-3 w-75">
					{{ $incidents_pag->links() }}
				</div>
			@else
				<article class="incident pt-3 text-center">
					<p>No hay incidentes según el criterio seleccionado</p>
				</article>
			@endif
		</div>
	</section>

	<div class="modal fade" id="shareIncident" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header py-2">
					<h5 class="modal-title" id="config-title">Compartir incidente</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body container-fluid">
					<div class="row" id="content-modal-id">
						<a class="twitter-link text-center p-3" href="" target="_blank">
							<img class="img-fluid" src="{{asset('images/icons/twitter_logo.svg')}}" width="40px">
						</a>

						<a class="facebook-link text-center p-3" href="" target="_blank">
							<img class="img-fluid" src="{{asset('images/icons/facebook_logo.png')}}" width="40px">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<script>
		var shareUrl = '{{ URL::asset('/images/icons/compartir.svg') }}';
	</script>
	<script src="{{asset('js/incidents/incidents.js')}}"></script>
@endsection
