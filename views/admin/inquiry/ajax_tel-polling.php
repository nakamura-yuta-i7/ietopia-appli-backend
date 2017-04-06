<?php
$model = new Inquiry();
$result = $model->findOne([
	"where" => " type = 'tel' ",
	"limit" => 1,
	"order" => " id DESC ",
]);
echo Json::encode( $result );