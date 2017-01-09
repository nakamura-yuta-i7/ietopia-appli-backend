<?php
require_once __DIR__ . "/../config/bootstrap.php";

$roomModel = new Room();
$room = new IetopiaSearchResultRoom("http://www.ietopia.jp/rent/308/13249");
$result = $room->getNaikanImageUrls();
var_export($result);

return;

$room->parseLoadContent();
$roomModel->upsert($room->content, $pk=Room::ID);
var_export($room->content);
return ;

# 家とぴあの情報をアプリ用データベースに取込
$roomModel = new Room();

# 豊島区の建物リストを取得
$rentSearchPageToshimaKu = new IetopiaRentSearchPageToshimaKu();
foreach ( $rentSearchPageToshimaKu->getBuildingList() as $item ) {
	
	# 建物
	$name = $item->getName();
	$id   = $item->getId();
	$detailUrl = $item->getDetailUrl();
	
	# 外観写真
	# $gaikanImageUrls = $item->getGaikanImageUrls();
	
	# 部屋
	$roomUrls = $item->getRoomUrls();
	
	$rooms = $item->getRooms();
	foreach ($rooms as $room) {
		$room->parseLoadContent();
		
		$roomModel->upsert($room->content, $pk=Room::ID );
		
		var_export($room->content);
exit;
	}
	
	var_export(compact("name","id","detailUrl","roomUrls","gaikanImageUrls","rooms"));
	
	echo PHP_EOL;
}