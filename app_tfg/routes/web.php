<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UsersController@index')->name('index');
Route::post('/', 'UsersController@login');

/* ********** REGISTRO ********** */
// Route::get('/registro', 'UsersController@registro');
Route::view('/registro', 'register')->name('registro'); //Devuelvo la vista
Route::post('/registro', 'UsersController@create');
Route::post('/registro-paso-2', 'UsersController@store');
// Route::get('/registro-paso-2', 'UsersController@create');  //Devuelvo la vista

Route::get('/logout', 'UsersController@logout')->name('logout');

/* ********** ZONA PERSONAL ********** */
Route::get('/zona-personal', 'UsersController@zonaPersonal')->name('zonaPersonal');
Route::post('/zona-personal', 'UsersController@update');
Route::get('/user_config', 'UsersController@getUserConfig');
Route::post('/user_config', 'UsersController@setUserConfig');

/* ********** INCIDENTES ********** */
Route::get('/mapa-incidentes','IncidentsController@mapaIncidentes')->name('mapaIncidentes');
Route::post('/get_map_incidents', 'IncidentsController@getMapIncidents');

Route::get('/lista-incidentes','IncidentsController@listaIncidentes')->name('listaIncidentes');
Route::post('/get_incident_details', 'IncidentsController@getIncidentDetails');
Route::get('/nuevo-incidente','IncidentsController@create')->name('nuevoIncidente');
Route::post('/nuevo-incidente','IncidentsController@store');
Route::get('/get_delitos','IncidentsController@getDelitos');

Route::get('/mis-publicaciones-incidentes', 'IncidentsController@incidentesSubidos')->name('incidentesSubidos');

Route::post('/ajax','AjaxController@index');
Route::get('/ajax_config', 'AjaxController@getConfigParams');
Route::post('/ajax_config', 'AjaxController@setConfigParams');


/* ********** CONTACTOS FAVORITOS ********** */
Route::get('/contactos-favoritos','FavContactsController@contactosFavoritos')->name('contactosFavoritos');
Route::get('/ordenar-contactos-favoritos','FavContactsController@ordenarContactosFavoritos')
	->name('ordenarContactosFavoritos');
Route::post('/ordenar-contactos-favoritos','FavContactsController@updateContactsOrder');
Route::get('/nuevo-contacto-favorito', 'FavContactsController@nuevoContacto')->name('nuevoContacto');
Route::get('/de-quien-soy-contacto', 'FavContactsController@whoseContactIm')->name('deQuienSoyContacto');
Route::post('/buscar_contacto', 'FavContactsController@buscarContacto');
Route::post('/add_fav_contact', 'FavContactsController@addContacto');
Route::post('/delete_reject_fav_contact', 'FavContactsController@removeRejectContact');
Route::post('/accept_favourite_contact', 'FavContactsController@acceptContact');

/* *********** ZONAS DE INTERÉS *********** */
Route::get('/zonas-interes', 'InterestAreasController@zonasInteres')->name('zonasInteres');
Route::get('/nueva-zona-interes', 'InterestAreasController@nuevaZonaInteres')->name('nuevaZona');
Route::post('/nueva-zona-interes', 'InterestAreasController@store');
Route::post('/get_interest_areas', 'InterestAreasController@getInterestAreas');
Route::post('/remove_interest_area', 'InterestAreasController@removeInterestArea');

//Route::get('/send_notification/{notification_type}/{usuario_id}/{contacto_favorito_id}',
//	'UserNotificationsController@sendNotification')->name('enviarNotificacion');
//Route::post('/reject_favourite_contact', 'FavContactsController@rechazarContacto');
Route::post('/mark_notification_as_read', 'UserNotificationsController@markNotificationAsRead');

// Administración
Route::get('/admin', 'UsersController@admin')->name('admin');
Route::post('/admin', 'UsersController@login');