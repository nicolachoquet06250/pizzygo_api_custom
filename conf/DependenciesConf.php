<?php
namespace custom;

class DependenciesConf extends \core\DependenciesConf {
	public $queues_plugin;
	public $oauth2_server;
	protected $conf = [
		'queues_plugin' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
		'jwt' 			=> [
			'repository' => 'https://github.com/nicolachoquet06250/experience_framework_jwt.git',
			'composer' => true,
		],
	];
}
