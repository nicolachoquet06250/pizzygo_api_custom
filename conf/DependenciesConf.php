<?php
namespace custom;

class DependenciesConf extends \core\DependenciesConf {
	public $conf = [
		'queues_plugin' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
		'oauth2_server' => 'https://github.com/bshaffer/oauth2-server-php.git',
	];
}