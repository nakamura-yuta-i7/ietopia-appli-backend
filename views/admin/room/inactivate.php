<?php
validateAdminAuth();
$roomId = $_GET["room_id"];

$room = new Room();
$room->update([
	"isinactive" => 1,
], " room.id = {$roomId} ");

echo "ok";