<?php
namespace custom;

class DependenciesConf extends \core\DependenciesConf {
	public $queues_plugin;
	public $oauth2_server;
	protected $conf = [
		'queues_plugin' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
		'oauth2' => [
			'repository' => 'https://github.com/RobDWaller/ReallySimpleJWT.git',
			'autoloader' => false,
			'autoloader_php' => '
				function load_oauth2_module($directory) {
				if(is_dir($directory) {
					$dir = opendir($directory);
						while(($elem = readdir($dir)) !== false) {
							if($elem !== \'.\' && $elem !== \'..\') {
								if(is_dir($directory.\'/\'.$elem) {
									load_oauth2_module($directory.\'/\'.$elem);
								}
								elseif(is_file()) {
									require_once $directory.\'/\'.$elem;
								}
							}
						}
					}
				}
				load_oauth2_module($dir = \''.__ROOT__.'/git_dependencies/oauth2/src\');
			'
		]
//		'oauth2_server' => [
//			'repository' => 'https://github.com/bshaffer/oauth2-server-php.git',
//			'autoloader' => 'src/OAuth2/Autoloader.php',
//			'autoloader_php' => 'OAuth2\Autoloader::register();',
//			'composer' => true,
//		],
	];
}