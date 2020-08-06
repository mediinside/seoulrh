<?
CLASS Online extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	// desc	 : 전화 상담 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Consel_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblFastCounsel where tfc_idx='$tfc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	
	// desc	 : 전화 상담 답변
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Consel_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblFastCounsel
			set
				tfc_result = '$tfc_result',
				tfc_rt_date = '$tfc_rt_date',
				tfc_result_con = '$tfc_result_con'
			where
				tfc_idx = '$tfc_idx'
			";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	// desc	 : 전화 상담 상세
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Counsel_Detail($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblFastCounsel where tfc_idx = '$tfc_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}

	// desc	 : 전화 상담 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Counsel_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";

		if($tfc_type != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " TKR.tfc_type = '$tfc_type' ";
		}

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " TKR.tfc_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 23:59:59'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "TKR.tfc_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblFastCounsel TKR LEFT OUTER JOIN tblMember M ON TKR.mb_code=M.mb_code";
		$args['q_where'] = $addQry;
		$args['q_order'] = "TKR.tfc_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	

	// desc	 : 전화 상담 신청
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Counsel_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$qry = "
			INSERT INTO
				tblFastCounsel
				(
					tfc_idx,
					mb_code,
					tfc_type,
					tfc_name,
					tfc_con,
					tfc_mobile,
					tfc_regdate,
					tfc_result
				)
				VALUES
				(
					''
					, '$mb_code'
					, '$tfc_type'
					, '$tfc_name'
					, '$tfc_con'
					, '$tfc_mobile'
					, NOW()
					, 'N'
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}


	// desc	 : 전화 상담 상세
	// auth  : JH 2013-09-16 월요일
	// param
	function Phone_Chk_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblFastCounsel where tfc_mobile = '$tfc_mobile' order by tfc_idx desc limit 0,1
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 온라인 예약 처리
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Reserve_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblOnlineReserve
			set
				tor_rs_type = '$tor_rs_type',
				tor_rs_result_date = '$tor_rs_result_date',
				tor_rs_result_content = '$tor_rs_result_content'
			where
				tor_idx = '$tor_idx'
			";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 온라인 예약 상세
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Reserve_Detail($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				* 
			from 
				tblOnlineReserve TOR LEFT OUTER JOIN tblMember M ON TOR.tor_mb_code=M.mb_code 
			where 
				TOR.tor_idx = '$tor_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 온라인 예약 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Reserve_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblOnlineReserve
			set
				tor_del_flag = 'Y'
			where
				tor_idx = '$tor_idx'
		";

		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 온라인 예약 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Reserve_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblOnlineReserve
				(
					tor_idx,
					tor_mb_code,
					tor_rs_date,
					tor_rs_time,
					tor_rs_content,
					tor_rs_type,
					tor_del_flag,					,
					tor_regdate
				)
				VALUES
				(
					''
					, '$tor_mb_code'
					, '$tor_rs_date'
					, '$tor_rs_time'
					, '$tor_rs_content'
					, '$tor_rs_type'
					, 'N'
					, 'N'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 온라인 예약 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Reserve_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and tor_del_flag = 'N' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " TOR.tor_del_flag BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		
		

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "TOR.tor_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblOnlineReserve TOR LEFT OUTER JOIN tblMember M ON TOR.tor_mb_code=M.mb_code";
		$args['q_where'] = $addQry;
		$args['q_order'] = "TOR.tor_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 상담요청시 관리자에게 메일 발송
	// auth  : JH 2012-10-10 수요일
	// param :
	function sendMail($args)
	{
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$sendRst = 0;

		if ($sender_email && $sender_name && $receive_email && $receive_name && $email_subject) {
			
			include_once $this -> GP -> CLS . 'class.mail.php';
			$C_SendMail = new SendMail();
			
			$C_SendMail -> setUseSMTPServer(true);
			$C_SendMail -> setSMTPServer($this->GP -> SMTP_IP, $this->GP -> SMTP_PORT);
			$C_SendMail -> setSMTPUser($this->GP -> SMTP_USER);
			$C_SendMail -> setSMTPPasswd($this->GP -> SMTP_PASS);			

			//$mailFormDir = @file_get_contents($this -> GP -> HOME."login/mem_find_pw_search.html");
			//$contents = str_replace("@@imsi_pwTxt@@","임시 비밀번호 : $new_pw", $mailFormDir);
		
			$C_SendMail -> setSubject($email_subject);
			$C_SendMail -> setMailBody($contents, true);
			$C_SendMail -> setFrom($sender_email, $sender_name);
			$C_SendMail -> addTo($receive_email, $receive_name);

			$sendRst = $C_SendMail->send();
			$C_SendMail = '';
		}
		return $sendRst;
	}
	
	
	// desc	 : 온라인 상담 답변
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblOnlineQna
			set
				toq_result = '$toq_result',
				toq_result_date = '$toq_result_date',
				toq_result_content = '$toq_result_content'
			where
				toq_idx = '$toq_idx'
			";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 온라인 상담 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblOnlineQna
			set
				toq_name = '$toq_name',
				toq_mobile = '$toq_mobile',
				toq_type = '$toq_type',
				toq_mb_code = '$toq_mb_code',
				toq_content = '$toq_content',
				toq_mod_date = NOW()
			where
				toq_idx = '$toq_idx'
				and toq_email ='$toq_email'
			";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 온라인 상담 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblOnlineQna
				(
					toq_idx,
					toq_name,
					toq_email,
					toq_mobile,
					toq_type,
					toq_mb_code,
					toq_password,
					toq_content,
					toq_del_flag,
					toq_regdate
				)
				VALUES
				(
					''
					, '$toq_name'
					, '$toq_email'
					, '$toq_mobile'
					, '$toq_type'
					, '$toq_mb_code'
					, '$toq_password'
					, '$toq_content'
					, 'N'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 온라인 상담 상세
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_Detail($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblOnlineQna where toq_idx = '$toq_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 온라인 상담 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblOnlineQna where toq_idx='$toq_idx' and toq_email='$toq_email'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 온라인 상담 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Online_Qna_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and toq_del_flag = 'N' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " toq_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "toq_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblOnlineQna";
		$args['q_where'] = $addQry;
		$args['q_order'] = "toq_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
}
?>