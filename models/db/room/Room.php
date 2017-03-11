<?php
require_once __DIR__ . "/IetopiaRoomDbModel.php";

class Room extends IetopiaRoomDbModel {
	
	public $table = "room";
	
	const ID = "id";
	const GAIKAN_IMAGES_ID = "gaikan_images_id";
	const NEW_ARRIVAL_FLAG = "new_arrival_flag";
	const PICKUP_FLAG      = "pickup_flag";
	
	static function createOrder($params) {
		$item = isset($params["order_item"]) ? 
			Sqlite3::escapeString($params["order_item"]) : " room.yatin_int ";
		$order = isset($params["order"]) ? 
			SQLite3::escapeString($params["order"]) : " ASC ";
		return " {$item} {$order} ";
	}
	static function createRoomSearchLimit($params) {
		$limit = 50;
		return $limit;
	}
	
	function raiseNewArrivalFlag($roomId) {
		return $this->update([
			static::NEW_ARRIVAL_FLAG => 1,
		], static::ID . " = '{$roomId}'");
	}
	function dropNewArrivalFlag($where=NULL) {
		return $this->update([
			static::NEW_ARRIVAL_FLAG => 0,
		], $where);
	}
	function raisePickUpFlag($roomId) {
		return $this->update([
			static::PICKUP_FLAG => 1,
		], static::ID . " = '{$roomId}'");
	}
	function dropPickUpFlag($where=NULL) {
		return $this->update([
			static::PICKUP_FLAG => 0,
		], $where);
	}
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
	static function gaikanImageMainField() {
		return "gaikan_images.image_main";
	}
	static function gaikanImagesField() {
		return "gaikan_images.images";
	}
	static function createSearchCondition() {
		$conditions = [];
		$conditions[] = " isinactive = 0 ";
		
		function createLikeOrConditions($key, $field) {
			$values = $_REQUEST[$key];
			$values = is_array($values) ? $values : [$values];
			$ORs = [];
			foreach ($values as $value) {
				$ORs[] = " {$field} LIKE '%". Sqlite3::escapeString($value) ."%' ";
			}
			if ( $ORs ) {
				return " ( ". implode($ORs, " OR ") ." ) ";
			}
			return "";
		}
		
		function createConcatStatement($values) {
			return implode(" || ", $values);
		}
		
		foreach ( $_REQUEST as $key => $val ) {
			if ( $key == "word" && $val ) {
				$fields = createConcatStatement([
					"name_full", "catchcopy", "shozaiti", "kotu", "comment",
					"basic_table", "detail_table",
				]);
				$val = Sqlite3::escapeString($val);
				$conditions[] = " {$fields} LIKE '%{$val}%' ";
			}
			if ( $key == "yatin-min" && $val ) {
				$conditions[] = " yatin_int >= " . Sqlite3::escapeString($_REQUEST["yatin-min"]);
			}
			if ( $key == "yatin-max" && $val ) {
				$conditions[] = " yatin_int <= " . Sqlite3::escapeString($_REQUEST["yatin-max"]);
			}
			if ( $key == "rosen" && $val ) {
				# 路線名は部屋テーブルに無いかな
				# $conditions[] = " yatin_int <= " . Sqlite3::escapeString($_REQUEST["rosen"]);
			}
			if ( $key == "station" && $val ) {
				$field = "kotu";
				$orConditions = createLikeOrConditions($key, $field);
				if ( $orConditions ) $conditions[] = $orConditions;
			}
			if ( $key == "madori" && $val ) {
				$field = "madori";
				$orConditions = createLikeOrConditions($key, $field);
				if ( $orConditions ) $conditions[] = $orConditions;
			}
			if ( $key == "menseki" && $val ) {
				$vals = is_array($val) ? $val : [$val];
				$orConditions = [];
				foreach ($vals as $value) {
					$min = explode("-", $value)[0];
					$max = explode("-", $value)[1];
					$orConditions[] = " ( menseki_int >= {$min} AND menseki_int <= {$max} ) ";
				}
				if ( $orConditions ) $conditions[] = " ( ". implode($orConditions, " OR ") ." ) ";
			}
			if ( $key == "ekitoho" && $val ) {
				$ORs = [];
				foreach (range(0, $val) as $int) {
					$toho = "徒歩{$int}";
					$ORs[] = " kotu LIKE '%". Sqlite3::escapeString($toho) ."%' ";
				}
				if ($ORs) $conditions[] = " ( ". implode($ORs, " OR ") ." ) ";
			}
			if ( $key == "tikunensu" && $val ) {
				$ORs = [];
				foreach (range(0, $val) as $int) {
					if ( $int == 0 ) {
						$tiku = "(築1年以内)";
					} else {
						$tiku = "(築{$int}年)";
					}
					$ORs[] = " tikunensu LIKE '%". Sqlite3::escapeString($tiku) ."%' ";
				}
				if ($ORs) $conditions[] = " ( ". implode($ORs, " OR ") ." ) ";
			}
			if ( $key == "kodawari_joken" && $val ) {
				$field = "setubi_joken";
				$orConditions = createLikeOrConditions($key, $field);
				if ( $orConditions ) $conditions[] = $orConditions;
			}
		}
		$where = $where . implode( $conditions, " AND " );
		Log::info(compact("conditions","where"));
		return $where;
	}
}