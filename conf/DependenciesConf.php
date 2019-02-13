<?php
namespace custom;

class DependenciesConf extends \core\DependenciesConf {
	public $conf = [
		'queues_plugin' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
		'oauth2_server' => [
			'repository' => 'https://github.com/bshaffer/oauth2-server-php.git',
			'autoloader' => 'src/OAuth2/Autoloader.php',
			'autoloader_php' => 'OAuth2\Autoloader::register();',
			'composer' => true,
		],
	];
}