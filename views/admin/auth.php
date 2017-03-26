<?php
if ( !isset($_POST["password"]) || !$_POST["password"] ) {
	exit("パスワードが必要です。");
}
if ( !$_POST["password"] == ADMIN_PASSWORD ) {
	exit("パスワードが一致しませんでした。");
}
# パスワードが一致したら
session_start();
$_SESSION["admin"] = 1;
header("location: ". adminUrl("index"));
exit;