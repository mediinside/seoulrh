<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// URL 인코드
function url_encode($str) {
	return str_replace('%', '.', urlencode($str));
}

// 리스트 정보 가공
function get_convert($row, $board, $skin_path, $subject_len=60, $qstr, $list_view=FALSE) {
	$row['href'] = RT_PATH.'/board/'.BO_TABLE.'/view/wr_id/'.$row['wr_id'].$qstr;
	$row['subject'] = cut_str(get_text($row['wr_subject']), $subject_len);
    $row['hit'] = number_format($row['wr_hit']);

	$tmp_name = cut_str(get_text($row['wr_name']), 18);
	$row['name'] = ($board['bo_use_sideview']) ? get_sideview($row['mb_id'], $tmp_name) : "<span class='".($row['mb_id']?'member':'guest')."'>".$tmp_name."</span>";
	
	// 당일인 경우 시간으로 표시함
    $row['datetime'] = substr($row['wr_datetime'],0,10);
	$row['datetime2'] = ($row['datetime'] == TIME_YMD) ? substr($row['wr_datetime'],11,5) : preg_replace('/\-/', '.', substr($row['wr_datetime'],0,10));
	
    if ($list_view) {
		// 최근 갱신 시간
		$row['last'] = substr($row['wr_last'],0,10);
		$row['last2'] = ($row['last'] == TIME_YMD) ? substr($row['wr_last'],11,5) : substr($row['wr_last'],5,5);

		$row['comment_cnt'] = '';
		if ($row['wr_comment'])
			$row['comment_cnt'] = '('.$row['wr_comment'].')';

		// 답변 여백
		$reply = strlen($row['wr_reply']);
		$row['ico_reply'] = '';
		if ($reply > 0) {
			for ($k=0; $k<$reply; $k++)
				$row['ico_reply'] .= ' &nbsp;&nbsp; ';
		}
		if ($reply > 0)
			$row['ico_reply'] .= "<img src='".$skin_path."/ico_reply.gif' title='답변' alt='답변'/>";

		$row['ico_new'] = '';
		if ($row['wr_datetime'] >= date("Y-m-d H:i:s", time() - ($board['bo_new'] * 3600)))
			$row['ico_new'] = "<img src='".$skin_path."/ico_new.gif' title='최신' alt='최신'/>";

		$row['ico_hot'] = '';
		if ($row['wr_hit'] >= $board['bo_hot'])
			$row['ico_hot'] = "<img src='".$skin_path."/ico_hot.gif' title='이슈' alt='이슈'/>";

		$row['ico_secret'] = '';
		if (strpos($row['wr_option'], 'secret') !== FALSE)
			$row['ico_secret'] = "<img src='".$skin_path."/ico_secret.gif' title='비밀' alt='비밀'/>";

		// 가변 파일 - 첨부파일이 0개 이상일 경우에만 실행
		$row['ico_file'] = $row['ico_image'] = $row['ico_movie'] = '';

		if ($row['uf_count_file'] > 0)
			$row['ico_file'] = "<img src='".$skin_path."/ico_file.gif' title='파일' alt='파일'/>";

		if ($row['uf_count_image'] > 0)
			$row['ico_image'] = "<img src='".$skin_path."/ico_image.gif' title='이미지' alt='이미지'/>";

		if (stripos($row['wr_content'], '&lt;embed'))
			$row['ico_movie'] = "<img src='".$skin_path."/ico_movie.gif' title='동영상' alt='동영상'/>";
	}

    return $row;
}

// 플래시 - document.write(flash_movie('".$base_url."/data/file/".$bid."/".$filename."', '_rt_".$no."', '".$size[0]."', '".$size[1]."', 'transparent'));
// 동영상 - document.write(obj_movie('".DATA_DIR."/file/".BO_TABLE."/".$file."', '_rt_".$ids."', '".$width."', '".$height."'));

// SNS 보내기
function sns_post($bid, $wr_id, $title, $content) {
	$url = urlencode('http://'.$_SERVER['HTTP_HOST'].'/board/'.$bid.'/view/wr_id/'.$wr_id);
	
	if(strtolower(preg_replace('/-/','',ENCODING)) == 'euckr')
	{
		/* EUC-KR */
		$title_de = mb_convert_encoding(strip_tags($title), 'UTF-8', 'EUC-KR');
		$content_de = mb_convert_encoding(cut_str(trim(str_replace(array('&nbsp;',"\n"), ' ', strip_tags($content))), 100), 'UTF-8', 'EUC-KR');
	}
	else
	{
		/* UTF-8 */
		$title_de = strip_tags($title);
		$content_de = cut_str(trim(str_replace(array('&nbsp;',"\n"), ' ', strip_tags($content))), 100);
	}

	$title = urlencode($title_de);
	$content = urlencode($content_de);
	$img = urlencode('http://'.$_SERVER['HTTP_HOST'].'/_board/thumbnail/sns/'.$bid.'/'.$wr_id);

	$str = '';
	$str .= "<a href='https://twitter.com/intent/tweet?text=".$title."%0A".$url."' target='_blank'><img src='".IMG_DIR."/board/btn_twitter.png' title='트위터' alt='트위터'/></a> ";
	$str .= "<a href='https://www.facebook.com/sharer/sharer.php?u=".$url."' target='_blank'><img src='".IMG_DIR."/board/btn_facebook.png' title='페이스북' alt='페이스북'/></a> ";
	$str .= "<a href='http://me2day.net/posts/new?new_post[body]=%22".str_replace('%22', '%5C%22', $title)."%22:".$url."%0A%0A".$url."&new_post[tags]=' target='_blank'><img src='".IMG_DIR."/board/btn_me2day.png' title='미투데이' alt='미투데이'/></a> ";
	$str .= "<a href='http://csp.cyworld.com/bi/bi_recommend_pop.php?url=".$url."&title=".urlencode(base64_encode($title_de))."&thumbnail=".$img."&summary=".urlencode(base64_encode($content_de))."&writer=' target='_blank'><img src='".IMG_DIR."/board/btn_cy.png' title='C공감' alt='C공감'/></a>";

	return $str;
}

// 키워드 차단
function blockWord($badStr, $str) {
	foreach ($badStr as $k=>$v) {
		if (is_int(strpos($str, $v))) {
			return true;
		}
	}
	return false;
}

// 관리자 확인
function is_boAdmin($board, $member) {
	if(!$board || !$member) {
		return FALSE;
	}
	
	$is_admin = FALSE;
	if(IS_MEMBER) {
		switch ($member['mb_id']) {
			case $board['gr_admin'] : $is_admin = 'group'; break;
			case $board['bo_admin'] : $is_admin = 'board'; break;
			default : $is_admin = ($member['mb_level'] == 10) ? 'super' : $is_admin; break;
		}
		$is_admin = ($member['mb_level'] >= ADMIN_MIN_LEVEL) ? 'super' : $is_admin;
	}

	return $is_admin;
}

// 게시판 목록
function board_select($board_list_arr=array(), $current_bo='', $onChange_href='', $is_all=FALSE) {
	if(!$board_list_arr) return FALSE;
	
	$onChange_script = '';
	if($onChange_href) {
		$onChange_script = $is_all ? "location.href=\"$onChange_href\";" : "if($(this).val()){location.href=\"$onChange_href\";}";
	}
	$start_option = $is_all ? "전체" : "게시판 이동";
	
	$html_code = "<select id='board_select' name='board_select' onchange='$onChange_script'>\n";
	$html_code .= "<option value=''>$start_option</option>\n";
	
	foreach($board_list_arr AS $board) {
		$is_selected = $board['bid'] == $current_bo ? 'selected="selected"' : '';
		$html_code .= "<option value='".$board['bid']."' $is_selected>".$board['bo_subject']."</option>\n";
	}
	$html_code .= "</select>\n";
	
	return $html_code;
}

// 검색 순서
function board_sort($a, $b) {
	$CI =& get_instance();
	return array_search($a, $CI->boards) > array_search($b, $CI->boards);
}

// 게시판 DB 설정(타 게시판 DB를 사용할 경우 bid, 카운터 등..)
function setBoardDb($boards) {
	if(!isset($boards) || !$boards) {
		return FALSE;
	}
	else if((!isset($boards[0]) || !is_array($boards[0])) && $boards) {
		$boards = array($boards);
	}
		
	$CI =& get_instance();
	foreach($boards AS $key => $board) {
		if(isset($board['bo_db'])) {
			if(isset($board['bid']) && (!$board['bo_db'] || $board['bo_db'] == $board['bid'])) {
				$boards[$key]['bo_db'] = $board['bid'];
				continue;
			}
			
			$db_board_info = $CI->M_basic->get_board($board['bo_db'], 'bo_notice, bo_count_write, bo_count_comment');
			
			if(isset($boards[$key]['bo_count_write']))		$boards[$key]['bo_count_write'] =		$db_board_info['bo_count_write'];
			if(isset($boards[$key]['bo_count_comment']))	$boards[$key]['bo_count_comment'] =		$db_board_info['bo_count_comment'];
			if(isset($boards[$key]['bo_notice']))			$boards[$key]['bo_notice'] =			$db_board_info['bo_notice'];
		}
	}
	
	return $boards;
}

// 게시물 리스팅 조건 설정
function bo_list_where($board, $is_admin=FALSE) {
	$CI =& get_instance();
	
	$where = $board['bo_notice'] ? array('wr_id NOT IN ("'. preg_replace('/,/', '","', $board['bo_notice']) .'")') : array();
	switch($board['bo_where']) {
		case 'mb_id' :
			if(!$is_admin) {
				$wr_ids = $CI->M_board->my_wr($CI->member['mb_id']);
				$where[] = "wr_num IN (". implode(',',$wr_ids) .")";
			}
			break;
		case 'sca' :
			if(!$is_admin) {
				$where[] = "ca_code IN (". $CI->seg->get_seg('sca') .")";
			}
			break;
		default :
			break;
	}
	
	return implode(' AND ', $where);
}
?>
