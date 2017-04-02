<?php includeHeader([
	"title" => "お知らせ ＞ 一覧",
	"description" => "マイページにある「お知らせ」を管理できます。",
]); ?>

<style>
table th { white-space: nowrap; }
table td.氏名 { white-space: nowrap; }
table a { cursor: pointer; }
table td.詳細,
table td.編集,
table td.削除 { text-align: center !important; }
table tr.disabled * { color: #ccc; text-decoration: line-through; }
</style>

<?php
$model = new News();
$rows = $model->findAll();

$tableData = array_map(function($row) {
	$id = $row["id"];
	$detailLink = "<a class='detail' href='/admin/news/modal?id={$id}'>詳細</a>";
	$editLink = "<a class='edit' href='/admin/news/edit?id={$id}'>編集</a>";
	$delLink = "<a class='delete' id='{$id}'>削除</a>";
	$body = $row["body"];
	$bodyLen = mb_strlen($body);
	$bodyAfter = ( $bodyLen > 50 ?  mb_substr($row["body"], 0, 50) . " ..." : $body );
	return [
		"作成日時" => $row["created_at"],
		"更新日時" => $row["updated_at"],
		"タイトル" => $row["title"],
		"本文"    => $bodyAfter,
		"詳細"    => $detailLink,
		"編集"    => $editLink,
		"削除"    => $delLink,
	];
}, $rows);
echo (new HtmlTable($tableData))->getHtml();
?>

<script>
$(document).ready(function() {
	$('#html-table').DataTable({stateSave: true,});
} );
$(function() {
	$("a.detail").on("click", function() {
		var url = $(this).attr("href");
		$("#modal").load(url, function() {
			$("#modal .ui.modal").modal("show");
		});
		return false;
	});
	$("a.delete").on("click", function() {
		var $tr = $(this).closest("tr");
		if (confirm("削除しますか？")) {
			$.ajax({
				url: "./ajax_delete",
				data: { id: $(this).attr("id") },
			}).then(function() {
				$tr.addClass("disabled");
			});
		}
	});
});
</script>
<?php includeFooter(); ?>