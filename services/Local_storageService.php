<?php

namespace custom;

use core\Service;

class Local_storageService extends Service implements ILocal_storageService {
	public function initialize_after_injection() {}

	public function mkdir($path, $permissions = 0777, $recusrsiv = true) {

	}
}