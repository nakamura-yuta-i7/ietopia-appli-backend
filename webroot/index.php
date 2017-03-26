<?php
require_once __DIR__ . "/../config/bootstrap.php";

try {
	$router = new Router();
	$router->dispatch();
	return;
	
} catch (NotFoundError $e) {
	
	http_response_code(404);
	
} catch (Exception $e) {
	
	http_response_code(500);
}
echo Json::encode([
	"error" => $e->getMessage(),
]);
//Log::fatal($e);

function PATH_INFO() {
	return preg_replace("/\?.+/", "", $_SERVER["REQUEST_URI"]);
};
class Router {
	function __construct() {
		$this->path     = PATH_INFO();
		$this->requests = $_REQUEST;
	}
	function dispatch() {
		$pathInfo = PATH_INFO();
		if ( preg_match("/\/$/", $pathInfo) ) {
			$pathInfo .= "index";
		}
		$viewPath = APP_ROOT . "/views" . $pathInfo . ".php";
		if ( !file_exists($viewPath) ) {
			throw new NotFoundError("Not Found");
		}
		$HTTP_ORIGIN = $_SERVER["HTTP_ORIGIN"];
		header("Access-Control-Allow-Origin: {$HTTP_ORIGIN}");
		# header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-csrf-token");
		header('Access-Control-Allow-Credentials: true');
		
		include($viewPath);
	}
}



# ADMIN用: ここから
function adminUrl($path="") { return ADMIN_ROOT_PATH . "$path"; }
function adminHref($path="") { return "href='". adminUrl($path) ."'"; }
function includeHeader() {
	validateAdminAuth();
	extract(func_get_arg(0));
	include( __DIR__ . "/../views". ADMIN_ROOT_PATH ."_header.php" );
}
function includeFooter() { include( __DIR__ . "/../views". ADMIN_ROOT_PATH ."_footer.php" ); }
function validateAdminAuth() {
	session_start();
	if ( ! $_SESSION["admin"] ) {
		header("location: " . adminUrl("login"));
		exit;
	}
}
# ADMIN用: ここまで



