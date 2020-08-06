<?
CLASS Search extends Dbconn 
{	
	private $DB;
	private $GP;	
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	
	// desc	 : 통합검색 진료과/전문센터
	// auth  : JH 2013-09-13
	// param 
	function Search_Treat($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				* 
			from 
				tblSearch 
			where 
				sch_cate='$sch_cate' 
				and (sch_title like '%$search_val%' or sch_content like '%$search_val%')
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 통합검색 삭제
	// auth  : JH 2013-09-13
	// param 
	function Sch_Info_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblSearch where sch_idx='$sch_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 통합검색 수정
	// auth  : JH 2013-09-13
	// param 
	function Sch_Info_Modify($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblSearch
			set			
				sch_cate = '$sch_cate',
				sch_title = '$sch_title',
				sch_content = '$sch_content',
				sch_url = '$sch_url'
			where
				sch_idx='$sch_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 통합검색 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Sch_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblSearch where sch_idx = '$sch_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 통합검색 등록
	// auth  : JH 2013-09-16 월요일
	// param	
	function Sch_Info_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblSearch
				(
					sch_idx,
					sch_cate,
					sch_title,
					sch_content,
					sch_url,
					sch_regdate
				)
				VALUES
				(
					''
					, '$sch_cate'
					, '$sch_title'
					, '$sch_content'
					, '$sch_url'				
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 통합검색 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Sch_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " sch_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "sch_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblSearch";
		$args['q_where'] = $addQry;
		$args['q_order'] = "sch_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>