@extends('layouts.admin-base')

@section('title', 'Usuarios')
@section('username', $username)
@section('stylesheet')
	<link href="{{asset('css/login.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/>
@endsection

@section('content')

	<h2 class="section-title pl-1 pl-sm-5 px-md-1">Usuarios</h2>

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
			<div class="datatables-wrapper mb-5">
				<table id="table" class="table table-striped table-condensed">
					<thead class="bg-dark text-white">
						<tr>
							<th class="bg-dark text-white">ID</th>
							<th class="bg-dark text-white">Email</th>
							<th class="bg-dark text-white">Nombre</th>
							<th class="bg-dark text-white">Apellidos</th>
							<th class="bg-dark text-white">D.N.I.</th>
							<th class="bg-dark text-white">Fecha nacimiento</th>
							<th class="bg-dark text-white">Fecha Alta</th>
							<th class="bg-dark text-white">Teléfono</th>
							<th class="bg-dark text-white">Acción de pánico</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>

@endsection

@section('scripts')
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
	<script src="{{asset('js/users/users.js')}}"></script>
@endsection
