<?php
$model = new Ekitoho();
echo Json::encode($model->findAll());