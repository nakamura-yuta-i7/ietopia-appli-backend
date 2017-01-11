<?php
require_once __DIR__ . "/../config/bootstrap.php";

// $roomModel = new Room();
// $room = new IetopiaSearchResultRoom("http://www.ietopia.jp/rent/425/13006");
// var_export($room->getContent());

// $roomModel->upsert($room->getContent(), $pk=Room::ID);

// return;





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
	$gaikanImageUrls = $item->getGaikanImageUrls();
	
var_export($gaikanImageUrls);
exit;
	
	# 部屋
	$roomUrls = $item->getRoomUrls();
	
	$rooms = $item->getRooms();
	foreach ($rooms as $room) {
		$room->parseLoadContent();
		
		$roomModel->upsert($room->getContent(), $pk=Room::ID );
exit;
	}
}