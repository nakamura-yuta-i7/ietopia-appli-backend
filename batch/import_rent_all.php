<?php
require_once __DIR__ . "/../config/bootstrap.php";

try {
	$batch = new IetopiaImportBatch();
	$batch->execute();
	
} catch (Exception $e) {
	Log::fatal($e);
}


abstract class BatchAbstract {
	function __construct() {
	}
	abstract function execute();
}
class IetopiaImportBatch extends BatchAbstract {
	
	function execute() {
		# 家とぴあの情報をアプリ用データベースに取込
		$roomModel = new Room();
		$gaikanImages = new GaikanImages();
		
		# 豊島区の建物リストを取得
		$rentSearchPageToshimaKu = new IetopiaRentSearchPageToshimaKu();
		foreach ( $rentSearchPageToshimaKu->getBuildingList() as $item ) {
			
			# 建物
			$name = $item->getName();
			$id   = $item->getId();
			$detailUrl = $item->getDetailUrl();
			
			# 外観写真
			$gaikanImages->upsert([
				$gaikanImages::ID     => $id,
				$gaikanImages::IMAGES => Json::encode(
					$item->getGaikanImageUrls()
				),
			], /* pk */ $gaikanImages::ID);
			
			# 部屋
			$rooms = $item->getRooms();
			foreach ($rooms as $room) {
				$roomModel->upsert(
					$room->getContent(),
					/* pk */ Room::ID
				);
			}
		}
	}
}



