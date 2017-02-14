<?php
$model = new Station();
echo Json::encode($model->findAll());