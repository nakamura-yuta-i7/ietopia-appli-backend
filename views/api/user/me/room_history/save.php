<?php
$uuid = $_GET["uuid"];
$roomId = $_GET["room_id"];

try {
	RoomHistory::insertRoomId($uuid, $roomId);
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);