<?
CLASS Treat extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}


	// desc	 : 치료법  리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Treat_Center_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		
		
		$addQry = " 1=1 ";	
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = " tc_idx ";
		$args['q_col'] = " k.*";

		if($treat_type != '') {
			if($treat_type == "A-1" || $treat_type == "B-1") {	//비수술적
				$args['q_table'] = "
					(
						select * from tblContent where tc_idx in ( select tc_idx_1 from tblConShow where tcs_cate2 = '$tcs_cate2')
					) as k
				";
			}

			if($treat_type == "A-2" || $treat_type == "B-2") {	//침소, 재생재건
				
				$args['q_table'] = "
					(
						select * from tblContent where tc_idx in ( select tc_idx_2 from tblConShow where tcs_cate2 = '$tcs_cate2')
					) as k
				";
			}
		}else {
			$args['q_table'] = "
				(
					select * from tblContent where tc_idx in ( select tc_idx_1 from tblConShow where tcs_cate2 = '$tcs_cate2')
					union 
					select * from tblContent where tc_idx in ( select tc_idx_2 from tblConShow where tcs_cate2 = '$tcs_cate2')
				) as k
			";
		}
		
		$args['q_where'] = $addQry;
		$args['q_order'] = "tc_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "treat_type=" . $treat_type ."&tcs_cate2=" . $tcs_cate2;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	function Treat_Con_view_1($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select tc_idx_1 from tblConShow where tcs_cate3 = '$tcs_cate3'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		
		$rst1 = "";
		if($rst['tc_idx_1'] != '') {
			$tc_idx_1 = $rst['tc_idx_1'];
		
			$qry1 = "
				select * from tblContent where tc_idx in (" . $tc_idx_1 . ");		
			";
			$rst1 =  $this -> DB -> execSqlList($qry1);
		}
		return $rst1;
	}
	
	
	function Treat_Con_view_2($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select tc_idx_2 from tblConShow where tcs_cate3 = '$tcs_cate3'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		
		
		$rst1 = "";
		if($rst['tc_idx_2'] != '') {
			$tc_idx_2 = $rst['tc_idx_2'];
		
			$qry1 = "
				select * from tblContent where tc_idx in (" . $tc_idx_2 . ");		
			";
			$rst1 =  $this -> DB -> execSqlList($qry1);
		}
		return $rst1;
	}
	
	
	function TREAT_Show_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblConShow where tcs_idx='$tcs_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 컨텐츠 수정
	// auth  : JH 2013-09-13
	// param 
	function TREAT_Show_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblConShow
			set			
				tcs_cate1 = '$tcs_cate1',
				tcs_cate2 = '$tcs_cate2',
				tcs_cate3 = '$tcs_cate3',
				tc_title_1 = '$tc_title_1',
				tc_idx_1 = '$tc_idx_1',
				tc_title_2 = '$tc_title_2',
				tc_idx_2 = '$tc_idx_2'
			where
				tcs_idx='$tcs_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	
	
	
	// desc	 : 컨텐츠 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function TREAT_Show_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblConShow where tcs_idx = '$tcs_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 치료법 관리 등록
	// auth  : JH 2013-09-13
	// param 
	function TREAT_Show_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblConShow
				(
					tcs_idx,
					tc_idx_1,
					tc_title_1,
					tc_idx_2,
					tc_title_2,
					tcs_cate1,
					tcs_cate2,
					tcs_cate3,
					tcs_regdate
				)
				VALUES
				(
					''
					, '$tc_idx_1'
					, '$tc_title_1'
					, '$tc_idx_2'
					, '$tc_title_2'
					, '$tcs_cate1'
					, '$tcs_cate2'
					, '$tcs_cate3'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 치료법 찾기
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function FindTreatList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($tc_cate1)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate1 = '$tc_cate1' ";
		}
		
		if(!empty($tc_cate2)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate2 = '$tc_cate2' ";
		}
		
		if(!empty($tc_cate3)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate3 = '$tc_cate3' ";
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "tc_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblContent";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tc_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "tc_cate1=" . $tc_cate1 . "&tc_cate2=" . $tc_cate2  . "&tc_cate3=" . $tc_cate3;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 치료법 관리 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Treat_Show_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " tcs_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if(!empty($tcs_cate1)) {
			$addQry .= " AND ";
			$addQry .= " tcs_cate1 = '$tcs_cate1' ";
		}
		
		if(!empty($tcs_cate2)) {
			$addQry .= " AND ";
			$addQry .= " tcs_cate2 = '$tcs_cate2' ";
		}
		
		if(!empty($tcs_cate3)) {
			$addQry .= " AND ";
			$addQry .= " tcs_cate3 = '$tcs_cate3' ";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tcs_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblConShow";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tcs_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
		
	// desc	 : 치료법 삭제
	// auth  : JH 2013-09-13
	// param 
	function TREAT_Info_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblContent where tc_idx = '$tc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 치료법 등록
	// auth  : JH 2013-09-13
	// param 
	function Treat_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblContent
				(
					tc_idx,
					tc_cate1,
					tc_cate2,
					tc_cate3,
					tc_title,
					tc_summary,
					tc_content,
					tc_img,
					tc_editor_img,
					tc_regdate
				)
				VALUES
				(
					''
					, '$tc_cate1'
					, '$tc_cate2'
					, '$tc_cate3'
					, '$tc_title'
					, '$tc_summary'
					, '$tc_content'
					, '$tc_img'		
					, '$tc_editor_img'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 컨텐츠 수정
	// auth  : JH 2013-09-13
	// param 
	function TREAT_Info_Modify($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblContent
			set			
				tc_cate1 = '$tc_cate1',
				tc_cate2 = '$tc_cate2',
				tc_cate3 = '$tc_cate3',
				tc_title = '$tc_title',
				tc_summary = '$tc_summary',
				tc_content = '$tc_content',
				tc_img = '$tc_img',
				tc_editor_img = '$tc_editor_img'
			where
				tc_idx='$tc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 컨텐츠 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function TREAT_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblContent where tc_idx = '$tc_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 컨텐츠 첨부 이미지 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function TREAT_ImgUpdate($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblContent
			set
				tc_img = ''
			where 
				tc_idx = '$tc_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 치료법 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Treat_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " tc_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if(!empty($tc_cate1)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate1 = '$tc_cate1' ";
		}
		
		if(!empty($tc_cate2)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate2 = '$tc_cate2' ";
		}
		
		if(!empty($tc_cate3)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate3 = '$tc_cate3' ";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tc_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblContent";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tc_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "tc_cate1=" . $tc_cate1 . "&tc_cate2=" . $tc_cate2 . "&tc_cate3=" . $tc_cate3 . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	// desc	 : 치료법 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Treat_List2 ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " tc_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if(!empty($tc_cate1)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate1 = '$tc_cate1' ";
		}
		
		if(!empty($tc_cate2)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate2 = '$tc_cate2' ";
		}
		
		if(!empty($tc_cate3)) {
			$addQry .= " AND ";
			$addQry .= " tc_cate3 = '$tc_cate3' ";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tc_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblContent";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tc_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "tc_cate1=" . $tc_cate1 . "&tc_cate2=" . $tc_cate2 . "&tc_cate3=" . $tc_cate3 . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	
}
?>