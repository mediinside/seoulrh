<?php

//답변권한
if($check_level < $db_config_data['jba_reply_level'])
{
	$C_Func->put_msg_and_back("해당게시판의 답변권한이 없습니다.");
	die;
}


//답변관련 정보 확인($jb_step=="" 0일경우 통과시킴 Null일경우만 경고메세지 출력)
if(!$jb_code || !$jb_idx || !$jb_group || $jb_step=="" || !$jb_depth) {
	$C_Func->put_msg_and_back("답글관련정보가 부족합니다. 작성하신 답글정보를 확인해 주세요.");
	die;
}


//답변은 1개로 제한한다. (FAQ용도로만 사용)
if($jb_step == 0) {	
	$args = '';
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;
	$rst = $C_JHBoard->Board_Reply_Detail($args);	

	if($rst['cnt'] > 1)	{
		$C_Func->put_msg_and_back("답변은 최초 한번만 가능합니다. 댓글(코멘트)를 이용해 주세요.");
		die;
	}	
} else {
	$C_Func->put_msg_and_back("답변은 최초 한번만 가능합니다. 댓글(코멘트)를 이용해 주세요.");
	die;
}

//페이지 이동 관련 변수 설정 - 폼의 액션
$get_par = "${query_page}?jb_code=${jb_code}&jb_idx=${jb_idx}&jb_group=${jb_group}&jb_step=${jb_step}&jb_depth=${jb_depth}";
$get_par.= "&${search_key}&search_keyword=$search_keyword&page=$page";
$get_par.= "&jb_mode=treply";

$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$rst = $C_JHBoard->Board_Read_Data($args);

foreach($rst as $key=>$value) {
	$$key=stripslashes($value);
}
unset($key);
unset($value);


$jb_content	.= "\n[ " . $jb_name . " 님의 글 ]\n\n";
?>