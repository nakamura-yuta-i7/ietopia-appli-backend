<?php
$user = Application::getInstance()->getUserWithAuthCheck();
$uuid = $user["uuid"];
$params = $_REQUEST;
$userName = $params["name"];

try {
	$mailer = IetopiaMailer::getInstance();
	$mailer->addAddress(IETOPIA_CORP_MAIL);
	$mailer->setSubject("家とぴあアプリ: {$userName}様からお問い合わせがありました。");
	$mailer->setHtmlBody(createHtmlBody($uuid, $params));
	$mailer->send();
	
	$inquiry = new Inquiry();
	$inquiry->insert([
		"user_id"     => $user["id"],
		"room_id"     => isset($params["room_id"]) ? $params["room_id"] : NULL,
		"params_json" => Json::encode($params),
		"type"        => "mail",
	]);
	
	http_response_code(200);
	$body = "ok";
	
} catch (Exception $e) {
	
	http_response_code(500);
	$body = ["error"=>$e->getMessage()];
}
echo JSON::encode($body);




function createHtmlBody($uuid, $params) {
	
	Log::info($params);
	extract($params);
	
	$kibou_renraku_houhou = implode(",", $kibou_renraku_houhou);
	$note = nl2br($note);
	
	$roomUrl = NULL;
	if (isset($room_id)) {
		$room = new Room();
		$res = $room->findOne([
			"fields" => [ Room::detailUrlField() . " AS url" ],
			"where"=> "id = ". SQLite3::escapeString($room_id) ]);
		$roomUrl = $res["url"];
	}
	
	$roomIdString = isset($room_id) ? "
		<b>物件ID:</b><br>
		　{$room_id} ($roomUrl)<br>
	" : "";
	
	return "
	<b><u>お問い合わせ内容</u></b><br>
	<br>
	{$roomIdString}
	<b>UUID:</b><br>
	　{$uuid}<br>
	<b>お名前:</b><br>
	　{$name}<br>
	<b>フリガナ:</b><br>
	　{$furigana}<br>
	<b>住所:</b><br>
	　{$jusho}<br>
	<b>電話番号:</b><br>
	　{$tel}<br>
	<b>メールアドレス:</b><br>
	　{$mail}<br>
	<b>ご希望の連絡方法:</b><br>
	　{$kibou_renraku_houhou}<br>
	<b>ご希望の連絡時間:</b><br>
	　{$kibou_renraku_jikan_start} ~ {$kibou_renraku_jikan_end}<br>
	<b>備考:</b><br>
	　{$note}<br>
";
}

