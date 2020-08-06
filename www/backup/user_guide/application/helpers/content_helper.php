<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 컨텐츠 데이터 반환
function getContent($dbVars, $p_key=FALSE) {
	if(!is_array($dbVars)) {
		$dbVars = array($dbVars);
	}
	
	$CI =& get_instance();
	
	$return = array();
	foreach($dbVars AS $key => $row) {
		switch($row['dbv_type']) {
			case 'board' :
				$data = $CI->M_basic->get_write($row['dbv_ref_table'], $row['dbv_ref_id']);
				$data['postlink'] = $data ? $CI->M_postlink->list_result($row['dbv_ref_table'], $row['dbv_ref_id']) : FALSE;
				$board = $CI->M_basic->get_board($row['dbv_ref_table'], 'bo_subject', FALSE);
				$data['type_name'] = cut_str($board['bo_subject'], 13);
				$data['subject'] = cut_str($data['wr_subject'], 32);
				break;
			case 'product' :
				// 필요하면 코딩
				break;
			case 'banner' :
				$CI->load->model('M_banner');
				$data = $CI->M_banner->get_banner($row['dbv_ref_id']);
				break;
			default :
				return FALSE;
				break;
		}
		$return[$key] = $p_key ? array_merge($dbVars[$key], array($p_key => $data)) : $data;
	}
	
	return $return;
}

// HTML 코드 파일로 저장
function setCode($filename, $code, $old_filename='', $dir='') {
	$path = $_SERVER['DOCUMENT_ROOT'] . HTML_PATH . $dir;
	
	$file = fopen($path.'/'.$filename.'.html', 'w');
	fwrite($file, $code, strlen($code));
	fclose($file);
	
	chmod($path.'/'.$filename.'.html', 0606);
	
	if($old_filename != $filename) {
		@unlink($path.'/'.$old_filename.'.html');
	}
	
	return TRUE;
}

// HTML 코드 반환
function getCode($url, $dir='') {
	$code = '';
	$file = fopen($_SERVER['DOCUMENT_ROOT'].HTML_PATH.$dir.'/'.$url.'.html', 'r');
	while(($line = fgets($file, 4096)) !== false) {
		$code .= $line;
	}
	fclose($file);
	return $code;
}

// HTML 코드 파일 삭제
function delCode($url, $dir='') {
	return unlink($_SERVER['DOCUMENT_ROOT'].HTML_PATH.$dir.'/'.$url.'.html');
}

// 파일명 변경
function chgFileName($old, $new, $dir='') {
	if(!$old || !$new) {
		return FALSE;
	}
	
	if($old !== $new) {
		rename($_SERVER['DOCUMENT_ROOT'].HTML_PATH.$dir.'/'.$new.'.html', $_SERVER['DOCUMENT_ROOT'].HTML_PATH.$dir.'/'.$old.'.html');
	}
	
	return TRUE;
}

// 변수 형식을 템플릿 형식으로 변환
function encodeVars(&$code) {
	$regular['s_script'] = '<\?((php)(?i))?( )?';
	$regular['e_script'] = '( )?\?>';
	
	$regular['s_echo'] = '( )?(echo|=)( )?(\(|")?( )?';
	$regular['e_echo'] = '( )?("?\)?)?( )?;?';
	
	$regular['s_view'] = '( )?\$this\->load\->view\(( )?';
	$regular['e_view'] = '( )?\);';
	
	$var_name = '\$(([가-힣a-zA-Z0-9-_\[\]\']|(\[")|("\]))*)';
	
	$code = preg_replace('/'.$regular['s_script'].$regular['s_echo']. $var_name .$regular['e_echo'].$regular['e_script'].'/', '{{$9}}', $code);
	$code = preg_replace('/'.$regular['s_script'].$regular['s_view']. $var_name .$regular['e_view'].$regular['e_script'].'/', '{{[$6]}}', $code);
	
	return $code;
}

// 변수 형식을 PHP 형식으로 변환
function decodeVars(&$code) {
	$var_name = '(([가-힣a-zA-Z0-9_]+)+([a-zA-Z0-9-_\[\]\'"]*))';
	
	$regular['echo'] = '{{'. $var_name .'}}';
	$regular['view'] = '{{\['. $var_name .'\]}}';
	
	$code = preg_replace('/'. $regular['echo'] .'/', '<?=$$1?>', $code);
	$code = preg_replace('/'. $regular['view'] .'/', '<? $this->load->view($$1); ?>', $code);
	
	return $code;
}
?>
