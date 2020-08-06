<?php
//삭제관련 정보 확인
if(!$jb_code || !$jb_idx || !$input_passd)
{
	$C_Func->put_msg_and_back("삭제관련정보가 부족합니다. 삭제정보를 확인해 주세요.");
	die;
}


$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;			
$args['check_level'] = $check_level;
$args['check_id'] = $check_id;
$rst = $C_JHBoard->Board_Detail($args);

foreach($rst as $key=>$value){
	$$key=$value;
}
unset($key);
unset($value);


//비밀번호 입력확인
if($check_id != '') {
	if ($input_passd != $jb_password) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}else{
	if (md5($input_passd) != $jb_password) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}


//내용, 답글, 코멘트 삭제 처리용 루프


$args = "";
$args['jb_code'] = $jb_code;
$args['jb_group'] = $jb_group;
$args['jb_depth'] = $jb_depth;
$rst = $C_JHBoard->Board_DEL_ALL($args);

//페이지 이동 관련 변수 설정
$get_par = "${index_page}?jb_code=${jb_code}";
$get_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("삭제가 완료되었습니다.", "${get_par}");
?>