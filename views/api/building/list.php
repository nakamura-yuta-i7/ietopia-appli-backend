<?php
$model = new Building();

$order = " name ";
$fields = [
	"id", "name"
];
$rows = $model->findAll(compact("fields","where","limit","offset","order"));

echo Json::encode($rows);
