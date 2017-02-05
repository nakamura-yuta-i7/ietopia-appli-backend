<?php
require_once __DIR__ . "/../config/bootstrap.php";

try {
	$router = new Router();
	$router->dispatch();
	
} catch (Exception $e) {
	
	if ( $e->getMessage() == 404 ) {
		header("HTTP/1.0 404 Not Found");
		return;
	}
	
	header('HTTP', true, 500);
	Log::fatal($e);
}

class Router {
	function __construct() {
		$this->path     = $_SERVER["PATH_INFO"];
		$this->requests = $_REQUEST;
	}
	function dispatch() {
		$viewPath = APP_ROOT . "/views" . $_SERVER["PATH_INFO"] . ".php";
		if ( !file_exists($viewPath) ) {
			throw new ErrorException(404);
		}
		header("Access-Control-Allow-Origin: *");
		include($viewPath);
	}
}

# echo "家とぴあAPI Backend Service";
# $_SERVER
# array (
# 	'DOCUMENT_ROOT' => '/Users/yuta/apps/ietopia/webroot',
# 	'REMOTE_ADDR' => '127.0.0.1',
# 	'REMOTE_PORT' => '53562',
# 	'SERVER_SOFTWARE' => 'PHP 5.6.27 Development Server',
# 	'SERVER_PROTOCOL' => 'HTTP/1.1',
# 	'SERVER_NAME' => '0.0.0.0',
# 	'SERVER_PORT' => '8080',
# 	'REQUEST_URI' => '/api/room/list?id=12161',
# 	'REQUEST_METHOD' => 'GET',
# 	'SCRIPT_NAME' => '/index.php',
# 	'SCRIPT_FILENAME' => '/Users/yuta/apps/ietopia/webroot/index.php',
# 	'PATH_INFO' => '/api/room/list',
# 	'PHP_SELF' => '/index.php/api/room/list',
# 	'QUERY_STRING' => 'id=12161',
# 	'HTTP_HOST' => 'localhost:8080',
# 	'HTTP_CONNECTION' => 'keep-alive',
# 	'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
# 	'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.95 Safari/537.36',
# 	'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
# 	'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch, br',
# 	'HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.8,en;q=0.6',
# 	'REQUEST_TIME_FLOAT' => 1484396905.988306,
# 	'REQUEST_TIME' => 1484396905,
# 	'argv' =>
# 	array (
# 		0 => 'id=12161',
# 	),
# 	'argc' => 1,
# )