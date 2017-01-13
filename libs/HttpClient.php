<?php
require_once APP_ROOT . "/libs/Json.php";

class HttpClient {
	static function request($url) {
		return static::requestSimple($url);
	}
	protected static function requestSimple($url) {
		$context = stream_context_create(array(
			'http' => array('ignore_errors' => true)
		));
		
		Log::info([__METHOD__, '$url: '.$url ]);
		
		$response = file_get_contents($url, false, $context);
		
		preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
		$status_code = $matches[1];
		
		if ( $status_code == 200 ) {
			# 正常系
			return $response;
		} else if ( $status_code >= 300 && $status_code >= 399 ) {
			
		} else if ( $status_code == 404 ) {
			throw new HttpStatus404Error( Json::encode([
				"message"     => "404 NotFound Error.",
				"status_code" => $status_code,
				"response"    => $response
			]) );
		} else if ( $status_code >= 400 && $status_code >= 499 ) {
			
		} else if ( $status_code >= 500 && $status_code >= 599 ) {
			
		}
		throw new HttpStatusError( Json::encode([
			"message"     => "HTTP Request Error.",
			"status_code" => $status_code,
			"response"    => $response
		]) );
	}
}

class HttpStatusError extends ErrorException {}
class HttpStatus404Error extends ErrorException {}
