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

			$result = compact(['username', 'contacts']);
			return view('fav_contacts.contacts', $result);
		}
		return redirect()->route('index');
	}

	public function nuevoContacto() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];

			$result = compact(['username']);
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

	public function addContacto(Request $request) {
		if(isset($request['userId'])) {
			$user = User::where('email', session('email'))->first();
			$contact = User::where('id', $request['userId'])->first();

			$input = array(
				'usuario_id' => $user['id'],
				'contacto_favorito_id' => $contact['id']
			);
			
			ContactosFavoritos::create($input);
		}
	}
}
