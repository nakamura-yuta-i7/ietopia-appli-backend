<?php
class ConnectionManager {
	static function getConnection($name) {
		switch ($name) {
			case "ietopia_room":
				$dsn = "sqlite:" . APP_ROOT . "/db/" . IETOPIA_ROOM_DB;
				$user = '';
				$password = '';
				return new SqliteConnection($dsn, $user, $password);
			case "ietopia_master":
				$dsn = "sqlite:" . APP_ROOT . "/db/" . IETOPIA_MASTER_DB;
				$user = '';
				$password = '';
				return new SqliteConnection($dsn, $user, $password);
			default:
				throw new ErrorException("未定義のデータベース接続を呼び出そうとしました");
		}
	}
}
interface ConnectionInterface {}
class SqliteConnection implements ConnectionInterface {
	protected $_dbh;
	function __construct($dsn, $user, $password) {
		$dbh = new PDO($dsn, $user, $password);
		if ( ! $dbh ) {
			throw new ErrorException("接続に失敗しました  info: ". var_export(func_get_args()));
		}
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->_dbh = $dbh;
	}
	function __call($name, $arguments) {
		return call_user_func_array([$this->_dbh, $name], $arguments);
	}
}