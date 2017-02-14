<?php
$model = new Rosen();
echo Json::encode($model->findAll());