<?php
$id = SQLite3::escapeString($_GET["id"]);
$where = " id = {$id} ";

$model = new News();
$model->delete($where);

echo "ok";