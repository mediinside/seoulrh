<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 회원권한을 SELECT 형식으로 얻음
function get_mb_level_select($name, $slt_no='', $not_id='', $maxLv=10, $show_false=FALSE) {
	$not_id = (!$not_id) ? "id='".$name."' " : ''; 
    $str = "<select ".$not_id."name='".$name."'>";

    for ($i=1; $i<=$maxLv; $i++) {
		$slt = ($slt_no == $i) ? "selected='selected'" : '';
        $str .= "<option value='".$i."' ".$slt.">".$i."</option>";
    }
    
    if($show_false) {
    	$str .= "<option value='99' ". (($slt_no==99)?"selected='selected'":'') .">불가</option>";
    }
    
    $str .= '</select>';
    return $str;
}

// 작업아이콘 출력
function icon($act, $link='', $target='_self') {
	$img = array('입력'=>'insert', '추가'=>'insert', '생성'=>'insert', '수정'=>'modify', '삭제'=>'delete', '보기'=>'view', '미리보기'=>'view', '로그인'=>'login');
    $icon = "<img src='".IMG_DIR."/".ADM_F."/icon_".$img[$act].".gif' width='22' height='21' align='middle' title='".$act."' alt='".$act."'/>";
    if ($link) {
		if (strpos($link, 'javascript') === FALSE)
	        $s = "<a href='".RT_PATH.'/'.ADM_F.'/'.$link."' target='".$target."'>".$icon."</a>";
		else
			$s = "<a href='javascript:;' onclick=\"".$link."\">".$icon."</a>";
    }
	else
        $s = $icon;
    return $s;
}

// 레이아웃을 SELECT 형식으로 얻음
function get_layout_select($name, $slt_id='', $not_id='') {
	$CI =& get_instance();
	$options = $CI->M_a_layout->get_layouts();
	
	$not_id = (!$not_id) ? "id='$name'" : '';
	$str = "<select $not_id name='$name'>";
	
	$str .= "<option value=''>선택</option>";
	foreach($options AS $layout) {
		$slt = ($slt_id == $layout['ly_id']) ? "selected='selected'" : '';
		$str .= "<option value='".$layout['ly_id']."' $slt>".$layout['ly_name']."</option>";
	}
	$str .= '</select>';
	return $str;
}

// 게시판 그룹을 SELECT 형식으로 얻음
function get_group_select($name, $slt_id='', $not_id='') {
	global $gr_select; // 한번만 실행

	if (!$gr_select) {
		$CI =& get_instance();
		$CI->db->select('gr_id,gr_subject');
		$query = $CI->db->get('ki_board_group');
		$gr_select = $query->result_array();
	}
		
	$not_id = (!$not_id) ? "id='".$name."' " : ''; 
    $str = "<select ".$not_id."name='".$name."'>";
	foreach($gr_select as $row) {
		$slt = ($slt_id == $row['gr_id']) ? "selected='selected'" : '';
		$str .= "<option value='".$row['gr_id']."' ".$slt.">".$row['gr_subject']."</option>";
	}
	$str .= '</select>';
    return $str;
}

// 게시판 답변 달기 옵션을 SELECT 형식으로 얻음
function get_order_select($name, $slt_id='', $not_id='') {
	$options = array('1' => '나중에 쓴 답변 아래로 달기 (기본)', '0' => '나중에 쓴 답변 위로 달기');
	
	$not_id = (!$not_id) ? "id='$name'" : '';
	$str = "<select $not_id name='$name'>";
	
	foreach($options AS $value => $text) {
		$slt = ($slt_id == $value) ? "selected='selected'" : '';
		$str .= "<option value='$value' $slt>$text</option>";
	}
	$str .= '</select>';
	return $str;
}

// 게시판 리스트 정렬필드 옵션을 SELECT 형식으로 얻음
function get_sort_select($name, $slt_id='', $not_id='') {
	$options = array(
		0									=> 'wr_num, wr_reply : 기본',
		'wr_datetime asc'					=> 'wr_datetime asc : 날짜 이전것 부터',
		'wr_datetime desc'					=> 'wr_datetime desc : 날짜 최근것 부터',
		'wr_hit asc, wr_num, wr_reply'		=> 'wr_hit asc : 조회수 낮은것 부터',
		'wr_hit desc, wr_num, wr_reply'		=> 'wr_hit desc : 조회수 높은것 부터',
		'wr_last asc'						=> 'wr_last asc : 최근글 이전것 부터',
		'wr_last desc'						=> 'wr_last desc : 최근글 최근것 부터',
		'wr_comment asc, wr_num, wr_reply'	=> 'wr_comment asc : 코멘트수 낮은것 부터',
		'wr_comment desc, wr_num, wr_reply'	=> 'wr_comment asc : 코멘트수 높은것 부터'
	);
	
	$not_id = (!$not_id) ? "id='$name'" : '';
	$str = "<select $not_id name='$name'>";
	
	foreach($options AS $value => $text) {
		$value = $value ? $value : '';
		$slt = ($slt_id == $value) ? "selected='selected'" : '';
		$str .= "<option value='$value' $slt>$text</option>";
	}
	$str .= '</select>';
	return $str;
}

// 스킨경로를 얻는다
function get_skin_dir($skin, $name, $slt_skin='', $not_id='') {
	global $skin_file; // 한번만 실행

	if (!$skin_file) {
		$skin_file = array();
		$dirname = SKIN_PATH.$skin.'/';
		$handle = opendir($dirname);
		while ($file = readdir($handle)) {
			if($file == '.' || $file == '..') continue;

			if (is_dir($dirname.$file))
				$skin_file[] = $file;
		}
		closedir($handle);
		sort($skin_file);
	}

	$not_id = (!$not_id) ? "id='".$name."' " : ''; 
	$str = "<select ".$not_id."name='".$name."'>";
	foreach($skin_file as $row) {
		$option = $row;
		if (strlen($option) > 30)
			$option = substr($row, 0, 38) . "…";

		$slt = ($slt_skin == $row) ? "selected='selected'" : '';
		$str .= "<option value='".$row."' ".$slt.">".$option."</option>";
	}
	$str .= '</select>';
	return $str;
}

// rm -rf 옵션 : exec(), system() 함수를 사용할 수 없는 서버 또는 win32용 대체
// www.php.net 참고 : pal at degerstrom dot com
function rm_rf($file) {
    if (file_exists($file)) {
        @chmod($file,0777);
        if (is_dir($file)) {
            $handle = opendir($file);
            while($filename = readdir($handle)) {
                if ($filename != '.' && $filename != '..')
                    rm_rf($file.'/'.$filename);
            }
            closedir($handle);
            rmdir($file);
        } else
            unlink($file);
    }
}

// 인풋배열을 HTML 형식으로 반환
function arrayToInput($colName, $info, $data=array(), $is_list=false, $img_dir='') {
	if(!$colName || !$info)
		return false;

	$HTML = '';
	$idx = array_shift($data);
	$fldName = preg_replace('/\[|\]/', '', $colName);
	$value = isset($data[$colName]) ? $data[$colName] : '';

	switch($info['input']) {
		case '' :			// 인풋이 아니면 텍스트 출력
			$HTML = $data[$colName] ."\n";
			break;
		
		case 'textarea' :	// 멀티라인 입력
			$HTML = '<textarea id="'.$colName.'" name="'.$colName.'" class="ed" cols="69" rows="5">'.$data[$colName].'</textarea>' ."\n";
			break;
			
		case 'select' :		// 셀렉트 입력
			$HTML = '<select id="'.$colName.'" name="'.$colName.($is_list?'['.$idx.']':'').'">' ."\n";
			$HTML .= '<option value="">선택</option>' ."\n";
			foreach($info['option'] as $val => $str) {
				$sel = ((string)$val === (string)$data[$colName]) ? 'selected="selected"' : '';
				$HTML .= '<option value="'.$val.'" '.$sel.'>'.$str.'</option>' ."\n";
			}
			$HTML .= '</select>' ."\n";
			break;
			
		case 'file' :		// 파일
			if(isset($data[$fldName]) && $data[$fldName]) {
				$file = is_numeric($data[$fldName]) ? 'no='.$data[$fldName] : 'file='.$data[$fldName];
				
				$thumb = '<img src="/useful/thumbnail/80x60/'.$img_dir.'/'.$idx.'?'.$file.'"/>' ."\n";
				$HTML = $thumb.' <input type="hidden" name="oldFileNo[]" value="'.$data[$fldName].'"/><input type="checkbox" name="delFile['.$colName.']" value="'.$data[$fldName].'"/> 삭제' ."\n";
			}
			else {
				$HTML = '<input type="'.$info['input'].'" id="'.$colName.'" name="'.$colName.'" class="ed"/>' ."\n";
			}
			break;
		
		case 'checkbox' :	// 체크박스
			$arr_id = $is_list ? '['.$idx.']' : '';
			$chk = $data[$fldName] ? 'checked="checked"' : '';
			$HTML = '<input type="'.$info['input'].'" id="'.$colName.$arr_id.'" name="'.$colName.$arr_id.'" '.$chk.' value="1"/>' ."\n";
			break;
			
		default :			// 그 외 일반적인 입력
			$HTML = '<input type="'.$info['input'].'" id="'.$colName.'" name="'.$colName.'" class="ed" value="'.$value.'"/>' ."\n";
			break;
	}
	
	return $HTML;
}
?>
