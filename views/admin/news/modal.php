<?php
$id = Sqlite3::escapeString($_GET["id"]);

$model = new News();
$content = $model->findOne(["where"=>" id = $id "]);

$content["body"] = News::convertBody($content["body"]);

$tableData = $content;
?>
<div class="ui modal small">
	<div class="header">
		<?php echo $content["title"]; ?>
	</div>
	<div class="content">
		<div class="description">
		<?php
		$table = new HtmlTable($tableData);
		$table->onSingleRowMode();
		echo $table->getHtml();
		?>
		</div>
	</div>
</div>