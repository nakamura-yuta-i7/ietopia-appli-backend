<?php
require_once __DIR__ . "/../../config/bootstrap.php";

$query = "CREATE TABLE `news` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`title` TEXT DEFAULT NULL,
`body` TEXT DEFAULT NULL,
`created_at` TEXT DEFAULT NULL,
`updated_at` TEXT DEFAULT NULL,
`isinactive` INTEGER DEFAULT 0
);";

$db = new IetopiaMasterDbModel($data);
$db->query($query);