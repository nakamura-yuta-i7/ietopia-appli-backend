<?php
$userId = Application::getInstance()->getUserWithAuthCheck()["id"];
echo Json::encode( SearchHistory::getByUserId($userId) );
