<?php
$model = new Room();

# 検索機能
# 駅で検索: 「交通フィールド」のテキスト検索
# こだわり条件: マスターDBで定義されたリストにある条件で「設備・条件フィールド」をテキスト検索

$where = call_user_func(function() {
	$where = " isinactive = 0 ";
	
	foreach ( $_GET as $key => $val ) {
		
	}
	return $where;
});

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
$limit = 10;
echo Json::encode(
	$model->findAll(compact("fields","where","limit"))
);
