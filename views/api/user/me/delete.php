<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];

try {
	User::deleteUser($uuid);
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);