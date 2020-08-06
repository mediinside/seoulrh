<?
CLASS history extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	
	// desc	 : 메인 슬라이드 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Main_history_Show($args='') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($h_lang != '') {
			$addQry .= " AND h_lang = '$h_lang' ";
		}else{
			$addQry .= " AND h_lang = 'kor' ";
		}
		
		if($h_type != '') {
			$addQry .= " AND h_type = '$h_type' ";
		}else{
			$addQry .= " AND h_type = 'main' ";
		}
		$qry = "
			select * from tblhistory where h_show ='Y' $addQry order by h_regdate asc $limit
		";
		if ($_SERVER["REMOTE_ADDR"] == '210.90.202.198') {
			// echo $qry;
		}
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
    }   
   
		
	// desc	 : 슬라이드 수정
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblhistory
			set
                h_year = '$h_year',
				h_day = '$h_day',
				h_content = '$h_content',
				h_show = '$h_show',
				h_type = '$h_type'
			where
				h_idx = '$h_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 삭제
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblhistory where h_idx = '$h_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_ImgUpdate($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$addQry = "";
		if($type == "W") {
			$addQry = " h_img = '' ";
		}else {
			$addQry = " h_img2 = '' ";
		}
		
		$qry = "
			update
				tblhistory
			set				
				$addQry
			where
				h_idx = '$h_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tblhistory where h_idx = '$h_idx'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			INSERT INTO
				tblhistory
				(
                    h_idx,
                    h_year,
                    h_day,
					h_content,		
					h_show,					
					h_regdate,
					h_type
				)
				VALUES
				(
					''		
					, '$h_year'
					, '$h_day'
					, '$h_content'			
					, '$h_show'
					,  NOW()
					, '$h_type'
				)
			";
			

		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}	
	
	// desc	 : 태그 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function history_List_year ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($h_show)) {
			$addQry .= " AND ";
			$addQry .= " h_show = '$h_show' ";
        }	
        
        if(!empty($h_type)) {
			$addQry .= " AND ";
			$addQry .= " h_type = '$h_type' ";
		}	
						
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "h_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblhistory";
		$args['q_where'] = $addQry;
		$args['q_order'] = "h_year desc, h_regdate desc";
		$args['q_group'] = "h_year";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
    }

    function history_List_day ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($h_year)) {
			$addQry .= " AND ";
			$addQry .= " h_year = '$h_year' ";
        }	
        
        if(!empty($h_show)) {
			$addQry .= " AND ";
			$addQry .= " h_show = '$h_show' ";
        }
        
        if(!empty($h_type)) {
			$addQry .= " AND ";
			$addQry .= " h_type = '$h_type' ";
		}	
	
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "h_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblhistory";
		$args['q_where'] = $addQry;
		$args['q_order'] = "h_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
        return $C_ListClass -> listInfo($args);
    }
    
    function history_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($h_show)) {
			$addQry .= " AND ";
			$addQry .= " h_show = '$h_show' ";
        }	               
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
        }
       
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "h_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblhistory";
		$args['q_where'] = $addQry;
		$args['q_order'] = "h_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
        $args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>