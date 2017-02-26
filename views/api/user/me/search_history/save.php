<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];
$searchParamsJson = $_POST["params_json"];

try {
	SearchHistory::saveParams($uuid, $searchParamsJson);
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);