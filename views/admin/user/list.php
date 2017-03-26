<?php includeHeader([
	"title" => "ユーザー ＞ 一覧",
	"description" => "アプリ初回起動時にユーザー登録が自動的に完了します。",
]); ?>

<style>
table th { white-space: nowrap; }
table td.氏名 { white-space: nowrap; }
</style>

<?php
$user = new User();
$users = $user->findAll();
$tableData = array_map(function($row) {
	$basicInfo = User::createBasicInfoForHtml($row);
	$kibouInfo = User::createKibouInfoForHtml($row);
	return [
		"登録日時" => $row["created_at"],
		"端末UUID" => $row["uuid"],
		"氏名"     => $row["name"],
		"基本情報"  => $basicInfo,
		"希望のお住い" => $kibouInfo,
	];
}, $users);
echo (new HtmlTable($tableData))->getHtml();
?>

<script>
$(document).ready(function() {
	$('#html-table').DataTable();
} );
</script>
<?php includeFooter(); ?>