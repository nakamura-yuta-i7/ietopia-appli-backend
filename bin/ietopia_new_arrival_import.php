<?php
require_once __DIR__ . "/../config/bootstrap.php";
# require_once APP_ROOT . "/models/batch/IetopiaImportBatch.php";


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
		return $item;
	}
}
class IetopiaNewArrivalItem {
	public $baseUrl;
	function __construct($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
}
class IetopiaPickUp extends IetopiaToppage {
	
	function getItems() {
		$selector = "#article > div.recomend_items.section .item";
		$this->loadUrl();
		$items = [];
		foreach (pq($selector) as $item) {
			$buildingUrl = IETOPIA_URL . pq($item)->find(".head a")->attr("href");
			$roomUrls = [];
			$items[] = new IetopiaPickUpItem($buildingUrl, $roomUrls);
		}
		return $item;
	}
}
class IetopiaPickUpItem {
	public $baseUrl;
	function __construct($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
}


require_once APP_ROOT . "/models/batch/BatchAbstract.php";
class IetopiaNewArrivalImportBatch extends BatchAbstract {
	function process() {
		
		$webpage = new IetopiaPickUp();
		$webpage->getItems();
		
		return;
		
		$webpage = new IetopiaNewArrival();
		$webpage->getItems();
	}
}

try {
	$batch = new IetopiaNewArrivalImportBatch();
	$batch->execute();

} catch (Exception $e) {
	Log::fatal($e);
}
