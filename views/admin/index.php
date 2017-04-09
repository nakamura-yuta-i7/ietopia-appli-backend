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
	<div class="two column row">
		<div class="column">
			<h3>アプリケーション構成図</h3>
			<p class="description">システム全体のアーキテクチャについて</p>
			<a class="application-diagram" href="/imgs/admin/application-diagram.png" target="_blank">
				<img src="/imgs/admin/application-diagram.png" width="100%">
			</a>
		</div>
		<div class="column">
			<h3>関連情報</h3>
			<p class="description">アプリ運用に関わる各種情報について</p>
			
			<h5>App Store
				<a href="https://itunes.apple.com/jp/app/池袋中心の賃貸-お部屋探しのことなら-家とぴあ/id1222305976?mt=8" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>
			
			<h5>Apple Developer Program
				<a href="https://developer.apple.com/programs/jp/" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>

			<h5>Google Play
				<a href="https://play.google.com/store/apps/details?id=jp.ietopia.appli&hl=ja" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>

			<h5>Google Play Developer Console
				<a href="https://play.google.com/apps/publish/" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>
			

			<h5>Nifty Mobile Backend
				<a href="http://mb.cloud.nifty.com/" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>

			<h5>Monaca
				<a href="https://ja.monaca.io/" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>

			<h5>Github
				<a href="https://github.com/nakamura-yuta-i7/ietopia-appli" target="_blank">
					<i class="linkify icon"></i></a>
			</h5>
			
		</div>
	</div>
</div>
<style>
.application-diagram {
	display: block;
	background: #efefef;
	padding: 5px;
}
.application-diagram img { display: block; }
</style>

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