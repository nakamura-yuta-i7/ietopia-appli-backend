<?php
require_once __DIR__ . "/../../config/bootstrap.php";

$model = new Room();
echo Json::encode($model->findAll());