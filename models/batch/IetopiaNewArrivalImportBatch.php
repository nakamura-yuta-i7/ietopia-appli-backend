<?php
require_once APP_ROOT . "/models/batch/BatchAbstract.php";
require_once APP_ROOT . "/models/ietopia/IetopiaToppage.php";

class IetopiaNewArrivalImportBatch extends BatchAbstract {
	function process() {
		
		# 新着物件情報取込
		$this->importNewArrival();
		
	}
	function importNewArrival() {
		$webpage = new IetopiaNewArrival();
		$newRoomItems = $webpage->getItems();
		
		$room = new Room();
		$room->beginTransaction();
		
		try {
			$newRoomIds = [];
			foreach ($newRoomItems as $roomItem) {
				$newRoomIds[] = $roomItem->getRoomID();
			}
			$room->dropNewArrivalFlag();
			foreach ($newRoomIds as $roomId) {
				$room->raiseNewArrivalFlag($roomId);
			}
			$room->commit();
			
		} catch (Exception $e) {
			
			$room->rollBack();
			throw new $e;
		}
	}
}
