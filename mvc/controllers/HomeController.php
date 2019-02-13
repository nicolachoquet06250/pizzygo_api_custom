<?php

namespace custom;

use core\AuthenticationKeys;
use core\Controller;
use core\ErrorController;
use core\JsonResponse;
use core\JsonService;
use core\Response;
use Exception;
use ReallySimpleJWT\Build;
use ReallySimpleJWT\Encode;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Validate;

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
	public function test_auth(MysqlConf $mysqlConf) {
		$auth_keys = AuthenticationKeys::create();
		$private = $auth_keys->get_actual_private_key();
		$public = $auth_keys->get_actual_public_key();

		$userId = 12;
		$secret = $private;
		$expiration = time() + 3600;
		$issuer = 'www.pizzygo.local';

		$token = Token::create($userId, $secret, $expiration, $issuer);

		$result = Token::validate($token, $private);

		$builder = new Build('JWT', new Validate(), new Encode());
		$token2 = $builder->setContentType('JWT')
				  ->setHeaderClaim('info', 'foo')
				  ->setSecret($private)
				  ->setIssuer('www.pizzygo.local')
				  ->setSubject('admins')
				  ->setAudience('www.pizzygo.local')
				  ->setExpiration(time() + 30)
				  ->setNotBefore(time() - 30)
				  ->setIssuedAt(time())
				  ->setJwtId('123ABC')
				  ->setPayloadClaim('uid', 12)
				  ->build();

		return $this->get_response(
			[
				'private_key' => $private,
				'public_key' => $public,
				'token' => $token,
				'token2' => $token2,
				'result' => $result,
			]
		);
	}
}