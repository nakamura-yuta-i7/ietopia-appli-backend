<?php
$uuid = $_GET["uuid"];

try {
	$me = Application::getInstance()->login($uuid);
	
	http_response_code(200);
	$body = $me;

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);