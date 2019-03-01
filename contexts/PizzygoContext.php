<?php

namespace custom;


use core\DbContext;

class PizzygoContext extends DbContext {
	/** @var UserDao $users */
	public $users;
	/** @var OrderDao $orders */
	public $orders;
	/** @var LikeDao $likes */
	public $likes;
	/** @var AddressDao $addresses */
	public $addresses;
	/** @var Address_typeDao $addresses_types */
	public $addresses_types;
	/** @var CommentDao $comments */
	public $comments;
	/** @var CredentialsDao $credentials */
	public $credentials;
	/** @var EmailDao $emails */
	public $emails;
	/** @var End_statusDao $end_status */
	public $end_status;
	/** @var Order_productDao $order_products */
	public $order_products;
	/** @var Order_statusDao $order_status */
	public $order_status;
	/** @var PhoneDao $phone_dao */
	public $phone_dao;
	/** @var Product_categoryDao $product_categories */
	public $product_categories;
	/** @var ProductDao $products */
	public $products;
	/** @var RoleDao $roles */
	public $roles;
	/** @var ShopDao $shops */
	public $shops;
	/** @var VariantDao $category_variants */
	public $category_variants;
}