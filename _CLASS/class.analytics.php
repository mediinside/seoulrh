<?
CLASS Analytics extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 페이지뷰 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Page_List($args = '') {
		global $C_Func, $GP;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";	
		
		
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " DATE_FORMAT(tps_dateitme,'%Y-%m-%d') between '$s_date' AND '$e_date'";
		}
		
		if($tps_type != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " tps_type ='$tps_type'";
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tps_idx";
		
		$case_str = "
				(				
					CASE tps_type
		";		
		foreach($GP->PAGE_TYPE as $key => $val) {												
			$case_str .= " WHEN '" . $val[0] ."' THEN '" . $val[1]. "' ";
		}		
		$case_str .= "
				ELSE '-'
				END
				) as tps_type
		";		
		
		if($excel_file != '') {
			$args['q_col'] =  $case_str . ", tps_dateitme, tps_ip, tps_url, tps_refer	";
		}else{
			$args['q_col'] = " * ";
		}
		
		$args['q_table'] = "tblPageStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tps_dateitme desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	// desc	 : 페이지뷰 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_PageView_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";	
		
		
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " DATE_FORMAT(s_register_date,'%Y-%m-%d') between '$s_date' AND '$e_date'";
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "s_idx";
		
		if($excel_file != '') {
			$args['q_col'] = " 
				s_StatusURL ,
				COUNT(s_StatusURL) as cnt  
			";
		}else{
			$args['q_col'] = " 
							s_StatusURL ,
							COUNT(s_StatusURL) as cnt  
			";
		}
		
		$args['q_table'] = "tblStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "cnt desc";
		$args['q_group'] = " s_StatusURL ";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : OS 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_OS_List ($args = '') {		
		
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;
		
		$addQry = "
			DATE_FORMAT(s_register_date,'%Y-%m-%d')  between '$s_date' and '$e_date'
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}	
		
		
		$qry = "
			SELECT 
				s_OS, count(*) as cnt 
			FROM 
				`tblStatus` 
			WHERE 
				$addQry		
			group by s_OS
			order by cnt desc
			
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : Bowser 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_BW_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;
		
		
		$addQry = "
			DATE_FORMAT(s_register_date,'%Y-%m-%d')  between '$s_date' and '$e_date'			
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}	
				
		
		$qry = "
			SELECT 
				s_Browser, count(*) as cnt 
			FROM 
				`tblStatus` 
			WHERE 
				$addQry		
			group by s_Browser
			order by cnt desc
			
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : Bowser 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Bot_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;
		
		$addQry = "
			DATE_FORMAT(s_register_date,'%Y-%m-%d')  between '$s_date' and '$e_date'				
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}		
		
		$qry = "
			SELECT 
				s_SearchBot, count(*) as cnt 
			FROM 
				`tblStatus` 
			WHERE 
				$addQry	
			group by s_SearchBot
			order by cnt desc
			
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : Bowser 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Engine_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;
		
		$addQry = "
			DATE_FORMAT(s_register_date,'%Y-%m-%d')  between '$s_date' and '$e_date'				
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}			
		
		$qry = "
			SELECT 
				s_SearchEngine, count(*) as cnt 
			FROM 
				`tblStatus` 
			WHERE 
				$addQry
			group by s_SearchEngine
			order by cnt desc
			
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : Agent 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_agent_List ($args = '') {
		
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$addQry = "
			DATE_FORMAT(s_register_date,'%Y-%m-%d')  between '$s_date' and '$e_date'
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}	

		$tail = "";		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "s_Agent";
		$args['q_col'] = " s_Agent, count(*) as cnt ";
		$args['q_table'] = "tblStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "cnt desc";
		$args['q_group'] = " s_Agent ";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 접속 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Insert($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO tblStatus 
				SET 
					s_register_date= NOW(),
					s_StatusIP='$s_StatusIP',
					s_StatusURL='$s_StatusURL',
					s_StatusReferer='$s_StatusReferer',
					s_SearchBot='$s_SearchBot',
					s_SearchEngine='$s_SearchEngine',
					s_Browser='$s_Browser',
					s_OS='$s_OS',
					s_Agent = '$s_Agent',
					s_lang	= '$lang'
		";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 접속 중복체크
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Check($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				count(*) as cnt 
			from 
				tblStatus
			WHERE 
				DATE_FORMAT(s_register_date,'%Y') = '$cnt_year' 
				AND DATE_FORMAT(s_register_date,'%m') = '$cnt_month' 
				AND DATE_FORMAT(s_register_date,'%d') = '$cnt_day'
				AND s_StatusIP = '$connect_ip'
				AND s_lang = '$lang'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 통계 합계
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Total($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$arr_tmp = array();
		
		if($lang) {
			$addQry = " Where s_lang = '$lang'";
			$addQry2 = " And s_lang = '$lang'";
		}
		
		$qry = "SELECT COUNT(*) as cnt FROM tblStatus $addQry";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		$arr_tmp[0] = $rst['cnt'];
		
		$qry1 = "SELECT COUNT(*) as cnt FROM tblStatus  WHERE DATE_FORMAT(s_register_date,'%Y') = '$Year' AND DATE_FORMAT(s_register_date,'%m') = '$Month' $addQry2";
		$rst1 =  $this -> DB -> execSqlOneRow($qry1);
		$arr_tmp[1] = $rst1['cnt'];
		
		$qry2 = "SELECT COUNT(*) as cnt FROM tblStatus  WHERE DATE_FORMAT(s_register_date,'%Y') = '$Year' AND DATE_FORMAT(s_register_date,'%m') = '$Month' AND DATE_FORMAT(s_register_date,'%d') = '$Day' $addQry2";
		$rst2 =  $this -> DB -> execSqlOneRow($qry2);
		
		echo $qry."<br>";
		echo $qry1."<br>";
		echo $qry2."<br>";				
		$arr_tmp[2] = $rst2['cnt'];
		return $arr_tmp;		
	}
	
	// desc	 : 월별 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Year_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 
			AND DATE_FORMAT(s_register_date,'%Y') = '$Year'
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "s_idx";
		if($excel_file != '') {
			$args['q_col'] = " 
				DATE_FORMAT(s_register_date,'%m') As StatusMonth, 
				COUNT(s_register_date) as cnt 
			";
		}else{
			$args['q_col'] = " DATE_FORMAT(s_register_date,'%m') As StatusMonth, COUNT(s_register_date) as cnt ";
		}
		
		$args['q_table'] = "tblStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "StatusMonth asc";
		$args['q_group'] = " DATE_FORMAT(s_register_date,'%m') ";

		$args['tail'] = "Year=" . $Year;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 월별 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Month_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 
			AND DATE_FORMAT(s_register_date,'%Y') = '$Year'
			AND DATE_FORMAT(s_register_date,'%m') = '$Month'
		";		
		
		if($lang) {
			$addQry .= "AND s_lang = '$lang'";	
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "s_idx";
		if($excel_file != '') {
			$args['q_col'] = " 
				DATE_FORMAT(s_register_date,'%Y-%m-%d') As StatusDay, 
				COUNT(s_register_date) as cnt 
			";
		}else{
			$args['q_col'] = " DATE_FORMAT(s_register_date,'%d') As StatusDay, COUNT(s_register_date) as cnt ";
		}
		$args['q_table'] = "tblStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "StatusDay asc";
		$args['q_group'] = " DATE_FORMAT(s_register_date,'%d') ";

		$args['tail'] = "Year=" . $Year . "&Month=" . $Month;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	

	
	
	// desc	 : 일별 통계 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Analytics_Day_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";	
		
		
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " DATE_FORMAT(s_register_date,'%Y-%m-%d') between '$s_date' AND '$e_date'";
		}
		
		if($lang){
			$addQry .= " AND s_lang='$lang'";	
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "s_idx";
		
		if($excel_file != '') {
			$args['q_col'] = " 
				s_register_date, 			
				(				
					CASE s_StatusReferer
					WHEN '-' THEN 'URL 입력 또는 즐겨찾기를 통해 접속'
					ELSE s_StatusReferer
					END
				) as s_StatusReferer,
				s_StatusIP
			";
		}else{
			$args['q_col'] = " * ";
		}
		
		$args['q_table'] = "tblStatus";
		$args['q_where'] = $addQry;
		$args['q_order'] = "s_register_date desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
}
?>