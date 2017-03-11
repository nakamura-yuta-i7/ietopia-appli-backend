<?php
$userId = Application::getInstance()->getUserWithAuthCheck()["id"];
echo Json::encode( Favorite::getListByUserId($userId) );
