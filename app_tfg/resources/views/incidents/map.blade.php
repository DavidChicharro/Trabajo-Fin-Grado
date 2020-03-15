@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)


@section('content')
    <main class="col-7 mt-4 p-5">
        <h2>Mapa de incidentes</h2>
        <img class="img-fluid" src="{{asset('images/mapa-grx.png')}}">
        <p>Body content</p>

    </main>
@endsection
