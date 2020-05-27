<?php

namespace App\Http\Controllers\API;

use App\ContactosFavoritos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FavContactsController as FavContactsCtrl;
use App\Http\Controllers\UserNotificationsController;
use App\User;
use Illuminate\Http\Request;

class FavContactsController extends Controller {
	public function getFavContacts(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$favContactsCtrl = new FavContactsCtrl();
				$contacts = $favContactsCtrl->getFavouriteContacts($user['id']);

				return response()
					->json([
						'status' => 'success',
						'favContacts' => $contacts
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function searchContact(Request $request) {
		if (isset($request['email'])) {
			$me = User::where('email', $request['email'])->first();

			if (!is_null($me)) {
				if (isset($request['contact'])) {
					$user_phone_email = str_replace(' ', '', $request['contact']);
					$phonePattern = "/^([6,7][0-9]{8})$/";
					if (filter_var($user_phone_email, FILTER_VALIDATE_EMAIL) ||
						preg_match($phonePattern, $user_phone_email)) {

						$user = User::where('email', $user_phone_email)
							->orWhere('telefono', $user_phone_email)->first();

						if (is_null($user))
							return response()
								->json([
									'status' => 'error',
									'message' => '¡El usuario no existe!'
								], 200);

						$contact = ContactosFavoritos::where('usuario_id', $me['id'])
							->where('contacto_favorito_id', $user['id'])->first();

						$result['id'] = $user['id'];
						$result['name'] = $user['nombre'] . " " . $user['apellidos'];
						$is_contact = is_null($contact) ? false : true;
						if ($is_contact)
							$result['is_fav'] = $contact['son_contactos'];
						else
							$result['is_fav'] = false;

						// Si el usuario no es él mismo
						if ($user['email'] != session('email'))
							return response()
								->json([
									'status' => 'success',
									'contact' => $result
								], 200);
					}
					return response()
						->json([
							'status' => 'error',
							'message' => 'Formato de email o teléfono inválido'
						], 200);
				}
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function addContact(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user) && !is_null($request['userId']) && !is_null($request['petition'])) {
				$contact = User::where('id', $request['userId'])->first();

				$favContactsCtrl = new FavContactsCtrl();
				$favContactsCtrl->addContact($user['id'], $contact['id'], $request['petition']);

				return response()
					->json([
						'status' => 'success'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function acceptContact(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user) && !is_null($request['favContactId'])) {
				$usersRelation = ContactosFavoritos::where('usuario_id', $request['favContactId'])
					->where('contacto_favorito_id', $user['id'])->first();

				if ($usersRelation['son_contactos'] == 0) {
					$usersRelation['son_contactos'] = 1;
					$usersRelation->save();

					return response()
						->json([
							'status' => 'success',
						], 200);
				}
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function removeRejectContact(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$user = User::where('email', $user['email'])->first();

				if (isset($request['contactId'])) { // Si se rechaza la petición para ser contacto favorito
					$contact = $request['contactId'];
					$favContactUser = $user['id'];
				} elseif (isset($request['userId'])) { // Si se elimina un contacto favorito
					$contact = $user['id'];
					$favContactUser = $request['userId'];

					// Si un usuario se elimina como contacto favorito
					if ($request['swap'] == "true")
						[$contact, $favContactUser] = [$favContactUser, $contact];
				}

				$favContact = ContactosFavoritos::where('usuario_id', $contact)
					->where('contacto_favorito_id', $favContactUser)
					->first();

				$favContact['son_contactos'] = 2;
				$favContact['orden'] = 99;
				$favContact['contador'] = $favContact['contador'] + 1;
				$favContact->save();

				return response()
					->json([
						'status' => 'success'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function updateContactsOrder(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				if (!is_null($request['order'])) {
					$order = explode(",", $request['order']);
					$favContactsCtrl = new FavContactsCtrl();
					$favContactsCtrl->setContactsOrder($order, $user['id']);

					return response()
						->json([
							'status' => 'success'
						], 200);
				}
				return response()
					->json([
						'status' => 'error',
						'message' => '¡El orden de contactos no es válido!'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function whoseContactIm(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$favContactsCtrl = new FavContactsCtrl();
				$contacts = $favContactsCtrl->getWhoseContactIm($user['id']);

				return response()
					->json([
						'status' => 'success',
						'contacts' => $contacts
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}
}
