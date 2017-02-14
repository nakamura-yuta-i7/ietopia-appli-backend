<?php
$model = new Menseki();
echo Json::encode($model->findAll());