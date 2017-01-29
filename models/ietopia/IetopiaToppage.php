<?php
require_once APP_ROOT . "/libs/HttpClient.php";
require_once APP_ROOT . "/libs/WhiteSpace.php";

class IetopiaToppage {
	public $baseUrl;
	function __construct() {
		$this->baseUrl = IETOPIA_URL;
	}
	public $didLoaded = false;
	function loadUrl() {
		if ( $this->didLoaded ) return;
		$html = HttpClient::request( $this->baseUrl );
		phpQuery::newDocument( $html );
		$this->didLoaded = true;
	}
}

class IetopiaNewArrival extends IetopiaToppage {
	
	function getItems() {
		$selector = "#new_items_list_rent > table > tr a";
		$this->loadUrl();
		$items = [];
		foreach (pq($selector) as $a) {
			$url = IETOPIA_URL . pq($a)->attr("href");
			$items[] = new IetopiaNewArrivalItem($url);
		}
		return $items;
	}
}
class IetopiaNewArrivalItem {
	public $baseUrl;
	function __construct($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
	function getRoomID() {
		$url = $this->baseUrl;
		return preg_replace("/.+\//", "", $url);
	}
}
class IetopiaPickUp extends IetopiaToppage {
	
	function getItems() {
		$selector = "#article > div.recomend_items.section .item";
		$this->loadUrl();
		$items = [];
		foreach (pq($selector) as $item) {
			
			$roomUrls = [];
			$roomATags = pq($item)->find(".room_list tr a");
			foreach ($roomATags as $aTag) {
				$roomUrls[] = IETOPIA_URL . pq($aTag)->attr("href");
			}
			if ( ! $roomUrls ) continue;
			
			$buildingUrl = IETOPIA_URL . pq($item)->find(".head a")->attr("href");
			$items[] = new IetopiaPickUpItem($buildingUrl, $roomUrls);
		}
		return $items;
	}
}
class IetopiaPickUpItem {
	
	public $buildingUrl;
	public $roomUrls;
	function __construct($buildingUrl, $roomUrls) {
		if ( ! $buildingUrl || ! $roomUrls ) {
			throw new ErrorException(
				"必須項目が不足しています  ".
				Json::encode(compact("buildingUrl","roomUrls")));
		}
		$this->buildingUrl = $buildingUrl;
		$this->roomUrls = $roomUrls;
	}
	function getRoomIds() {
		$ids = [];
		foreach ($this->roomUrls as $url) {
			$ids[] = preg_replace("/.+\//", "", $url);
		}
		return $ids;
	}
	function getBuildingID() {
		$url = $this->buildingUrl;
		if ( preg_match("/.+\/([0-9]+)/", $url, $matches) ) {
			return $matches[1];
		}
		throw new ErrorException("建物IDが見つけられませんでした  url: {$url}");
	}
}
