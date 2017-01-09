<?php
class WhiteSpace {
	# 前後ホワイトスペースや中間空白・改行を良しなに除去して返す
	static function clean($text) {
		$text = static::trimLineBreak($text);
		return trim( static::reduce($text) );
	}
	# 改行を取り除く
	static function trimLineBreak($text) {
		return preg_replace("/¥n|¥r|¥r¥n/", "", $text);
	}
	# 複数のホワイトスペースを詰めて一つにして返す
	static function reduce($text) {
		$text = static::zenkakuSpaceToHankaku($text);
		return preg_replace("/¥n|¥r|¥r¥n| +|　+/", " ", $text);
	}
	# 全角スペースを半角にして返す
	static function zenkakuSpaceToHankaku($str) {
		return mb_convert_kana( $str, "&quot;s&quot;" );
	}
}