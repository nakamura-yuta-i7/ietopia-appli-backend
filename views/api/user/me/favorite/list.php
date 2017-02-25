<?php
$model = new Favorite();
$uuid = SQLite3::escapeString($_GET["uuid"]);
$userId = User::getIdByUUID($uuid);
$where = " user_id = '{$userId}' ";
echo Json::encode( $model->findAll(compact("where")) );
