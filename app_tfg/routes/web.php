<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'UsersController@index')->name('index');
Route::post('/', 'UsersController@login');

// Route::get('/registro', 'UsersController@registro');
Route::view('/registro', 'register'); //Devuelvo la vista
Route::post('/registro', 'UsersController@create');
Route::post('/registro-paso-2', 'UsersController@store');
// Route::get('/registro-paso-2', 'UsersController@create');  //Devuelvo la vista

Route::get('/logout', 'UsersController@logout');

Route::get('/zona_personal', 'UsersController@zonaPersonal');

Route::get('/mapa-incidentes','IncidentsController@mapaIncidentes');
Route::get('/lista-incidentes','IncidentsController@listaIncidentes');