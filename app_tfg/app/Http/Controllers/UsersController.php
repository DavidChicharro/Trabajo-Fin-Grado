<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller {
	public function index() {
		$session = session('email');
		if(isset($session))
			return view('index',['session' => $session]);
		else
			return view('login-user');
	}

	public function admin() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];

//			config(['api.caducidad_incidentes.radio' => '30']);
//			$val = config('api.caducidad_incidentes');
//			dd($val);

			$result = compact(['session', 'username']);
			return view('admin', $result);
		}
		return view('login-admin');
//		return view('admin',['session' => $session]);
	}

	/**
	 * Valida los datos de auteticación del usuario.
	 * Si existe y la contraseña es correcta inicia sesión;
	 * si es incorrecta informa al usuario de que no coinciden.
	 * Si el usuario no existe se informa de ello.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	 public function login(Request $request) {
         $datos = $request->validate([
             'email' => 'required',
             'password' => 'required'
         ]);

         $user_exist = User::where('email',$datos['email'])->count();

         if($user_exist){
             $match = ['email'=>$datos['email'], 'password'=>$datos['password']];
             $user_pass = User::where($match)->first();	// todos los datos del usuario de la BD
//             dd($user_pass);
             if(!is_null($user_pass)){
             	//coinciden email y contraseña
			 	session(['email' => $datos['email']]);

				if($user_pass['es_admin'] == 0)
					return redirect()->route('listaIncidentes');
				else
					return redirect()->route('admin');
             }
             else{
				//no coinciden email y contraseña
				return redirect()->back()
					->withInput($request->only('email'))
					->withErrors(['message'=>'¡El email y la contraseña no coinciden!']);
             }
         }else{
         	return redirect()->back()
				->withInput($request->only('email'))
				->withErrors(['message'=>'¡El usuario introducido no existe!']);
         }

	 }

	/**
	 * Cierra la sesión
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function logout() {
		if(session('email')) {
			session()->forget('email');
			session()->flush();
		}

		return redirect()->route('index');
	}

	/**
	 * Si los datos del usuario son válidos y no existen en la BD,
	 * se devuelve la vista del segundo paso para el registro.
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function create(Request $request) {
		$datos = $request->validate([
			'email' => 'required',
			'password' => 'required'
		]);

		$user_exist = User::where('email',$datos['email'])->count();

		if($user_exist==0){
			//return view register-2 with params mail and password(encoded)
			return view('register-step-2')
				->with('datos',$datos);
		}else{
			return redirect()->back()
				->withErrors([
					'message'=>'¡El usuario introducido ya se encuentra registrado!'
				]);
		}
    }

	/**
	 * Registra a un usuario
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
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

		session(['email' => $datos['email']]);
		return redirect()
			->route('index')
			->with('message', '¡Registrado correctamente!');
	}

	public function email_available() {

	}

    public function zonaPersonal() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
//dd($user);

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['username','user']);
			return view('user-profile', $result);
		}
		return redirect()->route('index');

    }

    public function cambiarDatosUsuario() {

	}
}
