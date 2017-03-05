<?php
$userId = Application::getInstance()->getUserWithAuthCheck()["id"];
$where = " user_id = '{$userId}' ";
$model = new Favorite();
echo Json::encode( $model->findList(compact("where"), $key="room_id") );
