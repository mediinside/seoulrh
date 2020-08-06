<?
CLASS Grievance extends Dbconn
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
				tblGrievance
				(
					cs_name1,
					cs_no,
					cs_phone,
					cs_password,
					cs_date,
					cs_location,
					cs_victim,
					cs_perpetrator,
					cs_type,
					cs_condition,
					cs_degree,
					cs_witness,
					cs_title,
					cs_content,
					cs_file_code,
					cs_file_name,
					cs_lang,
					cs_regdate
				)
				VALUES
				(
					'$cs_name1',
					'$cs_no',
					'$cs_phone',
					'$cs_password',
					'$cs_date',
					'$cs_location',
					'$cs_victim',
					'$cs_perpetrator',
					'$cs_type',
					'$cs_condition',
					'$cs_degree',
					'$cs_witness',
					'$cs_title',
					'$cs_content',
					'$cs_file_code',
					'$cs_file_name',
					'$cs_lang',
					now()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}

	function Cs_Counsel_Mod($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		if ($cs_file_name != '' && $cs_file_code != ''){

			$qry = "
			UPDATE
				tblGrievance
			SET
					cs_name1 = '$cs_name1',
					cs_no = '$cs_no',
					cs_phone = '$cs_phone',
					cs_password = '$cs_password',
					cs_date = '$cs_date',
					cs_location = '$cs_location',
					cs_victim = '$cs_victim',
					cs_perpetrator = '$cs_perpetrator',
					cs_type = '$cs_type',
					cs_condition = '$cs_condition',
					cs_degree = '$cs_degree',
					cs_witness = '$cs_witness',
					cs_title = '$cs_title',
					cs_content = '$cs_content',
					cs_file_code = '$cs_file_code',
					cs_file_name = '$cs_file_name',
					cs_lang = '$cs_lang',
					cs_moddate = now()
				WHERE
					cs_idx = '$cs_idx';
			";
		}else{
			$qry = "
			UPDATE
				tblGrievance
			SET
					cs_name1 = '$cs_name1',
					cs_no = '$cs_no',
					cs_phone = '$cs_phone',
					cs_password = '$cs_password',
					cs_date = '$cs_date',
					cs_location = '$cs_location',
					cs_victim = '$cs_victim',
					cs_perpetrator = '$cs_perpetrator',
					cs_type = '$cs_type',
					cs_condition = '$cs_condition',
					cs_degree = '$cs_degree',
					cs_witness = '$cs_witness',
					cs_title = '$cs_title',
					cs_content = '$cs_content',
					cs_lang = '$cs_lang',
					cs_moddate = now()
				WHERE
					cs_idx = '$cs_idx';
			";
		}

		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}


	// desc	 : 상담신청
	// param
	function Cs_Chk_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblGrievance where cs_email = '$cs_email' order by cs_idx desc limit 0,1
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
		if ($pagetype == 'admin') {
			if ($search_key && $search_content) {
				if (!empty($addQry)) {
					$addQry .= " AND ";
					$addQry .= " $search_key LIKE ('%$search_content%')";
				}
			}
		}else{
			if ($cs_name1 && $cs_no) {
					$addQry .= " AND ";
					$addQry .= " cs_name1 LIKE ('%$cs_name1%')";
					$addQry .= " AND ";
					$addQry .= " cs_no LIKE ('%$cs_no%')";
			}
		}
		// if ($cs_lang) {
		// 	if (!empty($addQry)) {
		// 		$addQry .= " AND ";
		// 		$addQry .= " cs_lang = '$cs_lang'";
		// 	}
		// }		

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "cs_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblGrievance ";
		$args['q_where'] = $addQry;
		$args['q_order'] = "cs_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		// print_r($args);
		return $C_ListClass -> listInfo($args);
	}

	function Cs_Counsel_Detail($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblGrievance where cs_idx = '$cs_idx'
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
			delete from tblGrievance where cs_idx='$cs_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
}
?>