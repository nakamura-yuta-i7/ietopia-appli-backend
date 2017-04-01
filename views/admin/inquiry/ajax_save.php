<?php
if ( !isset($_POST["id"]) || !isset($_POST["memo"]) ) {
	new ErrorException("パラメータが不正です");
}

$values = [
	"id" => $_POST["id"],
	"memo" => $_POST["memo"],
];

$model = new Inquiry();
$model->upsert($values, $pk="id");

echo "ok";