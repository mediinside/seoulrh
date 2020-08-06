<?php	
# 댓글작성
if($jb_mode!="comment_modify") {
	
	//페이지 이동 관련 변수 설정 - 폼의 액션
	$get_c_par = "${query_page}?jb_mode=comment_write&jb_code=${jb_code}&jb_idx=${jb_idx}";
	$get_c_par.= "&${search_key}&search_keyword=${search_keyword}&page=${page}";		

	if($check_level >= $db_config_data['jba_comment_level'])
		include $GP -> INC_PATH . "/${skin_dir}/comment_write.php";
		
	$get_c_par = "";	
	# 댓글목록
	include $GP -> INC_PATH . "/${skin_dir}/comment_list.php";

	
} else {	
	include $GP -> INC_PATH . "/action/comment_modify.inc.php";
	include $GP -> INC_PATH . "/${skin_dir}/comment_modify.php";	
}
?>