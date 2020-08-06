<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 검색 파라미터
class search_seg {
	private $seg, $n;
	
	public function __construct($n=4) {
		$CI =& get_instance();
        $this->n = $n;
		$this->seg = array_map(array('search_seg','escape'), $CI->uri->uri_to_assoc($n));
	}

	private function escape($v) {
		return mysql_real_escape_string($v);
	}

	public function get_seg($seg) {
		if (isset($this->seg[$seg]))
			return $this->seg[$seg];
		return FALSE;
	}
    
    public function get_order($seg) {
        $odr = array_search($seg, array_keys($this->seg));
        if ($odr !== FALSE)
            return ($odr + 1) * 2 + ($this->n - 1);
        else
            return FALSE;
    }
	
	public function get_qstr() {
		if ($this->seg) {
			$CI =& get_instance();
			return '/'.$CI->uri->assoc_to_uri($this->seg);
		}
	}
}

function qstr_rep($qstr, $key, $val='') {
    if (!$key)
        return FALSE;

	$keys = explode(',', $key);
	foreach ($keys as $row)
		$srh[] = '(/'.$row.'/[.a-zA-Z0-9_-]+)';
	
	if ($val && !isset($keys[1])) {
		$val = '/'.$key.'/'.$val;
		if (strpos($qstr, '/'.$key.'/') === FALSE)
			return $qstr .= $val;
	}

	return preg_replace($srh, $val, $qstr);
}

// 검색어 디코드
function search_decode($stx) {
	$stx = preg_replace('/\.([^\.]+)/', '%\\1', $stx);
	
	if(strtolower(preg_replace('/-/','',ENCODING)) == 'euckr')
		return mb_convert_encoding(urldecode($stx), 'EUC-KR', 'UTF-8');	// EUC-KR
	else
		return urldecode($stx);	// UTF-8
}

// 필드 정렬
function sort_link($sst, $sod='asc') {
	$seg     = new search_seg(2);
	$sst_seg = $seg->get_seg('sst');
	$qstr    = $seg->get_qstr();

	$CI =& get_instance();
	if ($sst_seg == $sst) {
		$sod = $seg->get_seg('sod');
		$seg_qstr = qstr_rep($qstr, 'sod', ($sod == 'asc') ? 'desc' : 'asc');
	}
	else {
		$is_list = $CI->uri->segment(3) ? '' : 'list';

		$seg_qstr = qstr_rep($qstr, 'sst,sod').$is_list.'/sst/'.$sst.'/sod/'.$sod;
	}
	
    return RT_PATH.$CI->uri->slash_segment(1, 'leading').$seg_qstr;
}
?>
