<?php
$model = new KodawariJoken();
echo Json::encode($model->findAll());