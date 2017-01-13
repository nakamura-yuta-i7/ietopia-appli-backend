<?php
require_once __DIR__ . "/BatchAbstract.php";

class IetopiaImportBatch extends BatchAbstract {

	function process() {
		# 家とぴあの情報をアプリ用データベースに取込
		$roomModel = new Room();
		$gaikanImages = new GaikanImages();

		# 豊島区の建物リストを取得
		$rentSearchPageToshimaKu = new IetopiaRentSearchPageToshimaKu();
		if ( !IS_PROD ) $rentSearchPageToshimaKu->maxLimit = 200;

		$buildingList = $rentSearchPageToshimaKu->getBuildingList();

		Log::info([__METHOD__, 'count($buildingList): '.count($buildingList) ]);

		foreach ( $buildingList as $i => $building ) {
			$i++;
				
			# 建物
			$id        = $building->getId();
			$name      = $building->getName();
			$detailUrl = $building->getDetailUrl();
				
			# 外観写真
			$gaikanImages->upsert([
				$gaikanImages::ID     => $id,
				$gaikanImages::IMAGES => Json::encode(
					$building->getGaikanImageUrls()
				),
			], /* pk */ $gaikanImages::ID);
				
			# 部屋
			$rooms = $building->getRooms();
			foreach ($rooms as $room) {
				$roomModel->upsert(
					$room->getContent(),
					/* pk */ Room::ID
				);
			}
			
			Log::info(Ltsv::encode(compact("i", "name", "detailUrl")));
		}
	}
}
