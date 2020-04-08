<?php

namespace App\Http\Controllers;

use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;

class UserNotificationsController extends Controller
{
    public function sendNotification(Request $request) {
    	if($request['usuario_id']!=null && $request['contacto_favorito_id']!=null){
//			dd($request['usuario_id']);
			// Creo la notificación
			// Envío la notificación
			$user = User::where('id', $request['usuario_id'])->first();
			$recipient = User::where('id', $request['contacto_favorito_id'])->first();

			$details = [
				'notification_type' => 'befavcontact',
				'sender_id' => $user['id'],
				'sender_name' => $user['nombre'],
				'recipient_id' => $recipient['id'],
				'recipient_name' => $recipient['name'],
				'message' => "quiere agregarte como contacto favorito"
			];

			Notification::send($recipient, new UserNotification($details));

		}
	}
}
