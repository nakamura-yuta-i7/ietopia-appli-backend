<?php
class NotFoundError extends ErrorException {}
class UnauthorizedError extends ErrorException {}
class ForbiddenError extends ErrorException {}

class Application {
	static $self;
	static function getInstance() {
		if ( static::$self ) return static::$self;
		session_start();
		return static::$self = new Application();
	}
	function authCheck() {
		if ( ! $this->isLoggedIn() ) {
			throw new UnauthorizedError("Unauthorized Error.");
		}
	}
	function getUserWithAuthCheck() {
		$this->authCheck();
		return $this->getUser();
	}
	private $_user;
	function getUser() {
		if ( ! $this->_user ) $this->setUser();
		return $this->_user;
	}
	function setUser($uuid=NULL) {
		$uuid = $_SESSION["uuid"];
		$this->_user = User::findByUUID($uuid);
	}
	function isLoggedIn() {
		return isset($_SESSION["uuid"]) && $_SESSION["uuid"];
	}
	function login($uuid) {
		$user = User::findByUUID($uuid);
		# 初回アクセスの場合、uuidでユーザー登録
		if ( ! $user ) User::save($uuid);
		$me = User::getMe($uuid);
		$userId = $me["id"];
		Log::info("LOGIN.  user_id: {$userId}");
		
		$_SESSION["uuid"] = $uuid;
		return $me;
	}
	function logout() {
		$user = User::findByUUID($uuid);
		$userId = $user["id"];
		Log::info("LOGOUT.  user_id: {$userId}");
		
		unset($_SESSION["uuid"]);
		session_destroy();
	}
}
