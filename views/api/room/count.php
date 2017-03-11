<?php
$model = new Room();
$where = Room::createSearchCondition();
echo Json::encode($model->findCount($where));