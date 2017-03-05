<?php
$model = new Room();

# 検索機能
# 駅で検索: 「交通フィールド」のテキスト検索
# こだわり条件: マスターDBで定義されたリストにある条件で「設備・条件フィールド」をテキスト検索

$where = Room::createSearchCondition();

$fields = [
	"room.id",
	"room.yatin_int",
	"room.madori",
	"room.senyumenseki",
	"room.isinactive",
	"room.kotu_first_line",
	Room::gaikanImageMainField() . " AS gaikan_image_main",
	Room::gaikanImagesField() . " AS gaikan_images",
	Room::detailUrlField() . " AS detail_url",
];
$limit = createRoomSearchLimit();
$order = createOrder();
echo Json::encode(
	$model->findAll(compact("fields","where","limit","order"))
);

function createOrder() {
	$item = isset($_REQUEST["order_item"]) ? 
		Sqlite3::escapeString($_REQUEST["order_item"]) : " room.yatin_int ";
	$order = isset($_REQUEST["order"]) ? 
		SQLite3::escapeString($_REQUEST["order"]) : " ASC ";
	return " {$item} {$order} ";
}
function createRoomSearchLimit() {
	$limit = 50;
	return $limit;
}