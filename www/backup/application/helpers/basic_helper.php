<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function loger($msg) {
	$CI =& get_instance();
	$CI->load->helper('file');
	$member = unserialize(MEMBER);
	$file = DATA_PATH .'/logs/'. date('Y-m-d', TIME) .'.log';
	$text = read_file($file);
	$text = date('Y-m-d H:i:s', TIME) .' ['. $member['mb_id'] .'] : '. $msg ."\n". $text;
	write_file(DATA_PATH .'/logs/'. date('Y-m-d', TIME) .'.log', $text);
}

// validation 메세지를 alert 창으로 출력
function alert_valid_msg() {
	if(function_exists('validation_errors')) {
		echo validation_errors('<script type="text/javascript">alert("','");</script>');
	}
}

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

// 경고창 출력 후 레이어 다이얼로그 창 닫음
function alert_dlg_close($msg, $script='') {
	$CI =& get_instance();
	
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'>alert('".$msg."'); $script parent.CtrlWinDlg.closeWinDlg();</script>";
	exit;
}

// 스크립트 계속 진행 후 경고메세지 출력
function alert_continue($msg) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> alert('".$msg."');</script>";
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg, $is_win=TRUE) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	
	if($is_win) {
		echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
	}
	else {
		echo "<script type='text/javascript'> alert('".$msg."'); parent.location.href='$url'; parent.CtrlWinDlg.closeWinDlg(); </script>";
	}
	
	exit;
}

// 해당 url로 이동
function goto_url($url, $id='') {
	$temp = parse_url($url);
	$url = substr($url, 0, 1) == '/' ? substr($url, 1, strlen($url)) : $url;
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? RT_PATH.'/'.$url : $CI->config->item('base_url').RT_PATH;
	}
	$id = $id ? $id.'.' : '';
	echo "<script type='text/javascript'> ". $id ."location.href = '".$url."'; </script>";
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
		if(preg_replace('/^(.\d*)x(.\d*)(x[0-Z]*)?_?/', '', $file) == $filename) {
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

// 신파일 업로더 (필드명을 받아 업로드, 배열일 경우 사용 불가)
function fileupload($field, $path, $encrypt=FALSE, $allowed_types='*', $not_allowed_types = array('php', 'php3', 'php4', 'php5', 'js')) {
	$CI =& get_instance();
	$CI->load->library('upload');
		
	// 폼 파일 업로드 설정
	$config['upload_path'] =	$path['data_path'];
	$config['allowed_types'] =	'*';
	$config['max_size']	=		'10240';
	$config['encrypt_name'] =	$encrypt;
	$CI->upload->initialize($config);
	
	$filename = FALSE;
	if(isset($_FILES[$field]['name']) && $_FILES[$field]['name']) {
		if($CI->upload->do_upload($field)) {
			$upload_file = $CI->upload->data();
			$filename = $upload_file['file_name'];
			
			if($ext = $CI->upload->file_ext) {
				if(array_search($ext, $not_allowed_types) !== FALSE) {
					unlink($path['data_path'] .'/'. $filename);
					alert('업로드가 금지된 형식입니다.');
				}
			}
			
			chmod($path['data_path'] .'/'. $filename, 0606);
		}
		else {
			alert($CI->upload->display_errors());
		}
	}
	
	return $filename;
}

// 구파일 업로더 (업로드 파일 정보 배열로 받음)
function fileupload2($file, $path) {
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
function array_false($arr, $is_key=FALSE, $value=FALSE) {
	if ($is_key)
		$arr = array_keys($arr);

	foreach ($arr as $val) {
		$row[$val] = $value;
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

function form_upload($fid, $fname, $del_fname, $table='', $value='', $idx='', $thumb_size='', $link_url='') {
	$HTML = '';	
	if(isset($fname) && $value) {
		$file = is_numeric($value) ? 'no='.$value : 'file='.$value;
	
		$HTML .= '<img src="/useful/thumbnail/'.$thumb_size.'/'.$table.'/'.$idx.'?'.$file.'"/>' ."\n";
		$HTML .= '<span class="vaT"><input type="hidden" name="oldFileNo[]" value="'.$del_fname.'"/><input type="checkbox" name="delFile['.$del_fname.']" value="'.$value.'"/> 삭제</span>' ."\n";
		
		if($link_url) {
			$download_url = $link_url .'/download/'. $idx .'/'. $value;
			$HTML .= '<p><a href="'. $download_url .'">[ 다운로드 ]</a></p>' ."\n";
		}
	}
	else {
		$HTML = '<span class="vaT"><input type="file" id="'.$fid.'" name="'.$fname.'" class="ed"/></span>' ."\n";
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

function thumbnail($table, $id, $uf_count_image=FALSE, $size='160x120', $content='') {
	if(!$uf_count_image) {
		$hosts = array('youtube.com');
		
		preg_match('/(http(s)?:\/\/(www\.)?(youtube.com\/embed\/[^?]+))+/i', $content, $host);
		
		if($host) {
			if(preg_match('/'.$hosts[0].'/i', addslashes($host[0]))) {
				$url = explode('/', $host[0]);
				$url = explode('?', $url[count($url)-1]);
				$img = 'http://i2.ytimg.com/vi/'. $url[0] .'/mqdefault.jpg';
				return $img;
			}
		}
	}
	
	return "/useful/thumbnail/$size/$table/$id";
}

/* 값을 비교하여 checked 반환
 * description: $val1과 $val2를 비교하여 일치하면 $str을 html atribute 형식으로 반환
 * $val1과 $val2 둘중 하나가 배열형식이면 배열 값으로 확인
 * $ctype이 TRUE이면 데이터 형식까지 비교
 */
function checked($val1, $val2, $str='checked', $ctype=FALSE) {
	if(is_array($val1)) {
		return array_search($val2, $val1) !== FALSE ? $str .'='. $str : '';
	}
	else if(is_array($val2)) {
		return array_search($val1, $val2) !== FALSE ? $str .'='. $str : '';
	}
	else {
		if($ctype) {
			return $val1 === $val2 ? $str .'='. $str : '';
		}
		else {
			return $val1 == $val2 ? $str .'='. $str : '';
		}
	}
}

// post 형식으로 폼 전송
function post_s($url, $param) {
	$CI =& get_instance();
	$CI->load->helper('form');

	$form = form_open($url, 'method=post id=post_s name=post_s', $param) ."<script type='text/javascript'>document.post_s.submit();</script>";
	die($form);
}

// 디렉토리 유효성 확인
function chkDir($path, $create=FALSE) {
	$path = preg_replace('/(\/(\/)+)/', '/', $path);
	
	if(!is_dir($path)) {
		if(!$create) {
			return FALSE;
		}
		
		$curr_path = '/';
		$dir_arr = explode('/', $path);
		foreach($dir_arr AS $dirName) {
			$curr_path = $curr_path .'/'. $dirName;
			
			if(!is_dir($curr_path)) {
				mkdir($curr_path, 0707);
				chmod($curr_path, 0707);
			}
		}
	}
	
	return $path;
}

// 해당 디렉토리 안의 빈 디렉토리 모두 삭제
function emptyDir($path, $delete=TRUE) {
	$i = $cnt = 0;
	$rdi = new RecursiveDirectoryIterator($path);

	$list = array();
	foreach(new RecursiveIteratorIterator($rdi,
			RecursiveIteratorIterator::SELF_FIRST,
			RecursiveIteratorIterator::CATCH_GET_CHILD) AS $dir) {
		
		if($dir->isDir()) {
			if(2 == count(scandir($dir->__toString()))) {
				$list[$i++] = $dir->__toString();
			}
		}
	}
	
	foreach($list AS $dir) {
		$dir_arr = explode('/', preg_replace('/'. addcslashes($path,'/') .'/', '', $dir));
		array_shift($dir_arr);
		
		foreach($dir_arr AS $d) {
			$curr_dir = $path .'/'. implode('/', $dir_arr);
			array_pop($dir_arr);

			if($d) {
				$cnt++;
				if($delete) {
					@rmdir($curr_dir);
				}
			}
		}
	}
	
	return $cnt;
}

// 빈 배열 제거
function remove_empty($arr) {
	foreach($arr AS $key => $var) {
		if(!$var) {
			unset($arr[$key]);
		}
	}
	
	return $arr;
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
	else if(preg_match("/bot|slurp|spider/", $agent))     $s = "Robot";
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

// 인풋배열을 HTML 형식으로 반환
function getInput($type='', $name='', $value='', $attr='', $options=array()) {
	$HTML = '';
	switch($type) {
		case 'text' :
			if(is_array($value)) {
				$name = $name .'[]';
			}
			else {
				$value = array($value);
			}
			
			$HTML = '';
			foreach($value AS $val) {
				$HTML .= "<input type='text' class='ed' size='30' name='$name' $attr value='$val' />\n";
			}
			break;

		case 'textarea' :
			$value = is_array($value) ? implode("\n", $value) : $value;
			$HTML = "<textarea class='tx' name='$name' cols='60' rows='5' $attr>$value</textarea>\n";
			break;

		case 'file' :
			if($value) {
				$HTML = "$value &nbsp; <input type='checkbox' name='delFile[$name]' value='$value' /> 삭제";
			}
			else {
				$HTML = "<input type='file' class='ed' name='$name' $attr />\n";
			}
			break;

		case 'select' :
			$HTML = "<select class='ed' name='$name' $attr>\n";
			foreach($options AS $val => $str) {
				$sel = $str == $value ? "selected='selected'" : '';
				$HTML .= "<option value='$str' $sel>$str</option>\n";
			}
			$HTML .= "</select>\n";
			break;

		case 'checkbox' :
			$value = explode('|', $value);
			foreach($options AS $val => $str) {
				$chk = $value && array_search($str, $value) !== FALSE ? "checked='checked'" : '';
				$HTML .= "<input type='checkbox' name='{$name}[]' $attr value='$str' $chk /> $str 　 \n";
			}
			break;

		case 'radio' :
			foreach($options AS $val => $str) {
				$sel = $str == $value ? 'checked=checked' : '';
				$HTML .= "<input type='radio' name='$name' $attr value='$str' $sel />$str \n";
			}
			break;

		default :
			$HTML = $value ."\n";
			break;
	}

	return $HTML;
}

function autolink($str) {
	// URL 링크
	$patterns = array(
			"/&lt;/", "/&gt;/", "/&amp;/", "/&quot;/", "/&nbsp;/",
			"/([^(http:\/\/)]|\(|^)(www\.[a-zA-Z0-9\.-]+)/i",
			"/([^(href=\"?'?)|(SRC=\"?'?)]|^\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i",
			"/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i",
			"/\t_nbsp_\t/", "/\t_lt_\t/", "/\t_gt_\t/"
	);
	$replace = array(
			"\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t",
			"\\1<a href=\"http://\\2\" target='_blank'>\\2</a>",
			"\\1<a href=\"\\2\" target='_blank'>\\2</a>",
			"<a href='mailto:\\1'>\\1</a>",
			"&nbsp;", "&lt;", "&gt;"
	);
	
	return preg_replace($patterns, $replace, $str);
}

function email_selector($fld_name, $default_email='')
{
	$domain_list = array('chol.com','daum.net','dreamwiz.com','empal.com','freechal.com','gmail.com','hanafos.com','hanmail.net','hotmail.com','korea.com','kornet.net','lycos.co.kr','nate.com','naver.com','netsgo.com','paran.com','unitel.co.kr','yahoo.co.kr','yahoo.com');
	
	$CI =& get_instance();
	$CI->load->helper('form');
	
	$exp_email = explode('@', $default_email);
	$email_account = isset($exp_email[0]) ? $exp_email[0] : '';
	$email_domain = isset($exp_email[1]) ? $exp_email[1] : '';
	$domain_text = array_search($email_domain, $domain_list) !== false ? '' : $email_domain;
	$domain_list = array_merge(array('' => '선택'), array_combine($domain_list, $domain_list), array('0' => '직접입력'));
	$domain_selected = !$domain_text ? $email_domain : ($email_domain ? '0' : '');
	
	// 히든필드 (mbid@dmain 형식)
	$form_hidden = form_hidden(array($fld_name => $email_account .'@'. $email_domain));
	// 이메인 계정id
	$form_account = form_input(array('name' => 'account_'. $fld_name, 'class' => 'ed email_account', 'size' => '20', 'maxlength' => '50', 'onkeyup' => "email_merge($(this).next());", 'value' => $email_account));
	// 이메일 도메인 셀렉트
	$form_select = form_dropdown('select_'. $fld_name, $domain_list, $domain_selected, 'class="ed email_select" onChange="email_manual($(this), \''. $domain_text .'\');"');
	// 이메일 도메인 직접입력
	$form_domain = form_input(array('name' => 'domain_'. $fld_name, 'value' => $domain_text, 'maxlength' => '50', 'class' => (!$domain_text?'ed hide email_domain':'ed email_domain'), 'onkeyup' => "email_merge($(this).prev());"));

	$script = '<script type="text/javascript">$(document).ready(function(){ if($("select[name=\'select_'. $fld_name .'\']").val()=="0") $("#domain_'. $fld_name .'").show(); }); </script>';
	
	return $form_hidden . $form_account .' @ '. $form_select .' '. $form_domain . $script;
}
?>
