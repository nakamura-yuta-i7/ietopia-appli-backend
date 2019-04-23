<?php
class IetopiaAdminConsole {

	protected $_cookie_file_path = "";
	function __construct() {
		$this->_cookie_file_path = tempnam(sys_get_temp_dir(),'cookie_');
		$this->_login();
	}
	protected function _login() {
		$URL = "http://www.ietopia.jp/admins/login";
		$params = array(
			'_method' => 'POST',
			"data" => [
				"Admin" => [
					"mail"     => IETOPIA_ADMIN_CONSOLE_LOGIN_EMAIL,
					"password" => IETOPIA_ADMIN_CONSOLE_LOGIN_PASSWORD,
				],
			],
		);
		$this->_request($URL, $params);
	}
	protected function _request($URL, $params) {
		$curl=curl_init($URL);

		curl_setopt($curl,CURLOPT_POST, TRUE);
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($params));

		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);  // オレオレ証明書対策
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);  //

		curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡

		curl_setopt($curl, CURLOPT_HEADER, true);   // ヘッダーも出力する

		$cookie = $this->_cookie_file_path;
		curl_setopt($curl,CURLOPT_COOKIEJAR,$cookie);  # クッキーを書き込むファイルを指定
		curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie); # クッキーを読み込むファイルを指定

		//curl_setopt($curl,CURLOPT_REFERER,        "REFERER");
		//curl_setopt($curl,CURLOPT_USERAGENT,      "USER_AGENT");
		Log::info(compact("URL","params"));
		$response= curl_exec($curl);

		// ステータスコード取得
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		// header & body 取得
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); // ヘッダサイズ取得
		$header = substr($response, 0, $header_size); // headerだけ切り出し
		$body = substr($response, $header_size); // bodyだけ切り出し

		curl_close($curl);

		return compact("body","header","code","cookie");
	}
	function getTotalBuilding() {
		$URL = "http://www.ietopia.jp/admin_rent_mansions/";
		$result = $this->_request($URL, $params=[]);
		phpQuery::newDocument($result["body"]);
		if ( !preg_match("/件([0-9]+)件中/", pq("#search_result p.num_limit")->text(), $matches ) ) {
			throw new ErrorException("建物数の合計値を取得できませんでした。");
		}
		return intval($matches[1]);
	}
	function getBuildings() {
		$buildings = [];
		$totalBuilding = $this->getTotalBuilding();
		
		$max = 9999;
		# if ( IS_DEV ) $max = 100;
		
		$page = 1;
		$limit = 50; # これ以上のLIMITだと相手サーバーがエラーとなってしまう
		while ( true ) {
			$URL = "http://www.ietopia.jp/admin_rent_mansions/index/limit:{$limit}/page:{$page}";
			$result = $this->_request($URL, $params=[]);
				
			phpQuery::newDocument( $result["body"] );
			$builds = [];
			foreach ( pq("table#search_result_table tbody tr") as $tr ) {
				$td   = pq($tr)->find("td:nth-child(3)");
				$html = $td->html();
				$name = explode("<br>", $html)[1];
				$name = preg_replace("/\t|\r\n|\r|\n/", " ", $name);
				$name = explode(",", $name)[0];
				
				$td = pq($tr)->find("td:nth-child(6)");
				$detailBtn = pq($td)->find("a:nth-child(0)");
				$href = $detailBtn->attr("href");
				preg_match('/([0-9]+)/', $href, $matches);
				$id = $matches[1];
				
				$builds[] = [
					"name" => WhiteSpace::clean($name),
					"id"   => $id,
				];
			}
			if ( !$builds ) {
				break;
			}
			foreach ($builds as $build) $buildings[] = $build;
			if ( count($buildings) >= $totalBuilding ) {
				break;
			}
			if ( count($buildings) >= $max ) {
				break;
			}
			$page += 1;
		}
		return $buildings;
	}
}
