<?php
$model = new Room();
$id = $model->quote($_GET["id"]);
$where = " room.id = {$id} ";
$fields = [
	"room.*",
	Room::gaikanImageMainField() . " AS gaikan_image_main",
	Room::gaikanImagesField() . " AS gaikan_images",
	Room::detailUrlField() . " AS detail_url",
];
$limit = 1;
$roomRow = $model->findAll(compact("fields","where","limit"))[0];
unset($roomRow["basic_table"]);
unset($roomRow["detail_table"]);
echo Json::encode(
	$roomRow
);
