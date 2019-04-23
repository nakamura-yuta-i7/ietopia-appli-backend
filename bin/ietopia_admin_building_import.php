<?php
require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../models/ietopia/IetopiaAdminConsole.php";

$admin = new IetopiaAdminConsole();

$buildingRows = array_map(function($row) {
	return [
		Building::ID   => $row[Building::ID],
		Building::NAME => SQLite3::escapeString($row[Building::NAME]),
	];
}, $admin->getBuildings());

$model = new Building();
$model->sync($buildingRows, $pk="id");
