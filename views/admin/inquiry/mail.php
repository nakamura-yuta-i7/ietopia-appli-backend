<?php includeHeader([
	"title" => "お問い合わせ履歴 > メール",
	"description" => "お問い合わせフォームから送信されたメールの一覧です。",
]); ?>

<?php
$inquiryType = "mail";
include __DIR__ . "/_template.php";
?>