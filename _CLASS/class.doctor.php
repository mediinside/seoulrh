<?php
CLASS Doctor extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	// desc	 : 순위변경
	// auth  : 
	// param
	function DT_AUTO_CHAGE($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		

		$arr_tmp = explode(',',$tmp_id);
		
		//$max_desc = 22;
		for($i=0; $i<count($arr_tmp); $i++) {
			$idx = $arr_tmp[$i];			
			$qry = " update tblDoctor set dr_desc = '$max_desc' where dr_idx = '$idx'	";			
			$rst =  $this -> DB -> execSqlUpdate($qry);
			$max_desc--; 
		}
		
	}
	
	/*
	function DT_AUTO_CHAGE($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$arr_tmp = explode('_',$cl_id);
		$arr_tmp1 = explode('_',$ch_id);
		
		$n_idx = $arr_tmp[0];
		$n_desc = $arr_tmp[1];
		
		$a_idx = $arr_tmp1[0];
		$a_desc = $arr_tmp1[1];
		
		$qry = " update tblDoctor set dr_desc = '$a_desc' where dr_idx = '$n_idx'	";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		
		$qry1 = " update tblDoctor set dr_desc = '$n_desc' where dr_idx = '$a_idx'	";
		$rst1 =  $this -> DB -> execSqlUpdate($qry1);
		
		return $rst;
	}
	*/
	
	// desc	 : 통합검색 의료진 검색
	// auth  : 
	// param
	function Search_Doctor($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select 
				* 
			from 
				tblDoctor 
			where 
				(dr_treat like '%$search_val%')	or (dr_history like '%$search_val%') or (dr_name like '%$search_val%')
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_List_Expert($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select * from tblDoctor where dr_qa_show = 'Y' and dr_delflag = 'N' order by dr_desc desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}

	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_Main_Select($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select * from tblDoctor where dr_delflag = 'N' and dr_main_view = 'Y' order by dr_desc asc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_List_Select($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select * from tblDoctor where dr_delflag = 'N' order by dr_desc desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}

	// desc	 : 의료진 정보 전부
	// auth  : JH 2013-09-13
	// param 
	function Doctor_Center_List($args) {
		global $GP;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		if($dr_clinic != '') {			
			$addQry = " and dr_clinic = '$dr_clinic' ";
		}

		if($dr_center != '') {			
			$addQry = " and dr_center = '$dr_center' ";
		}
		
		$addQry .= " and dr_lang = 'kor' ";
		
		$qry = "
			select * from tblDoctor where dr_delflag = 'N' $addQry order by dr_desc asc
			";
		$rst =  $this -> DB -> execSqlList($qry);		
		return $rst;
	}
	
	// desc	 : 의료진 정보 전부
	// auth  : JH 2013-09-13
	// param 
	function Doctor_List_All($args) {
		global $GP;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblDoctor where dr_delflag = 'N' order by dr_desc desc
			";
		$rst =  $this -> DB -> execSqlList($qry);
		
		$arr_tmp = array();
		if($rst) {
			for	($i=0; $i<count($rst); $i++) {
				$dr_clinic = $rst[$i]['dr_clinic'];
				$dr_center = $rst[$i]['dr_center'];
				
				$arr_tmp[$rst[$i]['dr_idx']] = "[" . $GP -> CENTER_TYPE[$dr_center] . "] " . $GP -> CLINIC_TYPE[$dr_clinic] . " -  " . $rst[$i]['dr_name'];
			}
		}
		
		return $arr_tmp;
	}
	
	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_List_Sch($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select * from tblDoctor where dr_delflag = 'N' order by dr_desc desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
		
	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_Treat_All($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			select * from tblDoctor where dr_clinic = '$dr_clinic' and dr_delflag = 'N' order by dr_desc desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	
	
	
	// desc	 : 의사 순서 변경
	// auth  : JH 2013-09-16 월요일
	// param
	function DT_DESC_CHAGE($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($type == "asc") {			
			$up_desc = $dr_desc + 1;
			
			$qry = "select dr_idx from tblDoctor where dr_desc = '$up_desc' ";
			$rst =  $this -> DB -> execSqlOneRow($qry);
			
			$one_twz = $rst['dr_idx'];
			
			$qry = " update tblDoctor set dr_desc = dr_desc - 1 where dr_idx = '$one_twz' ";					
			$rst =  $this -> DB -> execSqlUpdate($qry);
			
			
			$qry1 = " update tblDoctor set dr_desc = dr_desc + 1 where dr_idx = '$dr_idx' ";			
			$rst =  $this -> DB -> execSqlUpdate($qry1);
		}
		
		if($type == "desc") {
			
			$dn_desc = $dr_desc - 1;
			
			$qry = "select dr_idx from tblDoctor where dr_desc = '$dn_desc' ";
			$rst =  $this -> DB -> execSqlOneRow($qry);
			
			$one_twz = $rst['dr_idx'];
			
			$qry = " update tblDoctor set dr_desc = dr_desc + 1 where dr_idx = '$one_twz' ";
			$rst =  $this -> DB -> execSqlUpdate($qry);			
			
			$qry1 = " update tblDoctor set dr_desc = dr_desc - 1 where dr_idx = '$dr_idx' ";
			$rst =  $this -> DB -> execSqlUpdate($qry1);
		}
		
		return $rst;
	}
	
	// desc	 : 저서 및 논문 수정
	// auth  : 
	// param	
	function BOOK_MODI($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblBook 
			set
				tb_title = '$tb_title',
				tb_content = '$tb_content',
				tb_file_code = '$tb_file_code',
				tb_editor_code = '$tb_editor_code'
			where 
				tb_idx='$tb_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	} 
	
	
	// desc	 : 저서 및 논문 이미지 삭제
	// auth  : 
	// param	
	function Book_ImgUpdate($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblBook 
			set
				tb_file_code = ''				
			where 
				tb_idx='$tb_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	} 
	
	
	
	// desc	 : 저서 및 논문 상세
	// auth  : 
	// param	
	function Book_Info($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "select * from tblBook where tb_idx='$tb_idx' ";	
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 저서 및 논문 상세
	// auth  : 
	// param	
	function Book_Info_List($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "select * from tblBook where dr_idx='$dr_idx' and tb_type='$tb_type' and tb_delflag = 'N' ";	
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 저서 및 논문 삭제
	// auth  : 
	// param	
	function Book_Del($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblBook 
			set
				tb_delflag = 'Y'
			where 
				tb_idx='$tb_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 저서 및 논문 등록
	// auth  : 
	// param
	function Book_Reg($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblBook
				(
					tb_idx,
					dr_idx,
					tb_type,
					tb_title,
					tb_content,
					tb_file_code,
					tb_editor_code,	
					tb_delflag,
					tb_regdate
				)
				VALUES
				(
					''
					, '$dr_idx'
					, '$tb_type'
					, '$tb_title'
					, '$tb_content'
					, '$tb_file_code'
					, '$tb_editor_code'
					, 'N'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}

	
	// desc	 : 저서 및 논문
	// auth  : 
	// param
	function BookList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and dr_idx='$dr_idx' and tb_delflag = 'N' ";
		

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tb_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblBook";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tb_idx desc";
		$args['q_group'] = "";

		$args['tail'] = "dr_idx=$dr_idx&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}	
	

	
	
	
	// desc	 : 닥터 삭제
	// auth  : 
	// param
	function Doctor_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			update 
				tblDoctor 
			set
				dr_delflag = 'Y'
			where 
				dr_idx = '$dr_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 닥터 수정
	// auth  : 
	// param
	function Doctor_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblDoctor 
			set				
				dr_name = '$dr_name',
				dr_choice = '$dr_choice',
				dr_position = '$dr_position',
				dr_thesis = '$dr_thesis',
				dr_treat = '$dr_treat',
				dr_m_sd = '$dr_m_sd',
				dr_a_sd = '$dr_a_sd',
				dr_special = '$dr_special',
				dr_history = '$dr_history',	
				dr_history1 = '$dr_history1',	
				dr_history2 = '$dr_history2',	
				dr_history3 = '$dr_history3',	
				dr_history4 = '$dr_history4',	
				dr_history5 = '$dr_history5',	
				dr_history6 = '$dr_history6',	
				dr_history7 = '$dr_history7',	
				dr_clinic = '$dr_clinic',			
				dr_center = '$dr_center',			
				dr_face_img = '$dr_face_img',
				dr_m_list_img = '$dr_m_list_img',
				dr_list_img = '$dr_list_img',
				dr_m_profile_img = '$dr_m_profile_img',
				dr_vod_thum_img = '$dr_vod_thum_img',
				dr_vod_url = '$dr_vod_url',
				dr_main_view = '$dr_main_view'
			where 
				dr_idx = '$dr_idx'
		";
	
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
		
	}
	
	
	// desc	 : 닥터 이미지 삭제
	// auth  : 
	// param
	function Doctor_ImgUpdate($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		switch ($type) {
			case 'F':
				$addQry = " dr_face_img = '' ";
				break;
			case 'L':
				$addQry = " dr_list_img = '' ";
				break;
			case 'ML':
				$addQry = " dr_m_list_img = '' ";
				break;
			case 'VOD':
				$addQry = " dr_vod_thum_img = '' ";
				break;			
		}

		$qry = "
			update 
				tblDoctor 
			set
				$addQry
			where 
				dr_idx = '$dr_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 닥터 상세
	// auth  : 
	// param
	function Doctor_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblDoctor where dr_idx = '$dr_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 닥터 상세
	// auth  : 
	// param
	function Doctor_Info_Code($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblDoctor where dt_code = '$dt_code'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	
	// desc	 : 닥터 상세 리스트
	// auth  : 
	// param
	function Doctor_Detail_List($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$addQry =  " 1=1 ";
		
		if($tr_type != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_clinic1 = '$tr_type' ";
		}
		
		if($ct_type != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_clinic2 = '$ct_type' ";
		}
		
		$qry = "
			select * from tblDoctor where $addQry order by dr_desc asc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	
	// desc	 : 닥터 등록
	// auth  : 
	// param
	function Doctor_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "select dr_desc  from tblDoctor order by dr_desc desc limit 0, 1";
		$rst = $this -> DB -> execSqlOneRow($qry);		
		if($rst) {
			$dr_desc = $rst['dr_desc'] + 1;
		}else{
			$dr_desc = 1;
		}
		
		$qry = "
			INSERT INTO
				tblDoctor
				(
					dr_idx,
					dr_name,
					dr_choice,
					dr_thesis,
                    dr_special,
                    dr_menu,
					dr_position,
					dr_treat,
					dr_m_sd,
					dr_a_sd,
					dr_history,
					dr_history1,
					dr_history2,
					dr_history3,
					dr_history4,
					dr_history5,
					dr_history6,
					dr_history7,
					dr_clinic,
					dr_center,
					dr_face_img,
					dr_list_img,
					dr_m_profile_img,
					dr_m_list_img,
					dr_vod_thum_img,
					dr_vod_url,
					dr_delflag,
					dr_desc,
					dr_main_view,
					dr_branch,
					dr_lang,
					dr_regdate
				)
				VALUES
				(				 
					''
					, '$dr_name'
					, '$dr_choice'
					, '$dr_thesis'
                    , '$dr_special'
                    , '$dr_menu'
					, '$dr_position'
					, '$dr_treat'
					, '$dr_m_sd'
					, '$dr_a_sd'
					, '$dr_history'				
					, '$dr_history1'				
					, '$dr_history2'				
					, '$dr_history3'				
					, '$dr_history4'				
					, '$dr_history5'				
					, '$dr_history6'				
					, '$dr_history7'				
					, '$dr_clinic'
					, '$dr_center'
					, '$dr_face_img'
					, '$dr_list_img'
					, '$dr_m_profile_img'
					, '$dr_m_list_img'
					, '$dr_vod_thum_img'
					, '$dr_vod_url'
					, 'N'
					, '$dr_desc'
					, 'N'
					, '$dr_branch'
					, '$dr_lang'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 닥터 리스트
	// auth  : 
	// param
	function Doctor_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and dr_delflag='N' ";
		
		if($ct_type != '' && $ct_type != "Z") {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_center = '$ct_type' ";
		}
	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
		
		if($dr_lang != '') {
			$addQry .= " AND dr_lang = '$dr_lang' ";
		}else{
			$addQry .= " AND dr_lang = 'kor' ";
		}
		
		if($dr_main_view != ''){
			$addQry .= " AND dr_main_view = '$dr_main_view' ";
        }
        
        if($dr_menu != ''){
			$addQry .= " AND dr_menu = '$dr_menu' ";
        }
        else{
            $addQry .= " AND dr_menu = '' ";

        }
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "dr_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblDoctor";
		$args['q_where'] = $addQry;
		// if($ct_type == "Z") {
		// 	$args['q_order'] = " dr_desc desc";
		// }else if($device == "m"){
		// 	$args['q_order'] = " dr_desc asc";
		// }else{
		// 	$args['q_order'] = " dr_desc desc";
		// }
		
		if($q_order != '') {
			$args['q_order'] = $q_order;
		}else{
			$args['q_order'] = " dr_desc desc";
		}
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	function Doctor_List_Dev ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and dr_delflag='N' ";
		
		if($ct_type != '' && $ct_type != "Z") {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_center = '$ct_type' ";
		}
	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
		
		if($dr_lang != '') {
			$addQry .= " AND dr_lang = '$dr_lang' ";
		}else{
			$addQry .= " AND dr_lang = 'kor' ";
		}
		
		if($dr_main_view != ''){
			$addQry .= " AND dr_main_view = '$dr_main_view' ";
		}
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "dr_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblDoctor";
		$args['q_where'] = $addQry;
		// if($ct_type == "Z") {
		// 	$args['q_order'] = " dr_desc desc";
		// }else if($device == "m"){
		// 	$args['q_order'] = " dr_desc asc";
		// }else{
		// 	$args['q_order'] = " dr_desc desc";
		// }
		
		if($q_order != '') {
			$args['q_order'] = $q_order;
		}else{
			$args['q_order'] = " dr_desc asc";
		}
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 닥터 리스트(앞단)
	// auth  : 
	// param
	function Doctor_List2 ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry[] = " dr_delflag='N' and dr_main_view ='Y'";
		
		if($dr_center != '') {
			$addQry[] = " dr_center like '%$dr_center%' ";
		}else{
			$addQry[] = " dr_clinic = '$dr_clinic' ";
		}
		
		/*if($dr_clinic == "A" || $dr_clinic =="B"){
				$addQry[] = " dr_clinic = 'A' or dr_clinic = 'B'";
		}else if($dr_clinic != '') {
		}*/
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			$addQry[] = " dr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 23:59:59'";
		}
		if ($search_key && $search_content) {
				$addQry[] = " $search_key LIKE ('%$search_content%')";
		}
		
		if($dr_center != '') {
			$addQry[] = " dr_center = '$dr_center' ";
		  }
		 
		 if($dr_idx != '') {
			$addQry[] = " dr_idx = '$dr_idx' ";
		  }
	
		if($addQry) $addQry = implode(" AND ",$addQry);
		
		//echo $addQry;
		//진료과목검색

		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "dr_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblDoctor";
		$args['q_where'] = $addQry;
		if($orderby != '') {
			$args['q_order'] = " $orderby ";
		}else{
			$args['q_order'] = " dr_desc desc";
		}
		$args['q_group'] = "";
		$args['q_group'] = "dr_name";

		$args['tail'] = "dr_center=" . $dr_center. "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	function Doctor_List2_TEST ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry[] = " dr_delflag='N' and dr_main_view ='Y'";
		
		if($dr_center != '') {
			$addQry[] = " dr_center like '%$dr_center%' ";
		}else{
			$addQry[] = " dr_clinic = '$dr_clinic' ";
		}
		
		/*if($dr_clinic == "A" || $dr_clinic =="B"){
				$addQry[] = " dr_clinic = 'A' or dr_clinic = 'B'";
		}else if($dr_clinic != '') {
		}*/
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			$addQry[] = " dr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 23:59:59'";
		}
		if ($search_key && $search_content) {
				$addQry[] = " $search_key LIKE ('%$search_content%')";
		}
		
		if($dr_center != '') {
			$addQry[] = " dr_center = '$dr_center' ";
		  }
		 
		 if($dr_idx != '') {
			$addQry[] = " dr_idx = '$dr_idx' ";
		  }
	
		if($addQry) $addQry = implode(" AND ",$addQry);
		
		//echo $addQry;
		//진료과목검색

		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "dr_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblDoctor";
		$args['q_where'] = $addQry;
		if($orderby != '') {
			$args['q_order'] = " $orderby ";
		}else{
			$args['q_order'] = " dr_desc desc";
		}
		$args['q_group'] = "";
		$args['q_group'] = "dr_name";

		$args['tail'] = "dr_center=" . $dr_center. "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "1";
		return $C_ListClass -> listInfo($args);
	}

	function Doctor_List_Test ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and dr_delflag='N' ";
		
		if($ct_type != '' && $ct_type != "Z") {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_center = '$ct_type' ";
		}
	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
		
		if($dr_lang != '') {
			$addQry .= " AND dr_lang = '$dr_lang' ";
		}else{
			$addQry .= " AND dr_lang = 'kor' ";
		}
		// 2019-07-08 윌스기념병원 수원점에 안양점 추가로 인한 지점명
		if($dr_branch != '') {
			$addQry .= " AND dr_branch = '$dr_branch' ";
		}else{
			$addQry .= " AND dr_branch = 'suwon' ";
		}
		if($dr_main_view != ''){
			$addQry .= " AND dr_main_view = '$dr_main_view' ";
		}
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "dr_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblDoctor";
		$args['q_where'] = $addQry;
		if($ct_type == "Z") {
			$args['q_order'] = " dr_desc desc";
		}else{
			$args['q_order'] = " dr_desc desc";
		}
		
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		print_r($args);
		return $C_ListClass -> listInfo($args);
	}
	
	
	
}
?>
