<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UsersController@index')->name('index');
Route::post('/', 'UsersController@login');

// Route::get('/registro', 'UsersController@registro');
Route::view('/registro', 'register'); //Devuelvo la vista
Route::post('/registro', 'UsersController@create');
Route::post('/registro-paso-2', 'UsersController@store');
// Route::get('/registro-paso-2', 'UsersController@create');  //Devuelvo la vista

Route::get('/logout', 'UsersController@logout');

Route::get('/zona_personal', 'UsersController@zonaPersonal');

Route::get('/mapa-incidentes','IncidentsController@mapaIncidentes')->name('mapaIncidentes');
Route::get('/lista-incidentes','IncidentsController@listaIncidentes')->name('listaIncidentes');
Route::post('/get_incident_details', 'IncidentsController@getIncidentDetails');

Route::post('/ajax','AjaxController@index');


// Administración
Route::get('/admin', 'UsersController@admin')->name('admin');
Route::post('/admin', 'UsersController@login');