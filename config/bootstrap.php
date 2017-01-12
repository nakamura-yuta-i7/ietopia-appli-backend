<?php
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . "/../vendor/autoload.php";
define("APP_ROOT", realpath(__DIR__."/../") );

# パスワード
require_once __DIR__ . "passwords.php";

# ライブラリ
require_once APP_ROOT . "/libs/Json.php";

# ログ設定
require_once APP_ROOT . '/models/logger/Log.php';
require_once APP_ROOT . '/models/mailer/IetopiaMailer.php';

# ログ: ファイル (INFOレベル)
Log::addLogger(call_user_func(function() {
	$logger = new FileLogger();
	$logger->destination = APP_ROOT . "/log/info.log"; # デフォルト保存ファイル
	return $logger;
}), LogLevel::INFO );

# ログ: メール (FATALレベル)
$mailer = IetopiaMailer::getInstance();
$mailer->setFrom('yuta_nakamura_i7@yahoo.co.jp', 'Ietopia API Backend Service MailLogger');
$mailer->addAddress('yuta.nakamura.i7@gmail.com');
$mailer->Subject = 'Log::fatal';
Log::addLogger(new MailLogger($mailer), LogLevel::FATAL);


# 家とぴあ
define("IETOPIA_URL", "http://www.ietopia.jp");
require_once APP_ROOT . "/models/ietopia/IetopiaRentSearch.php";

# データベース
require_once __DIR__ . "/ConnectionManager.php";
require_once APP_ROOT . "/models/db/Room.php";
require_once APP_ROOT . "/models/db/GaikanImages.php";
