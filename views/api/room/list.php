<?php
$model = new Room();
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
$limit = Room::createRoomSearchLimit($_REQUEST);
$order = Room::createOrder($_REQUEST);
$count = $model->findCount($where);
echo Json::encode([
	"count" => $count,
	"list"  => $model->findAll(compact("fields","where","limit","order")),
]);
