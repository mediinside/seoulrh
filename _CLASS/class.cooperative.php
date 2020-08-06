<?
CLASS Cooperative extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	// desc	 : 세브란스 따라한  비급여 항목 마지막 수정일
	// auth  : JH 2013-09-16 월요일
	// param
	function Last_Update_date($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$qry = "
			select cp_regdate from tblCooperative order by cp_regdate desc limit 0 , 1
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;	
	}
	
	// desc	 : 세브란스 따라한 비급여 항목 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_Info_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblCooperative where cp_idx = '$cp_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 세브란스 따라한 비급여 항목 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblCooperative where cp_idx = '$cp_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
		}

	// desc	 : 세브란스 따라한 비급여 항목 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_Info_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
                tblCooperative
			set
				cate1 = '$cate1',
				cate2 = '$cate2',
				cp_name = '$cp_name',
				cp_place = '$cp_place',
				cp_addr = '$cp_addr',				
                cp_phone = '$cp_phone',		
                cp_link = '$cp_link',	
                cp_img = '$cp_img',			
				cp_editdate = NOW()
			where
				cp_idx = '$cp_idx'				
        ";

		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}


	// desc	 :  세브란스 따라한 비급여 등록
	// auth  : JH 2013-09-16 월요일
	// param	
	function cooperative_Info_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblCooperative
			(
			cp_idx,
			cate1,			
			cate2,
			cp_name,	
			cp_place,	
			cp_addr,	
            cp_img,	
            cp_link,	
			cp_phone,		
			cp_regdate
			)
			VALUES
			(
			''
			, '$cate1'
			, '$cate2'
			, '$cp_name'
			, '$cp_place'
			, '$cp_addr'
            , '$cp_img'
            , '$cp_link'
			, '$cp_phone'
			, NOW()
			)
		";
		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	


	// desc :  세브란스 따라한 비급여 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_List ($args = '') {
		global $C_Func, $GP, $C_ListClass;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$tail = "";
		
		$addQry = " 1=1 ";
		
		if($cate1) {
			$addQry .= "
				AND cate1 = '$cate1' 
			";
		}

		if($cate2) {
			$addQry .= "
				AND cate2 = '$cate2' 
			";
		}	
		
		if($cp_place) {
			$addQry .= "
				AND cp_place like '%$cp_place%' 
			";
		}

		if($cp_name) {
			$addQry .= "
				AND cp_name like '%$cp_name%' 
			";
		}

		if($hp_type) {
			$addQry .= "
				AND hp_type ='$hp_type' 
			";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "cp_idx";

		

		$case_str = "
				(				
					CASE cate1
		";		
		foreach($GP -> cooperative_CATE_TYPE1 as $key => $val) {												
			$case_str .= " WHEN '" . $key ."' THEN '" . $val. "' ";
		}		
		$case_str .= "
				ELSE '-'
				END
				) as cate_type1
		";		

		$case_str2 = "
				(				
					CASE cate2
		";		
		foreach($GP -> cooperative_CATE_TYPE1_1 as $key => $val) {												
			$case_str2 .= " WHEN '" . $key ."' THEN '" . $val. "' ";
		}		
		$case_str2 .= "
				ELSE '-'
				END
				) as cate_type2
		";		
		
		if($excel_file != '') {
			$args['q_col'] =  $case_str . ",".   $case_str2 . ", cp_name, cp_place, cp_addr, cp_phone, np_row_price, np_high_price, np_ck1 , np_ck2, np_gita ";
		}else{
			$args['q_col'] = " * ";
		}


		$args['q_table'] = " tblCooperative ";
		$args['q_where'] = $addQry;
		
		if($masc == "asc") {
			$args['q_order'] = "cate2 desc, cate1 asc, cp_regdate desc";
		}else{
			$args['q_order'] = "cate2 desc, cate1 asc, cp_regdate desc";
		}
		$args['q_group'] = "";

		$args['tail'] = "cate1=$cate1&cate2=$cate2&cp_place=$cp_place&hp_type=$hp_type ";
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}


	
	// desc	 : 비급여 항목 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_Info_Del_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblCooperative_new where cp_idx = '$cp_idx'				
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 비급여 항목 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_info_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblCooperative_new	where	cp_idx = '$cp_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
		// desc	 : 비급여 항목 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_Info_Modi_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblCooperative_new
			set
				cate1 = '$cate1',
				cate2 = '$cate2',
				cp_name = '$cp_name',
				cp_place = '$cp_place',
				cp_addr = '$cp_addr',
				np_gubun = '$np_gubun',
				cp_phone = '$cp_phone',
				np_row_price = '$np_row_price',
				np_high_price = '$np_high_price',
				np_percent = '$np_percent',
				np_ck1 = '$np_ck1',
				np_ck2 = '$np_ck2',
				np_gita = '$np_gita'				
			where
				cp_idx = '$cp_idx'				
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	
	// desc	 : 비급여 등록
	// auth  : JH 2013-09-16 월요일
	// param	
	function cooperative_Info_Reg_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblCooperative_new
			(
			cp_idx,
			cate1,			
			cate2,
			cp_name,	
			cp_place,	
			cp_addr,	
			np_gubun,	
			cp_phone,	
			np_row_price,	
			np_high_price,	
			np_percent,
			np_ck1,
			np_ck2,
			np_gita,
			cp_regdate
			)
			VALUES
			(
			''
			, '$cate1'
			, '$cate2'
			, '$cp_name'
			, '$cp_place'
			, '$cp_addr'
			, '$np_gubun'
			, '$cp_phone'
			, '$np_row_price'
			, '$np_high_price'
			, '$np_percent'
			, '$np_ck1'
			, '$np_ck2'
			, '$np_gita'
			, NOW()
			)
		";
		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	
	
	// desc	 : 비급여 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function cooperative_List_new($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1  ";
		
		if($cate1) {
			$addQry .= "
				AND cate1 = '$cate1' 
			";
		}
		

		if($cp_place != '') {
			$addQry .= "
				AND cp_place like '%$cp_place%' 
			";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "cp_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblCooperative_new ";
		$args['q_where'] = $addQry;
		
		if($masc == "asc") {
			$args['q_order'] = "cp_idx asc";
		}else{
			$args['q_order'] = "cp_idx desc";
		}
		$args['q_group'] = "";

		$args['tail'] = "cate1=$cate1&cp_place=$cp_place ";
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	function Category_Print($cate){
		switch ($cate) {
			case '1':
				$txt = '종합병원';
				break;
			
			case '2':
				$txt = '병원';
				break;

			case '3':
				$txt = '의원';
				break;	
		}
		return $txt;
	}
	
}
?>