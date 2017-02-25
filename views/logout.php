<?php
try {
	$user = User::findByUUID($uuid);
	$userId = $user["id"];
	Log::info("LOGOUT.  user_id: {$userId}");
	
	session_start();
	unset($_SESSION["uuid"]);
	session_destroy();
	
	http_response_code(200);
	$body = "OK";
	
} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);