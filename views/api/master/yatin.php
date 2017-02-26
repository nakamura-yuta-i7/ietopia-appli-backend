<?php
$model = new Yatin();
echo Json::encode($model->findAll());