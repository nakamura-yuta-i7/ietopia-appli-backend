
<div class="ui basic modal small">
	<div class="ui icon header">
		<i class="call square icon"></i>
		電話でのお問い合わせボタンがクリックされました。
	</div>
	<div>
		
	</div>
</div>

<style>
#tel-modal {  }
.modal .call.square.icon {
	font-size: 60px !important;
}
</style>

<script type="text/javascript">
var lastID = 0;
setTimeout(telPolling, 3000);

function telPolling() {
	$.ajax({
		url: "/admin/inquiry/ajax_tel-polling",
	}).then(function(result) {
		var id = result.id;
		if ( lastID == 0 ) {
			lastID = id;
		}
		if ( lastID < result.id ) {
			lastID = result.id;
		}
	});
}
$('.ui.basic.modal').modal('show');
</script>
