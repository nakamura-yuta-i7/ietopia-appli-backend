<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_once APP_ROOT . "/models/batch/IetopiaPickUpImportBatch.php";

try {
	$batch = new IetopiaPickUpImportBatch();
	$batch->execute();

} catch (Exception $e) {
	Log::fatal($e);
}
