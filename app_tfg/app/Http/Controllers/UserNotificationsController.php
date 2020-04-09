<?php

namespace App\Http\Controllers;

use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;

class UserNotificationsController extends Controller
{
    public static function sendNotification($notificacion) {
    	if($notificacion['usuario_id']!=null && $notificacion['contacto_favorito_id']!=null){
			if($notificacion['notification_type']!=null) {
				$message = "";
				if ($notificacion['notification_type'] == 'befavcontact')
					$message = "quiere agregarte como contacto favorito";

				$user = User::where('id', $notificacion['usuario_id'])->first();
				$recipient = User::where('id', $notificacion['contacto_favorito_id'])->first();

				$details = [
					'notification_type' => $notificacion['notification_type'],
					'sender_id' => $user['id'],
					'sender_name' => $user['nombre'],
					'sender_email' => $user['email'],
					'recipient_id' => $recipient['id'],
					'recipient_name' => $recipient['nombre'],
					'message' => $message
				];

				Notification::send($recipient, new UserNotification($details));
			}
		}
	}

	public function markNotificationAsRead(Request $request) {
    	if($request['notificationId']!=null){
			$user = User::where('email',session('email'))->first();
			$unreadNotifications = $user->unreadNotifications->count();
			$user->unreadNotifications->where('id', $request['notificationId'])->markAsRead();

			return $unreadNotifications-1;
		}

    	return -1;
	}
}
