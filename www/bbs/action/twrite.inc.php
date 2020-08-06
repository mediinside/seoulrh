<?php
//쓰기권한
if($check_level < $db_config_data['jba_write_level'])
{
	$C_Func->put_msg_and_back("해당게시판의 쓰기권한이 없습니다.");
	die;	
}


//자동등록방지 key생성...
/*
if(!$check_id)
{
	$chkrobot_key = $C_Func->rand_make();
	$s_chkrobot_key = "wjsgud_" . $chkrobot_key;

	session_register("s_chkrobot_key");
}
*/

//페이지 이동 관련 변수 설정
$get_par = "${query_page}?jb_code=${jb_code}&jb_mode=${jb_mode}";
$get_par .= "&${search_key}&search_keyword=$search_keyword&page=$page";
?>