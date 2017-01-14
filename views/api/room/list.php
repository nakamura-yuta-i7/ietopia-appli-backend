<?php
$model = new Room();

# 検索機能
# 駅で検索: 「交通フィールド」のテキスト検索
# こだわり条件: マスターDBで定義されたリストにある条件で「設備・条件フィールド」をテキスト検索

$where = "";
$fields = [
	"room.id",
	"room.name",
	# Room::gaikanImagesField() . " AS gaikan_images",
	Room::detailUrlField() . " AS url",
];
echo Json::encode(
	$model->findAll(compact("fields","where"))
);
