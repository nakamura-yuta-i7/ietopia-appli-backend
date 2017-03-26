<?php
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . "/../vendor/autoload.php";
define("APP_ROOT", realpath(__DIR__."/../") );

define("IS_PROD", gethostname() == "www1818.sakura.ne.jp" );
define("IS_DEV", ! IS_PROD );

# 家とぴあ：運営者
define("IETOPIA_CORP_MAIL", "yuta.nakamura.i7@gmail.com");

# 家とぴあAPI
define("IETOPIA_API_SERVICE_NAME", "Ietopia API Backend Service");
define("IETOPIA_API_SERVICE_SMTP", "smtp.mail.yahoo.co.jp");
define("IETOPIA_API_SERVICE_EMAIL", "yuta_nakamura_i7@yahoo.co.jp");
define("IETOPIA_API_ADMIN_EMAIL", "yuta.nakamura.i7@gmail.com");

# 家とぴあアプリ：管理者画面：PATH
define("ADMIN_ROOT_PATH", "/admin/");

# パスワード
require_once __DIR__ . "/passwords.php";

# ライブラリ
require_once APP_ROOT . "/libs/Json.php";
require_once APP_ROOT . "/libs/Ltsv.php";
require_once APP_ROOT . "/libs/HtmlTable.php";

# アプリケーションクラス
require_once APP_ROOT . "/models/Application.php";

# ログ設定
require_once APP_ROOT . '/models/logger/Log.php';
require_once APP_ROOT . '/models/mailer/IetopiaMailer.php';

# ログ: ファイル
Log::addLogger(call_user_func(function() {
	$logger = new FileLogger();
	$logger->destination = APP_ROOT . "/log/info.log"; # デフォルト保存ファイル
	return $logger;
}), ( IS_PROD ? LogLevel::INFO : LogLevel::DEBUG ) );

# ログ: メール (FATALレベル)
$mailer = IetopiaMailer::getInstance();
$mailer->addAddress(IETOPIA_API_ADMIN_EMAIL);
$mailer->Subject .= 'Log::fatal';
Log::addLogger(new MailLogger($mailer), LogLevel::FATAL);


# 家とぴあ
define("IETOPIA_URL", "http://www.ietopia.jp");
define("IETOPIA_DETAIL_BASE_URL", IETOPIA_URL . "/rent");
require_once APP_ROOT . "/models/ietopia/IetopiaRentSearch.php";

# データベース
define("IETOPIA_ROOM_DB", "ietopia_room.sqlite");
define("IETOPIA_MASTER_DB", "ietopia_master.sqlite");
define("IETOPIA_USER_DB", "ietopia_user.sqlite");
require_once __DIR__ . "/ConnectionManager.php";
require_once APP_ROOT . "/models/db/room/IetopiaRoomDbModel.php";
require_once APP_ROOT . "/models/db/master/IetopiaMasterDbModel.php";
require_once APP_ROOT . "/models/db/user/IetopiaUserDbModel.php";

