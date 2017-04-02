<?php
$model = new News();
$rows = array_map(function($row) {
	$row["body"] = News::convertBody($row["body"]);
	return $row;
}, $model->findAll([
	"order" => " created_at DESC ",
]));
echo Json::encode($rows);