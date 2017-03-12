<?php
class IetopiaUserDbModel extends IetopiaDbModel {
	
	function __construct() {
		$conn = ConnectionManager::getConnection("ietopia_user");
		$this->setConnection($conn);
	}
}
class Favorite extends IetopiaUserDbModel {
	public $table = "favorite";
	static function getListByUserId($userId) {
		$where = " user_id = '{$userId}' ";
		$self = new static;
		return $self->findList(compact("where"), $key="room_id");
	}
	static function saveRoomId($uuid, $roomId) {
		$userId = User::getMe($uuid)["id"];
		$roomId = SQLite3::escapeString($roomId);
		if ( ! $roomId ) throw new ErrorException("room_id is required.");
		$self = new static;
		$values = [
			"user_id" => $userId,
			"room_id" => $roomId,
		];
		$where = " user_id = '{$userId}' AND room_id = '{$roomId}' ";
		$one = $self->findOne(compact("where"));
		if ( $one ) {
			$id = $one["id"];
			$where = " id = {$id} ";
			$self->update($values, $where);
		} else {
			$self->insert($values);
		}
	}
	static function deleteRoomId($uuid, $roomId) {
		$userId = User::getMe($uuid)["id"];
		$roomId = SQLite3::escapeString($roomId);
		if ( ! $roomId ) throw new ErrorException("room_id is required.");
		$self = new static;
		$where = " user_id = '{$userId}' AND room_id = '{$roomId}' ";
		$self->delete($where);
	}
}
class RoomHistory extends IetopiaUserDbModel {
	public $table = "room_history";
	static function findAllByUserId($userId) {
		$where = " user_id = '{$userId}' ";
		$order = " created_at DESC ";
		$self = new static;
		return $self->findAll(compact("where","order"));
	}
	static function insertRoomId($uuid, $roomId) {
		$userId = User::getMe($uuid)["id"];
		if ( ! $roomId ) throw new ErrorException("room_id is required.");
		$self = new static;
		$values = [
			"user_id" => $userId,
			"room_id" => SQLite3::escapeString($roomId),
		];
		$self->insert($values);
	}
	static function deleteHistory($uuid, $historyId) {
		$userId = User::getMe($uuid)["id"];
		$historyId = SQLite3::escapeString($historyId);
		if ( ! $historyId ) throw new ErrorException("history_id is required.");
		$self = new static;
		$where = " user_id = '{$userId}' AND id = '{$historyId}' ";
		$self->delete($where);
	}
}
class SearchHistory extends IetopiaUserDbModel {
	public $table = "search_history";
	static function saveParams($uuid, $paramsJson) {
		$userId = User::getMe($uuid)["id"];
		$self = new static;
		$values = [
			"user_id" => $userId,
			"params_json" => SQLite3::escapeString($paramsJson),
		];
		$self->upsert($values, $pk="user_id");
	}
	static function getByUserId($userId) {
		$where = " user_id = '{$userId}' ";
		$self = new static;
		$result = $self->findOne(compact("where"));
		return $result ? $result : [];
	}
}
class User extends IetopiaUserDbModel {
	public $table = "user";
	static function findByUUID($uuid) {
		$uuid = SQLite3::escapeString($uuid);
		$self = new static;
		$one = $self->findOne(["where"=>" uuid = '{$uuid}' "]);
		return $one;
	}
	static function getIdByUUID($uuid) {
		return static::getMe($uuid)["id"];
	}
	static function getMe($uuid) {
		$self = new static;
		$user = $self->findByUUID($uuid);
		if ( $uuid && ! $user ) {
			User::save($uuid);
			$user = $self->findByUUID($uuid);
		}
		if ( ! $user ) {
			throw new ErrorException("Not found user by uuid.  uuid: {$uuid}");
		}
		$user["madori"] = Json::decode($user["madori"]);
		$user["kibou_renraku_houhou"] = Json::decode($user["kibou_renraku_houhou"]);
		
		$result = SearchHistory::getByUserId($user["id"]);
		$user["search_history"] = $result ? Json::decode($result["params_json"]) : ["word"=>""];
		
		$user["favorite"] = Favorite::getListByUserId($user["id"]);
		
		return $user;
	}
	static function save($uuid, $params=[]) {
		if ( ! $uuid ) {
			throw new ErrorException("uuid is required.");
		}
		$self = new static;
		$params["uuid"] = $uuid;
		$existColumns = $self->getColumns();
		foreach ($params as $key => $val) {
			if ( ! in_array($key, $existColumns) ) {
				unset($params[$key]);
			}
		}
		$self->upsert($params, $pk="uuid");
	}
	static function deleteUser($uuid) {
		$uuid = SQLite3::escapeString($uuid);
		$where = " uuid = '{$uuid}' ";
		$self = new static;
		$self->delete($where);
	}
}