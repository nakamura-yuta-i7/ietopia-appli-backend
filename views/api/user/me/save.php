<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];
$params = $_REQUEST;

try {
	User::save($uuid, $params);
	http_response_code(200);
	$body = "ok";
	
} catch (Exception $e) {
	
	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);