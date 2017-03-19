<?php
$user = Application::getInstance()->getUserWithAuthCheck();
$uuid = $user["uuid"];
$userId = $user["id"];
$roomId = $_GET["room_id"];

try {
	RoomHistory::insertRoomId($uuid, $roomId);
	
	$i = 0;
	# 最大保存件数
	$maxSaveCnt = 100;
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