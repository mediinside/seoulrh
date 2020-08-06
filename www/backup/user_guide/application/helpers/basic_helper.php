<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 경고메세지를 경고창으로
function alert($msg='', $url='') {
	$CI =& get_instance();

	if (!$msg) $msg = '올바른 방법으로 이용하세요.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'>alert('".$msg."');";
	if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        goto_url($url);
	exit;
}

// 스크립트 계속 진행 후 경고메세지 출력
function alert_continue($msg) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> alert('".$msg."');</script>";
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
	exit;
}

// 해당 url로 이동
function goto_url($url) {
	$temp = parse_url($url);
	$url = substr($url, 0, 1) == '/' ? substr($url, 1, strlen($url)) : $url;
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? RT_PATH.'/'.$url : $CI->config->item('base_url').RT_PATH;
	}
	echo "<script type='text/javascript'> location.href = '".$url."'; </script>";
	exit;
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token() {
	$CI =& get_instance();

	$token = md5(uniqid(rand(), TRUE));
	$CI->session->set_userdata('ss_token', $token);

	return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token($url=FALSE) {
	$CI =& get_instance();
	// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
	if ($CI->input->post('token') && $CI->session->userdata('ss_token') == $CI->input->post('token')) {
		// 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
		$CI->session->unset_userdata('ss_token');
	}
	else
		alert('Access Error',($url) ? $url : $CI->input->server('HTTP_REFERER'));
	
	// 잦은 토큰 에러로 인하여 토큰을 사용하지 않도록 수정
	// $CI->session->unset_userdata('ss_token');
	// return TRUE;
}

function check_wrkey() {
	$CI =& get_instance();
	$key = $CI->session->userdata('captcha_keystring');
	if (!($key && $key == $CI->input->post('wr_key'))) {
		$CI->session->unset_userdata('captcha_keystring');
	    alert('정상적인 접근이 아닙니다.', '/');
	}
}

function del_thumb($path, $filename) {
	$dir = opendir($path);
	while($file = readdir($dir)) {
		if(preg_replace('/^(.\d*)x(.\d*)_?/', '', $file) == $filename) {
			unlink($path.'/'.$file);
		}
	}
	closedir($dir);
}

// 디렉토리 안의 파일 목록
function getFiles($path) {
	$file_arr = array();
	
	if(is_dir($path)) {
		$handle = opendir($path);
		while($filename = readdir($handle)) {
			if($filename != '.' && $filename != '..')
				$file_arr[] = $filename;
		}
		closedir($handle);
	}
	
	return $file_arr;
}

// 파일 업로더
function fileupload($file, $path) {
	global $_SERVER;
	
	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
	
	//	'%' 미디어플레이어가 인식 못함 처리
	shuffle($chars_array);
	$shuffle = implode("", $chars_array);

	// 파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상 처리
	$upfile = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $file['name']))); 
	$dest_file = $path.'/'.$upfile;
	
	if(!$error_code = move_uploaded_file($file['tmp_name'], $dest_file)) {
		return FALSE;
	}

	chmod($dest_file, 0606);
	
	return $upfile;
}

// 초를 시간으로 표현 (24시간 이상 표현)
function sectotime($sec)
{
	$minute = (int)($sec / 60);

	if($minute >= 60)
		return (int)($minute / 60) . ":" . sprintf("%02d", ($minute % 60)) . ":" . sprintf("%02d", ($sec % 60));
	else
		return $minute . ":" . sprintf("%02d", ($sec % 60));
}

// 기본값 설정
function setValue($default_value, $value)
{
	return $value ? $value : $default_value;
}

// array_fill_keys (PHP 5 >= 5.2.0) -_-
function array_false($arr, $is_key=FALSE) {
	if ($is_key)
		$arr = array_keys($arr);

	foreach ($arr as $val) {
		$row[$val] = FALSE;
	}
	return $row;
}

// 2차원 배열의 2차 키값으로 정렬
function array_sort(&$arr, $dimension) {
	if(!$dimension || !is_array($arr)) {
		return false;
	}

	$ksort_arr = $new_arr = $duplicate = array();
	foreach($arr AS $key => $val) {
		if(isset($ksort_arr[$val[$dimension]])) {
			$duplicate[$val[$dimension]][] = $val;
		}
		else {
			$ksort_arr[$val[$dimension]] = $val;
		}
	}
	@ksort($ksort_arr);
	foreach($ksort_arr AS $key => $val) {
		$new_arr[] = $val;
		if(isset($duplicate[$key])) {
			foreach($duplicate[$key] AS $dup) {
				$new_arr[] = $dup;
			}
		}
	}

	return $new_arr;
}

function form_upload($fid, $fname, $del_fname, $table='', $value='', $idx='', $thumb_size='') {
	$HTML = '<span class="vaT"><input type="file" id="'.$fid.'" name="'.$fname.'" class="ed"/></span>' ."\n";
	
	if(isset($fname) && $value) {
		$file = is_numeric($value) ? 'no='.$value : 'file='.$value;
	
		$HTML .= '<img src="/useful/thumbnail/'.$thumb_size.'/'.$table.'/'.$idx.'?'.$file.'"/>' ."\n";
		$HTML .= '<span class="vaT"><input type="hidden" name="oldFileNo[]" value="'.$del_fname.'"/><input type="checkbox" name="delFile['.$del_fname.']" value="'.$value.'"/> 삭제</span>' ."\n";
	}
	
	return $HTML;
}

function param_encode($param) {
	$return = array();
	if($param) {
		foreach($param['name'] AS $key => $name) {
			if($name) {
				$return[$name] = $param['value'][$key];
			}
		}
	}
	
	return json_encode($return);
}

function param_decode($param) {
	$return = array();
	if($param) {
		foreach(json_decode($param) AS $key => $val) {
			$return[$key] = $val;
		}
	}
	
	return $return;
}

// 에이전트로 브라우저 확인
function get_brow($agent)
{
	$agent = strtolower($agent);
	
	if(preg_match("/msie 4.[0-9]*/", $agent))             $s = "IE4.x";
	else if (preg_match("/msie 5.0[0-9]*/", $agent))      $s = "IE5";
	else if(preg_match("/msie 5.5[0-9]*/", $agent))       $s = "IE5.5";
	else if(preg_match("/msie 6.0[0-9]*/", $agent))       $s = "IE6";
	else if(preg_match("/msie 7.0[0-9]*/", $agent))       $s = "IE7";
	else if(preg_match("/msie 8.0[0-9]*/", $agent))       $s = "IE8";
	else if(preg_match("/msie 9.0[0-9]*/", $agent))       $s = "IE9";
	else if(preg_match("/msie 10.0[0-9]*/", $agent))      $s = "IE10";
	else if(preg_match("/chrome/", $agent))               $s = "Chrome";	// 크롬, 사파리, 게코 순서 바뀌면 안됨.
	else if(preg_match("/safari/", $agent))               $s = "Safari";
	else if(preg_match("/gec/", $agent))                  $s = "Gecko";
	else if(preg_match("/firefox/", $agent))              $s = "Firefox";
	else if(preg_match("/x11/", $agent))                  $s = "Netsc.";
	else if(preg_match("/opera/", $agent))                $s = "Opera";
	else if(preg_match("/bot|slurp|spider/", $agent))            $s = "Robot";
	else if(preg_match("/mozilla/", $agent))              $s = "Mozilla";
	else                                                  $s = "기타";

	return $s;
}

//에이전트로 OS 확인
function get_os($agent)
{
	$agent = strtolower($agent);

	if (preg_match("/windows 98/", $agent))               $s = "Win98";
	else if(preg_match("/windows 9x/", $agent))           $s = "WinME";
	else if(preg_match("/windows nt 5\.1/", $agent))      $s = "WinXP";
	else if(preg_match("/windows nt 6\.0/", $agent))      $s = "WinVista";
	else if(preg_match("/windows nt 6\.1/", $agent))      $s = "Win7";
	else if(preg_match("/windows ce/", $agent))           $s = "WinCE";
	else if(preg_match("/iphone|ipad/", $agent))          $s = "iOS";
	else if(preg_match("/android/", $agent))              $s = "Android";
	else if(preg_match("/mac/", $agent))                  $s = "MAC";
	else if(preg_match("/linux/", $agent))                $s = "Linux";
	else if(preg_match("/bada/", $agent))                 $s = "Bada";
	else if(preg_match("/blackberry/", $agent))           $s = "B.Berry";
	else if(preg_match("/symbian/", $agent))              $s = "Symbian";
	else if(preg_match("/bot|slurp|spider/", $agent))     $s = "Robot";
	else                                                  $s = "기타";

	return $s;
}
?>
