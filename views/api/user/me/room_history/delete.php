<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];
$historyId = $_GET["history_id"];

try {
	RoomHistory::deleteHistory($uuid, $historyId);
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);