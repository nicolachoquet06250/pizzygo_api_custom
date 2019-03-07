<?php
namespace custom;

use core\FactisSingleton;

class DependenciesConf extends \core\DependenciesConf {
	use FactisSingleton;
	protected $conf = [
		'queues_plugin' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
		'jwt' 			=> [
			'repository' => 'https://github.com/nicolachoquet06250/experience_framework_jwt.git',
			'composer' => true,
		],
	];
}
