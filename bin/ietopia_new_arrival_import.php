<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_once APP_ROOT . "/models/batch/IetopiaNewArrivalImportBatch.php";

try {
	$batch = new IetopiaNewArrivalImportBatch();
	$batch->execute();

} catch (Exception $e) {
	Log::fatal($e);
}
