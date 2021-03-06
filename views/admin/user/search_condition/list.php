<?php includeHeader([
	"title" => "ユーザー ＞ 検索条件",
	"description" => "ユーザーが最後に検索画面で指定した検索条件一覧です。",
]); ?>

<?php
$model = new User();
$rows = $model->findAll([
	"fields" => [
		"user.uuid",
		"user.name",
		"search_history.params_json",
		"search_history.updated_at"
	],
	"join" => " INNER JOIN search_history ON user_id = user.id ",
]);
$tableData = array_map(function($row) {
	$userLink = User::createUserModalLink($row["uuid"]);
	return [
		"最終更新日時"      => $row["updated_at"],
		"端末UUID"        => $userLink,
		"氏名"            => $row["name"],
		"最終検索パラメータ" => $row["params_json"],
	];
}, $rows);

echo (new HtmlTable($tableData))->getHtml();
?>
<script>
$(document).ready(function() {
	$('#html-table').DataTable({stateSave: true,});
} );
$(function() {
	$("a.user-modal").on("click", function() {
		var url = $(this).attr("href");
		$("#modal").load(url, function() {
			$("#modal .ui.modal").modal("show");
		});
		return false;
	});
});
</script>
<?php includeFooter(); ?>