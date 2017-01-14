<?php
class Json {
	static function encode() {
		$params = func_get_args();
		if ( ! isset($params[1]) ) {
			$params[1] = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
		}
		return call_user_func_array("json_encode", $params);
	}
	static function decode() {
		$params = func_get_args();
		return call_user_func_array("json_decode", $params);
	}
}