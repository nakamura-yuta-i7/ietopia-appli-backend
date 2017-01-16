<?php
require_once APP_ROOT . "/models/batch/BatchAbstract.php";
require_once APP_ROOT . "/models/ietopia/IetopiaSearchConditions.php";

class IetopiaMasterImportBatch extends BatchAbstract {
	
	function process() {
		$this->syncSearchConditions();
		$this->syncRoute();
	}
	
	function syncSearchConditions() {
		$searchForm = new IetopiaSearchConditions();
		foreach ([
			# マスタ, 条件一覧
			[new Madori()        , $searchForm->getMadoriList(),        ],
			[new Tikunensu()     , $searchForm->getTikunensuList(),     ],
			[new Menseki()       , $searchForm->getMensekiList(),       ],
			[new Ekitoho()       , $searchForm->getEkitohoList(),       ],
			[new KodawariJoken() , $searchForm->getKodawariJokenList(), ],
			
		] as $masterAndList) {
			list($master, $list) = $masterAndList;
			$count = count($list);
			$table = $master->table;
			
			Log::info(compact("table","count","list"));
			
			if ( $count < 2 ) {
				# マスタレコードが極端に少なくなるなら異常として検知
				return Log::fatal([
					__METHOD__,
					"取得された[$table]用マスタレコードが極端に少ない為、処理を停止しました  count: {$count}",
				]);
			}
			
			$syncRows = [];
			foreach ($list as $name) {
				$value = call_user_func(function() use($master, $name) {
					switch ($master->table) {
						case "tikunensu": return intval(preg_replace('/[^0-9]/', '', $name));
						case "ekitoho":   return intval(preg_replace('/[^0-9]/', '', $name));
						case "menseki":
							$name = preg_replace("/～/", "-", $name);
							if ( $name == "20㎡以下" ) return "0-19";
							if ( $name == "100㎡以上" ) return "101-999";
							return preg_replace("/㎡/", "", $name);
						default: return $name;
					}
				});
				$syncRows[] = ["name" => $name, "value" => $value];
			}
			# マスタに同期
			$master->sync($syncRows, "name");
		}
	}

	function syncRoute() {

		$rosenTable = new Rosen();
		$stationTable = new Station();

		$rosenRows = [];
		$stationRows = [];

		$route = new IetopiaSearchConditions();
		foreach ( $route->getRosenAndStationList() as $rosen => $stations ) {
			$rosenRows[] = [
				"name" => $rosen,
				"value" => $rosen,
			];
			foreach ($stations as $station) {
				$stationRows[] = [
					"rosen_name" => $rosen,
					"rosen_station" => $rosen . ":" . $station,
					"name" => $station,
					"value" => $station,
				];
			}
		}
		
		Log::info(compact("rosenRows","stationRows"));
		
		$count = count($rosenRows);
		
		if ( $count < 2 ) {
			# マスタレコードが極端に少なくなるなら異常として検知
			return Log::fatal([
				__METHOD__,
				"取得された[路線]用マスタレコードが極端に少ない為、処理を停止しました  count: {$count}",
			]);
		}
		
		$rosenTable->sync($rosenRows, $pk="name");
		$stationTable->sync($stationRows, $pk="rosen_station");
	}
}