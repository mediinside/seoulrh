<?
CLASS Customer extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	// desc	 : 고객의 소리 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Cs_Counsel_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$qry = "
			INSERT INTO
				tblConsultation
				(
					cs_name1,
					cs_name2,
					cs_name3,
					cs_nationality,
					cs_nationality_text,					
					cs_age,
					cs_sex,
					cs_birth1,
					cs_birth2,
					cs_birth3,
					cs_street,
					cs_city,
					cs_zipcode,
					cs_country,
					cs_email,
					cs_tel,
					cs_fax,
					cs_visit,		
					cs_title,
					cs_content,
					cs_file_code,
					cs_file_name,
					cs_lang,
					cs_regdate
				)
				VALUES
				(
					'$cs_name1'
					, '$cs_name2'
					, '$cs_name3'
					, '$cs_nationality'
					, '$cs_nationality_text'					
					, '$cs_age'
					, '$cs_sex'
					, '$cs_birth1'
					, '$cs_birth2'
					, '$cs_birth3'
					, '$cs_street'
					, '$cs_city'
					, '$cs_zipcode'
					, '$cs_country'
					, '$cs_email'
					, '$cs_tel'
					, '$cs_fax'
					, '$cs_visit'
					, '$cs_title'
					, '$cs_content'
					, '$cs_file_code'
					, '$cs_file_name'
					, '$cs_lang'					
					, now()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}


	// desc	 : 상담신청
	// param
	function Cs_Chk_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblConsultation where cs_email = '$cs_email' order by cs_idx desc limit 0,1
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}



	// desc	 : 고객의 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Cs_Counsel_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " cs_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
		
		if ($cs_lang) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " cs_lang = '$cs_lang'";
			}
		}		

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "cs_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblConsultation ";
		$args['q_where'] = $addQry;
		$args['q_order'] = "cs_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	function Cs_Counsel_Detail($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblConsultation where cs_idx = '$cs_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}


	// desc	 : 고객의 소리 답변
	// auth  : JH 2013-09-16 월요일
	// param
	function Cs_Consel_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblSoundCustomer
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

	// desc	 : 고객의 소리 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Cs_Consel_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblConsultation where cs_idx='$cs_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
}
?>