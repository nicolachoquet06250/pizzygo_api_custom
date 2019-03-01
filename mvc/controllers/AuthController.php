<?php


namespace custom;


use core\AuthenticationService;
use core\CookieService;
use core\Response;
use core\SessionService;
use core\UrlsService;

class AuthController extends \core\AuthController {

	public function index(AuthenticationService $authenticationService = null, CookieService $cookieService = null, SessionService $sessionService = null, UrlsService $urlsService = null): Response {
		if($this->get('email')) {
			$this->email = $this->get('email');
		}
		if($this->get('password')) {
			$this->passwd = $this->get('password');
		}
		return parent::index($authenticationService, $cookieService, $sessionService, $urlsService);
	}

	public function auth(AuthenticationService $authenticationService, UrlsService $urlsService) {
		/** @var UserDao $userDao */
		$userDao = $this->get_dao('user');
		if (!$this->get('email') && !$this->get('password')) {
			return $this->FORBIDDEN('email and password are required');
		}
		if ($user = $userDao->getByEmailAndPassword($this->get('email'), $this->get('password'))) {
			$this->user_id = $user->get('id');

			return parent::auth($authenticationService, $urlsService);
		}
		return $this->FORBIDDEN('email, password, or both are incorrect');
	}
}