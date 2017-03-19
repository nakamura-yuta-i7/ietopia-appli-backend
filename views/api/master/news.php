<?php
$model = new News();
echo Json::encode($model->findAll());