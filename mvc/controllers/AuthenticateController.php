<?php
	namespace custom;
	use core\Controller;
	use core\Response;
	use Exception;

	class AuthenticateController extends Controller {

		/** @var AuthenticateModel $model */
		public $model;

		/**
		 * @http_verb post
		 * @return Response
		 * @throws Exception
		 */
		public function index() {
			return $this->authenticate();
		}

		/**
		 * @return \core\ErrorController|Response
		 * @throws Exception
		 */
		public function authenticate() {
			$check = $this->model->checkToken($this->post('token'), $this->post('data'));
			return $check ? $this->OK(["secureData" => "Oo"]) // And we add the new token for the next request
				: $this->PAGE_NOT_FOUND('erreur lors de la création du token');
		}
	}