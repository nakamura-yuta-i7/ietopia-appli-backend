<?php
require_once __DIR__ . "/../IetopiaDbModel.php";

class IetopiaMasterDbModel extends IetopiaDbModel {
	
	function __construct() {
		$conn = ConnectionManager::getConnection("ietopia_master");
		$this->setConnection($conn);
	}
}
class KodawariJoken extends IetopiaMasterDbModel {
	public $table = "kodawari_joken";
}
class Ekitoho extends IetopiaMasterDbModel {
	public $table = "ekitoho";
}
class Menseki extends IetopiaMasterDbModel {
	public $table = "menseki";
}
class Tikunensu extends IetopiaMasterDbModel {
	public $table = "tikunensu";
}
class Madori extends IetopiaMasterDbModel {
	public $table = "madori";
}
class Rosen extends IetopiaMasterDbModel {
	public $table = "rosen";
}
class Station extends IetopiaMasterDbModel {
	public $table = "station";
}