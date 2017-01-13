<?php
# ログID: プロセス毎にランダム
define("LOG_ID", uniqid());

class Log {
	
	private static $_loggers = [];
	static function addLogger(
		Logger $logger, $level = LogLevel::INFO)
	{
		static::$_loggers[$level][] = $logger;
	}
	static function __callstatic($name, $arguments) {
		
		$logLevel = call_user_func(function() use($name) {
			switch ($name) {
				case "fatal": return LogLevel::FATAL;
				case "info" : return LogLevel::INFO;
				case "debug": return LogLevel::DEBUG;
				default: throw new ErrorException("Not Defined.");
			}
		});
		
		$outData = $arguments[0];
		
		$trace = debug_backtrace($limit = 2)[0];
		$file = $trace["file"];
		$line = $trace["line"];
		
		$params = compact("outData", "file", "line");
		static::loggersOut($params, $logLevel);
	}
	static function loggersOut($params, $logLevel) {
		$data = $params["outData"];
		$file = $params["file"];
		$line = $params["line"];
		
		$text = !is_array($data) ? 
			$data : Json::encode($data) ;
		
		foreach (static::$_loggers as $level => $loggers) {
			if ($level > $logLevel) {
				# 出力するログレベルよりも高いログはスキップ
				continue;
			}
			foreach ($loggers as $logger) {
				$logger->out($text, $file, $line, $logLevel);
			}
		}
	}
}
class LogLevel {
	const FATAL = 10;
	const INFO  = 5;
	const DEBUG = 1;
	static function nameByLevel($logLevel) {
		switch ($logLevel) {
			case static::FATAL: return "fatal";
			case static::INFO : return "info";
			case static::DEBUG: return "debug";
			default: throw new ErrorException("Not Defined.");
		}
	}
}
interface Logger {
	function out($text, $file, $line, $logLevel);
}
function logInfo($file, $line, $logLevel) {
	$now = date_create()->format("Y-m-d H:i:s");
	$logLevelName = LogLevel::nameByLevel($logLevel);
	$memoryUsageMB = memory_get_usage() / (1024 * 1024);
	$memoryUsageMB = round( $memoryUsageMB, 2 );
	return LOG_ID . "  [$logLevelName] $now $file ($line) memory_usage: " . $memoryUsageMB . "MB" . PHP_EOL;
}
class FileLogger implements Logger {
	function __construct() {
		$this->message_type  = 3; # ファイルに記録
		$this->destination   = "/tmp/ietopia_filelogger.log"; # デフォルト保存ファイル
		$this->extra_headers = null;
	}
	function out($text, $file, $line, $logLevel) {
		
		$lines = preg_split("/\r\n|\n|\r/", $text);
		$splited = "";
		foreach ($lines as $oneLine) {
			$splited .= LOG_ID . "  " . $oneLine . PHP_EOL;
		}
		
		$message = logInfo($file, $line, $logLevel) . $splited;
		
		$bool = error_log( $message,
			$this->message_type,
			$this->destination,
			$this->extra_headers
		);
		if (!$bool) {
			throw new ErrorException(
				"FileLogger Write Error.  destination: {$destination}" );
		}
	}
}
class MailLogger implements Logger {
	function __construct(PHPMailer $mail) {
		$this->mail = $mail;
		$this->mail->Body = "";
	}
	function out($text, $file, $line, $logLevel) {
		$br = ($this->mail->ContentType=="text/html"? "<br>" : PHP_EOL );
		$this->mail->Body .= logInfo($file, $line, $logLevel) . $text . $br;
	}
	function __destruct() {
		if ( ! $this->mail->Body ) {
			return;
		}
		if( !$this->mail->send() ) {
			throw new ErrorException( Json::encode($mail->ErrorInfo) );
		}
	}
}