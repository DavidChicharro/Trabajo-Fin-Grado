<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class AjaxController extends Controller
{
    public function index(Request $request) {
//    	$d = Delito::orderBy('id','desc')->first();
//		DB::select("")
//		dd($request);
		$input = $request->all();
		$incident_id = $input['incidentId'];



    	$var = "Estoy en el index del controlador de AJAX";
    	return response()->json(array('msg'=>$var, 'request'=>$incident_id), 200);
//    	return "Estoy en el index del controlador de AJAX";
//    	dd($var);
//    	echo $var;
	}

	private function getParams($key) {
		return config(('api.'.$key));
	}

	public function getZonasInteresParms() {
    	return $this->getParams('zonas_interes');
	}

	public function getConfigParams(Request $request) {
    	$modalParams = null;
		if (isset($request['params'])) {
			switch ($request['params']) {
				case 'caduc':
					$modalParams = $this->getParams('caducidad_incidentes');
					break;
				case 'cfav':
					$modalParams = $this->getParams('contactos_favoritos');
					break;
				case 'zint':
					$modalParams = $this->getParams('zonas_interes');
					break;
				default:
					break;
			}
		}
		return $modalParams;
	}

	private function setParams($key, $params) {
    	foreach ($params as $param => $value) {
			config(['api.' . $key . '.' . $param => $value]);
		}

		$fp = fopen(base_path().'/config/api.php','w');
		fwrite($fp, '<?php return '.var_export(config('api'), true).';');
	}

	public function setConfigParams(Request $request){
    	if (isset($request['configId']) and !empty($request['values'])) {
    		$key = "";
    		switch ($request['configId']){
				case 'caduc':
					$key = 'caducidad_incidentes';
					break;
				case 'cfav':
					$key = 'contactos_favoritos';
					break;
				case 'zint':
					$key = 'zonas_interes';
					break;
			}
			$this->setParams($key, $request['values']);
		}
	}

	public function getData(Request $request){
    	$data = array(
    		0 => array(
    			"nombre" => "David",
				"edad" => 22
			),
			1 => array(
				"nombre" => "Javier",
				"edad" => 21
			),
		);

		return response()->json($data);
	}

	/*public function testMap() {
		$user = User::where('email', 'david@mail.com')->first();
		$notifications = $user->unreadNotifications;
		return view('testMap', compact(['notifications']));
	}

	public function testMap2() {
		$user = User::where('email', 'david@mail.com')->first();
		$notifications = $user->unreadNotifications;
		return view('testMap2', compact(['notifications']));
	}*/

	public function sendMail() {
		$user = User::where('id', 1)->first();

		if (!is_null($user)) {
			$mailData = [
				'nameReceiver' => $user['nombre']
			];

//			Mail::to($user['email'])->send(new SendMail($mailData));

			$email = new \SendGrid\Mail\Mail();
			$email->setFrom("info@kifungo.live", "Kifungo");
			$email->setSubject("Asunto :)");
			$email->addTo("test@example.com", "Example User");
			$email->addTo($user['email'], $user['nombre']);
			$email->addContent(
				"text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
			);
			$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

			try {
				$response = $sendgrid->send($email);
				print $response->statusCode() . "\n\n";
				print_r($response->headers());
				print "\n\n" . $response->body() . "\n";
			} catch (\Exception $e) {
				echo 'Caught exception: '. $e->getMessage() ."\n";
			}
		}
	}
}
