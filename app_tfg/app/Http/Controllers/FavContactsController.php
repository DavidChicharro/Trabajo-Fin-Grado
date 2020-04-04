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
}
