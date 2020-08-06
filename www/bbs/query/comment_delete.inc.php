<?php
//삭제관련 정보 확인
if(!$jb_code || !$jb_idx || !$jbc_idx || !$input_passd)
{
	$C_Func->put_msg_and_back("삭제관련정보가 부족합니다. 삭제정보를 확인해 주세요.");
	die;
}

$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$args['jbc_idx'] = $jbc_idx;
$args['check_level'] = $check_level;
$args['check_id'] = $check_id;
$rst = $C_JHBoard->Board_Comment_Detail($args);

//비밀번호 입력확인
if ($input_passd != $rst['jbc_password']) {
	$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
	die; 
}

//코멘트 삭제
$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$args['jbc_idx'] = $jbc_idx;
$result_key = $C_JHBoard->Board_Comment_Del($args);

//리스트 입력이 완료되면 해당 부모글의 코멘트 수 증가
if($result_key)
{	
	$args = '';
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;
	$args['jbc_idx'] = $jbc_idx;
	$result_key1 = $C_JHBoard->BOARD_LIST_COMM_DOWN($args);
}


//페이지 이동 관련 변수 설정
$get_par = "${index_page}?jb_code=${jb_code}&jb_idx=${jb_idx}";
$get_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("삭제가 완료되었습니다.", "${get_par}");
?>