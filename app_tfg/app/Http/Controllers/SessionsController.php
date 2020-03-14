<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller {
	public static function accessSessionData(Request $request, $mail) {
		if($request->session()->has($mail))
			return $request->session()->get($mail);
		else
			echo "No data in the session";
	}

    public static function storeSessionData(Request $request, $mail) {
    	$request->session()->put('email',$mail);
    	return $request;
    }
}
