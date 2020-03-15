<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class IncidentsController extends Controller {
	public function mapaIncidentes() {


		$session = session('email');
		$username = User::where('email',$session)->first()->value('nombre');

		// Quizás no sea necesario devolver la sesión (email)
		$result = compact(['session','username']);
		return view('incidents.map',$result);
	}
}
