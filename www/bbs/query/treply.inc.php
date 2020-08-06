<?php

include $GP -> INC_PATH . "/xssFilter/HTML/Safe.php"; // xss filter을 include
include $GP -> INC_PATH .'/zmSpamFree/zmSpamFree.php';

if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'])) {
	$C_Func->put_msg_and_back("비정상적인 접근입니다.");
	die;
}

//답변권한
if($check_level < $db_config_data['jba_reply_level'])
{
	$C_Func->put_msg_and_back("해당게시판의 답변권한이 없습니다.");
	die;
}

//스팸방지
if ( !zsfCheck( $_POST['zsfCode'] ) ) {
	$C_Func->put_msg_and_back("스팸차단코드가 틀렸습니다.");
	die;	
}


//답변관련 정보 확인($jb_step=="" 0일경우 통과시킴 Null일경우만 경고메세지 출력)
if(!$jb_code || !$jb_idx || !$jb_group || $jb_step=="" || !$jb_depth || !$jb_title || !$jb_name || !$jb_content)
{
	$C_Func->put_msg_and_back("답글관련정보가 부족합니다. 작성하신 답글정보를 확인해 주세요.");
	die;
}


//답변은 1개로 제한한다. (FAQ용도로만 사용)
//Q&A, 자유게시판 등은 댓글(코멘트) 형식으로 프로그래밍
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


//업로드 파일 갯수
$file_cnt = count($_FILES[jb_file][tmp_name]); 
for($i=0; $i<$file_cnt; $i++) {
	 //파일의 확장자 및, 용량체크 - 허용용량을 초과 할 수 있으므로 DB Insert 보다 우선 처리
	if($_FILES[jb_file][name][$i])
		$C_Func->file_ext_check($_FILES[jb_file][name][$i], $_FILES[jb_file][size][$i]);
}


//자동줄바꿈 체크
if(!$jb_bruse_check) {
	$jb_bruse_check = 'N';
}

//비밀글 체크
if(!$jb_secret_check) {
	$jb_secret_check = 'N';	
}


//자동구문변수처리
$jb_group=$jb_group; //삭제 및 관련글 쿼리용 그룹코드
$jb_order = 100; //답변은 공지할 수 없다.
$jb_reg_date= date('Y-m-d H:i:s');
$jb_mod_date= date('Y-m-d H:i:s');
$jb_reg_ip=$_SERVER['REMOTE_ADDR'];
$jb_see=0;
$jb_comment_count =0;
$jb_mb_id=$check_id;
$jb_mb_level=$check_level;
$jb_del_flag = 'N';

$safe = new HTML_Safe; // xss filter 객체 생성
$input = base64_decode($jb_content); 
$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입
$jb_content = $C_Func->enc_contents($output);

//답글관련 쓰레드변경
$jb_step = ($jb_step + 1); //답변글인지 여부
$jb_depth= ($jb_depth - 1); //부모글 아래로 정렬하기 위한 Depth(DESC로 정렬)


//==============================================================================================
# 자동 DB Insert 구문 생성 Start
//==============================================================================================
$keys="";
$values="";
$rst_board = $C_JHBoard->DESC_BOARD_LIST();

for($i=0; $i<count($rst_board); $i++) {
	if($rst_board[$i][Extra]=="auto_increment") continue;
	if($rst_board[$i][Field]=="jb_file_name") continue;
	if($rst_board[$i][Field]=="jb_file_code") continue;
	
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
	$result_key = $C_JHBoard->BOARD_WRITE($args);			
}
//==================================================================== 자동 DB Insert 구문 생성 End


//리스트 입력이 완료되면 내용을 입력
if($result_key) {
	//리스트에 입력된 게시물 번호
	$jb_idx = $result_key;
	
	$keys="";
	$values="";
	$rst_detail = $C_JHBoard->DESC_BOARD_DETAIL();
	for($i=0; $i<count($rst_detail); $i++) {
		if($rst_detail[$i][Extra]=="auto_increment") continue;
	
		$keys.=$rst_detail[$i][Field] . ",";	//자동 Key 생성
		$values.="'" . $$rst_detail[$i][Field] . "',"; //자동 Value 생성
	}
	
	//끝부분 "," 제거
	$keys=rtrim($keys, ",");
	$values=rtrim($values, ",");
	
	
	//내용 테이블에 기본정보 입력
	if($keys && $values)
	{
		$args = "";
		$args['keys'] = $keys;
		$args['values'] = $values;
		$result_key_detail = $C_JHBoard->BOARD_WRITE_DETAIL($args);		
	}
}


//다중 파일 업로드
$file_save_path = $GP -> UP_IMG_SMARTEDITOR ."jb_" . $jb_code; //저장될 경로...
$real_file_names="";
$new_file_names="";
for($i=0; $i<$file_cnt; $i++) {
	$new_file_name="";
	
	//파일업로드	
	if($_FILES[jb_file][name][$i]) {
		$new_file_name = $C_Func->file_upload($_FILES[jb_file][tmp_name][$i], $_FILES[jb_file][name][$i], $_FILES[jb_file][size][$i], $file_save_path, "");
		
		$real_file_names.=$_FILES[jb_file][name][$i] . "|";
		$new_file_names.=$new_file_name . "|";
	}	
}

$args = "";
//첨부파일
if($real_file_names!="" && $new_file_names!="")
{
	$real_file_names = rtrim($real_file_names, "|");
	$new_file_names = rtrim($new_file_names, "|");
	
	$args['jb_file_name'] = $real_file_names;
	$args['jb_file_code'] = $new_file_names;
}

//에디터
if($img_full_name != "") {
	$Arr_img = explode(',', $img_full_name);	
	$img_name = "";
	for	($i=0; $i<count($Arr_img); $i++) {		
		if(ereg($C_Func->escape_ereg($Arr_img[$i]), $C_Func->escape_ereg($jb_content))) {		
			$img_name .= trim($Arr_img[$i]) . ",";		
		}else{
			@unlink($file_save_path. "/". $Arr_img[$i]);
		}
	}
	$img_name = rtrim($img_name , ",");
	
	$args['jb_img_code'] = $img_name;
}

$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;	
$file_update = $C_JHBoard->BOARD_FILE_UPDATE($args);	


//페이지 이동 관련 변수 설정
$get_par = "${index_page}?jb_code=${jb_code}";
$get_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("답변이 등록되었습니다.", "${get_par}");
?>
