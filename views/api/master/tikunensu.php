<?php
$model = new Tikunensu();
echo Json::encode($model->findAll());