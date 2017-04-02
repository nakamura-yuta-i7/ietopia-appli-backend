<style>

</style>

<?php
$isEdit = ( isset($_REQUEST["id"]) && $_REQUEST["id"] );
if ( $isEdit ) {
	$id = Sqlite3::escapeString($_REQUEST["id"]);
	$model = new News();
	$content = $model->findOne(["where"=>" id = {$id} "]);
	$createdAt = $content["created_at"];
	$title = $content["title"];
	$body = $content["body"];
} else {
	# 新規の時
	$id = "";
	$createdAt = date_create()->format("Y-m-d H:i:s");
	$title = "";
	$body = "";
}
?>
<form class="ui form news-form">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<div class="field">
		<label>公開日時</label>
		<input type="text" name="created_at" placeholder="" value="<?php echo $createdAt; ?>">
	</div>
	<div class="field">
		<label>タイトル</label>
		<input type="text" name="title" placeholder="タイトル" value="<?php echo $title; ?>">
	</div>
	<div class="field">
		<label>本文</label>
		<textarea name="body"><?php echo $body; ?></textarea>
	</div>
	<button class="ui button" type="submit">Submit</button>
</form>

<script>
$(".news-form").on("submit", function() {
	if ( $(this).find("[name=created_at]").val().length == 0 ) {
		alert("公開日時は必須です");
		return false;
	}
	if ( $(this).find("[name=title]").val().length == 0 ) {
		alert("タイトルは必須です");
		return false;
	}
	if ( $(this).find("[name=body]").val().length == 0 ) {
		alert("本文は必須です");
		return false;
	}
	$.ajax({
		type: "POST",
		url: "./ajax_save",
		data: $(this).serialize(),
	}).then(function() {
		<?php if ($isEdit) : ?>
			history.back();
		<?php else : ?>
			location.href = "/admin/news/list";
		<?php endif; ?>
	});
	return false;
});
</script>
