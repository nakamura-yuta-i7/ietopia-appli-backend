<?php
$model = new Madori();
echo Json::encode($model->findAll());