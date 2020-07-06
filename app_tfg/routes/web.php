<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UsersController@index')->name('index');
//Route::get('/', 'IncidentsController@listaIncidentes')->name('index');
Route::post('/', 'UsersController@login');
//Route::get('/', 'UsersController@index')->name('login');
//Route::get('login', [ 'as' => 'login', 'uses' => 'UsersController@index']);

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
//Route::get('/incidente', 'IncidentsController@getIncident')->name('incidente');
Route::get('/incidente', 'IncidentsController@incidente')->name('incidente');
Route::get('/get_incident_details', 'IncidentsController@getIncidentDetails');
Route::post('/ajax_hideIncident', 'IncidentsController@hideIncident');
Route::post('/ajax_removeIncident', 'IncidentsController@removeIncident');
Route::get('/nuevo-incidente','IncidentsController@nuevoIncidente')->name('nuevoIncidente');
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
Route::get('/usuarios', 'UsersController@users')->name('users');
Route::get('/ajax_getUsers', 'UsersController@getUsers');

Route::get('test', 'UserNotificationsController@test');

// Pruebas
Route::get('/get_data', 'AjaxController@getData');

// API
Route::get('/api/login', 'API\UsersController@login');
Route::get('/api/check_user', 'API\UsersController@create');
Route::get('/api/regist_user', 'API\UsersController@store');
Route::get('/api/update_user', 'API\UsersController@update')->middleware('auth:api');
Route::get('/api/update_pass', 'API\UsersController@updatePass')->middleware('auth:api');
Route::get('/api/get_user_data', 'API\UsersController@getUserData')->middleware('auth:api');
Route::get('/api/get_config', 'API\UsersController@getUserConfig')->middleware('auth:api');
Route::get('/api/set_config', 'API\UsersController@setUserConfig')->middleware('auth:api');

Route::get('/api/update_location', 'API\UsersController@setLocation')->middleware('auth:api');
Route::get('/api/share_location', 'API\UsersController@shareLocation')->middleware('auth:api');
Route::get('/api/get_user_location', 'API\UsersController@getUserLocation')->middleware('auth:api');

Route::get('/api/get_notifications', 'API\UsersController@getNotifications')->middleware('auth:api');
Route::get('/api/mark_notification_as_read', 'API\UsersController@markNotificationAsRead')->middleware('auth:api');

Route::get('/api/get_list_incidents', 'API\IncidentsController@getList');
Route::get('/api/get_list_centers_incidents_areas', 'API\IncidentsController@getCentersIncidentsAreas')->middleware('auth:api');
Route::get('/api/get_uploaded_incidents', 'API\IncidentsController@getUploadedIncidentsByUser')->middleware('auth:api');
Route::get('/api/get_delitos', 'API\IncidentsController@getDelitos');
Route::get('/api/store_incident', 'API\IncidentsController@store')->middleware('auth:api');

Route::get('/api/get_interest_areas', 'API\InterestAreasController@getInterestAreas')->middleware('auth:api');
Route::get('/api/new_area', 'API\InterestAreasController@newArea')->middleware('auth:api');
Route::get('/api/store_interest_area', 'API\InterestAreasController@store')->middleware('auth:api');

Route::get('/api/get_fav_contacts','API\FavContactsController@getFavContacts')->middleware('auth:api');
Route::get('/api/search_contact','API\FavContactsController@searchContact')->middleware('auth:api');
Route::get('/api/add_contact','API\FavContactsController@addContact')->middleware('auth:api');
Route::get('/api/get_whose_contact_im','API\FavContactsController@whoseContactIm')->middleware('auth:api');
Route::get('/api/accept_favourite_contact', 'API\FavContactsController@acceptContact')->middleware('auth:api');
Route::get('/api/remove_reject_contact','API\FavContactsController@removeRejectContact')->middleware('auth:api');
Route::get('/api/update_contacts_order','API\FavContactsController@updateContactsOrder')->middleware('auth:api');;

Route::get('login', [ 'as' => 'login', 'uses' => 'API\UsersController@failAuthAPI']);

//Route::post('/api/test', 'API\IncidentsController@test');
//
//
//Route::get('test_map', 'AjaxController@testMap');
//Route::get('test_map_2', 'AjaxController@testMap2');
//Route::get('test_calc', 'IncidentsController@calcIncidentsSeverityLevel');
//Route::get('set_centers', 'IncidentsController@setCentersSeverityLevel');
//
//Route::get('/tweet', 'IncidentsController@publishIncidentTwitter');

Route::get('test_token', function() {
	$user = \Auth::user();
	return $user;
})->middleware('auth:api');

// Política de Privacidad y Términos y Condiciones
Route::get('/politica-privacidad', function () {
	return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terminos-y-condiciones', function () {
	return view('terms');
})->name('terms');

Route::get('/send_mail', 'AjaxController@sendMail');
Route::get('/confirm-password', 'AjaxController@confPswd')->name('confirmPswd');
