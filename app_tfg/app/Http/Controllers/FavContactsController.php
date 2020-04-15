<?php

namespace App\Http\Controllers;

use App\ContactosFavoritos;
use App\User;
use Illuminate\Http\Request;

class FavContactsController extends Controller
{
    public function contactosFavoritos() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$data = ContactosFavoritos::where('usuario_id',$user['id'])
				->join('users', 'son_contactos_favoritos.contacto_favorito_id', '=', 'users.id')
				->orderBy('orden')
				->get()->toArray();

//			dd($data);
			$contacts = [];
			if(!empty($data)){
				$order = 1;
				foreach ($data as $key => $favContact){
					if($favContact['son_contactos'] == 1){
						$contacts[$key]['orden_real'] = $favContact['orden'];
						$contacts[$key]['orden_vista'] = $order;
						$contacts[$key]['nombre'] = $favContact['nombre']." ".$favContact['apellidos'];
						$contacts[$key]['fav_contact_id'] = $favContact['id'];
						$contacts[$key]['email'] = $favContact['email'];
						$contacts[$key]['telefono'] = $favContact['telefono'];
					}
					$order++;
				}
//				dd($contacts);
			}

			$result = compact(['username', 'notifications', 'contacts']);
			return view('fav_contacts.contacts', $result);
		}
		return redirect()->route('index');
	}

	public function nuevoContacto() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$result = compact(['username', 'notifications']);
			return view('fav_contacts.new-contact', $result);
		}
		return redirect()->route('index');
	}

	public function buscarContacto(Request $request) {
		if(isset($request['contact'])) {
			$user_phone_email = str_replace(' ', '', $request['contact']);
			$phonePattern = "/^([6,7][0-9]{8})$/";
			if( filter_var($user_phone_email, FILTER_VALIDATE_EMAIL) ||
				preg_match($phonePattern, $user_phone_email) ){
				$me = User::where('email', session('email'))->first();

				$user = User::where('email', $user_phone_email)
					->orWhere('telefono', $user_phone_email)->first();

				if(is_null($user))
					return "";

				$contact = ContactosFavoritos::where('usuario_id', $me['id'])
					->where('contacto_favorito_id', $user['id'])->first();

				$result['id'] = $user['id'];
				$result['name'] = $user['nombre']." ".$user['apellidos'];
				$is_contact = is_null($contact) ? false : true;
				if($is_contact)
					$result['is_fav'] = $contact['son_contactos'];
				else
					$result['is_fav'] = false;

				// Si el usuario no es él mismo
				if($user['email'] != session('email'))
					return $result;

				return "";
			}
		}
		return "";
	}

	/**
	 * Petición para ser contacto favorito
	 *
	 * @param Request $request
	 */
	public function addContacto(Request $request) {
		if(!is_null($request['userId'])) {
			$user = User::where('email', session('email'))->first();
			$contact = User::where('id', $request['userId'])->first();

			$input = array(
				'usuario_id' => $user['id'],
				'contacto_favorito_id' => $contact['id']
			);

			if(!is_null($request['petition'])) {
				if ($request['petition'] == "false") {
					ContactosFavoritos::create($input);
				} else {
					$favContact = ContactosFavoritos::where('usuario_id', $user['id'])
						->where('contacto_favorito_id', $contact['id'])
						->first();

					$favContact['son_contactos'] = 0;
					$favContact->save();
				}
			}

			//Crear notificación
			$notification = array_merge(array('notification_type'=>'befavcontact'), $input);
			UserNotificationsController::sendNotification($notification);
		}
	}

	/**
	 * Eliminar o rechazar un contacto favorito. En ambos casos se
	 * mantiene la relación pero cambian dos entradas para reflejarlo
	 *
	 * @param Request $request
	 * @return string|null
	 */
	public function removeRejectContact(Request $request) {
		$session = session('email');

		if( isset($session)) {
			$user = User::where('email', $session)->first();

			if(isset($request['contactId'])) { // rechazar
				$contact = $request['contactId'];
				$favContactUser = $user['id'];
			}elseif ($request['userId']){ // eliminar
				$contact = $user['id'];
				$favContactUser = $request['userId'];
			}

			$favContact = ContactosFavoritos::where('usuario_id', $contact)
				->where('contacto_favorito_id', $favContactUser)
				->first();

			$favContact['son_contactos'] = 2;
			$favContact['contador'] = $favContact['contador'] + 1;
			$favContact->save();

			return "success";
		}
		return null;
	}
	

	public function acceptContact(Request $request) {
    	if($request['userId']!=null && $request['favContactId']!=null) {
    		$usersRelation = ContactosFavoritos::where('usuario_id', $request['userId'])
				->where('contacto_favorito_id', $request['favContactId'])->first();

			if($usersRelation['son_contactos'] == 0) {
				$usersRelation['son_contactos'] = 1;
				$usersRelation->save();

				return "success";
			}
		}
    	return null;
	}
}
