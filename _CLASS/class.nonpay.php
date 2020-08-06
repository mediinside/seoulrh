<?
CLASS Nonpay extends Dbconn
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
			select np_regdate from tblNonPay order by np_regdate desc limit 0 , 1
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;	
	}
	
	// desc	 : 세브란스 따라한 비급여 항목 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_Info_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblNonPay where np_idx = '$np_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 세브란스 따라한 비급여 항목 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblNonPay where np_idx = '$np_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
		}

	// desc	 : 세브란스 따라한 비급여 항목 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_Info_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblNonPay
			set
				cate1 = '$cate1',
				cate2 = '$cate2',
				np_bunru = '$np_bunru',
				np_item = '$np_item',
				np_code = '$np_code',
				np_gubun = '$np_gubun',
				np_price = '$np_price',
				np_row_price = '$np_row_price',
				np_high_price = '$np_high_price',
				np_percent = '$np_percent',
				np_ck1 = '$np_ck1',
				np_ck2 = '$np_ck2',
				np_gita = '$np_gita',
				np_editdate = NOW()
			where
				np_idx = '$np_idx'				
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}


	// desc	 :  세브란스 따라한 비급여 등록
	// auth  : JH 2013-09-16 월요일
	// param	
	function NonPay_Info_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblNonPay
			(
			np_idx,
			cate1,			
			cate2,
			np_bunru,	
			np_item,	
			np_code,	
			np_gubun,	
			np_price,	
			np_row_price,	
			np_high_price,	
			np_percent,
			np_ck1,
			np_ck2,
			np_gita,
			np_regdate
			)
			VALUES
			(
			''
			, '$cate1'
			, '$cate2'
			, '$np_bunru'
			, '$np_item'
			, '$np_code'
			, '$np_gubun'
			, '$np_price'
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
	


	// desc :  세브란스 따라한 비급여 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_List ($args = '') {
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
		
		if($np_item) {
			$addQry .= "
				AND np_item like '%$np_item%' 
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
		$args['q_idx'] = "np_idx";

		

		$case_str = "
				(				
					CASE cate1
		";		
		foreach($GP -> NONPAY_CATE_TYPE1 as $key => $val) {												
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
		foreach($GP -> NONPAY_CATE_TYPE1_1 as $key => $val) {												
			$case_str2 .= " WHEN '" . $key ."' THEN '" . $val. "' ";
		}		
		$case_str2 .= "
				ELSE '-'
				END
				) as cate_type2
		";		
		
		if($excel_file != '') {
			$args['q_col'] =  $case_str . ",".   $case_str2 . ", np_bunru, np_item, np_code, np_price, np_row_price, np_high_price, np_ck1 , np_ck2, np_gita ";
		}else{
			$args['q_col'] = " * ";
		}


		$args['q_table'] = " tblNonPay ";
		$args['q_where'] = $addQry;
		
		if($masc == "asc") {
			$args['q_order'] = "cate2 desc, cate1 asc, np_regdate desc";
		}else{
			$args['q_order'] = "cate2 desc, cate1 asc, np_regdate desc";
		}
		$args['q_group'] = "";

		$args['tail'] = "cate1=$cate1&cate2=$cate2&np_item=$np_item&hp_type=$hp_type ";
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}


























	
	// desc	 : 비급여 항목 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_Info_Del_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblNonPay_new where np_idx = '$np_idx'				
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 비급여 항목 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_info_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblNonPay_new	where	np_idx = '$np_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
		// desc	 : 비급여 항목 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function NonPay_Info_Modi_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblNonPay_new
			set
				cate1 = '$cate1',
				cate2 = '$cate2',
				np_bunru = '$np_bunru',
				np_item = '$np_item',
				np_code = '$np_code',
				np_gubun = '$np_gubun',
				np_price = '$np_price',
				np_row_price = '$np_row_price',
				np_high_price = '$np_high_price',
				np_percent = '$np_percent',
				np_ck1 = '$np_ck1',
				np_ck2 = '$np_ck2',
				np_gita = '$np_gita'				
			where
				np_idx = '$np_idx'				
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	
	// desc	 : 비급여 등록
	// auth  : JH 2013-09-16 월요일
	// param	
	function NonPay_Info_Reg_New($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblNonPay_new
			(
			np_idx,
			cate1,			
			cate2,
			np_bunru,	
			np_item,	
			np_code,	
			np_gubun,	
			np_price,	
			np_row_price,	
			np_high_price,	
			np_percent,
			np_ck1,
			np_ck2,
			np_gita,
			np_regdate
			)
			VALUES
			(
			''
			, '$cate1'
			, '$cate2'
			, '$np_bunru'
			, '$np_item'
			, '$np_code'
			, '$np_gubun'
			, '$np_price'
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
	function NonPay_List_new($args = '') {
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
		

		if($np_item != '') {
			$addQry .= "
				AND np_item like '%$np_item%' 
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
		$args['q_idx'] = "np_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblNonPay_new ";
		$args['q_where'] = $addQry;
		
		if($masc == "asc") {
			$args['q_order'] = "np_idx asc";
		}else{
			$args['q_order'] = "np_idx desc";
		}
		$args['q_group'] = "";

		$args['tail'] = "cate1=$cate1&np_item=$np_item ";
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>