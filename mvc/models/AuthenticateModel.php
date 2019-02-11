<?php
	namespace custom;
	use core\BaseModel;

	class AuthenticateModel extends BaseModel {
		const SECRET_KEY = 'fakesecretkey';
		const VALIDITY_TIME = 3600;

		function createToken($data) {
			/* Create a part of token using secretKey and other stuff */
			$tokenGeneric = self::SECRET_KEY.$_SERVER["SERVER_NAME"]; // It can be 'stronger' of course

			/* Encoding token */
			$token = hash('sha256', $tokenGeneric.$data);

			return [
				'token' => $token,
				'userData' => $data
			];
		}

		function auth($login, $password) {
			// we check user. For instance, it's ok, and we get his ID and his role.
			$userID = 1;
			$userRole = "admin";

			// Concatenating data with TIME
			$data = time()."_".$userID."-".$userRole;
			$token = $this->createToken($data);
			return $token;
		}

		function checkToken($receivedToken, $receivedData) {
			/* Recreate the generic part of token using secretKey and other stuff */
			$tokenGeneric = self::SECRET_KEY.$_SERVER["SERVER_NAME"];

			// We create a token which should match
			$token = hash('sha256', $tokenGeneric.$receivedData);

			// We check if token is ok !
			if ($receivedToken != $token) {
				echo 'wrong Token !';
				return false;
			}

			list($tokenDate, $userData) = explode("_", $receivedData);
			// here we compare tokenDate with current time using VALIDITY_TIME to check if the token is expired
			// if token expired we return false

			// otherwise it's ok and we return a new token
			return $this->createToken(time()."#".$userData);
		}
	}