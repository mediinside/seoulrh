<?
CLASS Recruit extends Dbconn 
{	
	private $DB;
	private $GP;	
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}


	// desc	 : 
	// auth  : 
	// param
	function Recruit_info_All($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblRecruit where rc_idx in ($arr_idx)
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;	
	}


	// desc	 : 입사지원 일괄 삭제
	// auth  : 
	// param
	function Recruit_Del_All($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblRecruit where rc_idx in ($arr_idx)
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}

	// desc	 : 불합격으로 이동
	// auth  : JH 2013-09-16 월요일
	// param
	function Recruit_Pass_N($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblRecruit
			set
				rc_pass = 'N'
			where
				rc_idx in ($arr_idx)
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	// desc	 : 합격으로 이동
	// auth  : JH 2013-09-16 월요일
	// param
	function Recruit_Pass_Y($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblRecruit
			set
				rc_pass = 'Y'
			where
				rc_idx in ($arr_idx)
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 온라인 문자 발송
	// auth  : JH 2013-09-16 월요일
	// param
	function Recruit_SMS_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblRecruit
			set
				rc_sms = 'Y'
			where
				rc_idx = '$rc_idx'
		";

		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 입사지원 삭제
	// auth  : 
	// param
	function Recruit_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblRecruit where rc_idx ='$rc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	
	// desc	 : 입사지원 수정
	// auth  : 
	// param
	function Recruit_Modify($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblRecruit
			set
				rc_name = '$rc_name',
				rc_sex = '$rc_sex',
				rc_birth = '$rc_birth',
				rc_age = '$rc_age',
				rc_mobile = '$rc_mobile',
				rc_email = '$rc_email',
				rc_post = '$rc_post',
				rc_addr1 = '$rc_addr1',
				rc_addr2 = '$rc_addr2',
				rc_pass = '$rc_pass'
			where
				rc_idx ='$rc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 입사지원 상세
	// auth  : 
	// param
	function Recruit_info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblRecruit where rc_idx ='$rc_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;		
	}
	
	// desc	 : 입사지원 리스트
	// auth  : 
	// param
	function Recruit_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 and jb_idx = '$jb_idx'";

	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " rc_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		if($rc_pass != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " rc_pass = '$rc_pass' ";
		}

		

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "rc_idx";
		if($excel_file != '') {
			$args['q_col'] = " 
					rc_name,
					rc_email,
					rc_sex,
					rc_birth,
					rc_mobile,
					rc_post,
					rc_addr1,
					rc_addr2,
					(				
						CASE rc_pass
							WHEN 'Y' THEN '합격'
							WHEN 'N' THEN '불합격'
						ELSE 
							'심사중'
						END
					) as rc_pass,
					rc_regdate
			";
		}else{
			$args['q_col'] = " * ";
		}
		$args['q_table'] = " tblRecruit ";
		$args['q_where'] = $addQry;
		$args['q_order'] = " rc_idx desc";
		$args['q_group'] = "";

		$args['tail'] = "rc_pass=" . $rc_pass . "&jb_idx=" . $jb_idx . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	
	// desc	 : 입사지원 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Recruit_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			INSERT INTO
				 tblRecruit
				(
					rc_idx,
					jb_idx,
					jb_code,
					rc_name,
					rc_sex,
					rc_birth,
					rc_age,
					rc_mobile,
					rc_email,
					rc_post,
					rc_addr1,
					rc_addr2,
					rc_pic,
					rc_doc,
					rc_sms,
					rc_regdate
				)
				VALUES
				(
					''		
					, '$jb_idx'
					, '$jb_code'
					, '$rc_name'
					, '$rc_sex'
					, '$rc_birth'
					, '$rc_age'
					, '$rc_mobile'
					, '$rc_email'
					, '$rc_post'
					, '$rc_addr1'
					, '$rc_addr2'
					, '$rc_pic'
					, '$rc_doc'
					, 'N'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
}
?>