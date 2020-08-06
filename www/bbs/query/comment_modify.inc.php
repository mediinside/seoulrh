<?php

include $GP -> INC_PATH . "/xssFilter/HTML/Safe.php"; // xss filter을 include

//수정관련 정보 확인
if(!$jb_code || !$jb_idx || !$jbc_idx || !$jbc_name || !$jbc_content) {
	$C_Func->put_msg_and_back("수정관련정보가 부족합니다. 수정정보를 확인해 주세요.");
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
if ($jbc_password != $rst['jbc_password']) {
	$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
	die; 
}

//자동구문변수처리
$jbc_step = 0;
$jbc_mod_date = date('Y-m-d H:i:s');
$jbc_reg_ip = $_SERVER['REMOTE_ADDR'];

$safe = new HTML_Safe; // xss filter 객체 생성
$input = base64_decode($jbc_content); 
$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입
//$output = iconv("UTF-8", "EUC-KR", $output);
$output = addslashes($output);
$jbc_content = htmlspecialchars($output);


//==============================================================================================
# 자동 DB Update 구문 생성 Start
//==============================================================================================
$keys_values="";
$rst_board = $C_JHBoard->DESC_BOARD_COMMENT();

for($i=0; $i<count($rst_board); $i++) {
	if($rst_board[$i][Extra]=="auto_increment") continue;
	if($rst_board[$i][Field]=="jb_idx") continue;
	if($rst_board[$i][Field]=="jb_code") continue;
	if($rst_board[$i][Field]=="jbc_group") continue;
	if($rst_board[$i][Field]=="jbc_step") continue;
	if($rst_board[$i][Field]=="jbc_depth") continue;
	if($rst_board[$i][Field]=="jbc_reg_date") continue;
	if($rst_board[$i][Field]=="jbc_mb_id") continue;
	if($rst_board[$i][Field]=="jbc_password") continue;
	if($rst_board[$i][Field]=="jbc_del_flag") continue;
	if($rst_board[$i][Field]=="jbc_p_id") continue;
	if($rst_board[$i][Field]=="jbc_p_name") continue;
	
	$keys_values.=$rst_board[$i][Field] . "='" . $$rst_board[$i][Field] . "',"; //자동 Update Query 생성
}
//끝부분 "," 제거
$keys_values=rtrim($keys_values, ",");


//코멘트테이블 해당정보 수정
if($keys_values)
{
	$args = "";
	$args['keys_values'] = $keys_values;
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;
	$args['jbc_idx'] = $jbc_idx;
	$result_key = $C_JHBoard->BOARD_COMMENT_UPDATE($args);	
}
//==================================================================== 자동 DB Update 구문 생성 End



//페이지 이동 관련 변수 설정
$get_par = "${index_page}?jb_code=${jb_code}&jb_idx=${jb_idx}";
$get_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("수정이 완료되었습니다.", "${get_par}");
?>
