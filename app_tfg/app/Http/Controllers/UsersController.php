<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SessionsController;
use App\User;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller {
	public function index() {

        return view('index',['session' => session('email')]);
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
             $user_pass = User::where($match)->first();
//             dd($user_pass);
             if(!is_null($user_pass)){
             	//coinciden email y contraseña
				session(['email' => $datos['email']]);
				return redirect()->route('index');
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
		if(session('email'))
			session()->forget('email');

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
			return view('register-step-2')->with('datos',$datos);
		}else{
			return redirect()->back()
				->withErrors(['message'=>'¡El usuario introducido ya se encuentra registrado!']);
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
		return redirect()->route('index')->with('message', '¡Registrado correctamente!');
	}

	public function email_available() {

	}

    public function zonaPersonal() {

    }
}
