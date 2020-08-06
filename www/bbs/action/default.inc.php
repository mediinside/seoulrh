<?php	

# 상세보기
if($jb_idx) {	
	# 상세보기 처리 루틴 및 스킨
	include $GP -> INC_PATH . "/action/tdetail.inc.php";
} 


# 목록보기 (상세보기 코드가 없거나 상세보기페이지에 리스트를 보여줄 경우)
# 아래 프로그램은 게시판스킨의 페이지수, 총게시물수 표기를 위해 list.inc.php include전에 선처리 해야함
if (!$jb_idx || $db_config_data['jba_list_use'] == "Y") {
	//==============================================================================================
	# 검색 조건 설정
	//==============================================================================================
	
	
	$args							= array();
	
	if($_GET['page_row'] != '') {
		$args['show_row'] = $_GET['page_row'];	
	}else{
		$args['show_row'] = $db_config_data['jba_list_scale'];	
	}
	
	$args['show_page'] = $db_config_data['jba_page_scale'];	 
	$args['jb_code']  = $jb_code;	

	if($mypage_id != '') {
		$args['my_mb_id']  = $mypage_id;	
	}
	if($jb_code >= 200 ){
		if($_SESSION['suserlevel'] < 9){
			$args['jb_etc3'] = 'A';	
		}
	}
	
	$data = "";
	$data = $C_JHBoard -> Board_List(array_merge($_GET,$_POST,$args));
	
	$data_list 			= $data['data'];
	$page_link 			= $data['page_info']['link'];
	$page_search 		= $data['page_info']['search'];
	$totalcount 		= $data['page_info']['total'];
	$totalpages 		= $data['page_info']['totalpages'];
	$nowPage 				= $data['page_info']['page'];	
	$num_idx				= $data['page_info']['start_num'];
	
	$totalcount_l 	= number_format($totalcount,0);
	$data_list_cnt 	= count($data_list);
	
	#리스트 처리 루틴 및 스킨
	include $GP -> INC_PATH . "/${skin_dir}/board_list.php";	
}
?>