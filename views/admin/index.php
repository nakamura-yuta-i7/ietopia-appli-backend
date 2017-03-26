<?php
includeHeader([
	"title" => "ダッシュボード",
]); ?>

<div class="ui grid">
	<div class="two column row">
		<div class="column">
			<h3>新着ユーザー</h3>
			<p class="description">アプリ初回起動時にユーザー登録が自動的に完了します。</p>
			<?php
			$user = new User();
			$users = $user->findAll(["limit"=>3]);
			echo "<pre>"; var_export($users); echo "</pre>";
			?>
		</div>
		<div class="column">
			<h3>新着お問い合わせ</h3>
			<p class="description">アプリからの最近のお問い合せを表示します。</p>
			<?php
			$inquiry = new Inquiry();
			$inquiries = $inquiry->findAll(["limit"=>2]);
			echo "<pre>"; var_export($inquiries); echo "</pre>";
			?>
		</div>
	</div>
</div>

<div class="ui grid">
	<div class="two column row">
		<div class="column">
			<h3>アプリケーション仕様</h3>
			<p class="description">システム全体のアーキテクチャについて</p>
		</div>
		<div class="column">
			<h3>関連情報</h3>
			<p class="description">アプリ運用に関わる各種情報について</p>
		</div>
	</div>
</div>

<?php includeFooter(); ?>