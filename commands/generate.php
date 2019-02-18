<?php
namespace custom;

use core\Base;
use Exception;

class generate extends \core\generate {
	/**
	 * @throws Exception
	 */
	public function vendor_user(UserDao $user_dao, RoleDao $role_dao) {
		$name = $this->get_arg('name');
		$surname = $this->get_arg('surname');
		$email = $this->get_arg('email');
		$phone = $this->get_arg('phone');
		$address = $this->get_arg('address');
		$password = $this->get_arg('password');
		$description = $this->get_arg('description');
		$profil_img = '';
		$premium = false;
		$active = true;
		$activate_token = '';
		$user = $user_dao->create(function (Base $object) use ($name, $surname, $email, $phone, $address,
			$password, $description, $profil_img, $premium,
			$active, $activate_token) {
			/** @var UserEntity $user */
			$user = $object->get_entity('user');
			$user->initFromArray(
				[
					'name' => $name,
					'surname' => $surname,
					'email' => $email,
					'phone' => $phone,
					'address' => $address,
					'password' => sha1(sha1($password)),
					'description' => $description,
					'profil_img' => $profil_img,
					'premium' => $premium,
					'active' => $active,
					'activate_token' => $activate_token,
				]
			);
			return $user;
		});

		$role_dao->create(function (Base $_object) use ($user) {
			/** @var RoleEntity $role */
			$role = $_object->get_entity('role');
			$role->initFromArray(
				[
					'role' => RoleEntity::VENDOR,
					'user_id' => $user->get('id'),
				]
			);
			return $role;
		});

		return $user->toArrayForJson();
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function customer_user(UserDao $user_dao, RoleDao $role_dao) {
		$name = $this->get_arg('name');
		$surname = $this->get_arg('surname');
		$email = $this->get_arg('email');
		$phone = $this->get_arg('phone');
		$address = $this->get_arg('address');
		$password = $this->get_arg('password');
		$description = $this->get_arg('description');
		$profil_img = '';
		$premium = false;
		$active = true;
		$activate_token = '';

		$user = $user_dao->create(function (Base $object) use ($name, $surname, $email, $phone, $address,
			$password, $description, $profil_img, $premium,
			$active, $activate_token) {
			/** @var UserEntity $user */
			$user = $object->get_entity('user');
			$user->initFromArray(
				[
					'name' => $name,
					'surname' => $surname,
					'email' => $email,
					'phone' => $phone,
					'address' => $address,
					'password' => sha1(sha1($password)),
					'description' => $description,
					'profil_img' => $profil_img,
					'premium' => $premium,
					'active' => $active,
					'activate_token' => $activate_token,
				]
			);
			return $user;
		});

		$role_dao->create(function (Base $_object) use ($user) {
			/** @var RoleEntity $role */
			$role = $_object->get_entity('role');
			$role->initFromArray(
				[
					'role' => RoleEntity::USER,
					'user_id' => $user->get('id'),
				]
			);
			return $role;
		});

		return $user->toArrayForJson();
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function admin_user(UserDao $user_dao, RoleDao $role_dao) {
		$name = $this->get_arg('name');
		$surname = $this->get_arg('surname');
		$email = $this->get_arg('email');
		$phone = $this->get_arg('phone');
		$address = $this->get_arg('address');
		$password = $this->get_arg('password');
		$description = $this->get_arg('description');
		$profil_img = '';
		$premium = false;
		$active = true;
		$activate_token = '';

		$user = $user_dao->create(function (Base $object) use ($name, $surname, $email, $phone, $address,
			$password, $description, $profil_img, $premium,
			$active, $activate_token) {
			/** @var UserEntity $user */
			$user = $object->get_entity('user');
			$user->initFromArray(
				[
					'name' => $name,
					'surname' => $surname,
					'email' => $email,
					'phone' => $phone,
					'address' => $address,
					'password' => sha1(sha1($password)),
					'description' => $description,
					'profil_img' => $profil_img,
					'premium' => $premium,
					'active' => $active,
					'activate_token' => $activate_token,
				]
			);
			return $user;
		});

		$role_dao->create(function (Base $_object) use ($user) {
			/** @var RoleEntity $role */
			$role = $_object->get_entity('role');
			$role->initFromArray(
				[
					'role' => RoleEntity::ADMIN,
					'user_id' => $user->get('id'),
				]
			);
			return $role;
		});
		return $user->toArrayForJson();
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function update_password_user(UserDao $user_dao) {
		$email = $this->get_arg('user').'.user@pizzygo.fr';
		/** @var UserEntity $user */
		if($user = $user_dao->getByEmail($email)) {
			$user->set('password', sha1(sha1($this->get_arg('password'))));
			if($user->save()) {
				return 'La modification à eu lieux avec succès !!';
			}
			else {
				return 'Une erreur est survenue lors de la modification !!';
			}
		}
		return 'Aucun utilisateur n\'à été trouvé !!';
	}
}