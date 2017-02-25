<?php
$uuid = $_GET["uuid"];
$searchParamsJson = $_GET["params_json"];

try {
	SearchHistory::saveParams($uuid, $searchParamsJson);
	http_response_code(200);
	$body = "ok";

} catch (Exception $e) {

	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);