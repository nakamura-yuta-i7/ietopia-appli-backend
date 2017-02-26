<?php
try {
	Application::getInstance()->logout();
	
	http_response_code(200);
	$body = "OK";
	
} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);