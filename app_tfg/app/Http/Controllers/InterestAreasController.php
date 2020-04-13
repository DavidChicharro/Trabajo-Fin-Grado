<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class InterestAreasController extends Controller
{
    public function zonasInteres() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

//			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$interestAreas = array();
//			foreach ($groupIncidents as $id => $incident){
//				$incidentTypes[$id] = $incident[0]['nombre_delito'];
//			}

			$result = compact(['username', 'notifications', 'interestAreas']);
			return view('interest_areas.areas', $result);
		}
		return redirect()->route('index');
	}

    public function getUserInterestAreas(Request $request) {

	}

	public function nuevaZonaInteres() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$interestAreas = array();

			$result = compact(['username', 'notifications', 'interestAreas']);
			return view('interest_areas.new-area', $result);
		}
		return redirect()->route('index');
	}
}
