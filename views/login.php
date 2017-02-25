<?php
$uuid = $_GET["uuid"];

try {
	$user = User::findByUUID($uuid);
	
	if ( ! $user ) {
		# 初回アクセスの場合、uuidでユーザー登録
		User::save($uuid);
	}
	$me = User::getMe($uuid);
	$userId = $me["id"];
	Log::info("LOGIN.  user_id: {$userId}");
	
	session_start();
	$_SESSION["uuid"] = $uuid;
	
	http_response_code(200);
	$body = $me;

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);