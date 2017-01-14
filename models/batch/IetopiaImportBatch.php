<?php
require_once __DIR__ . "/BatchAbstract.php";

class IetopiaImportBatch extends BatchAbstract {

	function process() {
		# 家とぴあの情報をアプリ用データベースに取込
		$roomModel = new Room();
		$gaikanImages = new GaikanImages();
		
		# 豊島区の建物リストを取得
		$rentSearchPageToshimaKu = new IetopiaRentSearchPageToshimaKu();
		if ( IS_DEV ) {
			$rentSearchPageToshimaKu->pageLimit = 40; # 開発時の1ページ内件数
			$rentSearchPageToshimaKu->maxLimit  = 40; # 開発時の最大取得数
		}
		
		$buildingList = $rentSearchPageToshimaKu->getBuildingList();

		Log::info([__METHOD__, 'count($buildingList): '.count($buildingList) ]);
		
		if ( count(count($buildingList)) == 0 ) {
			Log::info([__METHOD__, '建物数が0件だったので処理を停止しました' ]);
			return;
		}
		
		# 取得した建物リスト以外は
		# 最後に無効化する為、PKをメモしておく
		$importedBuildingIds = [];
		
		foreach ( $buildingList as $i => $building ) {
			$i++;
			$count = count($buildingList) . "/" . $i;
			
			# 建物
			$buildingId = $building->getId();
			$name       = $building->getName();
			$detailUrl  = $building->getDetailUrl();
			
			$importedBuildingIds[] = $buildingId;

			Log::info(Ltsv::encode(compact("count", "name", "detailUrl")));
			
			try {
				# 外観写真
				$gaikanImages->upsert([
					$gaikanImages::ID     => $buildingId,
					$gaikanImages::IMAGES => Json::encode(
						$building->getGaikanImageUrls()
					),
				], /* pk */ $gaikanImages::ID);
				
				# 部屋
				$rooms = $building->getRooms();
				foreach ($rooms as $room) {
					$roomId   = $room->ID();
					# データをそのまま保存して
					$roomModel->upsert(
						$room->getContent(),
						/* pk */ Room::ID
					);
					# 有効化もする
					$roomModel->activate($roomId);
					
					$roomName = $room->getContent()[$room::NAME];
					$roomUrl  = $room->detailUrl();
					Log::info(Ltsv::encode(compact("count", "roomId", "roomUrl")));
				}
			} catch (Exception $e) {
				Log::fatal(compact("count", "name", "detailUrl"));
				Log::fatal($e->getMessage());
			}
		}
		
		# 建物IDから部屋を無効化
		# 条件
		$inactivateCondition = 
			# 今回取込対象以外、且つ
			$roomModel::GAIKAN_IMAGES_ID . " NOT IN (". implode(",", $importedBuildingIds) .")" .
			# まだ無効化されてない部屋を探す
			" AND " . $roomModel::ISINACTIVE . " = " . $roomModel::ISINACTIVE_OFF ;
		# 無効化する件数
		$inactivateCount = $roomModel->findCount($inactivateCondition);
		# 無効化実行
		$roomModel = new Room();
		$roomModel->update(
			[ $roomModel::ISINACTIVE => $roomModel::ISINACTIVE_ON ],
			$inactivateCondition
		);
		
		# 管理者に通知
		$mail = IetopiaMailer::getInstance();
		$mail->addAddress(IETOPIA_API_ADMIN_EMAIL);
		$mail->Subject .= __METHOD__ . " Finish";
		$mail->Body = 
"取込処理が完了しました。

同期:建物件数: ". count($buildingList) ."
同期:部屋件数: ". $roomModel->findCount("isinactive = 0") ."
無効化:部屋件数: ". $inactivateCount ."
";
		
		if( ! $mail->send() ) {
			throw new ErrorException( Json::encode($mail->ErrorInfo) );
		}
		
		Log::info( $mail->Body );
	}
}
