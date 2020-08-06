<?php

include $GP -> INC_PATH . "/xssFilter/HTML/Safe.php"; // xss filter을 include


//등록관련 정보 확인
if(!$jb_code || !$jb_idx || !$jbc_name || !$jbc_content)
{
	$C_Func->put_msg_and_back("등록관련정보가 부족합니다. 등록정보를 확인해 주세요.");
	die;
}


//자동구문변수처리
$jbc_step=0;
$jbc_reg_date	= date('Y-m-d H:i:s');
$jbc_mod_date	= date('Y-m-d H:i:s');
$jbc_reg_ip	=	$_SERVER['REMOTE_ADDR'];
$jbc_mb_id = $check_id;


//마지막 jb_depth 입력값
$args = "";
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$rst = $C_JHBoard->Board_Comment_Depth_Max($args);

/*
//Depth
if($rst['max_depth'] > 0)
	$jbc_depth=($rst['max_depth'] + 10); //답변 9개
else
	$jbc_depth=10;

//Group
$jbc_group=($jbc_depth / 10);
*/

//Group
if($rst['max_group'] > 0)
	$jbc_group=($rst['max_group'] + 1); 
else
	$jbc_group = 1;

//Group
$jbc_depth = '';
//$jbc_group = (($jbc_depth * -1) / 10);

$safe = new HTML_Safe; // xss filter 객체 생성
$input = base64_decode($jbc_content); 
$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입
//$output = iconv("UTF-8", "EUC-KR", $output);
$output = addslashes($output);
$jbc_content = htmlspecialchars($output);

$jbc_del_flag = 'N';


//==============================================================================================
# 자동 DB Insert 구문 생성 Start
//==============================================================================================
$keys="";
$values="";
$rst_board = $C_JHBoard->DESC_BOARD_COMMENT();

for($i=0; $i<count($rst_board); $i++) {
	if($rst_board[$i][Extra]=="auto_increment") continue;
	
	$keys.=$rst_board[$i][Field] . ",";	//자동 Key 생성
	$values.="'" . $$rst_board[$i][Field] . "',"; //자동 Value 생성
}

//끝부분 "," 제거
$keys=rtrim($keys, ",");
$values=rtrim($values, ",");

//리스트테이블에 기본정보 입력
if($keys && $values)
{
	$args = "";
	$args['keys'] = $keys;
	$args['values'] = $values;
	$result_key = $C_JHBoard->BOARD_COMMENT_WRITE($args);		
}
//==================================================================== 자동 DB Insert 구문 생성 End


//리스트 입력이 완료되면 해당 부모글의 코멘트 수 증가
if($result_key)
{
	$args = "";
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;
	$result_key2 = $C_JHBoard->BOARD_LIST_COMM_UP($args);	
}


//페이지 이동 관련 변수 설정
$get_par = "${index_page}?jb_code=${jb_code}&jb_idx=${jb_idx}&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("등록되었습니다.", "${get_par}");
?>