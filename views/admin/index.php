<?php includeHeader([
	"title"       => "ダッシュボード",
	"description" => "家とぴあアプリの管理者トップページです。",
]); ?>

<div class="ui grid">
	<div class="two column row">
		<div class="column">
			<h3>新着ユーザー</h3>
			<p class="description">アプリ初回起動時にユーザー登録が自動的に完了します。</p>
			<?php
			$tableData = User::getDashboardRows();
			$table = new HtmlTable($tableData);
			$table->requireTFoot(FALSE);
			echo $table->getHtml();
			?>
		</div>
		<div class="column">
			<h3>新着お問い合わせ</h3>
			<p class="description">アプリからの最近のお問い合せを表示します。</p>
			<?php
			$tableData = Inquiry::getDashboardRows();
			$table = new HtmlTable($tableData);
			$table->requireTFoot(FALSE);
			echo $table->getHtml();
			?>
		</div>
	</div>
</div>

<div class="ui grid">
	<div class="column row">
		<div class="column">
			<h3>アプリケーション仕様</h3>
			<p class="description">システム全体のアーキテクチャについて</p>
			<img src="/imgs/admin/application-diagram.png" width="">
		</div>
	</div>
</div>

<div class="ui grid">
	<div class="column row">
		<div class="column">
			<h3>関連情報</h3>
			<p class="description">アプリ運用に関わる各種情報について</p>
		</div>
	</div>
</div>

<script>
$(function() {
	$("a.user-modal").on("click", function() {
		var url = $(this).attr("href");
		$("#modal").load(url, function() {
			$("#modal .ui.modal").modal("show");
		});
		return false;
	});
	$("a.detail").on("click", function() {
		var url = $(this).attr("href");
		$("#modal").load(url, function() {
			$("#modal .ui.modal").modal("show");
		});
		return false;
	});
});
</script>

<?php includeFooter(); ?>