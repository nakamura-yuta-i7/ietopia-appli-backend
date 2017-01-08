<?php
require_once __DIR__ . "/../vendor/autoload.php";

$url = "http://www.ietopia.jp/rent_search/area/%E6%9D%B1%E4%BA%AC%E9%83%BD-%E8%B1%8A%E5%B3%B6%E5%8C%BA/limit:50";
$url = "http://www.ietopia.jp/rent/53";

phpQuery::newDocumentFileHTML($url);

var_export(pq("#item_name")->html());