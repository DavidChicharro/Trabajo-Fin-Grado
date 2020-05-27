<?php

namespace App\Http\Controllers;

use App\Delito;
use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;

class UserNotificationsController extends Controller
{
	private function calcDistance($latitudeFrom, $longitudeFrom,
	$latitudeTo, $longitudeTo, $earthRadius = 6371000) {
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);

		return $angle * $earthRadius;
	}

	/**
	 * Envía una notificación
	 *
	 * @param $notificacion
	 */
    public static function sendNotification($notificacion) {
    	if (isset($notificacion['usuario_id']) && isset($notificacion['contacto_favorito_id'])){
			if(!is_null($notificacion['notification_type'])) {
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
		} else if (isset($notificacion['usuario_id']) && !is_null($notificacion['notification_type'])) {
			if ($notificacion['notification_type'] == 'interest_area_incident') {
				$delito = Delito::where('id', $notificacion['incident']['delito_id'])
					->value('nombre_delito');
				$date = date('d/m/Y', strtotime($notificacion['incident']['fecha_hora_incidente']));
				$time = date('H:i', strtotime($notificacion['incident']['fecha_hora_incidente']));

				$message = "Ha ocurrido un nuevo incidente en una de tus zonas de interés:" .
				ucfirst($delito) . ". Ocurrido en " . $notificacion['incident']['nombre_lugar'] .
				" el " . $date . " a las " . $time;

				$recipient = User::where('id', $notificacion['usuario_id'])->first();
				$details = [
					'notification_type' => $notificacion['notification_type'],
					'sender_id' => $notificacion['incident']['id'],
					'sender_name' => null,
					'sender_email' => null,
					'recipient_id' => $recipient['id'],
					'recipient_name' => $recipient['nombre'],
					'message' => $message
				];

				Notification::send($recipient, new UserNotification($details));
			}
		}
	}

	public function test() {
		$delito = Delito::where('id', 2)
			->value('nombre_delito');
		dd($delito);
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

	public function notifyNewIncident($incInput) {
		$latIncident = $incInput['latitud_incidente'];
		$lngIncident = $incInput['longitud_incidente'];

		$intAreasCtrl = new InterestAreasController();
		$interestAreas = $intAreasCtrl->getAllInterestAreas();

		foreach ($interestAreas as $interestArea) {
			$distance = $this->calcDistance(
				$interestArea['latitud_zona_interes'],
				$interestArea['longitud_zona_interes'],
				$latIncident,
				$lngIncident
			);

			if ($distance < $interestArea['radio_zona_interes']) {
				// Notifico
				$notification = array(
					'usuario_id' => $interestArea['usuario_id'],
					'incident' => $incInput,
					'notification_type' => 'interest_area_incident'
				);

				$this->sendNotification($notification);
			}
		}
	}
}
