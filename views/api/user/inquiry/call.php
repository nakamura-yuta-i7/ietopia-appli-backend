<?php
$user = Application::getInstance()->getUserWithAuthCheck();
$params = $_REQUEST;
$room_id = isset($params["room_id"]) ? $params["room_id"] : NULL;

try {
	
	$inquiry = new Inquiry();
	$inquiry->insert([
		"user_id"     => $user["id"],
		"room_id"     => $room_id,
		"type"        => "tel",
	]);
	
	http_response_code(200);
	$body = "ok";
	
} catch (Exception $e) {
	
	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);