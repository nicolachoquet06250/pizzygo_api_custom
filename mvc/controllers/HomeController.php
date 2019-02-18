<?php

namespace custom;

use core\AuthenticationService;
use core\Controller;
use core\ErrorController;
use core\JsonResponse;
use core\JsonService;
use core\Response;
use Exception;
use MiladRahimi\Jwt\Enums\PublicClaimNames;

class HomeController extends Controller {

	/**
	 * @inheritdoc
	 * @alias_method home
	 * @throws Exception
	 */
	public function index(): JsonResponse {
		return $this->home();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function home(): JsonResponse {
		return $this->get_response(
			[
				'controller' => 'home',
				'get' => $this->http_service->get(),
				'session' => $this->http_service->session(),
				'server' => $_SERVER['SERVER_SOFTWARE'],
			]
		);
	}

	/**
	 * @param string $address
	 * @param int $limit
	 * @param bool $coordinates
	 * @return ErrorController|Response
	 * @throws Exception
	 */
	public function addresses(JsonService $json_service): JsonResponse {
		if(!$this->get('address')) {
			return $this->PAGE_NOT_FOUND('Vous devez entrer votre address');
		}
		$url_request = 'https://api-adresse.data.gouv.fr/search/?q='.str_replace(' ', '+', $this->get('address'));
		if($this->get('limit')) {
			$url_request .= '&limit='.$this->get('limit');
		}
		$result = file_get_contents($url_request);
		$result = $json_service->decode($result, true);
		if($this->get('coordinates')) {
			$result = $result['features'][0]['geometry']['coordinates'];
			$result['latitude'] = $result[0];
			$result['longitude'] = $result[1];
			unset($result[0]);
			unset($result[1]);
		}
		return $this->get_response($result);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function test_auth(AuthenticationService $authenticationService) {
		if(!is_null($this->get('disconnect'))) {
			$authenticationService->disconnect($this->get_base_url().'/home/test_auth');
		}

		if(!$authenticationService->authenticated()) {
			if(!is_null($this->post('auth'))) {
				$authenticationService->add_claim(PublicClaimNames::SUBJECT, 1)
					 ->add_claim(PublicClaimNames::ID, 2);
				if($authenticationService->authenticate()) {
					return $this->get_response(
						[
							'referer' => $this->get_base_url().'/home/test_auth',
						]
					);
				}
			}
			return $this->get_response('<head>
	<script src="https://code.jquery.com/jquery-3.3.1.js"
			integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
			crossorigin="anonymous"></script>
	<script>
		$(window).ready(() => {
		    $(\'#auth\').on(\'click\', () => {
		    	$.ajax({
		    		url: \''.$this->get_base_url().'/home/test_auth\',
		    		method: \'post\',
		    		data: {
		    		    auth: true,
		    		    debug: true
		    		}
		    	}).done(data => {
		    	    window.location.href = data.referer;
		    	});
		    });
		});
	</script>
</head>
<input type="button" name="auth" id="auth" value="demander l\'authentification">', Response::HTML);
		}
		return $this->get_response('<input type="button" onclick="window.location.href=\''.$this->get_base_url().'/home/test_auth?disconnect=1\'" 
												value="déconnection">
<input type="button" onclick="window.location.href=\''.$this->get_base_url().'/home/test_auth2\'" value="page 2">', Response::HTML);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function test_auth2(AuthenticationService $authenticationService) {
		if(!is_null($this->get('disconnect'))) {
			$authenticationService->disconnect($this->get_base_url().'/home/test_auth');
		}
		if(!$authenticationService->authenticated()) {
			$authenticationService->redirect($this->get_base_url().'/home/test_auth');
		}
		var_dump($authenticationService->get_connected_user_id());
		return $this->get_response('<input type="button" value="page 1" onclick="window.location.href=\''.$this->get_base_url().'/home/test_auth\'" /><input type="button" 
													onclick="window.location.href=\''.$this->get_base_url().'/home/test_auth?disconnect=1\'" 
													value="déconnection" />', Response::HTML);
	}
}