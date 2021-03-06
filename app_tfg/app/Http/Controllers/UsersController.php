<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller {
	public function index() {
		$session = session('email');
		if (isset($session))
			return redirect()->route('listaIncidentes');
		else
			return view('login-user');
	}

	public function admin() {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			if ($user['es_admin'] == 1) {
				$username = $user['nombre'];
				$notifications = $user->unreadNotifications;

				$result = compact(['session', 'username', 'notifications']);
				return view('admin', $result);
			} else
				return redirect()->back();
		}
		return view('login-user');
	}

	public function getUsers(Request $request) {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			if ($user['es_admin'] == 1) {
				if (!$request->ajax()) {
					abort(404);
				}
				$data = [];

				$results = User::all()->toArray();
				if (!empty($results)) {
					foreach ($results as $res) {
						$data[] = [
							"id" => $res['id'],
							"email" => $res['email'],
							"name" => $res['nombre'],
							"surnames" => $res['apellidos'],
							"dni" => $res['dni'],
							"birthday" => $res['fecha_nacimiento'],
							"dateRegister" => $res['fecha_alta'],
							"tlf" => $res['telefono'],
							"panicAction" => $res['accion_panico']
						];
					}
				}

				return response()->json([
					'data' => $data
				]);
			}
		}

		return response()
			->json([
				'status' => 'error'
			], 400);
	}

	public function users() {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			if ($user['es_admin'] == 1) {
				$username = $user['nombre'];
				$notifications = $user->unreadNotifications;

				$result = compact(['username', 'notifications']);
				return view('users.users', $result);
			} else
				return redirect()->back();
		}
		return view('login-user');
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

         $user_exist = User::where('email', $datos['email'])->count();

         if ($user_exist) {
             $user_pass = User::where('email', $datos['email'])->first();

			 if (Hash::check($datos['password'], $user_pass['password'])) {
			 	session(['email' => $datos['email']]);

				if ($user_pass['es_admin'] == 0)
					return redirect()->route('listaIncidentes');
				else {
					session(['admin' => true]);
					return redirect()->route('admin');
				}
             }
             else {
				return redirect()->back()
					->withInput($request->only('email'))
					->withErrors(['message'=>'¡El email y la contraseña no coinciden!']);
             }
         } else {
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
		if (session('email')) {
			session()->forget('email');
			session()->forget('admin');
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
			'email' => 'bail|required|min:7|max:255',
			'password' => 'bail|required|min:8',
		]);

		$user_exist = User::where('email', $datos['email'])->count();

		if ($user_exist==0) {
			return view('register-step-2')
				->with('datos',$datos);
		} else {
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
			'email' => 'bail|required|min:7|max:255',
			'password' => 'bail|required|min:8',
			'nombre' => 'bail|required|min:2|max:255',
			'apellidos' => 'bail|required|min:2|max:255',
			'dni' => 'bail|required|min:9|max:10',
			'fecha_nacimiento' => 'bail|required|date|before:-12years',
			'telefono' => 'bail|required|regex:/([6-7])[0-9]{8}/'
		]);
		$datos['password'] = Hash::make($datos['password'],['rounds'=>15]);
		$datos['api_token'] = Str::random(255);
		$datos['remember_token'] = Str::random(100);

		User::create($datos);

		session(['email' => $datos['email']]);
		return redirect()
			->route('mapaIncidentes')
			->with('message', '¡Registrado correctamente!');
	}

	public function update(Request $request) {
		$session = session('email');
		$userEmail = $session;
		$user = User::where('email', $userEmail)->first();

		if ($request->has('formData')) {
			$request->validate([
				'email' => 'bail|required|min:7|max:255',
				'nombre' => 'bail|required|min:2|max:255',
				'apellidos' => 'bail|required|min:2|max:255',
				'fecha_nacimiento' => 'bail|required|date|before:-12years',
				'telefono' => 'bail|required|regex:/([6-7])[0-9]{8}/',
				'telefono_fijo' => 'nullable|regex:/([8-9])[0-9]{8}/'
			]);

			// Si modifica el email
			if ($request['email'] != $userEmail) {
				$email_exist = User::where('email', $request['email'])->count();

				//Válido->Cambio
				if ($email_exist != 0) {
					return redirect()->route('zonaPersonal')
						->withErrors([
							'message' => '¡El email introducido pertenece a otro usuario!'
						]);
				}
				session(['email' => $request['email']]);
			}
			// Si modifica el teléfono
			$userTlf = $user['telefono'];
			if ($request['telefono'] != $userTlf) { //Cambia el teléfono
				$tlf_exist = User::where('telefono', $request['telefono'])->count();
				if ($tlf_exist != 0) {
					return redirect()->route('zonaPersonal')
						->withErrors([
							'message' => '¡El teléfono introducido pertenece a otro usuario!'
						]);
				}
			}
			$input = $request->all();
		} elseif ($request->has('formPass')) {
			$request->validate([
				'password' => 'bail|required|min:8',
				'new_password' => 'bail|required|min:8'
			]);

			if (!Hash::check($request['password'], $user['password'])) {
				return redirect()->route('zonaPersonal')
					->withErrors([
						'message' => '¡La contraseña es incorrecta!'
					]);
			}
			$input = ['password' => Hash::make($request['new_password'],['rounds'=>15])];
		}

		if (isset($input)) {
			$user->fill($input)->save();

			return redirect()->route('zonaPersonal')
				->with('message', '¡Datos actualizados correctamente!');
		}

		return redirect()->route('zonaPersonal')
			->withErrors([
				'message' => '¡Algo salió mal!'
			]);
	}

    public function zonaPersonal() {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$result = compact(['username', 'notifications', 'user']);
			return view('user-profile', $result);
		}
		return redirect()->route('index');
    }

	/**
	 * Devuelve los parámetros de configuración de acción de pánico y pin secreto del usuario
	 * @param Request $request
	 * @return |null
	 */
	public function getUserConfig(Request $request){
		$modalParams = null;
		if (isset($request['params'])) {
			switch ($request['params']) {
				case 'panicact':
					$modalParams = User::where('email', session('email'))->value('accion_panico');
					break;
				case 'secretpin':
					$modalParams = User::where('email', session('email'))->value('pin_secreto');
					break;
				default:
					$modalParams = null;
					break;
			}
		}
		return $modalParams;
	}

	public function setUserConfig(Request $request){
		if (isset($request['configId']) and !empty($request['value'])) {
			$input = null;
			switch ($request['configId']){
				case 'panicact':
					$input['accion_panico'] = $request['value'];
					break;
				case 'secretpin':
					$input['pin_secreto'] = $request['value'];
					break;
				default:
					break;
			}
			$user = User::where('email', session('email'))->first();
			$user->fill($input)->save();
		}
	}
}
