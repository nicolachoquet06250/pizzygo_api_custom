<?php


namespace custom;


use core\LoggerService;
use Exception;

class install extends \core\install {

	private $dummy_data = [
		'address' => [
			[
				'id' => 1,
				'address' => "155 ch des combes\n06400, toto",
				'user_id' => 2,
				'type_id' => 1
			],
			[
				'id' => 2,
				'address' => "155 ch des combes\n06400, toto",
				'user_id' => 2,
				'type_id' => 2
			],
		],
		'address_type' => [
			['id' => 1, 'type' => 'De livraison'],
			['id' => 2, 'type' => 'Personnelle'],
			['id' => 3, 'type' => 'Professionnelle']
		],
		'email' => [
			[
				'id' => 1,
				'email' => 'nicolas.choquet@campusid.eu',
				'user_id' => 1
			],
			[
				'id' => 2,
				'email' => 'nicolas.choquet@doctissimo.fr',
				'user_id' => 1
			],
			[
				'id' => 3,
				'email' => 'yann.choquet@sap.fr',
				'user_id' => 2
			]
		],
		'end_status' => [
			[
				'id' => 1,
				'name' => 'En attente'
			],
			[
				'id' => 2,
				'name' => 'En cours'
			],
			[
				'id' => 3,
				'name' => 'Terminé'
			],
			[
				'id' => 4,
				'name' => 'Livré'
			]
		],
		'order' => [
			[
				'id' => 1,
				'comment' => '',
				'user_id' => 2,
				'address_id' => 3,
				'status_id' => 1,
				'end_status' => 4
			],
			[
				'id' => 2,
				'comment' => '',
				'user_id' => 2,
				'address_id' => 3,
				'status_id' => 1,
				'end_status' => 3
			],
			[
				'id' => 3,
				'comment' => '',
				'user_id' => 2,
				'address_id' => 3,
				'status_id' => 1,
				'end_status' => 2
			]
		],
		'order_product' => [
			[
				'id' => 1,
				'product_id' => 1,
				'variant_id' => 1,
				'order_id' => 1
			],
			[
				'id' => 2,
				'product_id' => 2,
				'variant_id' => 3,
				'order_id' => 1
			],
			[
				'id' => 3,
				'product_id' => 3,
				'variant_id' => 6,
				'order_id' => 1
			],
			[
				'id' => 4,
				'product_id' => 1,
				'variant_id' => 1,
				'order_id' => 2
			],
			[
				'id' => 5,
				'product_id' => 2,
				'variant_id' => 3,
				'order_id' => 2
			],
			[
				'id' => 6,
				'product_id' => 3,
				'variant_id' => 6,
				'order_id' => 2
			],
			[
				'id' => 7,
				'product_id' => 1,
				'variant_id' => 1,
				'order_id' => 3
			],
			[
				'id' => 8,
				'product_id' => 2,
				'variant_id' => 3,
				'order_id' => 3
			],
			[
				'id' => 9,
				'product_id' => 3,
				'variant_id' => 6,
				'order_id' => 3
			]
		],
		'order_status' => [
			[
				'id' => 1,
				'name' => 'À livrer'
			],
			[
				'id' => 2,
				'name' => 'À emporter'
			]
		],
		'phone' => [
			[
				'id' => 1,
				'phone' => '0644676545',
				'user_id' => 2
			]
		],
		'product' => [
			[
				'id' => 1,
				'name' => 'San pélégrino',
				'category_id' => 1,
				'comment' => '',
				'image' => '/uploads/boisson.jpg',
				'image_alt' => 'Boisson San Pélégrino',
				'background_dark' => true
			],
			[
				'id' => 2,
				'name' => 'Coca Cola',
				'category_id' => 1,
				'comment' => '',
				'image' => '/uploads/boisson.jpg',
				'image_alt' => 'Boisson Coca Cola',
				'background_dark' => true
			],
			[
				'id' => 3,
				'name' => 'Fanta Orange',
				'category_id' => 1,
				'comment' => '',
				'image' => '/uploads/boisson.jpg',
				'image_alt' => 'Boisson Fanta Orange',
				'background_dark' => true
			],
			[
				'id' => 4,
				'name' => 'Ice The',
				'category_id' => 1,
				'comment' => '',
				'image' => '/uploads/boisson.jpg',
				'image_alt' => 'Boisson Ice The',
				'background_dark' => true
			],
			[
				'id' => 5,
				'name' => '4 Fromages',
				'category_id' => 2,
				'comment' => '',
				'image' => '/uploads/quarter_pizza.jpg',
				'image_alt' => 'Pizza 4 Fromages',
				'background_dark' => true
			],
			[
				'id' => 6,
				'name' => 'Margeritte',
				'category_id' => 2,
				'comment' => '',
				'image' => '/uploads/quarter_pizza.jpg',
				'image_alt' => 'Pizza Margeritte',
				'background_dark' => true
			],
			[
				'id' => 7,
				'name' => '4 Saisons',
				'category_id' => 2,
				'comment' => '',
				'image' => '/uploads/quarter_pizza.jpg',
				'image_alt' => 'Pizza 4 Saisons',
				'background_dark' => true
			],
			[
				'id' => 8,
				'name' => 'Napolitaine',
				'category_id' => 2,
				'comment' => '',
				'image' => '/uploads/quarter_pizza.jpg',
				'image_alt' => 'Pizza Napolitaine',
				'background_dark' => true
			],
			[
				'id' => 9,
				'name' => 'Le grec',
				'category_id' => 3,
				'comment' => '',
				'image' => '/uploads/sandwich.jpg',
				'image_alt' => 'Sandwich grec',
				'background_dark' => false
			],
			[
				'id' => 10,
				'name' => 'L\'italien',
				'category_id' => 3,
				'comment' => '',
				'image' => '/uploads/sandwich.jpg',
				'image_alt' => 'Sandwich italien',
				'background_dark' => false
			],
			[
				'id' => 11,
				'name' => 'Club jambon',
				'category_id' => 3,
				'comment' => '',
				'image' => '/uploads/sandwich.jpg',
				'image_alt' => 'Sandwich club jambon',
				'background_dark' => false
			],
			[
				'id' => 12,
				'name' => 'Club raclette',
				'category_id' => 3,
				'comment' => '',
				'image' => '/uploads/sandwich.jpg',
				'image_alt' => 'Club raclette',
				'background_dark' => false
			]
		],
		'product_category' => [
			[
				'id' => 1,
				'name' => 'Boissons',
				'user_id' => 1
			],
			[
				'id' => 2,
				'name' => 'Pizzas',
				'user_id' => 1
			],
			[
				'id' => 3,
				'name' => 'Sandwichs',
				'user_id' => 1
			]
		],
		'role' => [
			[
				'id' => 1,
				'role' => 'role_vendor',
				'user_id' => 1
			],
			[
				'id' => 2,
				'role' => 'role_customer',
				'user_id' => 2
			]
		],
		'user' => [
			[
				'id' => 1,
				'first_name' => 'Nicolas',
				'last_name' => 'Choquet',
				'email' => 'nicolachoquet06250@gmail.com',
				'phone' => '0763207630',
				'address' => "1102 ch de l espagnol\n06110, Le cannet",
				'password' => '2669NICOLAS2107',
				'description' => 'Je m\'appel Nicolas Choquet et c\'est moi qui ai développé ce site.',
				'profil_img' => '/uploads/ma_photo_de_profile.jpg',
				'premium' => false,
				'active' => true,
				'activate_token' => '',
				'fb_access_token' => '',
				'local_access_token' => '',
			],
			[
				'id' => 2,
				'first_name' => 'Yann',
				'last_name' => 'Choquet',
				'email' => 'yannchoquet@gmail.com',
				'phone' => '0625564568',
				'address' => "105 av Francis tonner\n06110, Le cannet",
				'password' => '1204YANN2107',
				'description' => 'No comment',
				'profil_img' => '/uploads/photo_de_profile_yann.jpg',
				'premium' => false,
				'active' => true,
				'activate_token' => '',
				'fb_access_token' => '',
				'local_access_token' => '',
			],
			[
				'id' => 3,
				'first_name' => 'User',
				'last_name' => 'Vendor',
				'email' => 'vendor.user@pizzygo.fr',
				'phone' => '',
				'address' => "105 av Francis tonner\n06110, Le cannet",
				'password' => 'uservendor',
				'description' => 'No comment',
				'profil_img' => '',
				'premium' => false,
				'active' => true,
				'activate_token' => '',
				'fb_access_token' => '',
				'local_access_token' => '',
			],
			[
				'id' => 4,
				'first_name' => 'User',
				'last_name' => 'Admin',
				'email' => 'admin.user@pizzygo.fr',
				'phone' => '',
				'address' => "105 av Francis tonner\n06110, Le cannet",
				'password' => 'useradmin',
				'description' => 'No comment',
				'profil_img' => '',
				'premium' => false,
				'active' => true,
				'activate_token' => '',
				'fb_access_token' => '',
				'local_access_token' => '',
			],
			[
				'id' => 5,
				'first_name' => 'User',
				'last_name' => 'Customer',
				'email' => 'customer.user@pizzygo.fr',
				'phone' => '',
				'address' => "105 av Francis tonner\n06110, Le cannet",
				'password' => 'usercustomer',
				'description' => 'No comment',
				'profil_img' => '',
				'premium' => false,
				'active' => true,
				'activate_token' => '',
				'fb_access_token' => '',
				'local_access_token' => '',
			],
		],
		'variant' => [
			[
				'id' => 1,
				'name' => '30 cl',
				'category_id' => 1,
				'price' => 2.50
			],
			[
				'id' => 2,
				'name' => '50 cl',
				'category_id' => 1,
				'price' => 5.50
			],
			[
				'id' => 3,
				'name' => '30 cm',
				'category_id' => 2,
				'price' => 12.50
			],
			[
				'id' => 4,
				'name' => '50 cm',
				'category_id' => 2,
				'price' => 15.50
			],
			[
				'id' => 5,
				'name' => 'demi baguette',
				'category_id' => 3,
				'price' => 10.50
			],
			[
				'id' => 6,
				'name' => 'baguette',
				'category_id' => 3,
				'price' => 12.50
			],
		],
	];

	/**
	 * @@translate dummy = factise
	 * @throws Exception
	 */
	public function dummy_data(PizzygoContext $context, LoggerService $logger) {
		$logger->add_logger(LoggerService::CONSOLE);

		foreach ($context->get_db_sets() as $repository) {
			$repository->create_table();
			$name = $repository->get_table_name(false);
			if(isset($this->dummy_data[$name])) {
				$id = 1;
				foreach ($this->dummy_data[$name] as $entity_array) {
					$entity_object = $this->get_entity($name);
					if(isset($entity_array['password'])) {
						$entity_array['password'] = sha1(sha1($entity_array['password']));
					}
					$entity_object->initFromArray($entity_array);
					if(!$entity_object->save(false)) {
						throw new Exception('L\'entité '.$name.' avec l\'id '.$id.' n\'à pas pu être entregistré !!');
					}
					$id++;
				}
			}
		}
		$logger->log('Les données ont biens été installés !!');
	}
}