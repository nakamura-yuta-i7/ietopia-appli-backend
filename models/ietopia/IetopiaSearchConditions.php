<?php
require_once APP_ROOT . "/libs/HttpClient.php";
require_once APP_ROOT . "/libs/WhiteSpace.php";

class IetopiaSearchConditions {
	
	# 路線＆駅リストを取得
	function getRosenAndStationList() {
		$route = new IetopiaSearchFormRoute();
		$list = [];
		foreach ($route->getRosenList() as $rosen) {
			foreach ($route->getStationList($rosen) as $station) {
				$list[$rosen][] = $station;
			}
		}
		return $list;
	}
	
	# 各種マスタ一覧を取得
	# 検索フォームURL読み込み
	public $didLoaded = false;
	function loadUrl($url=NULL) {
		if ($this->didLoaded) return;
		$url = $url ?: IETOPIA_URL . "/rent_search/area/" . urlencode("東京都") . "/limit:1";
		$html = HttpClient::request( $url );
		phpQuery::newDocument( $html );
		$this->didLoaded = true;
	}
	function getKodawariJokenList() {
		$selector = "#side_tag_link > div.module_body > div > ul:nth-child(14) a";
		return $this->getLinkTexts($selector);
	}
	function getEkitohoList() {
		$selector = "#side_tag_link > div.module_body > div > ul:nth-child(12) a";
		return $this->getLinkTexts($selector);
	}
	function getMensekiList() {
		$selector = "#side_tag_link > div.module_body > div > ul:nth-child(10) a";
		return $this->getLinkTexts($selector);
	}
	function getTikunensuList() {
		$selector = "#side_tag_link > div.module_body > div > ul:nth-child(8) a";
		return $this->getLinkTexts($selector);
	}
	function getMadoriList() {
		$selector = "#side_tag_link > div.module_body > div > ul:nth-child(6) a";
		$linkTexts = $this->getLinkTexts($selector);
		$list = [];
		foreach ($linkTexts as $text) {
			foreach (explode("・", $text) as $madori) $list[] = $madori;
		}
		return $list;
	}
	protected function getLinkTexts($selector) {
		$this->loadUrl();
		$links = pq($selector);
		$linkTexts = [];
		foreach ($links as $link) $linkTexts[] = preg_replace("/\(.+\)/", "", pq($link)->text());
		return $linkTexts;
	}
}
class IetopiaSearchFormRoute {
	public $baseUrl;
	function __construct() {
		$this->baseUrl = IETOPIA_URL . "/rent_search/route/";
	}
	function getRosenList() {
		$this->loadUrl();
		$list = [];
		$links = pq(".search_info_box .tag_link_list li a");
		foreach ($links as $link) {
			$list[] = pq($link)->text();
		}
		return $list;
	}
	function getStationList($rosenName) {
		$url = $this->baseUrl . urlencode($rosenName) . "/limit:1";
		$this->loadUrl($url);

		$list = [];
		$links = pq("#article > div:nth-child(3) > div > ul > li > a");
		foreach ($links as $link) {
			$text = pq($link)->text();
			$list[] = preg_replace("/\(.+\)/", "", $text);
		}
		return $list;
	}
	# 路線・駅検索フォームURL読み込み
	function loadUrl($url=NULL) {
		$url = $url ?: $this->baseUrl;
		$html = HttpClient::request( $url );
		phpQuery::newDocument( $html );
	}
}