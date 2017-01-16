<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_once APP_ROOT . "/models/batch/IetopiaMasterImportBatch.php";

try {
	$batch = new IetopiaMasterImportBatch();
	$batch->execute();

} catch (Exception $e) {
	Log::fatal($e);
}
