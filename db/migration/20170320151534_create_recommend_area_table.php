<?php
require_once __DIR__ . "/../../config/bootstrap.php";


$db = new IetopiaMasterDbModel($data);

$db->query("DROP TABLE IF EXISTS 'recommend_area'; ");

$db->query("CREATE TABLE `recommend_area` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` TEXT DEFAULT NULL,
`value` TEXT DEFAULT NULL,
`created_at` TEXT DEFAULT NULL,
`updated_at` TEXT DEFAULT NULL,
`isinactive` INTEGER DEFAULT 0
);");

$db->query("INSERT INTO `recommend_area` VALUES (1,'池袋本町<br>1〜4丁目','池袋本町',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (2,'豊島区<br>全域','豊島区',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (3,'上池袋<br>1〜4丁目','上池袋',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (4,'西池袋<br>1〜5丁目','西池袋',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (5,'池袋<br>1〜4丁目','豊島区池袋',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (6,'東池袋<br>1〜5丁目','東池袋',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (7,'目白<br>1〜5丁目','目白',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (8,'南池袋<br>1〜4丁目','南池袋',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (9,'雑司が谷<br>1〜3丁目','雑司が谷',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (10,'北大塚<br>1〜3丁目','北大塚',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (11,'南大塚<br>1〜3丁目','南大塚',NULL,NULL,0);               ");
$db->query("INSERT INTO `recommend_area` VALUES (12,'(文京区)大塚<br>1〜6丁目','文京区',NULL,NULL,0);               ");
