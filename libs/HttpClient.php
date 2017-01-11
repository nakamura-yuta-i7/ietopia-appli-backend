<?php
class HttpClient {
	static function request($url) {
		return static::requestSimple($url);
	}
	protected static function requestSimple($url) {
		$context = stream_context_create(array(
			'http' => array('ignore_errors' => true)
		));
		$response = file_get_contents($url, false, $context);
		
		preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
		$status_code = $matches[1];
		
		if ( $status_code == 200 ) {
			# 正常系
			return $response;
			
		} else if ( $status_code >= 300 && $status_code >= 399 ) {
			
		} else if ( $status_code >= 400 && $status_code >= 499 ) {
			
		} else if ( $status_code >= 500 && $status_code >= 599 ) {
			
		}
		throw new ErrorException(json_encode([
			"message"     => "HTTP Request Error.",
			"status_code" => $status_code,
			"response"    => $response
		], JSON_UNESCAPED_UNICODE));
	}
}
