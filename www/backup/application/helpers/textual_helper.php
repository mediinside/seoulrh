<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 한글 한글자(2byte, 유니코드 3byte)는 길이 2, 공란.영숫자.특수문자는 길이 1
function cut_str($str, $len, $suffix='…') {
    $s = substr($str, 0, $len);
    
    $cnt = 0;
    for ($i=0; $i<strlen($s); $i++) {
        if (ord($s[$i]) > 127)
            $cnt++;
    }
    
    $CI =& get_instance();
    if (strtoupper($CI->config->item('charset')) == 'UTF-8')
        $s = substr($s, 0, $len - ($cnt % 3));
    else
        $s = substr($s, 0, $len - ($cnt % 2));
        
    if (strlen($s) >= strlen($str))
        $suffix = '';
        
    return $s . $suffix;
}

function conv_content($str, $html) {
	$CI =& get_instance();
	$CI->load->library('typography'); 
    
    if ($html) {
		// 동영상 출력
        function entity_decode($text) { return htmlspecialchars_decode($text[0]); }
        $str = preg_replace_callback('#(<|&lt;)/?(object|embed|param)([^>]*|[^&gt;]*)(&gt;|>)#i', 'entity_decode', $str);
        
        // html 작성시 &nbsp;가 삽입됨 & 자동 줄바뀜
		//$str = $CI->typography->auto_typography($str); // format_characters
	}
    else {
		$str = get_text($str);
		$str = $CI->typography->nl2br_except_pre($str);
	}
	
	return autolink($str);
}

function get_text($str) {
	// &nbsp; &amp; &middot; 등의 코드를 정상으로 출력
	// "/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i" -> "&#038;\\1;"
	$str = str_replace(
		array("<", ">", "'"), 
		array("&lt;", "&gt;", "&#039;"), $str
	);

	return $str;
}

function search_font($str, $stx, $tag_open="<span class='sFont'>", $tag_close = '</span>') {
	if ($str == '')
		return FALSE;
	
	if ($stx != '') {
		// 문자앞에 \ 를 붙인다.
		$src = array('/', '|');
		$dst = array('\/', '\|');

		if (!trim($stx)) return $str;

		// 검색어 전체를 공란으로 나눈다
		$s = explode(' ', $stx);

		// '/(검색1|검색2)/i' 와 같은 패턴을 만듬
		$pattern = '';
		$bar = '';
		foreach($s as $row) {
			if (trim($row) == '')
				continue;
			$tmp_str = str_replace($src, $dst, quotemeta($row));
			$pattern .= $bar . $tmp_str . '(?![^<]*>)';
			$bar = '|';
		}

		return preg_replace('/('.$pattern.')/i', $tag_open.'\\1'.$tag_close, $str);

		// 기존
		// return preg_replace('/('.preg_quote($stx, '/').')/i', $tag_open."\\1".$tag_close, $str);
	}

	return $str;
}
?>
