<?php
require_once __DIR__ . "/../IetopiaDbModel.php";

class IetopiaMasterDbModel extends IetopiaDbModel {
	
	function __construct() {
		$conn = ConnectionManager::getConnection("ietopia_master");
		$this->setConnection($conn);
	}
}