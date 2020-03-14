<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SessionsController;
use App\User;


class UsersController extends Controller {
	public function index() {
		// $mail = 'david@mail.com';
		// if(SessionsController::accessSessionData($request, $mail)){
		// 	$value = SessionsController::accessSessionData($request, $mail);
		// 	dd($value);
		// }
		// else{

		// }
		if(session('email')){
			// echo "Hay sesión\n";
			return view('index',['session' => session('email')]);

		}else{
			echo "NO hay sesión\n";
		}
		
	}

	// public function registro() {
	// 	return view ('register');
	// }

	public function create(Request $request) {
		$datos = $request->validate([
			'email' => 'required', 
			'password' => 'required'
		]);

		// dd($datos['email']);
		$user_exist = User::where('email',$datos['email'])->count();

		if($user_exist==0){
			//return view register-2 with params mail and password(encoded)
			return view('register-step-2')->with('datos',$datos);
		}else{
			//existe el usuario
			$var = "else";
			dd($var);
		}
	}

	public function store(Request $request) {
		$datos = $request->validate([
			'email' => 'required', 
			'password' => 'required', 
			'nombre' => 'required', 
			'apellidos' => 'required', 
			'dni' => 'required', 
			'fecha_nacimiento' => 'required',
			'telefono' => 'required'
		]);

		User::create($datos);

		/*$session = */session(['email' => $datos['email']]);
		// $request = SessionsController::storeSessionData($request, $datos['email']);
		return redirect()->route('index');
		// return back();
	}

	public function email_available() {

	}

    public function zonaPersonal() {

    }
}
