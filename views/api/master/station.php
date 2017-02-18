<?php
$params = $_GET;
$model = new Station();
echo Json::encode($model->findAll($params));