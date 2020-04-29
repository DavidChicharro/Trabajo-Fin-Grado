<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
	public function login(Request $request) {
		$data = $request->validate([
			'email' => 'required',
			'password' => 'required'
		]);

		$user_exist = User::where('email',$data['email'])->count();

		if($user_exist){
			$user_pass = User::where('email',$data['email'])->first();

			if(Hash::check($data['password'], $user_pass['password'])){
//				session(['email' => $data['email']]);
				return response()
					->json([
						'status' => 'success',
						'message' => 'Login correcto'
					], 200);

				/*if($user_pass['es_admin'] == 0)
					return redirect()->route('listaIncidentes');
				else
					return redirect()->route('admin');*/
			}
			else{
				return response()
					->json([
						'status' => 'error',
						'message' => '¡El email y la contraseña no coinciden!'
					], 401);
				/*return redirect()->back()
					->withInput($request->only('email'))
					->withErrors(['message'=>'¡El email y la contraseña no coinciden!']);*/
			}
		}else{
			return response()
				->json([
					'status' => 'error',
					'message' => '¡El usuario introducido no existe!'
				], 401);
			/*return redirect()->back()
				->withInput($request->only('email'))
				->withErrors(['message'=>'¡El usuario introducido no existe!']);*/
		}
	}
}
