<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserNotificationsController;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
	public function login(Request $request) {
		$data = $request->validate([
			'email' => 'required',
			'password' => 'required'
		]);

		$user_exist = User::where('email',$data['email'])->count();

		if ($user_exist) {
			$user_pass = User::where('email',$data['email'])->first();

			if (Hash::check($data['password'], $user_pass['password'])) {
				return response()
					->json([
						'status' => 'success',
						'message' => 'Login correcto',
						'api_token' => $user_pass['api_token']
					], 200);

				/*if($user_pass['es_admin'] == 0)
					return redirect()->route('listaIncidentes');
				else
					return redirect()->route('admin');*/
			}
			else {
				return response()
					->json([
						'status' => 'error',
						'message' => '¡El email y la contraseña no coinciden!'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario introducido no existe!'
			], 200);
	}

	public function getUserData() {
		$user = Auth::user();

		if(!is_null($user)) {
			return response()
				->json([
					'status' => 'success',
					'userData' => $user
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario introducido no existe!'
			], 200);
	}

	/**
	 * Si los datos del usuario son válidos y no existen en la BD,
	 * se devuelve la vista del segundo paso para el registro.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function create(Request $request) {
		$datos = $request->validate([
			'email' => 'bail|required|min:7|max:255',
		]);

		$user_exist = User::where('email',$datos['email'])->count();

		if($user_exist==0) {
			return response()
				->json([
					'status' => 'success',
				], 200);
		} else {
			return response()
				->json([
					'status' => 'error',
					'message' => '¡El usuario introducido ya se encuentra registrado!'
				], 200);
		}
    }

	/**
	 * Registra a un usuario
	 *
	 * @param Request $request
	 * @return JsonResponse
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
		$datos['password'] = Hash::make($datos['password'], ['rounds'=>15]);
		$datos['api_token'] = Str::random(255);
		$datos['remember_token'] = Str::random(100);

		User::create($datos);

		return response()
			->json([
				'status' => 'success',
				'message' => '¡Registrado correctamente!'
			], 200);
	}

	public function update(Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			$userEmail = $user['email'];    //email almacenado hasta el momento

			$request->validate([
				'email' => 'bail|required|min:7|max:255',
				'nombre' => 'bail|required|min:2|max:255',
				'apellidos' => 'bail|required|min:2|max:255',
				'fecha_nacimiento' => 'bail|required|date|before:-12years',
				'telefono' => 'bail|required|regex:/([6-7])[0-9]{8}/'
			]);
			$request['telefono_fijo'] = ($request['telefono_fijo']=='null') ? null : $request['telefono_fijo'];

			// Si modifica el email
			if ($request['email'] != $userEmail) {
				$email_exist = User::where('email', $request['email'])->count();

				//Válido->Cambio
				if ($email_exist != 0) {
					return response()
						->json([
							'status' => 'error',
							'message' => '¡El email introducido pertenece a otro usuario!'
						], 200);
				}
				$notifyNewEmail = $request['email'];
			}
			// Si modifica el teléfono
			$userTlf = $user['telefono'];
			if ($request['telefono'] != $userTlf) {
				$tlf_exist = User::where('telefono', $request['telefono'])->count();
				if ($tlf_exist != 0) {
					return response()
						->json([
							'status' => 'error',
							'message' => '¡El teléfono introducido pertenece a otro usuario!'
						], 200);
				}
			}
			$input = $request->all();

			if (isset($input)) {
				$user->fill($input)->save();

				return response()
					->json([
						'status' => 'success',
						'message' => 'Datos actualizados correctamente',
						'newEmail' => isset($notifyNewEmail) ? $notifyNewEmail : null
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 401);
	}

	public function updatePass (Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			if (isset($request['password']) && isset($request['newPass'])) {
				$request->validate([
					'password' => 'bail|required|min:8',
					'newPass' => 'bail|required|min:8'
				]);

				if (!Hash::check($request['password'], $user['password'])) {
					return response()
						->json([
							'status' => 'error',
							'message' => '¡La contraseña es incorrecta!'
						], 401);
				}
				$input = ['password' => Hash::make($request['newPass'], ['rounds'=>15])];

				if (isset($input)) {
					$user->fill($input)->save();

					return response()
						->json([
							'status' => 'success',
							'message' => 'Contraseña actualizada correctamente'
						], 200);
				}
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 401);
	}
	
	public function getUserConfig (Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			$email = $user['email'];

			$configParams = null;
			if(isset($request['params']) && !is_null($user)) {
				switch ($request['params']) {
					case 'panicact':
						$configParams = User::where('email', $email)->value('accion_panico');
						break;
					case 'secretpin':
						$configParams = User::where('email', $email)->value('pin_secreto');
						break;
					default:
						$configParams = null;
						break;
				}
				
				return response()
				->json([
					'status' => 'success',
					'configParams' => $configParams
				], 200);
			}			
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 401);
	}

	public function setUserConfig(Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			if (isset($request['configId']) and !empty($request['value'])) {
				$input = null;
				switch ($request['configId']) {
					case 'panicact':
						$value = ($request['value'] == 'null') ? null : $request['value'];
						$input['accion_panico'] = $value;
						break;
					case 'secretpin':
						$input['pin_secreto'] = $request['value'];
						break;
					default:
						break;
				}

				$user->fill($input)->save();
				return response()
					->json([
						'status' => 'success',
						'input' => $input
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 401);
	}

	public function setLocation(Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			if (isset($request['lat']) and isset($request['lng'])) {
				$input['latitud_actual'] = ($request['lat'] == 'null') ? null : $request['lat'];
				$input['longitud_actual'] = ($request['lng'] == 'null') ? null : $request['lng'];

				$user->fill($input)->save();
				return response()
					->json([
						'status' => 'success'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 200);
	}

	public function shareLocation(Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			if (!empty($request['contacts'])) {
				$contacts = $request['contacts'];

				$notification = [
					'userId' => $user['id'],
					'contactsIds' => $contacts
				];

				$notifCtrl = new UserNotificationsController();
				$notifCtrl->notifyShareLocation($notification);

				return response()
					->json([
						'status' => 'success'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 200);
	}

	public function getUserLocation(Request $request) {
		$user = Auth::user();

		if (!is_null($user) && isset($request['contactId'])) {
			$contact = User::where('id', $request['contactId'])->first();

			if (!is_null($contact)) {
				$lat = $contact['latitud_actual'];
				$lng = $contact['longitud_actual'];

				if (!is_null($lat) & !is_null($lng)) {
					$location = [
						'lat' => $lat,
						'lng' => $lng
					];

					return response()
						->json([
							'status' => 'success',
							'location' => $location
						], 200);
				}
				return response()
					->json([
						'status' => 'error',
						'location' => -1,
						'lat' => $lat,
						'lng' => $lng
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 200);
	}

	public function getNotifications() {
		$user = Auth::user();

		if (!is_null($user)) {
			$notifications = $user->unreadNotifications;

			return response()
				->json([
					'status' => 'success',
					'notifications' => $notifications
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
				'message' => 'Error en la petición'
			], 401);
	}

	public function markNotificationAsRead(Request $request) {
		$user = Auth::user();

		if (!is_null($user) && !is_null($request['notificationId'])) {
			$unreadNotifications = $user->unreadNotifications->count();
			$user->unreadNotifications->where('id', $request['notificationId'])->markAsRead();

			return response()
				->json([
					'status' => 'success',
					'unreadNotifications' => $unreadNotifications - 1
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
			], 200);
	}

	public function failAuthAPI() {
		return response()
			->json([
				'status' => 'error',
			], 200);
	}
}
