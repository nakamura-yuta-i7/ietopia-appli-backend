<!DOCTYPE html>
<html lang="ja">
<?php include __DIR__ . "/_head.php"; ?>
<body>

<div id="modal"></div>

<?php include __DIR__ . "/_tel-modal.php"; ?>

<header>
	<div class="ui small menu">
		<a class="item" <?php echo adminHref(); ?>>
			<h1>
				<img src="/imgs/common/header/logo.png" width="103">
				アプリ
			</h1>
		</a>
		<div class="ui dropdown item">
			ユーザー <i class="dropdown icon"></i>
			<div class="menu">
				<a class="item" <?php echo adminHref("user/list"); ?>>一覧</a>
				<a class="item" <?php echo adminHref("user/favorite/list"); ?>>お気に入り登録</a>
				<a class="item" <?php echo adminHref("user/search_condition/list"); ?>>検索条件</a>
			</div>
		</div>
		<div class="ui dropdown item">
			お問い合わせ <i class="dropdown icon"></i>
			<div class="menu">
				<a class="item" <?php echo adminHref("inquiry/mail"); ?>>メール</a>
				<a class="item" <?php echo adminHref("inquiry/tel"); ?>>電話</a>
			</div>
		</div>
		<div class="ui dropdown item">
			物件 <i class="dropdown icon"></i>
			<div class="menu">
				<a class="item" <?php echo adminHref("room/list"); ?>>一覧</a>
				<a class="item" <?php echo adminHref("room/list?favorite=1"); ?>>お気に入り登録物件</a>
			</div>
		</div>
		<div class="ui dropdown item">
			お知らせ <i class="dropdown icon"></i>
			<div class="menu">
				<a class="item" <?php echo adminHref("news/list"); ?>>一覧</a>
				<a class="item" <?php echo adminHref("news/create"); ?>>新規作成</a>
			</div>
		</div>
		<a class="item" <?php echo adminHref("push"); ?>>
			プッシュ<br>通知
		</a>
		
		<div class="right menu">
			<div class="item">
				<a class="ui basic button" <?php echo adminHref("logout"); ?>>ログアウト</a>
			</div>
		</div>
	</div>
</header>

<main>
	
	<div class="title">
		<h2><?php echo $title; ?></h2>
		<p class="description"><?php echo $description; ?></p>
	</div>
	
	<div class="contents">