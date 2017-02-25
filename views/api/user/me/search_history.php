<?php
$model = new SearchHistory();
$uuid = SQLite3::escapeString($_GET["uuid"]);
$userId = User::getIdByUUID($uuid);
$where = " user_id = '{$userId}' ";
echo Json::encode( $model->findOne(compact("where")) );
