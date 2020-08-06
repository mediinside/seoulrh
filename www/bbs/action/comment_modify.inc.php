<?php
if(!$jb_code || !$jb_idx || !$jbc_idx) {
	$C_Func->put_msg_and_back("비정상적인 접근입니다.");
	die;
}


//페이지 이동 관련 변수 설정 - 폼의 액션
$get_c_par = "${query_page}?jb_mode=comment_modify&jb_code=${jb_code}&jb_idx=${jb_idx}&jbc_idx=${jbc_idx}";
$get_c_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$args['jbc_idx'] = $jbc_idx;
$args['check_level'] = $check_level;
$args['check_id'] = $check_id;
$rst = $C_JHBoard->Board_Comment_Detail($args);

foreach($rst as $key=>$value) {
	//textarea 필드 타입의 특수문자는 그대로 둠
	if($key=="jbc_content")	{
		$$key=stripslashes($value);
	}else {
		$$key= $C_Func->replace_quot($value);
	}
}
unset($key);
unset($value);

//회원 또는 관리자의 경우 비밀번호
if(isset($check_id)) {	
	$jbc_password=$rst['jbc_password'];
} else {
	$jbc_password="";
}
?>

