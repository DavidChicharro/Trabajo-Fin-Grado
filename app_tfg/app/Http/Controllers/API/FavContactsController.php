<?php

namespace App\Http\Controllers\API;

use App\ContactosFavoritos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FavContactsController as FavContactsCtrl;
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
}
