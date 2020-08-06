<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 컨텐츠 데이터 반환
function getContent($dbVars, $get_info=FALSE) {
	if(!is_array($dbVars)) {
		$dbVars = array($dbVars);
	}
	
	$CI =& get_instance();
	
	$return = array();
	foreach($dbVars AS $key => $row) {
		$data = array('subject' => '');
		
		switch($row['dbv_type']) {
			case 'board' :
				if(!$get_info) {
					$CI->load->model('M_latest');
					$ext = json_decode($row['dbv_ext']);
					$data = $CI->M_latest->write($row['dbv_ref_table'], $ext->qty, $ext->len, $ext->size);
				}
				break;
			case 'post' :
				$data = $CI->M_basic->get_write($row['dbv_ref_table'], $row['dbv_ref_id']);
				$data['postlink'] = $data ? $CI->M_postlink->list_result($row['dbv_ref_table'], $row['dbv_ref_id']) : FALSE;
				$board = $CI->M_basic->get_board($row['dbv_ref_table'], 'bo_subject', FALSE);
				$data['subject'] = '<strong>['. cut_str($board['bo_subject'], 10) .']</strong>'. cut_str($data['wr_subject'], 30);
				break;
			case 'product' :
				if(!$get_info) {
					$CI->load->model('M_shop');
					$ext = json_decode($row['dbv_ext']);
					$data = $CI->M_shop->latest(array($row['dbv_ref_table']), $ext->qty, $ext->len, $ext->size);
				}
				break;
			case 'banner' :
				$CI->load->model('M_banner');
				$data = $CI->M_banner->get_banner($row['dbv_ref_id']);
				if($get_info) {
					$data = array('subject' => '', 'code' => $data);
				}
				break;
			default :
				return FALSE;
				break;
		}
		$return[$key] = $get_info ? array_merge($row, array('resource' => $data)) : $data;
	}
	
	return $return;
}

// HTML 코드 파일로 저장
function setCode($filename, $code, $old_filename='', $fullPath='') {
		if(!$path = chkDir($fullPath, TRUE)) {
		return FALSE;
	}
	
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
function getCode($filename, $fullPath='') {
	$code = '';
	$file_path = preg_replace('/(\/(\/)+)/', '/', $fullPath .'/'. $filename .'.html');
	
	$file = fopen($file_path, 'r');
	while(($line = fgets($file, 4096)) !== false) {
		$code .= $line;
	}
	fclose($file);
	return $code;
}

// HTML 코드 파일 삭제
function delCode($url, $fullPath='') {
	$file_path = preg_replace('/(\/(\/)+)/', '/', $fullPath.'/'.$url.'.html');
	return unlink($file_path);
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
