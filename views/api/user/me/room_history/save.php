<?php
$user = Application::getInstance()->getUserWithAuthCheck();
$uuid = $user["uuid"];
$userId = $user["id"];
$roomId = $_GET["room_id"];

try {
	RoomHistory::insertRoomId($uuid, $roomId);
	
	# 50件を最大保存件数とする
	$i = 0;
	$maxSaveCnt = 50;
	foreach ( RoomHistory::findAllByUserId($userId) as $h ) {
		$i++;
		if ($i > $maxSaveCnt) {
			RoomHistory::deleteHistory($uuid, $h["id"]);
		}
	}
	
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);