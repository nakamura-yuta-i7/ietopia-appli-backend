<?php
$bool = Application::getInstance()->isLoggedIn();
echo JSON::encode( "{$bool}" );