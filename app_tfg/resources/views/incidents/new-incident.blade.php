@extends('layouts.base')

@section('title', 'Añadir incidente')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

@endsection

@section('content')
	<h2>Dar de alta incidente</h2>
	<section class="main-content mx-1">
		<form class="px-3 pr-5 col-md-9" method="post" action="{{ Request::url() }}">
			@csrf
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="categ-delito">Tipo de delito</label>
					<select class="form-control selectpicker" id="categ-delito" title="Categoría delito" multiple data-live-search="true" data-selected-text-format="count > 3">
						@foreach($delitos as $del)
							<option value="{{$del}}">{{ucfirst(strtolower($del))}}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group col-md-6">
					<label for="categ-delito">Delito</label>
					<select class="form-control selectpicker" id="delito" title="Delito" multiple data-live-search="true" data-selected-text-format="count > 3">
						@foreach($delitos as $del)
							<option value="{{$del}}">{{ucfirst(strtolower($del))}}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="fecha_incidente">Fecha</label>
					<div class="input-group">
						<input type="date" name="fecha_incidente" class="form-control">
						<div class="input-group-prepend">
							<img class="input-group-text px-1" src="{{asset('images/icons/calendario.svg')}}">
						</div>
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="hora_incidente">Hora</label>
					<div class="input-group">
						<input type="time" name="hora_incidente" class="form-control">
						<div class="input-group-prepend">
							<img class="input-group-text py-2 px-1" src="{{asset('images/icons/reloj.svg')}}">
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="lugar">Lugar</label>
				<input type="search" name="lugar" class="form-control">
				<img class="img-fluid my-2" src="{{asset('images/mapa-grx.png')}}">
			</div>

			<div class="form-group">
				<label for="descripcion">Descripción</label>
				<textarea id="descripcion" class="form-control" rows="4"></textarea>
			</div>

			<div class="form-group">
				<label class="d-block" for="agravantes">Agravantes</label>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="agr-disfraz" value="agr-1">
					<label class="form-check-label" for="agr-disfraz">Disfraz</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="agr-abuso" value="agr-2">
					<label class="form-check-label" for="agr-abuso">Abuso de superioridad</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="agr-sufimiento" value="agr-3">
					<label class="form-check-label" for="agr-sufimiento">Sufrimiento inhumano</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="agr-discriminacion" value="agr-4">
					<label class="form-check-label" for="agr-discriminacion">
						Racismo, discriminación, homofobia, machismo, ...
					</label>
				</div>
			</div>

			<div class="form-group">
				<label class="d-block" for="agravantes">He sido</label>
				<span>Afectado</span>
				<label class="switch">
					<input type="checkbox" id="afectado_testigo" value="1" checked>
					<span class="slider round"></span>
				</label>
				<span>Testigo</span>
			</div>


			<input type="submit" value="Añadir incidente" class="form-button col-md-6" name="formData">
		</form>
	</section>

@endsection

@section('scripts')
{{--	<script src="{{asset('js/user-profile.js')}}"></script>--}}
	<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

	<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>

@endsection