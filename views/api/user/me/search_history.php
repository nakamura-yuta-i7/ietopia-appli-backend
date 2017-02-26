<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];

$model = new SearchHistory();
$userId = User::getIdByUUID($uuid);
$where = " user_id = '{$userId}' ";
echo Json::encode( $model->findOne(compact("where")) );
