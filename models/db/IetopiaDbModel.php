<?php
require_once __DIR__ . "/Database.php";

class IetopiaDbModel extends Database {
	const ISINACTIVE_ON  = 1;
	const ISINACTIVE_OFF = 0;
	
	function insert($values) {
		if ( ! array_key_exists("created_at", $values) ) {
			$values["created_at"] = date_create()->format("Y-m-d H:i:s");
		}
		return parent::insert($values);
	}
	function update($values, $where) {
		if ( ! array_key_exists("updated_at", $values) ) {
			$values["updated_at"] = date_create()->format("Y-m-d H:i:s");
		}
		return parent::update($values, $where);
	}
}