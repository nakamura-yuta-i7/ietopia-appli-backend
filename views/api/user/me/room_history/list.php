<?php
$userId = Application::getInstance()->getUserWithAuthCheck()["id"];
$where = " user_id = '{$userId}' ";
$model = new RoomHistory();
echo Json::encode( $model->findAll(compact("where")) );
