<?php
$model = new Room();
$where = Room::createSearchCondition();

$fields = [
	"room.id",
	"room.yatin_int",
	"room.madori",
	"room.name",
	"room.senyumenseki",
	"room.isinactive",
	"room.kotu_first_line",
	Room::gaikanImageMainField() . " AS gaikan_image_main",
	Room::gaikanImagesField() . " AS gaikan_images",
	Room::detailUrlField() . " AS detail_url",
];
$limit = Room::createRoomSearchLimit($_REQUEST);
$order = Room::createOrder($_REQUEST);
$count = $model->findCount($where);
$rows = $model->findAll(compact("fields","where","limit","order"));

# 閲覧履歴を返却する場合
if ( isset($_REQUEST["history"]) && $_REQUEST["history"] ) {
	
	$userId = Application::getInstance()->getUserWithAuthCheck()["id"];
	$histories = RoomHistory::findAllByUserId($userId);
	$findHistory = function($roomId) use($histories) {
		foreach ($histories as $h) {
			if ($roomId == $h["room_id"]) return $h;
		}
		return false;
	};
	$rows = array_map(function($row) use($findHistory) {
		$roomId = $row["id"];
		if ( $h = $findHistory($roomId) ) {
			$row["history_created_at"] = $h["created_at"];
		}
		return $row;
	}, $rows);
	$sort = [];
	foreach ($rows as $key => $val) {
		$sort[$key] = $val["history_created_at"];
	}
	array_multisort($sort, SORT_DESC, $rows);
}

echo Json::encode([
	"count" => $count,
	"list"  => $rows,
]);
