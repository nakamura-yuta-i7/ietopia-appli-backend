<?php includeHeader([
	"title" => "物件管理 > 一覧",
	"description" => "アプリに掲載されている物件情報の一覧です。",
]); ?>
<style>
table th { white-space: nowrap; }
table td.アクション { text-align: center !important; }
table td.アクション button { white-space: nowrap; }
table td.お気に入り件数 { text-align: center !important; }
table tr.disabled { text-decoration: underline; }
</style>
<?php
$model = new Room();
$params = [];
$allFavorite = [];
if ( isset($_GET["favorite"]) && $_GET["favorite"] ) {
	$allFavorite = Favorite::getAllGroupByRoomId();
	$favoriteRoomIds = array_keys($allFavorite);
	if ( $favoriteRoomIds ) {
		$params["where"] = " room.id IN (". implode(",", $favoriteRoomIds) .") ";
	} else {
		$params["where"] = " 1 = 0 ";
	}
}
$rows = $model->findAll($params);
$tableData = array_map(function($row) use($allFavorite) {
	$disableBtn = '<button class="ui red button mini inverted" onclick="inactivate('.$row["id"].', event);">無効化</button>';
	$roomLink = '<a href="'.$row["detail_url"].'" target="_blank">'.$row["id"].'</a>';
	$data = [
		"取込日時" => $row["created_at"],
		"物件ID"  => $roomLink,
		"物件名"  => $row["name"],
	];
	if ( isset($_GET["favorite"]) && $_GET["favorite"] ) {
		$data["お気に入り件数"] = $allFavorite[$row["id"]];
	} else {
		$data["アクション"] = $disableBtn;
	}
	return $data;
}, $rows );
echo (new HtmlTable($tableData))->getHtml();
?>

<script>
$(document).ready(function() {
	$('#html-table').DataTable();
} );
function inactivate(room_id, event) {
	if (!confirm("無効化しますか？")) {
		return false;
	}
	var $selfBtn = $(event.target);
	$.get("<?php echo adminUrl("room/inactivate")?>", {room_id: room_id}, function(result) {
		if ( result != "ok" ) return false;
		alert("無効化しました。");
		$selfBtn.attr("disabled","disabled");
		var $selfTr = $selfBtn.closest("tr");
		$selfTr.addClass("disabled");
	});
}
</script>

<?php includeFooter(); ?>