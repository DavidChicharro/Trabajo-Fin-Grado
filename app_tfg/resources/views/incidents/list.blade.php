@extends('layouts.base')

@section('title', 'Mapa de incidentes')
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

		<a class="btn-add-incidcente mt-4" href="/nuevo-incidente">Añadir incidente</a>
	</div>
@endsection

@section('content')
	<h2>Lista de incidentes</h2>
	<section class="main-content mx-1">
		<div class="my-3">
			<a href="/mis-publicaciones-incidentes">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/mapa-incidentes">Ver mapa</a>
		</div>

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
										<span id="vm{{$inc['id']}}" class="view-more text-right sp-as-lk">Ver más</span>
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

@endsection

@section('scripts')
{{--	<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>--}}

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<script>
		var shareUrl = '{{ URL::asset('/images/icons/compartir.svg') }}';
	</script>
	<script src="{{asset('js/incidents/incidents.js')}}"></script>
@endsection
