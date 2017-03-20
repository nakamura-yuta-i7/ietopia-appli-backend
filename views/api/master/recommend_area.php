<?php
$model = new RecommendArea();
echo Json::encode($model->findAll());