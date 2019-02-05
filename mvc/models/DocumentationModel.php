<?php

	class DocumentationModel extends BaseModel {
		private $routes;
		private $section_template = <<<HTML
			<div class="row" id="{{write_json_response}}">
				<div class="card">
					<div class="card-title" style="padding-left: 15px; padding-top: 10px;">
						<div class="row {{affichage}}">
							<div class="col s12">
								<h5 class="title">{{title}}</h5>
							</div>
						</div>
					</div>
					<div class="card-content">
						<div class="row">
							<div class="col s12">
								<p>
									{{describe}}
								</p>
							</div>
							<div class="col s10">
								<div class="row">
									<div class="col s12 api_url">
										<code><pre><b>{{http_method}} [domain]/api/index.php{{url}}</b></pre></code>	
									</div>
									<div class="col s12 api_url">
										 <code><pre><i>{{alias}}</i></pre></code>
									</div>
								</div>
							</div>
							<div class="col s2">
								<span data-badge-caption="" class="http-code-{{write_json_response}}"></span>
							</div>
							<div class="col s12">
								{{input_fields}}
							</div>
							<div class="col s12" style="max-height: 300px; overflow: auto;">
								<pre class="write_json_response {{write_json_response}}"><code></code></pre>
							</div>
						</div>
					</div>
				</div>
			</div>
HTML;

		/**
		 * @param $http_verb
		 * @param $url
		 * @param array $params
		 * @param null $alias
		 * @param string $title
		 * @param string $describe
		 * @param bool $request_active
		 * @return mixed
		 * @throws Exception
		 */
		private function get_section_template($http_verb, $url, $params = [], $alias = null, $title = '', $describe = '', $request_active = true) {
			$input_fields = '';
			foreach ($params as $param => $type) {
				if($type === 'string') {
					$type = 'text';
				}
				elseif ($type === 'int') {
					$type = 'number';
				}
				elseif ($type === 'bool' || $type === 'boolean') {
					$type = 'checkbox';
				}
				else {
					$type = 'text';
				}
				if($type === 'checkbox') {
					$input_fields .= '<div class="col s12 m6 l4">
	<div class="input-field">
		<p>
			<label>
				<input 	type="'.$type.'" value=1 
						class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" 
						id="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'"
						 placeholder="'.$param.'"/>
				<span>'.$param.'</span>
        	</label>
    	</p>
	</div>
</div>';
				}
				else {
					$input_fields .= '<div class="col s12 m6 l4">
	<div class="input-field">
		<label for="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'">'.$param.'</label>
		<input type="'.$type.'" class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" id="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'" placeholder="'.$param.'" />
	</div>
</div>';
				}
			}
			if($request_active) {
				$input_fields .= '<div class="col s12">
	<input 	type="button" class="btn orange" 
			data-url="/api/index.php'.$url.(!is_null($alias) ? '/'.$alias : '').'" 
			value="Envoyer" data-http_verb="'.$http_verb.'" 
			data-class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" />
</div>';
			}

			/** @var OsService $service_os */
			$service_os = $this->get_service('os');
			$describe = str_replace(' * ', '', $describe);
			$describe = str_replace($service_os->get_chariot_return(), '<br>', $describe);
			$describe = str_replace("\t", '', $describe);

			return str_replace(
				[
					'{{http_method}}',
					'{{url}}',
					'{{input_fields}}',
					'{{alias}}',
					'{{write_json_response}}',
					'{{title}}',
					'{{describe}}',
					'{{affichage}}'
				], [
					$http_verb,
					$url,
					$input_fields,
					(is_null($alias) ? '' : '[ALIAS '.$http_verb.' [domain]/api/index.php'.$url.'/'.$alias.']'),
					'write_json_response'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : ''),
					$title,
					$describe,
					($title === '' ? 'hide' : 'show')
				], $this->section_template
			);
		}

		/**
		 * @throws ReflectionException
		 * @throws Exception
		 */
		private function generate_routes() {
			/** @var OsService $service_os */
			$service_os = $this->get_service('os');
			$retour = $service_os->get_chariot_return();
			$routes = [];
			foreach ($this->get_controllers() as $controller) {
				$class = $controller;
				$controller = ucfirst($controller).'Controller';
				if(is_file(__DIR__.'/../controllers/'.$controller.'.php')) {
					require_once __DIR__.'/../controllers/'.$controller.'.php';
					$ref     = new ReflectionClass($controller);
					$class_doc = $ref->getDocComment();
					$class_doc = str_replace('/**'.$retour, '', $class_doc);
					$class_doc = str_replace($retour."\t */", '', $class_doc);
					$class_doc = str_replace($retour."\t\t */", '', $class_doc);
					$class_doc = explode($retour, $class_doc);
					$class_in_doc = true;
					$request = true;
					foreach ($class_doc as $line) {
						preg_match('`@not_in_doc`', $line, $matches);
						if(!empty($matches)) {
							$class_in_doc = false;
							continue;
						}

						preg_match('`@not_request`', $line, $matches);
						if(!empty($matches)) {
							$request = false;
							continue;
						}
					}
					if(!$class_in_doc) {
						continue;
					}

					$methods = $ref->getMethods();
					foreach ($methods as $method) {
						if ($method->getName() !== $class && $method->isPublic() && $method->class !== Controller::class && $method->class !== Base::class) {
							$params = [];
							$alias = null;
							$http_verb = 'GET';
							$title = '';
							$describe = '';
							$not_in_doc = false;
							$doc = $method->getDocComment();
							$doc = str_replace('/**'.$retour, '', $doc);
							$doc = str_replace($retour."\t */", '', $doc);
							$doc = str_replace($retour."\t\t */", '', $doc);
							$doc = explode($retour, $doc);
							foreach ($doc as $line) {
								preg_match('`@param ([a-z]+) \$([A-Za-z0-9\_]+)`', $line, $matches);
								if(!empty($matches)) {
									$params[$matches[2]] = $matches[1];
								}
								preg_match('`@alias_method ([a-zA-Z\_]+)`', $line, $matches);
								if(!empty($matches)) {
									$alias = $matches[1];
									continue;
								}
								preg_match('`@http_verb ([a-zA-Z]+)`', $line, $matches);
								if(!empty($matches)) {
									$http_verb = strtoupper($matches[1]);
									continue;
								}
								preg_match('`@not_in_doc`', $line, $matches);
								if(!empty($matches)) {
									$not_in_doc = true;
									continue;
								}

								preg_match('`@title ([^\r\n\@]+)`', $line, $matches);
								if(!empty($matches)) {
									$title = $matches[1];
									continue;
								}

								preg_match('`@not_request`', $line, $matches);
								if(!empty($matches)) {
									$method_request = false;
									continue;
								}

								preg_match('`@describe ([^\@]+)`', $line, $matches);
								if(!empty($matches)) {
									$describe = $matches[1];
									continue;
								}

								if(!strstr($line, '@')) {
									$describe .= $service_os->get_chariot_return().$line;
								}
							}

							$infos = [
								'alias' => $alias,
								'params' => $params,
								'http_verb' => $http_verb,
								'in_doc' => !$not_in_doc,
								'title' => $title,
								'describe' => $describe,
								'request' => (isset($method_request) ? $method_request : $request),
							];

							if ($method->getName() === 'index') {
								$routes[strtolower(str_replace('Controller', '', $controller))]['/'.$class] = $infos;
							}
							else {
								$routes[strtolower(str_replace('Controller', '', $controller))]['/'.$class.'/'.$method->getName()] = $infos;
							}
						}
					}
				}
			}

			$this->routes = $routes;
		}

		private function get_nb_routes_to_show($controller) {
			$routes = $this->routes[$controller];
			$count = 0;
			foreach ($routes as $route) {
				if($route['in_doc']) {
					$count++;
				}
			}
			return $count;
		}

		/**
		 * @return string
		 * @throws ReflectionException
		 * @throws Exception
		 */
		public function get_doc_content() {
			$sections = '';
			$sidenav_controllers = '';
			$this->generate_routes();

			ksort($this->routes);

			foreach ($this->routes as $controller => $routes) {
				if($controller !== 'errors') {
					$sections .= '<div class="row page" id="'.$controller.'">
	<div class="col s12 center-align">
		<h4>'.ucfirst($controller).' routes</h4>
	</div>
	<div class="col s12">';
					if($this->get_nb_routes_to_show($controller) === 0) {
						$sections .= '<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-title center-align" style="padding-left: 15px; padding-top: 10px; padding-bottom: 10px;">
				<h5>Il n\'y a aucune route à afficher dans le controlleur `'.$controller.'`</h5>
			</div>
		</div>
	</div>
</div>';
					}
					else {
						foreach ($routes as $route => $detail) {
							if ($detail['in_doc']) {
								$sections .= $this->get_section_template($detail['http_verb'], $route, $detail['params'], $detail['alias'], $detail['title'], $detail['describe'], $detail['request']);
							}
						}
					}
					$sections .= '</div></div>';
				}
			}

			foreach ($this->get_controllers() as $controller) {
				if($controller !== 'errors') {
					$sidenav_controllers .= '<li '.($controller === 'documentation' ? 'class="active"' : '').'>
	<a href="#'.strtolower($controller).'" class="page-changer">'.ucfirst($controller).'</a>
</li>';
				}
			}

			$object = <<<HTML
	<DOCTYPE html>
	<html lang="fr">
		<head>
        	<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="utf-8" />
			<title>Documentation Pizzygo API</title>
			<link rel="icon" href="/public/img/logo_pizzygo.png" />
			<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
			<link rel="stylesheet" href="/public/libs/materialize/css/materialize.min.css" />
			<script src="https://code.jquery.com/jquery-3.3.1.js"
			          integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
					  crossorigin="anonymous"></script>
			<script src="/public/libs/materialize/js/materialize.min.js"></script>
			<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/ocean.min.css">
			<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
			<script>
				$(window).ready(() => {
				    let valid_form = (http_verb, class_data, url) => {
				        let inputs = $('.' + class_data);
				        let data = {};
				        inputs.each((key, input) => {
				            let field = $(input).attr('placeholder');
				            data[field] = $(input).val();
				        });
				        data.debug = true;
				        $.ajax({
				        	beforeSend: () => {
								$('#write_json_response' + class_data)
								.append('<div class="col s4 offset-s4">' +
								 	'<img id="loader_' + class_data + '" src="/public/img/loader.gif" alt="loading..." />' +
								  '</div');
							},
				    		url: url,
				    		method: http_verb,
				    		data: data
				        }).done((data, textStatus, response) => {
				            $('#loader_' + class_data).remove();
				            $('.http-code-write_json_response' + class_data).html(response.status).addClass('new badge green white-text');
				            $('.write_json_response' + class_data).html(JSON.stringify(data, null, "  "));
				            hljs.highlightBlock(document.querySelector('.write_json_response' + class_data));
				        }).fail(response => {
				            $('#loader_' + class_data).remove();
				            let data = response.responseJSON;
				            $('.http-code-write_json_response' + class_data).html(response.status).addClass('new badge red white-text');
				            $('.write_json_response' + class_data).html(JSON.stringify(data, null, "  "));
				            hljs.highlightBlock(document.querySelector('.write_json_response' + class_data));
				        });
				    };
				    let resize_urls = () => {
				         $('.api_url').css('max-width', $(document).width() + 50 + 'px').css('overflow', 'auto');
				    };
				    let init_change_page = () => {
				        $('.sections .page').each((key, elem) => {
				            let id = $(elem).attr('id');
				            $(elem).css('display', 'none');
				            let onglet = $('#controllers a[href="#' + id + '"]').parent();
				            if (onglet.hasClass('active')) {
				                $(elem).css('display', 'block');
				            }
				        })
				    };
				    let change_page = (parent) => {
				        $('#controllers li').each((key, elem) => {
				            $(elem).removeClass('active');
				        });
				        parent.addClass('active');
				        init_change_page();
				    };
				    
				    init_change_page();
				    
				    $('input[type=button]').on('click', elem => {
				        valid_form($(elem.target).data('http_verb'), $(elem.target).data('class'), $(elem.target).data('url'));
				    });
				    
				    $('a.page-changer').on('click', elem => {
				        elem.preventDefault();
				        change_page($(elem.target).parent());
				    });
				    
				    resize_urls();
				    $(window).resize(resize_urls);
				    $('.sidenav').sidenav();
				});
			  </script>
		</head>
		<body>
			<nav>
				<div class="nav-wrapper orange">
					<a href="#" data-target="controllers"  class="brand-logo sidenav-trigger show-on-medium-and-up show-on-medium-and-down" >
						<img src="/public/img/logo_pizzygo.png" style="padding-left: 10px;height: 65px;" alt="logo" />
					</a>
            		<a href="#" data-target="menu-sidenav" class="sidenav-trigger">
            			<i class="material-icons">menu</i>
            		</a>
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li class="active"><a href="/api/index.php/documentation/developer">Développeur</a></li>
                		<li><a href="/api/index.php/documentation/user">Utilisateur</a></li>
                		<li><a href="/api/index.php/documentation/disconnect">Déconnection</a></li>
					</ul>
					<ul class="sidenav" id="menu-sidenav">
						<li class="active">
							<a href="/api/index.php/documentation/developer">Développeur</a>
						</li>
						<li>
							<a href="/api/index.php/documentation/user">Utilisateur</a>
						</li>
                		<li>
                			<a href="/api/index.php/documentation/disconnect">Déconnection</a>
                		</li>
					</ul>
					<ul class="sidenav" id="controllers">
						{$sidenav_controllers}
					</ul>
				</div>
			</nav>
			<header>
				<div class="container">
					<div class="row">
						<div class="col s12 center-align">
							<h1 class="title" style="font-size: 45px;">
								Documentation Pizzygo API
							</h1>
						</div>
					</div>
				</div>
			</header>
			<main>
				<div class="container sections">
					{$sections}
				</div>
			</main>
		</body>
	</html>
HTML;
			return $object;
		}

		public function get_connexion_content($error_message = null) {
			if(is_null($error_message)) {
				$error_message = '';
			}
			$color_class = $error_message === '' ? '' : 'red-text';
			$content = <<<HTML
	<DOCTYPE html>
	<html lang="fr">
		<head>
        	<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="utf-8" />
			<title>Documentation Pizzygo API</title>
			<link rel="icon" href="/public/img/logo_pizzygo.png" />
			<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
			<link rel="stylesheet" href="/public/libs/materialize/css/materialize.min.css" />
			<script src="https://code.jquery.com/jquery-3.3.1.js"
			          integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
					  crossorigin="anonymous"></script>
			<script src="/public/libs/materialize/js/materialize.min.js"></script>
			<script>
				$(window).ready(() => {
					$('.sidenav').sidenav();
				});
			</script>
		</head>
		<body>
			<nav>
				<div class="nav-wrapper orange">
					<a href="#" class="brand-logo">
						<img src="/public/img/logo_pizzygo.png" style="padding-left: 10px;height: 65px;" alt="logo" />
					</a>
            		<a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li class="active"><a href="/api/index.php/documentation/developer">Développeur</a></li>
                		<li><a href="/api/index.php/documentation/user">Utilisateur</a></li>
				  	</ul>
				  	<ul class="sidenav" id="mobile-demo">
						<li class="active"><a href="/api/index.php/documentation/developer">Développeur</a></li>
						<li><a href="/api/index.php/documentation/user">Utilisateur</a></li>
					</ul>
				</div>
			</nav>
			<header>
				<div class="container">
					<div class="col s12 center-align">
						<h1 class="title">Connexion</h1>
					</div>
				</div>
			</header>
			<main>
				<div class="container">
					<form method="post" action="/api/index.php/documentation">
						<div class="row">
							<div class="col s12 m6">
								<div class="input-field">
									<label for="email">Email</label>
									<input name="email" type="email" id="email" placeholder="Email" />
								</div>
							</div>
							<div class="col s12 m6">
								<div class="input-field">
									<label for="password">Password</label>
									<input name="password" type="password" id="password" placeholder="Password" />
								</div>
							</div>
							<div class="col s12 {$color_class}">{$error_message}</div>
							<div class="col s12 m4 offset-m4">
								<div class="btn-block center-align">
									<input type="submit" id="connexion" class="btn orange" value="Se connected" />
								</div>
							</div>
						</div>
					</form>
				</div>
			</main>
		</body>
	</html>
HTML;
			return $content;

		}

		/**
		 * @param UserEntity $user
		 * @throws Exception
		 */
		public function create_session(UserEntity $user) {
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			$session_service->set('doc_admin', $user->toArrayForJson());
		}

		/**
		 * @throws Exception
		 */
		public function delete_session() {
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			$session_service->remove('doc_admin');
			return !$session_service->has_key('doc_admin');
		}

		public function get_user_doc_content() {
			if(is_file(__DIR__.'/../views/documentation.html')) {
				return file_get_contents(__DIR__.'/../views/documentation.html');
			}
			else {
				return '<center><b>La vue documentation n\'existe pas</b></center>';
			}
		}
	}