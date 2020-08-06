<?php

include $GP -> INC_PATH . "/xssFilter/HTML/Safe.php"; // xss filter을 include
include $GP -> INC_PATH .'/zmSpamFree/zmSpamFree.php';

if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'])) {
	$C_Func->put_msg_and_back("비정상적인 접근입니다.");
	die;
}

//스팸방지
if ( !zsfCheck( $_POST['zsfCode'] ) ) {
//	$C_Func->put_msg_and_back("스팸차단코드가 틀렸습니다.");
//	die;	
}

//수정권한(쓰기권한이 있어야 수정 가능)
if($check_level < $db_config_data['jba_write_level']) {
	$C_Func->put_msg_and_back("해당게시판의 수정권한이 없습니다.");
	die;
}

//수정관련 정보 확인
if(!$jb_code || !$jb_idx || !$jb_password || !$jb_title || !$jb_name || !$jb_content) {
	$C_Func->put_msg_and_back("수정관련정보가 부족합니다. 수정정보를 확인해 주세요.");
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
	if ($jb_password != $rst['jb_password']) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}else{
	if (md5($jb_password) != $rst['jb_password']) {
		$C_Func->put_msg_and_back("비밀번호를 정확히 입력하세요.");
		die; 
	}
}



//업로드 파일 갯수
$file_cnt = count($_FILES[jb_file][tmp_name]); 
for($i=0; $i<$file_cnt; $i++) {
	 //파일의 확장자 및, 용량체크 - 허용용량을 초과 할 수 있으므로 DB Insert 보다 우선 처리
	if($_FILES[jb_file][name][$i])
		$C_Func->file_ext_check($_FILES[jb_file][name][$i], $_FILES[jb_file][size][$i], "");
}



//자동줄바꿈 체크
if(!$jb_bruse_check) {
	$jb_bruse_check = 'N';
}

//비밀글 체크
if(!$jb_secret_check) {
	$jb_secret_check = 'N';
}
	
//공지글
if($jb_notice_check == "Y") {
	$jb_order = 50;
}else{
	$jb_order = 100;
}

if($jb_etc2_chk == "채용시까지"){
    $jb_etc2 = "채용시까지" ;
}

//자동구문변수처리
$jb_step=0;
if($jb_reg_date && $jb_code >= "200") $jb_reg_date = $jb_reg_date." ".date('H:i:s');
$jb_mod_date= date('Y-m-d H:i:s');
$jb_reg_ip=$_SERVER['REMOTE_ADDR'];

$jb_title = addslashes($jb_title);

$safe = new HTML_Safe; // xss filter 객체 생성
$safe->setAllowTags(array('object', 'embed', 'iframe')); // 플래시를 위해 object 및 embed 태그 설정
$input = base64_decode($jb_content); 
$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입
//$output = iconv("UTF-8", "EUC-KR", $output);
$jb_content = $C_Func->enc_contents($output);
//$jb_content = htmlspecialchars($output);


//==============================================================================================
# 자동 DB Update 구문 생성 Start
//==============================================================================================
$keys="";
$values="";
$rst_board = $C_JHBoard->DESC_BOARD_LIST();

for($i=0; $i<count($rst_board); $i++) {
	if($rst_board[$i][Extra]=="auto_increment") continue;
	if($rst_board[$i][Field]=="jb_group") continue;
	if($rst_board[$i][Field]=="jb_step") continue;
	if($rst_board[$i][Field]=="jb_depth") continue;
	if($jb_reg_date && $jb_code >= "200") {
	}else{
		if($rst_board[$i][Field]=="jb_reg_date") continue;
	}
	if($rst_board[$i][Field]=="jb_see") continue;
	if($rst_board[$i][Field]=="jb_comment_count") continue;
	if($rst_board[$i][Field]=="jb_mb_id") continue;
	if($rst_board[$i][Field]=="jb_mb_level") continue;
	if($rst_board[$i][Field]=="jb_password") continue;
	if($rst_board[$i][Field]=="jb_file_name") continue;
	if($rst_board[$i][Field]=="jb_file_code") continue;
	if($rst_board[$i][Field]=="jb_del_flag") continue;
	
	$keys_values.=$rst_board[$i][Field] . "='" . $$rst_board[$i][Field] . "',"; //자동 Update Query 생성
}

//끝부분 "," 제거
$keys_values=rtrim($keys_values, ",");


//리스트테이블 해당정보 수정
if($keys_values)
{
	$args = "";
	$args['keys_values'] = $keys_values;
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;
	$result_key = $C_JHBoard->BOARD_UPDATE($args);	
	
	
	$args = "";
	$args['jb_content'] = $jb_content;
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;	
	$result_key = $C_JHBoard->BOARD_DETAIL_UPDATE($args);	
}
//==================================================================== 자동 DB Update 구문 생성 End



//==================================================================================================================
# 다중파일업로드 관련 처리 Start
//==================================================================================================================
//파일명 쿼리(삭제 및 수정시 사용)
$args = "";
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;	
$row_del = $C_JHBoard->BOARD_FILE_CONTENT($args);


//파일명 분리 및 저장된 파일 갯수
$ex_jb_file_name = explode("|", $row_del[jb_file_name]);
$ex_jb_file_code = explode("|", $row_del[jb_file_code]);

//다중 파일 업로드 (파일 삭제, 수정, 새파일 업로드)
$file_save_path = $GP -> UP_IMG_SMARTEDITOR ."jb_" . $jb_code; //저장될 경로...
$real_file_names="";
$new_file_names="";

for($i=0; $i<$file_cnt; $i++) {
	$real_file_name="";
	$new_file_name="";

	//DB에 저장된 파일순으로 처리
	if($ex_jb_file_code[$i]) {
		//해당 파일 삭제 여부
		/****************************************************************************/	
		/* $_POST[del_file][$i] 를  $del_file[$i]의 지역변수 형식으로 사용하면 에러 발생           */
		/****************************************************************************/	
		if($_POST[del_file][$i]=="Y") {
			$C_Func->del_file($ex_jb_file_code[$i], $file_save_path); //파일삭제(삭제할 파일 명, 파일이 저장된 경로)
			$C_Func->del_file("s_" . $ex_jb_file_code[$i], $file_save_path); //파일삭제(삭제할 파일 명, 파일이 저장된 경로)
		} else {
			$real_file_name = $ex_jb_file_name[$i]; //삭제하지 않을 경우 DB 저장용 파일명 생성
			$new_file_name = $ex_jb_file_code[$i]; //삭제하지 않을 경우 DB 저장용  파일코드 생성
		}
	}		
	
	//파일업로드	
	if($_FILES[jb_file][name][$i]) {
		$new_file_name = $C_Func->file_upload($_FILES[jb_file][tmp_name][$i], $_FILES[jb_file][name][$i], $_FILES[jb_file][size][$i], $file_save_path, $ex_jb_file_code[$i]);
		
		$real_file_names.=$_FILES[jb_file][name][$i] . "|";
		$new_file_names.=$new_file_name . "|";
	} else {
		//파일 업로드를 하지 않아도 삭제하지 않은 이전파일이 있다면 DB에 저장될 파일명 및 파일 코드에 추가
		if($real_file_name!="" && $new_file_name!="") {
			$real_file_names.=$real_file_name . "|";
			$new_file_names.=$new_file_name . "|";
		}
	}	
	
} 

$real_file_names = rtrim($real_file_names, "|");
$new_file_names = rtrim($new_file_names, "|");	

$args = "";
$args['jb_file_name'] = $real_file_names;
$args['jb_file_code'] = $new_file_names;
//====================================================================================================== 다중파일업로드 관련 처리 End


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
$get_par = "${index_page}?jb_code=${jb_code}&jb_mode=tdetail&jb_idx=${jb_idx}";
$get_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";

$C_Func->put_msg_and_go("수정이 완료되었습니다.", "${get_par}");
?>
