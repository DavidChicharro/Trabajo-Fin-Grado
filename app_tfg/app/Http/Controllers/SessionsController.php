<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller {
	public function accessSessionData(Request $request) {
		if($request->session()->has('email'))
			echo $request->session()->get('email');
		else
			echo "No data in the session";
	}

    public function storeSessionData(Request $request) {
    	$request->session()->put('email','user@mail.com');
    	echo "Data has been added to session";
    }
}
