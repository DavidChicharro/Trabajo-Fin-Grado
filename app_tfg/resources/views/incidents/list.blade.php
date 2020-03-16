@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)


@section('content')
	<h2>Lista de incidentes</h2>
	<section class="main-content mx-1">
		<div class="my-3 w-75">
			<a href="#">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/mapa-incidentes">Ver mapa</a>
		</div>
		<div class="incidents">
			@for($i=1; $i<5; $i++)
				<article class="incident px-2 py-3 mb-1 w-75 bg-warning">
					<table class="w-100">
						<tbody>
							<tr>
								<td><h5>Incidente {{$i}}</h5></td>
								<td class="w-25"><small class="float-right">dd/mm/yyyy - hh:mm</small></td>
							</tr>
							<tr>
								<td class="pt-3">Lugar</td>
								<td class="w-25 pt-3 text-right"><a href="#" class="text-right">Ver más</a></td>
							</tr>
						</tbody>
					</table>
				</article>
			@endfor
		</div>
	</section>

@endsection
