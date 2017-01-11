<?php
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . "/../vendor/autoload.php";
define("APP_ROOT", realpath(__DIR__."/../") );

# ライブラリ
require_once APP_ROOT . "/libs/Json.php";

# 家とぴあ
define("IETOPIA_URL", "http://www.ietopia.jp");
require_once APP_ROOT . "/models/ietopia/IetopiaRentSearch.php";

# データベース
require_once __DIR__ . "/ConnectionManager.php";
require_once APP_ROOT . "/models/db/Room.php";
require_once APP_ROOT . "/models/db/GaikanImages.php";
