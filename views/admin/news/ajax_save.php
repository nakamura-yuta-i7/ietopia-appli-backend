<?php
# 新規の時
$values = [
	"created_at" => $_REQUEST["created_at"],
	"title"      => $_REQUEST["title"],
	"body"       => $_REQUEST["body"],
];

if ( isset($_REQUEST["id"]) && $_REQUEST["id"] ) {
	# 編集の時
	$id = SQLite3::escapeString($_REQUEST["id"]);
	$values["id"] = $id;
} else {
	# 新規の時
	
}

$model = new News();
$model->upsert($values, $pk="id");

echo "ok";