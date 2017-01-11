<?php
require_once APP_ROOT . "/libs/HttpClient.php";
require_once APP_ROOT . "/libs/WhiteSpace.php";

class IetopiaRentSearchPageToshimaKu {
	public $pageLimit = 200;

	function __construct() {
		$baseUrl = IETOPIA_URL . "/rent_search/area/" . urlencode("東京都-豊島区");
		$this->baseUrl = $baseUrl;
		$this->loadUrl( $this->baseUrl . "/limit:" . $this->pageLimit );
	}
	# 検索結果を読み込み
	function loadUrl($url) {
		$html = HttpClient::request( $url );
		phpQuery::newDocument( $html );
	}
	# 建物総数を取得
	function totalBuilding() {
		$searchResultNumText = pq("#search_result_num > p")->text();
		if ( preg_match("/(.+)棟/", $searchResultNumText, $matches) ) {
			$totalCount = trim($matches[1]);
			return $totalCount;
		}
		throw new ErrorException("検索結果が0件でした");
	}
	# 建物一覧を取得
	function getBuildingList() {
		$total = $this->totalBuilding();
		$list = [];
		while (true) {
			$buildingItems = pq(".rent_item");
			foreach ($buildingItems as $item) {
				$list[] = new IetopiaSearchResultBuilding($item);
			}
			# TODO: ページング処理を必ず追加する事
			break;
		}
		return $list;
	}
}
class IetopiaSearchResultBuilding {
	public $pqObj;
	public $baseUrl = IETOPIA_URL;
	function __construct($pqObj) {
		$this->pqObj = $pqObj;
	}
	# 建物名を返す
	function getName() {
		$name = pq($this->pqObj)->find("h2")->text();
		return WhiteSpace::clean($name);
	}
	# 建物IDを返す
	function getId() {
		$href = pq($this->pqObj)->find("h2 > a")->attr("href");
		if ( $id = preg_replace('/[^0-9]/', '', $href) ) {
			return $id;
		}
		throw new ErrorException("IDが見つかりませんでした  href: ". $href );
	}
	# 建物詳細URLを返す
	function getDetailUrl() {
		$href = pq($this->pqObj)->find("h2 > a")->attr("href");
		return $this->baseUrl . $href;
	}
	# 建物写真URLリストを返す
	function getGaikanImageUrls() {
		$url = $this->getDetailUrl();
		$html = HttpClient::request( $url );
		phpQuery::newDocument( $html );
		
		$list = [];
		foreach ( pq("#thumb_list ul li a img") as $thumImage ) {
			$src = pq($thumImage)->attr("src");
				
			$gaikanImageId = $this->getId();
				
			$thumId = "";
			if (preg_match("/.+64x48_(.+).jpg/", $src, $matches)) {
				$thumId = $matches[1];
			}
				
			$thumSmallUrl = $this->baseUrl . $src;
			$thumBigUrl = preg_replace("/64x48/", "480x360", $thumSmallUrl);
				
			$list[] = compact("gaikanImageId","thumId","thumSmallUrl","thumBigUrl");
		}
		return $list;
	}
	# 建物内の部屋URLリストを返す
	function getRoomUrls() {
		$roomTrs = pq($this->pqObj)->find(".item_room_table tbody tr");
		$urls = [];
		foreach ( $roomTrs as $roomTr ) {
			$url = $this->baseUrl . pq($roomTr)->find("a")->attr("href");
			$urls[] = $url;
		}
		return $urls;
	}
	# 部屋インスタンスを返す
	function getRooms() {
		$urls = $this->getRoomUrls();
		$list = [];
		foreach ($urls as $url) {
			$list[] = new IetopiaSearchResultRoom($url);
		}
		return $list;
	}
}
class IetopiaSearchResultRoom {

	# フィールド一覧
	# see: https://docs.google.com/spreadsheets/d/1ECoCeNmhP_qIyaiJR-XMILhSNAywsXhqgY83eylqFYU/edit#gid=0
	const ID                       = "id";
	
	const YATIN_INT                = "yatin_int";
	const MENSEKI_INT              = "menseki_int";
	const NEW_ARRIVAL_FLAG         = "new_arrival_flag";
	const CREATED_AT               = "created_at";
	
	const NAME                     = "name";
	const NAME_FULL                = "name_full";
	const CATCHCOPY                = "catchcopy";
	const SHOZAITI                 = "shozaiti";
	const KOTU                     = "kotu";
	const TINRYO                   = "tinryo";
	const KANRIHI_KYOEKIHI         = "kanrihi_kyoekihi";
	const SIKIKIN                  = "sikikin";
	const HOSHOKIN                 = "hoshokin";
	const REIKIN                   = "reikin";
	const SHOKYAKU_SIKIHIKI        = "shokyaku_sikihiki";
	const MADORI                   = "madori";
	const SENYUMENSEKI             = "senyumenseki";
	const BALCONY                  = "balcony";
	const BUKKEN_SHUBETU           = "bukken_shubetu";
	const TIKUNENSU                = "tikunensu";
	const HOUI                     = "houi";
	const KOZO                     = "kozo";
	const SHOZOIKAI                = "shozoikai";
	const SOTOSU                   = "sotosu";
	const COMMENT                  = "comment";
	const KAKAKU                   = "kakaku";
	const SIKIKIN_HOSHOUKIN        = "sikikin_hoshoukin";
	const REIKIN_SHOKYAKU_SIKIHIKI = "reikin_shokyaku_sikihiki";
	const KOUSHINRYO               = "koushinryo";
	const SONOTA_HIYO              = "sonota_hiyo";
	const MADORI_UTIWAKE           = "madori_utiwake";
	const BALCONY_MENSEKI          = "balcony_menseki";
	const CHUSHAJO                 = "chushajo";
	const HOKENRYO                 = "hokenryo";
	const KEIYAKUKIKAN             = "keiyakukikan";
	const HIKIWATASHI              = "hikiwatashi";
	const GENJO                    = "genjo";
	const KANRI                    = "kanri";
	const SETUBI_JOKEN             = "setubi_joken";
	const BIKOU                    = "bikou";
	const SHUHENSHISETU            = "shuhenshisetu";
	const TORIHIKITAIYO            = "torihikitaiyo";

	public $baseUrl;
	public $didLoaded = false;
	function __construct($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
	function ID() {
		return preg_replace("/.+\//", "", $this->baseUrl);
	}
	function detailUrl() {
		return $this->baseUrl;
	}
	function getContent() {
		$this->_parseLoadContent();
		return $this->content;
	}
	function getNaikanImageUrls() {
		$this->load();
		$list = [];
		foreach ( pq("#thumb_list ul li a img") as $img ) {
			$src = pq($img)->attr("src");
			$url = IETOPIA_URL . $src;
			
			$thumId = "";
			if (preg_match("/.+64x48_(.+).jpg/", $src, $matches)) {
				$thumId = $matches[1];
			}
			
			$thumSmallUrl = IETOPIA_URL . $src;
			$thumBigUrl = preg_replace("/64x48/", "480x360", $thumSmallUrl);
			
			$list[] = compact("gaikanImageId","thumId","thumSmallUrl","thumBigUrl");
		}
		return $list;
	}
	function reload() {
		$html = HttpClient::request( $this->baseUrl );
		phpQuery::newDocument( $html );
		$this->didLoaded = true;
	}
	protected function load() {
		if ( ! $this->didLoaded ) {
			$this->reload();
		}
	}
	protected $content = [];
	protected function _parseLoadContent() {
		$this->load();
		$html = pq("#contents #article");
		$basic = pq("#item_info .item_table");
		$detail = pq("#item_detail .item_table");
		$basicVal = function($name) use ($basic) {
			return trim( $basic->find("th:contains('{$name}')")->next("td")->text() );
		};
		$detailVal = function($name) use ($detail) {
			return trim( $detail->find("th:contains('{$name}')")->next("td")->text() );
		};
		# MEMO: phpQueryの:containsセレクターにはバグがあり、tr:eq() th:eq() で暫定取得している
		#       原因不明な為、一旦はこのような方法で回避していることに注意！
		
		# 基本情報
		$this->content[static::ID] = $this->ID();
		$this->content[static::NAME] = WhiteSpace::clean( $html->find("#item_name")->text() );
		$this->content[static::NAME_FULL] = $basicVal("名前");
		$this->content[static::CATCHCOPY] = $html->find("#appeal_point")->text();
		$this->content[static::SHOZAITI] = $detailVal("所在地");
		$this->content[static::KOTU] = $detailVal("交通");
		$this->content[static::TINRYO] = $basicVal("賃料");
		$this->content[static::KANRIHI_KYOEKIHI] = $basicVal("管理費");
		$this->content[static::SIKIKIN] = $basicVal("敷金");
		$this->content[static::HOSHOKIN] = $basicVal("保証金");
		$this->content[static::REIKIN] = $basicVal("礼金");
		$this->content[static::SHOKYAKU_SIKIHIKI] = $basicVal("償却・敷引");
		$this->content[static::MADORI] = $basicVal("間取り");
		$this->content[static::SENYUMENSEKI] = $basicVal("専有面積");
		$this->content[static::BALCONY] = $basicVal("バルコニー");
		$this->content[static::BUKKEN_SHUBETU] = $basicVal("物件種別");
		$this->content[static::TIKUNENSU] = $basicVal("築年月");
		$this->content[static::HOUI] = $basicVal("方位");
		$this->content[static::KOZO] = trim( $basic->find("tr:eq(8) th:eq(1)")->next("td")->text() );
		$this->content[static::SHOZOIKAI] = WhiteSpace::clean( $basicVal("所在階") );
		$this->content[static::SOTOSU] = $basicVal("総戸数");
		$this->content[static::COMMENT] = $html->find("#admin_comment p")->text();
		
		# 詳細情報
		$kakaku = $detail->find("tr:eq(0) th:eq(0)")->next("td")->text();
		$this->content[static::KAKAKU] = trim( $kakaku );
		$this->content[static::SIKIKIN_HOSHOUKIN] = $detailVal("敷金/保証金");
		$this->content[static::REIKIN_SHOKYAKU_SIKIHIKI] = $detailVal("礼金/償却・敷引");
		$this->content[static::KOUSHINRYO] = $detailVal("更新料");
		$this->content[static::SONOTA_HIYO] = $detailVal("その他費用");
		$this->content[static::MADORI_UTIWAKE] = trim( $detail->find("tr:eq(6) th:eq(1)")->next("td")->text() );
		$this->content[static::BALCONY_MENSEKI] = $detailVal("バルコニー面積");
		$this->content[static::CHUSHAJO] = $detailVal("駐車場");
		$this->content[static::HOKENRYO] = $detailVal("保険料");
		$this->content[static::KEIYAKUKIKAN] = $detailVal("契約期間");
		$this->content[static::HIKIWATASHI] = $detailVal("引渡");
		$this->content[static::GENJO] = $detailVal("現況");
		$this->content[static::KANRI] = trim( $detail->find("tr:eq(13) th:eq(0)")->next("td")->text() );
		$this->content[static::SETUBI_JOKEN] = $detailVal("設備・条件");
		$this->content[static::BIKOU] = $detailVal("備考");
		$this->content[static::SHUHENSHISETU] = $detailVal("周辺");
		$this->content[static::TORIHIKITAIYO] = $detailVal("取引");
		
		# 拡張項目
		$this->content[static::YATIN_INT] = $this->kakakuToInteger($kakaku);
		$this->content[static::MENSEKI_INT] = $this->mensekiToInterger($basicVal("専有面積"));
		
		#foreach ( $this->content as $key => $val ) {
		#	$this->content[$key] = static::trim($val);
		#}
	}
	
	protected function kakakuToInteger($kakaku) {
		return str_replace("万円", "", $kakaku) * 10000;
	}
	protected function mensekiToInterger($menseki) {
		return str_replace("m²", "", $menseki);
	}
}