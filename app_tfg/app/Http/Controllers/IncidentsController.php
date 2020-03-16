<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class IncidentsController extends Controller {
	public function mapaIncidentes() {
		$session = session('email');

		if(isset($session)) {
			$username = User::where('email', $session)->first()->value('nombre');

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['session', 'username']);
			return view('incidents.map', $result);
		}
		return redirect()->route('index');
	}

	public function listaIncidentes() {
		$session = session('email');

		if(isset($session)) {
			$username = User::where('email', $session)->first()->value('nombre');

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['session', 'username']);
			return view('incidents.list', $result);
		}
		return redirect()->route('index');
	}
}
