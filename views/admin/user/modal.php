<?php
$user = User::findByUUID($uuid=$_GET["uuid"]);
$tableData = $user;
?>
<div class="ui modal small">
	<i class="close icon"></i>
	<div class="header">
		<?php echo $user["uuid"]; ?>
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