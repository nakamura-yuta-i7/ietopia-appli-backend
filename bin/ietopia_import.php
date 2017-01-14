<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_once APP_ROOT . "/models/batch/IetopiaImportBatch.php";

try {
	$batch = new IetopiaImportBatch();
	$batch->execute();
	
} catch (Exception $e) {
	Log::fatal($e);
}
