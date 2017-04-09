
<div class="ui basic modal small">
	<div class="ui icon header">
		<i class="call square icon"></i>
		<div class="tel-message">
			お問い合わせ（電話）ボタンがクリックされました。
		</div>
		<div class="ui inverted button" id="open-user-data">ユーザー情報を開く</div>
		<script type="text/javascript">
		$("#open-user-data").on("click", function() {
			var url = "/admin/user/modal?id=" + telUserId;
			$("#modal").load(url, function() {
				$("#modal .ui.modal").modal("show");
			});
		});
		</script>
	</div>
</div>

<style>
#tel-modal {  }
.modal .call.square.icon {
	font-size: 60px !important;
}
.tel-message {
	margin-bottom: 2em;
}
</style>

<script type="text/javascript">
var lastID = 0;
var telUserId = null;
telPolling();
function telPolling() {
	$.ajax({
		url: "/admin/inquiry/ajax_tel-polling",
		dataType: "json",
	}).then(function(result) {
		var id = parseInt( result.id );
		if ( lastID == 0 ) {
			lastID = id;
		}
		if ( lastID < result.id ) {
			$('.ui.basic.modal').modal('show');
			alert("お問い合わせ（電話）ボタンがクリックされました。");
			lastID = result.id;
			telUserId = result.user_id;
		}
		setTimeout(telPolling, 3000);
	});
}
</script>
