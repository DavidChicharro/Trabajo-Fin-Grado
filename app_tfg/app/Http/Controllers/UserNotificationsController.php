<?php

namespace App\Http\Controllers;

use App\Delito;
use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;

class UserNotificationsController extends Controller
{
	/**
	 * Envía una notificación
	 *
	 * @param $notificacion
	 */
    public static function sendNotification($notificacion) {
    	switch ($notificacion['notification_type']) {
			case 'befavcontact':
				if (isset($notificacion['usuario_id']) && isset($notificacion['contacto_favorito_id'])) {
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
				break;
			case 'interest_area_incident':
				if (isset($notificacion['usuario_id'])) {
					$delito = Delito::where('id', $notificacion['incident']['delito_id'])
						->value('nombre_delito');
					$date = date('d/m/Y', strtotime($notificacion['incident']['fecha_hora_incidente']));
					$time = date('H:i', strtotime($notificacion['incident']['fecha_hora_incidente']));

					$message = "Ha ocurrido un nuevo incidente en una de tus zonas de interés: " .
						ucfirst($delito) . ". En " . $notificacion['incident']['nombre_lugar'] .
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
				break;
			case 'share_location_panic':
				$message = 'está en una posible situación de peligro y ha activado el botón del pánico.';

				$details = [
					'notification_type' => $notificacion['notification_type'],
					'sender_id' => $notificacion['user']['id'],
					'sender_name' => $notificacion['user']['nombre'],
					'sender_email' => $notificacion['user']['email'],
					'recipient_id' => $notificacion['contact']['id'],
					'recipient_name' => $notificacion['contact']['nombre'],
					'message' => $message
				];

				Notification::send($notificacion['contact'], new UserNotification($details));
				break;
			default:
				break;
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
			$incCtrl = new IncidentsController();
			$distance = $incCtrl->calcDistance(
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

	public function notifyShareLocation($input) {
		$user = User::where('id', $input['userId'])->first();
		$contacts = explode(',', $input['contactsIds']);

		foreach ($contacts as $contactId) {
			$contact = User::where('id', $contactId)->first();

			if (!is_null($user) & !is_null($contact)) {
				$notification = array(
					'notification_type' => 'share_location_panic',
					'user' => $user,
					'contact' => $contact
				);

				$this->sendNotification($notification);
			}
		}
	}
}
