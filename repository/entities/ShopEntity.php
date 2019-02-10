<?php
namespace custom;

use core\Entity;
use Exception;


class ShopEntity extends Entity {
		/**
		 * @var int $id
		 * @not_null
		 * @primary
		 */
		protected $id = 0;
		/**
		 * @var int $user_id
		 * @not_null
		 * @entity user
		 */
		protected $user_id = 0;
		/**
		 * @var string $name
		 * @not_null
		 * @size(255)
		 */
		protected $name = '';
		/**
		 * @var string $description
		 * @not_null
		 * @text
		 */
		protected $description = '';

		/**
		 * @return mixed
		 */
		public function get_nb_likes() {
			$query = $this->get_mysql()->query('SELECT COUNT(id) FROM '.$this->get_table_name().' WHERE `shop_id`='.$this->get('id'));
			list($count) = $query->fetch_row();
			return $count;
		}

		/**
		 * @return bool
		 * @throws Exception
		 */
		public function I_liked_this() {
			$query = $this->get_mysql()->query('SELECT COUNT(id) FROM '.$this->get_table_name().' WHERE `shop_id`='.$this->get('id').' AND `user_id`='.$this->get_service('session')->get('user')['id']);
			list($count) = $query->fetch_row();
			return $count === 1;
		}

		/**
		 * @param bool $recursive
		 * @return array
		 * @throws Exception
		 */
		public function toArrayForJson($recursive = true) {
			$array = parent::toArrayForJson();

			/** @var UserDao $user_dao */
			$user_dao = $this->get_dao('user');
			/** @var UserEntity|bool $user */
			$user = $user_dao->getById($array['user_id']);
			$user = $user ? $user->toArrayForJson() : $array['user_id'];
			$array['user_id'] = $user;

			return $array;
		}
	}
