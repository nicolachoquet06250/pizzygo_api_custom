<?php

namespace custom;


use core\DbContext;

class PizzygoContext extends DbContext {
	/** @var UserDao $users */
	public $users;
	/** @var OrderDao $orders */
	public $orders;
}