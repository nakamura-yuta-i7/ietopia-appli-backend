<style>
table th { white-space: nowrap; }
table td.氏名 { white-space: nowrap; }
table td { height: 100%; }
table textarea { min-height: 30px; height: 100%; width:100%; box-sizing: border-box; border:1px solid #efefef; display: block; }
table td.メモ\28担当者記入欄\29 { padding:0px !important; }
</style>

<?php
$user = new User();
$users = [];
foreach ($user->findAll() as $user) { $users[$user["id"]] = $user; }

$model = new Inquiry();
$rows = $model->findAll([
	"where" => " type = '{$inquiryType}' ",
]);

$tableData = array_map(function($row) use($users, $inquiryType) {
	$id   = $row["id"];
	$user = $users[$row["user_id"]];
	
	$params = $row["params_json"] ? Json::decode($row["params_json"], TRUE) : NULL;
	
	$basicInfo = User::createBasicInfoForHtml($user);
	$kibouInfo = User::createKibouInfoForHtml($user);

	$memoTextarea = "<textarea inquiry-id='{$id}'>".$row["memo"]."</textarea>";
	
	$userLink = User::createUserModalLink($user["uuid"]);
	
	if ( $inquiryType == "mail" ) {
		return [
			"送信日時"      => $row["created_at"],
			"端末UUID"     => $userLink,
			"氏名"         => $user["name"],
			"希望の連絡方法" => implode(",", $params["kibou_renraku_houhou"]),
			"メッセージ"    => nl2br($params["note"]),
			"メモ(担当者記入欄)" => $memoTextarea,
		];
	} else if ( $inquiryType == "tel" ) {
		
		return [
			"送信日時"      => $row["created_at"],
			"端末UUID"     => $userLink,
			"氏名"         => $user["name"],
			"メモ(担当者記入欄)" => $memoTextarea,
		];
	}
	
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
	$("textarea").on("change", function() {
		$.ajax({
			url: "./ajax_save",
			type: "POST",
			data: {
				id: $(this).attr("inquiry-id"),
				memo: $(this).val(),
			},
		});
	});
	$("textarea").on("focus", function() {
		if ( $(this).height() < 200 ) {
			$(this).height(200);
		}
	});
	$("textarea").on("focusout", function() {
		$(this).height("");
	});
});
</script>
<?php includeFooter(); ?>