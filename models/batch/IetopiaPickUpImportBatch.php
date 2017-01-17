<?php
require_once APP_ROOT . "/models/batch/BatchAbstract.php";
require_once APP_ROOT . "/models/ietopia/IetopiaToppage.php";

class IetopiaPickUpImportBatch extends BatchAbstract {
	function process() {
		
		# オススメ物件情報取込
		$this->importPickUp();
		
	}
	function importPickUp() {
		$webpage = new IetopiaPickUp();
		$buildingItems = $webpage->getItems();
		
		$room = new Room();
		$room->beginTransaction();
		
		try {
			$roomIds = [];
			foreach ($buildingItems as $item) {
				foreach($item->getRoomIds() as $id ) {
					$roomIds[] = $id;
				}
			}
			$room->dropPickUpFlag();
			foreach (array_unique($roomIds) as $roomId) {
				$room->raisePickUpFlag($roomId);
			}
			$room->commit();
			
		} catch (Exception $e) {
			
			$room->rollBack();
			throw $e;
		}
	}
}

