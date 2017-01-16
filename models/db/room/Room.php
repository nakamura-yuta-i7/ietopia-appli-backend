<?php
require_once __DIR__ . "/IetopiaRoomDbModel.php";

class Room extends IetopiaRoomDbModel {
	
	public $table = "room";
	
	const ID = "id";
	const GAIKAN_IMAGES_ID = "gaikan_images_id";
	
	function activate($id) {
		$this->update(
			[static::ISINACTIVE=>static::ISINACTIVE_OFF],
			/* where */ static::ID . " = $id"
		);
	}
	function findAll($params) {
		if ( !isset($params["fields"]) ) {
			$params["fields"] = [
				"room.*",
				static::gaikanImagesField() . " AS gaikan_images",
			];
		}
		if ( !isset($params["join"]) ) {
			$params["join"] = " LEFT JOIN gaikan_images ON gaikan_images_id = gaikan_images.id ";
		}
		
		return array_map(function($row) {
			if ( isset($row["naikan_images"]) ) {
				$row["naikan_images"] = Json::decode($row["naikan_images"]);
			}
			if ( isset($row["gaikan_images"]) ) {
				$row["gaikan_images"] = Json::decode($row["gaikan_images"]);
			}
			return $row;
		}, parent::findAll($params));
	}
	static function detailUrlField() {
		return "'". IETOPIA_DETAIL_BASE_URL . "/" ."' || gaikan_images.id || '/' || room.id";
	}
	static function gaikanImagesField() {
		return "gaikan_images.images";
	}
}