<?php
/**
 * @param string $body 処理対象のテキスト
 * @param string|null $link_title リンクテキスト
 * @return string
 */
function url2link($body, $link_title = null)
{
	$pattern = '/(href=")?https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
	
	$body = preg_replace_callback($pattern, function($matches) use ($link_title) {
		// 既にリンクの場合や Markdown style link の場合はそのまま
		if (isset($matches[1])) return $matches[0];
		$link_title = $link_title ?: $matches[0];
		$url = $matches[0];
		return "<a onclick='window.open(\"{$url}\", \"_blank\");'>$link_title</a>";
	}, $body);
	
	return $body;
}