<!DOCTYPE html>
<html lang="ja">
<?php include __DIR__ . "/_head.php"; ?>
<body>

<form class="ui form" action="./auth" method="post">
	
	<div class="cell middle center">
		<img src="/imgs/common/header/logo.png" width="103">
		アプリ管理者画面
	</div>
	
	<div class="field">
		<label>パスワード</label>
		<input type="password" name="password" class="" placeholder="Password">
	</div>
	
	<input type="submit" value="ログイン" class="ui button orange">
	
</form>

<style>
form {
	position: fixed;
	width: 250px;
	text-align: center;
	z-index: 9999;
	top: 50%;
	left: 50%;
	transform: translateY(-50%) translateX(-50%);
	
	padding-bottom: 50px;
}
form .cell { width: 350px; font-size: 16px; font-weight: 900; padding-bottom: 30px; }
form .cell img { margin-right: 10px; }
</style>

</body>
</html>