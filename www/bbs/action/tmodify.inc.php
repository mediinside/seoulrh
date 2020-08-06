<?php

//수정권한(쓰기권한이 있어야 수정 가능)
if($check_level < $db_config_data['jba_write_level'])
{
	$C_Func->put_msg_and_back("해당게시판의 수정권한이 없습니다.");
	die;
}


if(!$jb_code || !$jb_idx || !$input_passd) {
	$C_Func->put_msg_and_back("비정상적인 접근입니다.");
	die;
}

$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;			
$args['check_level'] = $check_level;
$args['check_id'] = $check_id;
$rst = $C_JHBoard->Board_Detail($args);

//비밀번호 입력확인
if($check_id != '') {
	if ($input_passd != $rst['jb_password']) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}else{
	if (md5($input_passd) != $rst['jb_password']) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}



//페이지 이동 관련 변수 설정 - 폼의 액션
$get_par = "${query_page}?jb_mode=tmodify&jb_code=${jb_code}&jb_idx=${jb_idx}&page=${page}";


$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$board_data = $C_JHBoard->Board_Read_Data($args);

foreach($board_data as $key=>$value) {
	//textarea 필드 타입의 특수문자는 그대로 둠
	if($key=="jb_content") {
		$$key=stripslashes($value);
	}	else {
		$$key= $C_Func->replace_quot($value);
	}
}
unset($key);
unset($value);


//파일명 분리 및 저장된 파일 갯수
$ex_jb_file_name = explode("|", $jb_file_name);
$ex_jb_file_code = explode("|", $jb_file_code);
$file_cnt = count($ex_jb_file_name); 
?>
