<?php
namespace custom;

use core\Entity;


class PhoneEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $phone
	 * @not_null
	 * @size(10);
	 */
	protected $phone = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @entity user
	 * @size(11)
	 */
	protected $user_id = 0;
}