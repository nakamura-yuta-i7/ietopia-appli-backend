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
	abstract function process();
	
	protected function preProcess() {
		Log::info([get_class($this), 'execute: start' ]);
	}
	function execute() {
		$this->preProcess();
		$this->process();
		$this->postProcess();
	}
	protected function postProcess() {
		Log::info([get_class($this), 'execute: finish' ]);
	}
	
	function __destruct() {
		
	}
}


class IetopiaImportBatch extends BatchAbstract {
	
	function process() {
		# 家とぴあの情報をアプリ用データベースに取込
		$roomModel = new Room();
		$gaikanImages = new GaikanImages();
		
		# 豊島区の建物リストを取得
		$rentSearchPageToshimaKu = new IetopiaRentSearchPageToshimaKu();
		if ( !IS_PROD ) $rentSearchPageToshimaKu->maxLimit = 200;
		
		foreach ( $rentSearchPageToshimaKu->getBuildingList() as $i => $building ) {
			
			# 建物
			$name = $building->getName();
			$id   = $building->getId();
			$detailUrl = $building->getDetailUrl();
			
			Log::info(Ltsv::encode(compact("i", "name")));
continue;
			
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
		}
	}
}



