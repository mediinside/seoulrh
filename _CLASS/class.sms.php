<?
CLASS Sms extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}	
	
	// desc	 : SMS 발송 내역 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Send_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from " . $table . " where ssl_idx='$ssl_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 회원 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Send_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " ssl_senddate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($ssa_idx) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " SL.ssa_idx = '$ssa_idx' ";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "ssl_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblSmsSendList_" . $year . " SL";
		$args['q_where'] = $addQry;
		$args['q_order'] = "ssl_senddate desc";
		$args['q_group'] = "";

		$args['tail'] = "ssa_idx=" . $ssa_idx . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	function Mms_Send_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " ssl_senddate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($ssa_idx) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " SL.ssa_idx = '$ssa_idx' ";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "ssl_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblMmsSendList_" . $year . " SL";
		$args['q_where'] = $addQry;
		$args['q_order'] = "ssl_senddate desc";
		$args['q_group'] = "";

		$args['tail'] = "ssa_idx=" . $ssa_idx . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	// desc	 : SMS 고객 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_Del ($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblSmsAccount 
			set
				ssa_delflag = 'Y',
				ssa_deldate  = NOW()
			where 
				ssa_idx='$ssa_idx'
		";	
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 고객 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			insert into tblSmsAccount 
			(
			ssa_idx,
			ssa_name,
			ssa_mobile,
			ssa_email,
			ssa_id,
			ssa_pwd,
			ssa_num,
			ssa_regdate,
			ssa_delflag
			)
			VALUES
			(
				'',
				'$ssa_name',
				'$ssa_mobile',
				'$ssa_email',
				'$ssa_id',
				'$ssa_pwd',
				'$ssa_num',
				NOW(),
				'N'
			)
		";
		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : SMS 고객 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_Modi ($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblSmsAccount 
			set
				ssa_name = '$ssa_name',
				ssa_id = '$ssa_id',
				ssa_pwd = '$ssa_pwd',
				ssa_email = '$ssa_email',
				ssa_mobile = '$ssa_mobile',
				ssa_num = '$ssa_num'
			where 
				ssa_idx='$ssa_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 고객 설정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_Info ($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblSmsAccount where ssa_idx='$ssa_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : SMS 고객 설정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_ALL ($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblSmsAccount where ssa_delflag = 'N'
		";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	
	// desc	 : SMS 고객 설정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Client_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1  ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " ssa_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "ssa_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "  tblSmsAccount";
		$args['q_where'] = $addQry;
		$args['q_order'] = "ssa_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	
	
	
	
	
	
	
	// desc	 : SMS 설정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Setup_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			insert into tblSmsSetup 
			(
			tss_idx,
			tss_name,
			tss_mobile
			)
			VALUES
			(
				'',
				'$tss_name',
				'$tss_mobile'
			)
		";
		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : SMS 설저 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Setup_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblSmsSetup 
			set
				tss_name='$tss_name',
				tss_mobile='$tss_moible'
			where
				tss_idx='$tss_idx'
		";	
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	
	// desc	 : SMS 회원 이동
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Setup_Info() {
		
		$qry = "
			select * from tblSmsSetup						
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : SMS 회원 이동
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Mem_Move($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblMember
			set
				mb_gr_code = '$gr_code'
			where
				mb_code = '$mb_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : SMS 회원 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Mem_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblMember
			set
				mb_del_flag = 'Y'
			where
				mb_code = '$mb_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : SMS 회원 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Mem_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblMember
			set
				mb_name = '$mb_name',
				mb_mobile = '$mb_mobile'
			where
				mb_code = '$mb_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : SMS 그룹삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblMember
			set
				mb_gr_code = '0'
			where
				mb_gr_code = '$gr_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		
		$qry = "
			delete from tblSmsGroup where gr_code = '$gr_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 그룹이동
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_Move($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblMember
			set
				mb_gr_code = '$gr_code'
			where
				mb_gr_code = '$bf_gr_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 그룹명 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblSmsGroup
			set
				gr_name = '$gr_name'
			where
				gr_code = '$gr_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : SMS 그룹명 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblSmsGroup where gr_code='$gr_code'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : SMS 그룹명 체크
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Chk_Group($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from tblSmsGroup where gr_name='$gr_name'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : SMS 그룹 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			insert into tblSmsGroup 
			(
			gr_code,
			gr_name,
			gr_regdate
			)
			VALUES
			(
				'',
				'$gr_name',
				NOW()
			)
		";
		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : SMS 회원 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Mem_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and mb_del_flag='N' and mb_status = 'M' and mb_sms ='Y' and mb_mobile != '' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " mb_register_date BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "mb_code";
		$args['q_col'] = "*";
		$args['q_table'] = " tblMember M LEFT OUTER JOIN tblSmsGroup G ON M.mb_gr_code=G.gr_code";
		$args['q_where'] = $addQry;
		$args['q_order'] = "mb_register_date desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	// desc	 : SMS 회원 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Group_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1  ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " gr_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "gr_code";
		$args['q_col'] = "*, (select count(*) as cnt from tblMember where G.gr_code = mb_gr_code and mb_del_flag = 'N' ) as cnt";
		$args['q_table'] = " tblSmsGroup as G";
		$args['q_where'] = $addQry;
		$args['q_order'] = "gr_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	// desc	 : SMS 그룹 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Group_list($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
				select * from tblSmsGroup 
			";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	// desc	 : SMS 회원 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Mem_list($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
				select * from tblMember where mb_status = 'M'  and mb_sms ='Y' and mb_mobile != ''
			";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	// desc	 : SMS 회원 그룹리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Sms_Mem_Group_List($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
				select * from tblMember where mb_gr_code='$mb_gr_code' and mb_status = 'M' and mb_sms ='Y'
			";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
}
?>