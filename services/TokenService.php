<?php
	namespace custom;

	class TokenService extends \core\TokenService {
		public function generate_token_for_user(UserEntity $user = null) {
			if(!is_null($user)) {
				return sha1(sha1(sha1(md5($user->get('email').$user->get('name').$user->get('surname')))));
			}
			return parent::generate_token_for_user();
		}
	}