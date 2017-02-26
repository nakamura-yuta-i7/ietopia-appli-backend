<?php
$uuid = Application::getInstance()->getUserWithAuthCheck()["uuid"];

$model = new SearchHistory();
$userId = User::getIdByUUID($uuid);
$where = " user_id = '{$userId}' ";
$result = $model->findOne(compact("where"));
echo Json::encode( $result ? $result : [] );
