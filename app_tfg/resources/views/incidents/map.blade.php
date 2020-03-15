@extends('layouts.base')

@section('title', 'Mapa de incidentes')

@section('sidebar')
{{--    @parent--}}

    <p>This appends to the master sidebar</p>
@endsection

@section('content')
    <p>Body content</p>
    <p>{{$username}}</p>
@endsection
