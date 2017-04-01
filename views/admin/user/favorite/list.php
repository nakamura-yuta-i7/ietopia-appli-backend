<?php includeHeader([
	"title" => "ユーザー ＞ お気に入り登録",
	"description" => "ユーザーのお気に入り登録された物件の一覧です。",
]); ?>

<style>
table th { white-space: nowrap; }
table td.氏名 { white-space: nowrap; }
</style>

<?php
$user = new User();
$rows = $user->findAll([
	"fields" => [
		"favorite.created_at",
		"user.uuid",
		"user.name",
		"favorite.room_id",
	],
	"join" => implode([
		" inner join favorite on user.id = favorite.user_id ",
	]),
]);

$roomIds = [];
foreach ($rows as $row) { $roomIds[] = $row["room_id"]; }
$rooms = call_user_func(function() use($roomIds) {
	$rtn = [];
	$room = new Room();
	$rooms = $room->findAll(["where"=>" room.id IN (".implode(",", $roomIds).") "]);
	foreach ($rooms as $room) { $rtn[$room["id"]] = $room; }
	return $rtn;
});

$tableData = array_map(function($row) use($rooms) {
	$room = $rooms[$row["room_id"]];
	$roomUrl = $room["detail_url"];
	$roomLink = '<a href="'.$roomUrl.'" target="_blank">'.$room["id"].'</a>';
	return [
		"登録日時" =>  $row["created_at"],
		"端末UUID" => $row["uuid"],
		"氏名"     => $row["name"],
		"物件ID"   => $roomLink,
		"物件名"   => $room["name"],
	];
}, $rows);
echo (new HtmlTable($tableData))->getHtml();
?>

<script>
$(document).ready(function() {
	$('#html-table').DataTable({stateSave: true,});
} );
</script>
<?php includeFooter(); ?>