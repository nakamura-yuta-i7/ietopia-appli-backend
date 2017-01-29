<?php
require_once __DIR__ . "/../config/bootstrap.php";

$logPath = APP_ROOT . "/log/info.log";

exec(' echo "" > '. $logPath );

