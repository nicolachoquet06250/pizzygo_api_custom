<?php

namespace custom;


use core\Singleton;

class MysqlConf extends \core\MysqlConf {
	use Singleton;

	protected function __construct() { parent::__construct(); }

	public $password;
	public $user;
	public $database;
	public $host;
}