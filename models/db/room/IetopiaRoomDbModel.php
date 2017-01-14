<?php
require_once __DIR__ . "/../IetopiaDbModel.php";

class IetopiaRoomDbModel extends IetopiaDbModel {
	
	function __construct() {
		$conn = ConnectionManager::getConnection("ietopia_room");
		$this->setConnection($conn);
	}
}