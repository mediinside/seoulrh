<?php

// 읽기권한체크	
if($check_level < $db_config_data['jba_read_level']) {
	$C_Func->put_msg_and_go("해당게시판의 읽기권한이 없습니다.", "/member/login.html");
	die;
}

//조회수 증가 (관리자 제외)
if($check_level < 9) {	
	$args = "";
	$args['jb_code'] = $jb_code;
	$args['jb_idx'] = $jb_idx;	
	$rst = $C_JHBoard->Board_See_Up($args);	
}



$args = '';
$args['jb_code'] = $jb_code;
$args['jb_idx'] = $jb_idx;
$board_data = $C_JHBoard->Board_Read_Data($args);


if($mypage_id != '') {
	$args['my_mb_id']  = $mypage_id;	
}
$board_data_B_F = $C_JHBoard->Board_Read_Data_B_A($args);

if($board_data) {
	foreach($board_data as $key=>$value) {
		//textarea 필드 타입의 특수문자는 그대로 둠
		if($key=="jb_content") {
			//$$key=stripslashes(eregi_replace("\'", "&quot;", $value));						
			$$key=$value;
		} else {		
			$$key=$value;
			//$$key=$C_Func->replace_quot($value);
		}
	}
	unset($key, $value);
	

	//비밀글 일 경우 읽기권한 처리 루틴 - 관리자가 아닐 경우와 비밀번호 입력전만 체크
	if($check_level < $db_config_data['jba_reply_level'] && $jb_secret_check == "Y" && $_SESSION['ssecret_chk_key'] != $jb_idx) {
		//비회원이 작성한 비밀글일 경우
		if($jb_mb_id == "") {
			 //비밀번호 입력 체크
			if(empty($_POST['input_passd'])) {
				$C_Func->put_msg_and_back("비밀번호를 입력해주세요.");
				die;
			} else {
				
				if(md5($_POST['input_passd']) != $jb_password) {
					$C_Func->put_msg_and_back("비밀번호를 정확하게 입력해주세요.");					
					die;
				}
			}
			
			//비밀글일 경우 상세보기 처리를 위한 세션처리 - 최초 한번만 입력하면 되도록 세션처리
			$ssecret_chk_key = $jb_idx;
			session_register("ssecret_chk_key");			
		}
		//회원이 작성한 비밀글일 경우
		else
		{
			//비밀글의 경우 회원도 자신이 쓴 글에대한 답변만 볼 수 있다.
			$args = "";
			$args['jb_code'] = $jb_code;
			$args['jb_group'] = $jb_group;
			$rst = $C_JHBoard -> MEM_SECRET_CHECK($args);		
			
			if($rst['jb_mb_id'] == '') {
				if(empty($_POST['input_passd'])) {
					$C_Func->put_msg_and_back("비밀번호를 입력해주세요.");
					die;
				} else {
					if($_POST['input_passd'] != $jb_password) {
						$C_Func->put_msg_and_back("비밀번호를 정확하게 입력해주세요.");					
						die;
					}
				}
			}else{			
				//작성자 아이디와 로그인 아이디 비교
				if($check_id != $rst['jb_mb_id']) {
					$C_Func->put_msg_and_back("작성회원만 글을 읽을 수 있습니다.");
					die;
				}			
			}		
		}
	}
	
	
	
	//관리자의 경우 회원 정보보기 및 작성자 아이피 보기	
	//if($check_level==9) {
		$jb_reg_ip= $C_Func->blindInfo($jb_reg_ip, 11);
	//} else {
		//$jb_reg_ip="";
	//}


	if($jb_link != "") { $jb_link = $C_Func->auto_link($jb_link); }		//홈페이지 (URL 자동 링크 처리)
	
	//HTML TAG 제거, 특수문자 처리
	if($db_config_data['jba_tag_use'] == "N") {
		$jb_content = $C_Func->replace_string($jb_content, "", "");
	}	
	
	//자동줄바꿈
	//$jb_content=$C_Func->auto_br($jb_content, $jb_bruse_check);
	
	
	//자동링크 생성(http, email), 필터링 - 구현해야 함
	//영문 러시아는 파일안보임
	if($jb_code < 200){
	//파일명 분리 및 저장된 파일 갯수
		if($jb_file_name!="") {
			$ex_jb_file_name = explode("|", $jb_file_name);
			$ex_jb_file_code = explode("|", $jb_file_code);
			$file_cnt = count($ex_jb_file_name);
		} else {
			$file_cnt = 0;
		}
	}
	
	
	$jb_reg_date 			= date("Y.m.d", strtotime($jb_reg_date));	
	
	//이름 ** 처리
	if($jb_code != "50" && $jb_code != "120" && $jb_code != "160" && $jb_code != "170") {
		$jb_name =  $C_Func->blindInfo($jb_name, 6);
	}
	
	$content	= $C_Func->dec_contents_edit($jb_content);				
	//$pattern = "/(<img.*?)/i"; 
	//$content = preg_replace( $pattern, "\\1 width=100%", $jb_content ); 	
	
	if($jb_homepage != "") { $jb_homepage = $C_Func->auto_link($jb_homepage); }	
	
	

	//이동 페이지 명 및 기본 변수 처리
	$get_par = "${index_page}?jb_code=${jb_code}&jb_idx=${jb_idx}&jb_group=${jb_group}&jb_step=${jb_step}&jb_depth=${jb_depth}";
	$get_par.= "&${search_key}&search_keyword=$search_keyword&page=${page}&dep1=${dep1}&dep2=${dep2}";
	
	$be_idx = $board_data_B_F[0]['jb_idx'];
	$af_idx = $board_data_B_F[1]['jb_idx'];	
	
	$be_content	= $C_Func->dec_contents_edit($board_data_B_F[0]['jb_title']);
	$be_content = strip_tags($be_content);
	$be_content = $C_Func->strcut_utf8($be_content, 52, false, "...");		
		
	$af_content	= $C_Func->dec_contents_edit($board_data_B_F[1]['jb_title']);
	$af_content = strip_tags($af_content);
	$af_content = $C_Func->strcut_utf8($af_content, 52, false, "...");	
	
	
	//이전글
	if($be_idx != '') {
		$get_par1 = "${index_page}?jb_code=${jb_code}&jb_mode=tdetail&jb_idx=${be_idx}&jb_group=${jb_group}&jb_step=${jb_step}&jb_depth=${jb_depth}";
		$get_par1.= "&${search_key}&search_keyword=$search_keyword&page=${page}&dep1=${dep1}&dep2=${dep2}";
	}	
	
	//다음글
	if($af_idx != '') {
		$get_par2 = "${index_page}?jb_code=${jb_code}&jb_mode=tdetail&jb_idx=${af_idx}&jb_group=${jb_group}&jb_step=${jb_step}&jb_depth=${jb_depth}";
		$get_par2.= "&${search_key}&search_keyword=$search_keyword&page=${page}&dep1=${dep1}&dep2=${dep2}";
	}
	
	#
	# 상세보기 스킨
	#
	//include $GP -> INC_PATH . "/${skin_dir}/board_read.php";
	
}
else
{
	$C_Func->put_msg_and_back("선택하신 글번호에 대한 상세 내용이 없습니다.");
	die;
}
?>
