<?php includeHeader([
	"title" => "プッシュ通知",
	"description" => "iOS & Android にメッセージを通知することができます。",
]); ?>

<style>
.left { float: left; }
img.left { margin-right: 15px; }
p { zoom:1; overflow: hidden; }
p, ol { margin-bottom: 5em; }
.contents {
	padding-left: 100px;
}
</style>

<h3>目次</h3>
<ol>
	<li><a href="#a1">ニフティクラウドmobile backend にログインします。</a></li>
	<li><a href="#a2">メニューから「プッシュ通知」をクリックします。</a></li>
	<li><a href="#a3">「+新しいプッシュ通知」をクリックします。</a></li>
	<li><a href="#a4">「タイトル」「メッセージ」を入力します。</a></li>
	<li><a href="#a5">「配信日時」「配信対象」を選択します。</a></li>
	<li><a href="#a6">「プッシュ通知を作成する」をクリックします。</a></li>
</ol>

<h3><a name="a1"></a>１．ニフティクラウドmobile backend にログインします。</h3>
<p>
<img src="/imgs/admin/push/1-1.png" width="214" class="left">
ニフティクラウドmobile backend<br>
ログイン画面URL<br>
<a target="_blank" href="https://console.mb.cloud.nifty.com/">https://console.mb.cloud.nifty.com/</a>
</p>

<h3><a name="a2"></a>２．メニューから「プッシュ通知」をクリックします。</h3>
<p>
<img src="/imgs/admin/push/2-1.png" width="422">
</p>

<h3><a name="a3"></a>３．「+新しいプッシュ通知」をクリックします。</h3>
<p>
<img src="/imgs/admin/push/3-1.png" width="419">
</p>

<h3><a name="a4"></a>４．「タイトル」「メッセージ」を入力します。</h3>
<p>
<img src="/imgs/admin/push/4-1.png" width="412">
</p>

<h3><a name="a5"></a>５．「配信日時」「配信対象」を選択します。</h3>
<p>
「配信日時」はメニューから「日時」を選択することで廃止日時を予約することができます。<br>
<br>
<img src="/imgs/admin/push/5-1.png" width="369" class="left">
</p>

<p>
「アクション」「ダイアログ」については指定しません。<br>
iOSアプリのアイコンに赤いバッチで件数表示したい場合には「数値を指定する」を選択し数値を入力してください。<br>
<br>
<img src="/imgs/admin/push/5-2.png" width="593">

</p>


<h3><a name="a6"></a>６．「プッシュ通知を作成する」をクリックします。</h3>
<p>
<img src="/imgs/admin/push/6-1.png" width="194">
</p>

<?php includeFooter(); ?>